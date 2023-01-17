<?php

namespace App\Repositories;

use App\Models\Actor;
use Illuminate\Support\Facades\Cache;

class ActorsRepository
{
    public function getActorsPaginated()
    {
        return Cache::rememberForever(self::keyForGetActorsPaginates(), function () {
            return Actor::with('thumbnails', 'thumbnails.urls')->orderBy(Actor::ID_COLUMN)->paginate(15);
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
