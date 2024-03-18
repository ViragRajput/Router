<?php

require __DIR__ . '/../vendor/autoload.php';

use ViragRouter\Route;
use ViragRouter\Router;
use App\Controllers\HomeController;
use App\Controllers\AboutController;
use App\Controllers\ContactController;

// Define your routes
// Route::get('/', [HomeController::class, 'index']);
// Route::get('/about', [AboutController::class, 'index'])->name('about');
// Route::get('/contact', [ContactController::class, 'index'])->name('contact');

$router = new Router();

// Define routes using Route::get() method
// $router->get('/', function () {
//     return 'Home Page';
// });

// $router->get('/about', function () {
//     return 'About Page';
// });

// Route::get('/', 'HomeController@index');

Route::get('/', function () {
    echo "Hello, World!";
});

// Handle incoming requests
$router->handle(new \App\Http\Request());
