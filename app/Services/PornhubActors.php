<?php

namespace App\Services;

use App\Interfaces\ActorsInterface;
use App\Repositories\ActorsRepository;

class PornhubActors implements ActorsInterface
{
    private ActorsRepository $actorsRepository;

    public function __construct(ActorsRepository $actorsRepository)
    {
        $this->actorsRepository = $actorsRepository;
    }

    public function getAll()
    {
        $actors = $this->actorsRepository->getActorsPaginated();
        return $actors;
    }

    public function getById($id)
    {
        $actor = $this->actorsRepository->getById(($id));
        return $actor;
    }
}
