<?php

namespace App\Http\Middleware;

use App\Services\Caches\FileCache;
use Closure;
use Illuminate\Http\Request;

class ImageCacheMiddleware
{
    private FileCache $fileCache;

    public function __construct(FileCache $fileCache)
    {
        $this->fileCache = $fileCache;
    }

    public function handle(Request $request, Closure $next)
    {
        $imagePath = $this->removeSlashFromTheBegining($request->getPathInfo());
        return response($this->fileCache->getFileContentFromCache($imagePath))->header('Content-Type', 'image/png');
    }

    private function removeSlashFromTheBegining($imagePath)
    {
        return ltrim($imagePath, '/');
    }
}
