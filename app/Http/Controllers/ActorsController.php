<?php

namespace App\Http\Controllers;

ini_set('max_execution_time', '1200');

use App\Enums\CategoryEnum;
use App\Services\Actors\ActorService;
use App\Services\Http\HttpService;
use Illuminate\Http\Request;

class ActorsController extends Controller
{
    public function index(Request $request, ActorService $actorService)
    {
        $actors = $actorService->getAll();

        return view('actors.index')
            ->with('error', $request->get('error'))
            ->with('actors', $actors);
    }

    public function show($id, ActorService $actorService)
    {
        $actor = $actorService->getById($id);

        return view('actors.show')
            ->with('actor', $actor);
    }

    public function store(Request $request, HttpService $httpService)
    {
        $error = null;

        try {
            $httpService->import(CategoryEnum::ACTORS);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return redirect(route('actors.index', ['error' => $error]));
    }
}
