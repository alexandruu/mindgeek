<?php

namespace App\Http\Middleware;

use App\Interfaces\CacheInterface;
use Closure;
use Illuminate\Http\Request;

class ImageCacheMiddleware
{
    private CacheInterface $cacheService;

    public function __construct(CacheInterface $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function handle(Request $request, Closure $next)
    {
        $imagePath = $this->removeSlashFromTheBegining($request->getPathInfo());
        return response($this->cacheService->getFileContentFromCache($imagePath))->header('Content-Type', 'image/png');
    }

    private function removeSlashFromTheBegining($imagePath)
    {
        return ltrim($imagePath, '/');
    }
}
