<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AngularAppController extends Controller
{
    public function __invoke(?string $path = null): BinaryFileResponse
    {
        $buildDirectory = public_path('angular-build');
        $asset = $path ? realpath($buildDirectory.DIRECTORY_SEPARATOR.$path) : false;
        $buildRoot = realpath($buildDirectory);

        if ($asset && $buildRoot && str_starts_with($asset, $buildRoot.DIRECTORY_SEPARATOR) && is_file($asset)) {
            $contentType = match (strtolower(pathinfo($asset, PATHINFO_EXTENSION))) {
                'js', 'mjs' => 'application/javascript; charset=UTF-8',
                'css' => 'text/css; charset=UTF-8',
                'json' => 'application/json; charset=UTF-8',
                'ico' => 'image/x-icon',
                'svg' => 'image/svg+xml',
                'png' => 'image/png',
                'jpg', 'jpeg' => 'image/jpeg',
                'webp' => 'image/webp',
                'woff' => 'font/woff',
                'woff2' => 'font/woff2',
                default => 'application/octet-stream',
            };

            return response()->file($asset, ['Content-Type' => $contentType]);
        }

        $index = $buildDirectory.DIRECTORY_SEPARATOR.'index.html';

        abort_unless(is_file($index), 404, 'Angular aplikacija nije izgrađena.');

        return response()->file($index);
    }
}
