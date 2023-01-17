<?php

namespace App\Http\Controllers;

ini_set('max_execution_time', '1200');
// ini_set('memory_limit', '-1');

use App\Interfaces\ActorsInterface;
use App\Models\Actor;
use App\Services\Import\ImportService;
use Illuminate\Http\Request;

class ActorsController extends Controller
{
    public function index(Request $request, ActorsInterface $actorsService)
    {
        $actors = $actorsService->getAll();

        return view('actors.index')
            ->with('error', $request->get('error'))
            ->with('actors', $actors);
    }

    public function show(Actor $actor)
    {
        return view('actors.show')
            ->with('actor', $actor);
    }

    public function store(Request $request, ImportService $import)
    {
        $error = null;

        try {
            $import->import('pornhub.actors');
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return redirect(route('actors.index', ['error' => $error]));
    }
}
