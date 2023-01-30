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

        return response($this->cacheService->getFileContentFromCache($imagePath))
            ->withHeaders([
                'Content-Type' => 'image/png',
                'Cache-Control' => 'public, max_age=3600'
            ]);
    }

    private function removeSlashFromTheBegining($imagePath)
    {
        return ltrim($imagePath, '/');
    }
}
