<?php

namespace App\Repositories;

use App\Models\Actor;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ActorRepository
{
    public function getActorsPaginated()
    {
        return Cache::rememberForever(self::keyForGetActorsPaginates(), function () {
            return DB::table('actors')
                ->selectRaw('actors.id')
                ->selectRaw('actors.name')
                ->selectRaw('actors.license')
                ->selectRaw('urls.id as url_id')
                ->selectRaw('urls.url')
                ->selectRaw('urls.url_cache')
                ->leftJoin('thumbnails', 'actors.id', '=', 'thumbnails.actor_id')
                ->leftJoin('urls', 'thumbnails.id', '=', 'urls.thumbnail_id')
                ->groupBy('id')
                ->paginate(15);
        });
    }

    public function getById($id)
    {
        return Cache::rememberForever(self::keyForGetById($id), function () use ($id) {
            return Actor::find($id);
        });
    }

    public static function keyForGetActorsPaginates()
    {
        return 'actors-page-' . self::getCurrentPage();
    }

    public static function keyForGetById($id)
    {
        return 'actor-' . $id;
    }

    private static function getCurrentPage()
    {
        return request()->get('page', 1);
    }
}
