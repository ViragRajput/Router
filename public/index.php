<?php

require __DIR__ . '/../vendor/autoload.php';

use ViragRouter\Route;
use App\Controllers\HomeController;
use App\Controllers\AboutController;
use App\Controllers\ContactController;

// Define your routes
Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

// Route::get('/', 'HomeController@index');

Route::get('/', function () {
    echo "Hello, World!";
});

// Handle incoming requests
$router = new \ViragRouter\Router();
$router->handle(new \App\Http\Request());
