<?php

namespace App\Service;

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

        $tags = $attributes['tags']?? null;
        unset($attributes['tags']);

        $this->post = Post::updateOrCreate(['id' => $this->post->id], $attributes);
        $this->post->syncTags($tags);

        return $this;
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
        return $this;
    }
}