# Virag Router

ViragRouter is a lightweight PHP router package that allows you to easily define and handle routes in your PHP applications.

With ViragRouter, you're equipped with a robust suite of capabilities essential for efficient route handling: seamless route definition, dynamic parameter management, route grouping with middleware support, named routes for URL generation, and more. Its lightweight architecture guarantees minimal overhead, delivering optimal performance without sacrificing functionality.

## Note :

ViragRouter is actively developed and continuously enhanced. While it's currently in its developmental phase and may contain occasional bugs, so  it is not recommended for production use at this time. We appreciate your patience and understanding as i work towards delivering a stable and reliable product. However, you can safely use it for learning purposes, exploring its features, and exploring advanced routing concepts in PHP.

## Installation

You can install ViragRouter via Composer:

```bash
composer require viragrajput/router
```

## Run local PHP Server
```
php -S localhost:8000 -t public
```

## Usage

### Creating Routes

You can define routes using the `Route` class provided by ViragRouter. Here's an example of how to define a route:

```php
use Virag\Router\Route;

Route::get('/hello', function () {
    echo "Hello, World!";
});

Route::get('/hello', [HelloController::class, 'index']);
```

### Route Parameters

You can define route parameters by enclosing them in curly braces `{}`. These parameters will be passed to your route handler. Here's an example:

```php
use Virag\Router\Route;

Route::get('/users/{id}', function ($id) {
    echo "User ID: $id";
});

Route::get('/users/{id}', [UserController::class, 'show']);
```

### Route Groups

You can group routes and apply common attributes using the `group` method. Here's an example:

```php
use Virag\Router\Route;

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', 'DashboardController@index');
    Route::get('/settings', 'SettingsController@index');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
});
```

### Named Routes

You can name your routes using the `name` method. Named routes allow you to generate URLs based on the route name. Here's an example:

```php
use Virag\Router\Route;

Route::get('/profile', 'ProfileController@index')->name('profile');

Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
```

### Middleware

You can apply middleware to routes using the `middleware` method. Middleware allows you to filter HTTP requests entering your application. Here's an example:

```php
use Virag\Router\Route;

Route::get('/admin', 'AdminController@index')->middleware('admin');

Route::get('/admin', [AdminController::class, 'index'])->middleware('admin');
```

### Generating URLs

You can generate URLs for named routes using the `generateUrl` method. Here's an example:

```php
use Virag\Router\Route;

$url = Route::generateUrl('profile');
echo "Profile URL: $url";
```

### Handling Requests

You can handle incoming requests using the dispatch method of the Router class. Here's an example:

```php
use Virag\HttpFoundation\Request;
use Virag\HttpFoundation\Response;

$router = new Router();

$request = Request::createFromGlobals();
$response = $router->dispatch($request);
$response->send();
```


## How To Use This Package in Your Custom PHP Project

Certainly! Below is an example of how you can integrate the ViragRouter package into your custom PHP Project:

### Step 1: Install ViragRouter via Composer

First, install the ViragRouter package via Composer:

```bash
composer require viragrajput/router
```

### Step 2: Define Routes in Your Custom Project

In your custom Project, you'll need to define routes using the ViragRouter `Route` class. Here's an example of how you can define routes in your Project:

```php
// index.php

require_once 'vendor/autoload.php';

use ViragRouter\Route;

Route::get('/', function () {
    echo "Welcome to my custom Project!";
});

Route::get('/hello/{name}', function ($name) {
    echo "Hello, $name!";
});
```

### Step 3: Create a Router Instance and Handle Requests

In your Project entry point (e.g., `index.php`), create a router instance and handle incoming requests:

```php
// index.php

require_once 'vendor/autoload.php';

use ViragRouter\Router;
use Virag\HttpFoundation\Request;
use Virag\HttpFoundation\Response;

$router = new Router();

$request = Request::createFromGlobals();
$response = $router->dispatch($request);
$response->send();
```

### Step 4: Handle Requests in Your Custom Project

In your custom Project, ensure that you have logic to handle requests and invoke the appropriate route handlers based on the incoming requests.

### Step 6: Additional Configuration (Optional)

Depending on your custom Project's architecture and requirements, you may need to configure additional features such as middleware, route groups, named routes, etc., provided by ViragRouter.

By following these steps, you should be able to integrate the ViragRouter package into your custom PHP project and define and handle routes effectively. Adjustments may be needed based on your project's specific requirements and architecture.

## License

This package is open-source software licensed under the [MIT license](LICENSE).

---
