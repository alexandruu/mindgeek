<?php

namespace App\Http\Middleware;

use App\Services\FileCache;
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
        $imagePath = $this->removeSlashFromLeftPart($request->getPathInfo());
        return response($this->fileCache->getFileContentFromCache($imagePath))->header('Content-Type', 'image/png');
    }

    private function removeSlashFromLeftPart($imagePath)
    {
        return ltrim($imagePath, '/');
    }
}
