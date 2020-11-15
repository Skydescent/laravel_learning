<?php


namespace App\Recipients;


class AdminRecipient extends Recipient
{
    public function __construct()
    {
        $this->email = config('admin.notifications.postStatus.email');
    }
}
