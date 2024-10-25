<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use League\Glide\ServerFactory;
use League\Glide\Responses\LaravelResponseFactory;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified','verify_admin','revalidate'])->name('dashboard');

Route::middleware(['auth','verify_admin','revalidate'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Our resource routes
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});

Route::get('phpinfo', function(){
    return phpinfo();
});

Route::get('demo/images/{path}', function ($path) {
    // dd($path);
    $server = ServerFactory::create([
        'source' => public_path('images'), // Correct path to public/images
        'cache' => storage_path('app/public/cache'), // Cache directory for processed images
        'cache_path_prefix' => 'cache',
    ]);

    // But, a better approach is to use information from the request
    $server->outputImage($path, $_GET);

    $server->deleteCache($path);


    $response = $server->getImageResponse($path, request()->all());

    // return Response::make($response->getBody(), $response->getStatusCode(), $response->getHeaders());
});


Route::get('clear-cache', function(){
    $server = ServerFactory::create([
        'source' => public_path('images'), // Correct path to public/images
        'cache' => storage_path('app/public/cache'), // Cache directory for processed images
        'cache_path_prefix' => 'cache',
    ]);

    $server->deleteCache('kayaks.jpg');

    return 'Cache has been cleared';
});


require __DIR__.'/auth.php';
