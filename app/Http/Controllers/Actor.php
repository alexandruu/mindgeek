<?php

namespace App\Http\Controllers;

use App\Interfaces\Actors;
use App\Models\Actor as ModelsActor;
use App\Services\ActorsImport;
use Illuminate\Http\Request;

class Actor extends Controller
{
    public function index(Request $request, Actors $actorsService)
    {
        $actors = $actorsService->getAll();

        return view('actor.index')
            ->with('error', $request->get('error'))
            ->with('actors', $actors);
    }

    public function show(ModelsActor $actor, Actors $actorsService)
    {
        $actor = $actorsService->getById($actor->id);

        return view('actor.show')
            ->with('actor', $actor);
    }

    public function store(Request $request, ActorsImport $actorsImport)
    {
        $error = null;

        try {
            $actorsImport->import('pornhub.actors');
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return redirect(route('actor.index', ['error' => $error]));
    }
}
