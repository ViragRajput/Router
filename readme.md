# ViragRouter

ViragRouter is a lightweight PHP router package that allows you to easily define and handle routes in your PHP applications.

## Installation

You can install ViragRouter via Composer:

```bash
composer require viragrajput/viragrouter
```

## Run local PHP Server
```
php -S localhost:8000 -t public
```

## Usage

### Creating Routes

You can define routes using the `Route` class provided by ViragRouter. Here's an example of how to define a route:

```php
use ViragRouter\Route;

Route::get('/hello', function () {
    echo "Hello, World!";
});

Route::get('/hello', [HelloController::class, 'index']);
```

### Route Parameters

You can define route parameters by enclosing them in curly braces `{}`. These parameters will be passed to your route handler. Here's an example:

```php
use ViragRouter\Route;

Route::get('/users/{id}', function ($id) {
    echo "User ID: $id";
});

Route::get('/users/{id}', [UserController::class, 'show']);
```

### Route Groups

You can group routes and apply common attributes using the `group` method. Here's an example:

```php
use ViragRouter\Route;

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
use ViragRouter\Route;

Route::get('/profile', 'ProfileController@index')->name('profile');

Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
```

### Middleware

You can apply middleware to routes using the `middleware` method. Middleware allows you to filter HTTP requests entering your application. Here's an example:

```php
use ViragRouter\Route;

Route::get('/admin', 'AdminController@index')->middleware('admin');

Route::get('/admin', [AdminController::class, 'index'])->middleware('admin');
```

### Generating URLs

You can generate URLs for named routes using the `generateUrl` method. Here's an example:

```php
use ViragRouter\Route;

$url = Route::generateUrl('profile');
echo "Profile URL: $url";
```

### Handling Requests

You can handle incoming requests using the `handle` method of the `Router` class. Here's an example:

```php
use ViragRouter\Router;

$router = new Router();
$request = new MyRequestImplementation(); // Implement RequestInterface
$router->handle($request);
```

## Request Interface

ViragRouter expects a request object that implements the `RequestInterface`. Here's an example of the interface:

```php
namespace ViragRouter;

interface RequestInterface
{
    public function getMethod(): string;
    public function getUri(): string;
}
```

## How To Use This Package in Your Custom PHP Project

Certainly! Below is an example of how you can integrate the ViragRouter package into your custom PHP Project:

### Step 1: Install ViragRouter via Composer

First, install the ViragRouter package via Composer:

```bash
composer require viragrajput/viragrouter
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
use YourCustomProject\Request; // Assuming you have a custom request implementation

$router = new Router();

$request = new Request($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

$router->handle($request);
```

### Step 4: Implement Your Custom Request Class

Ensure that you have a custom request class that implements the `RequestInterface`. This class should provide methods to retrieve the HTTP method and URI. Here's an example:

```php
// YourCustomProject/Request.php

namespace YourCustomProject;

use ViragRouter\RequestInterface;

class Request implements RequestInterface
{
    private $method;
    private $uri;

    public function __construct($method, $uri)
    {
        $this->method = $method;
        $this->uri = $uri;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): string
    {
        return $this->uri;
    }
}
```

### Step 5: Handle Requests in Your Custom Project

In your custom Project, ensure that you have logic to handle requests and invoke the appropriate route handlers based on the incoming requests.

### Step 6: Additional Configuration (Optional)

Depending on your custom Project's architecture and requirements, you may need to configure additional features such as middleware, route groups, named routes, etc., provided by ViragRouter.

By following these steps, you should be able to integrate the ViragRouter package into your custom PHP project and define and handle routes effectively. Adjustments may be needed based on your project's specific requirements and architecture.

## License

This package is open-source software licensed under the [MIT license](LICENSE).

---
