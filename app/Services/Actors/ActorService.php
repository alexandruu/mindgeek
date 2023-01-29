<?php

namespace App\Services\Actors;

use App\Repositories\ActorRepository;

class ActorService
{
    private ActorRepository $actorRepository;

    public function __construct(ActorRepository $actorRepository)
    {
        $this->actorRepository = $actorRepository;
    }

    public function getAll()
    {
        $actors = $this->actorRepository->getActorsPaginated();
        return $actors;
    }

    public function getById($id)
    {
        $actor = $this->actorRepository->getById(($id));
        return $actor;
    }
}
