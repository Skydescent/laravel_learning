<?php

use App\Service\UsersService;
use App\User;

if (!function_exists('flash')) {
    /**
     * @param $message
     * @param string $type
     */
    function flash($message, $type = 'success')
    {
        session()->flash('message', $message);
        session()->flash('message_type', $type);
    }
}

if (!function_exists('push_all')) {

    /**
     * @param null $title
     * @param null $text
     * @return \App\Service\Pushall|mixed
     */
    function push_all($title = null, $text = null)
    {
        if (is_null($title) || is_null($text)) {
            return app(\App\Service\Pushall::class);
        }

        return app(\App\Service\Pushall::class)->send($title, $text);

    }
}

if (!function_exists('cachedUser')) {
    /**
     * @param $request
     *
     */
    function cachedUser($id = null): User
    {
        $userId = $id;

        if (!$userId) {
            $request = \request();
            if (!$request->hasSession()) return new User();
            $sessionKeys = $request->session()->all();
            foreach ($sessionKeys as $key => $value) {
                if (str_starts_with($key, 'login_web_')) {
                    $userId = $value;
                }
            }
        }

        $userService = new UsersService();

        if (!$userId) return new User();

        return $userService->find($userId)->model;
    }
}



