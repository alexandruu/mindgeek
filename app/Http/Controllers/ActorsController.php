<?php

namespace App\Http\Controllers;

ini_set('max_execution_time', '1200');

use App\Factories\HttpStreamFactory;
use App\Services\Actors\ActorService;
use App\Services\Normalizers\Providers\PornhubResponseNormalizer;
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

    public function store(Request $request, ActorService $actorService)
    {
        $error = null;

        try {
            $actors = HttpStreamFactory::import([
                'endpoint' => env('ENDPOINT_ACTOR_PROVIDER_1'),
                'normalizer' => PornhubResponseNormalizer::class,
                'limit' => 15000
            ]);

            foreach ($actors as $actor) {
                $actorService->saveFromArray($actor);
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return redirect(route('actors.index', ['error' => $error]));
    }
}
