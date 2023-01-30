<?php

namespace App\Http\Controllers;

ini_set('max_execution_time', '1200');

use App\Dtos\Provider;
use App\Dtos\ProviderDto;
use App\Enums\CategoryEnum;
use App\Enums\HttpTypeEnum;
use App\Factories\HttpStreamFactory;
use App\Services\Actors\ActorService;
use App\Services\Actors\ActorsSavedInChuncksService;
use App\Services\Http\HttpService;
use App\Services\Http\Requests\StreamRequest;
use App\Services\Http\Responses\StreamResponse;
use App\Services\Normalizers\Providers\PornhubResponseNormalizer;
use App\Services\Savers\Providers\ActorsSaver;
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

    public function store(Request $request, ActorsSavedInChuncksService $actorsSavedInChuncksService)
    {
        $error = null;

        try {
            $actors = HttpStreamFactory::import([
                'endpoint' => 'https://www.pornhub.com/files/json_feed_pornstars.json',
                'normalizer' => PornhubResponseNormalizer::class,
                'limit' => 1000
            ]);

            foreach ($actors as $actor) {
                $actorsSavedInChuncksService->save($actor);
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return redirect(route('actors.index', ['error' => $error]));
    }
}
