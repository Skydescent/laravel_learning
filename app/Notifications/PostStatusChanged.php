<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PostStatusChanged extends Notification
{
    use Queueable;

    public $postTitle;

    public $postUri;

    public $status;

    /**
     * Create a new notification instance.
     *
     * @param $status
     * @param $postTitle
     * @param null $postUri
     */
    public function __construct($status, $postTitle, $postUri = null)
    {
        $this->status = $status;
        $this->postTitle = $postTitle;
        $this->postUri = $postUri;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mailMessage = new MailMessage();

        $mailMessage
            ->greeting('Здравствуйте!')
            ->subject("В блоге {$this->status}")
            ->line("В блоге {$this->status}: {$this->postTitle}");

        if (!is_null($this->postUri)) {
            $mailMessage->action('Смотреть', url($this->postUri));
        }

        $mailMessage->salutation('Хорошего дня!');
        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
