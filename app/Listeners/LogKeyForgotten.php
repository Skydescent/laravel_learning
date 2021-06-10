<?php

namespace App\Listeners;

use Illuminate\Cache\Events\KeyForgotten;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogKeyForgotten
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
     * @param KeyForgotten $event
     * @return void
     */
    public function handle(KeyForgotten $event)
    {
//        if ($event->key === $this->allCachedKeys) {
//            \Cache::put($this->allCachedKeys, \Cache::get($this->allCachedKeys));
//        } else {
//            $allCachedKeys = \Cache::get($this->allCachedKeys) ?? [];
//            \Cache::put($this->allCachedKeys, array_diff($allCachedKeys, [$event->key]));
//        }
//
//        $forgottenLog = \Cache::get('forgottenLog') ?? [];
//        $forgottenLog[] = ['key' => $event->key, 'when' => now()->toDateTimeString()];
//        Log::info('Ключ ' . $event->key . 'забыт');
//        \Cache::forever('forgottenLog', [$event->key,]);
    }
}
