<?php

namespace App\Service;

use App\Http\Requests\PostStoreAndUpdateRequest;
use App\Notifications\PostStatusChanged;
use App\Post;
use App\Recipients\AdminRecipient;

class PostsService
{
    /**
     * @var
     */
    private $post;

    /**
     * PostsService constructor.
     * @param Post $post
     */
    public function __construct(Post $post = null)
    {
        $post = $post ?? new Post;
        $this->post = $post;
    }

    /**
     * @return \App\Post
     */
    public function getPost(): Post
    {
        return $this->post;
    }

    /**
     * @param $attributes
     */
    public function storeOrUpdate($attributes)
    {
        $attributes['published'] = $attributes['published'] ?? 0;
        $attributes['owner_id'] = $this->post->owner ? $this->post->owner->id :auth()->id();

        $tags = $attributes['tags']?? null;
        unset($attributes['tags']);

        $this->post = Post::updateOrCreate(['id' => $this->post->id], $attributes);

        $this->post->syncTags($tags);
    }

    /**
     * @throws \Exception
     */
    public function destroy()
    {
            $this->post->delete();
    }

    /**
     * @param $message
     * @param null $routeName
     */
    public function notifyAdmin($message, $routeName = null)
    {
        $route = $routeName ? route($routeName, ['post' => $this->post]) : null;
        $recipient = new AdminRecipient();
        $recipient->notify(new PostStatusChanged(
            $message,
            $this->post->title,
            $route
        ));
    }

    public static function postStoreOrUpdate($request, $post, $message = null, $routeName = null)
    {
        $postService = new static($post);
        $postService->storeOrUpdate($request->validated());
        if (!is_null($message)) {
            $postService->notifyAdmin($message, $routeName);
        }
    }

    public static function postDestroy($post, $message)
    {
        $postService = new static($post);
        $postService->destroy();
        $postService->notifyAdmin($message);
    }
}