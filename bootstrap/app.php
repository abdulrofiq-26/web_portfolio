<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// 1. Buat dan simpan aplikasinya ke dalam variabel $app terlebih dahulu
$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// 2. Ubah path storage HANYA jika sedang berjalan di Vercel
if (isset($_ENV['VERCEL'])) {
    $app->useStoragePath('/tmp/storage');
}

// 3. Kembalikan aplikasi yang sudah dimodifikasi
return $app;