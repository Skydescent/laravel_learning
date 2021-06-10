<?php

namespace App\Listeners;

use Illuminate\Cache\Events\KeyWritten;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogKeyWritten
{
    //TODO: May be remove Cache Events listeners

    private string $allCachedKeys;


    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->allCachedKeys = config('cache.cache_service.nameOfAllCacheKeysKey');
    }

    /**
     * Handle the event.
     *
     * @param KeyWritten $event
     * @return void
     */
    public function handle(KeyWritten $event)
    {

//        if ($event->key !== $this->allCachedKeys) {
//
//            $allCachedKeys = \Cache::get($this->allCachedKeys);
//
//            if ($allCachedKeys) {
//                if (!in_array($event->key, $allCachedKeys)) {
//                    $allCachedKeys = array_merge($allCachedKeys, [$event->key]);
//                }
//            } else {
//                $allCachedKeys = [$event->key];
//            }
//
//            \Cache::put($this->allCachedKeys, $allCachedKeys);
//        }
    }
}
