<?php

namespace App\Service;

use App\Events\PostCreated;
use App\Http\Requests\PostStoreAndUpdateRequest;
use App\Notifications\PostStatusChanged;
use App\Post;
use App\Recipients\AdminRecipient;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Illuminate\Http\Request;

class PostsService implements RepositoryServiceable
{
    /**
     * @var
     */
    private $post;

    /**
     * PostsService constructor.
     * @param Post $post
     */
    public function setPost(Post $post)
    {
        $this->post = $post;
        return $this;
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

        $tags = $attributes['tags'] ?? null;
        unset($attributes['tags']);

        $post = Post::updateOrCreate(['id' => $this->post->id], $attributes);
        if (!$this->post->id) {
            event(new PostCreated($post));
        }
        $this->post = $post;
        $this->post->syncTags($tags);

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function destroyPost()
    {
            $this->post->delete();

            return $this;
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
        return $this;
    }

    public function storePost(ValidatesWhenResolved $request, $message, Post $post = null)
    {
        $post = $post ?? new Post();

        $this->setPost($post)
            ->storeOrUpdate($request->validated())
            ->notifyAdmin($message, 'posts.show');
    }

    public function store(ValidatesWhenResolved|Request $request)
    {
        $this->storePost($request, 'добавлена статья', null);
    }

    public function update(ValidatesWhenResolved|Request $request, $post)
    {
        $this->storePost($request, 'обновлена статья', $post);
    }

    public function destroy($post)
    {
        $this->setPost($post)
            ->destroyPost()
            ->notifyAdmin('статья удалена');
    }
}