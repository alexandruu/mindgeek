<?php

namespace App\Http\Controllers;

ini_set('max_execution_time', '1200');
// ini_set('memory_limit', '-1');

use App\Enums\CategoryEnum;
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

    public function show($id, ActorsInterface $actorsService)
    {
        $actor = $actorsService->getById($id);

        return view('actors.show')
            ->with('actor', $actor);
    }

    public function store(Request $request, ImportService $import)
    {
        $error = null;

        try {
            $import->import(CategoryEnum::ACTORS);
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        return redirect(route('actors.index', ['error' => $error]));
    }
}
