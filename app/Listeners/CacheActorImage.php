<?php

namespace App\Listeners;

use App\Events\ActorImported;
use App\Services\Import\Caches\ActorCache;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CacheActorImage
{
    private ActorCache $actorCache;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(ActorCache $actorCache)
    {
        $this->actorCache = $actorCache;
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ActorImported  $event
     * @return void
     */
    public function handle(ActorImported $event)
    {
        $this->actorCache->cacheImagesFor($event->actor);
    }
}
