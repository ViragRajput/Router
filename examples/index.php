<?php

require __DIR__ . '/../vendor/autoload.php';

use ViragRouter\Route;
use ViragRouter\Router;
use Examples\App\Controllers\HomeController;
use Examples\App\Controllers\AboutController;
use Examples\App\Controllers\ContactController;

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define your routes
Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');

$router = new Router();



// Handle incoming requests
$router->handle(new \Examples\App\Http\Request());
