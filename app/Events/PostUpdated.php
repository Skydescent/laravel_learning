<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public \App\Post $post;

    public \App\User $user;

    public string $postUrl;

    public $updatedFields;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(\App\Post $post, \App\User $user)
    {
        $this->post = $post;
        $this->user = $user;
        $this->updatedFields = implode('; ', json_decode($post->history->last()->pivot['changed_fields']));
        $this->postUrl = route('posts.show',['post'=>$post],true);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PresenceChannel('post_updated');
    }
}
