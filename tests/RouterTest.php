<?php

use PHPUnit\Framework\TestCase;
use ViragRouter\Route;
use ViragRouter\Router;
use App\Controllers\HomeController;
use App\Controllers\AboutController;

class RouterTest extends TestCase
{
    public function testRouteMatching()
    {
        // Create a router instance
        $router = new Router();

        // Define routes using Route::get() method
        Route::get('/', function () {
            return 'Home Page';
        });

        Route::get('/about', function () {
            return 'About Page';
        });

        // Simulate requests and check responses
        $this->assertEquals('Home Page', $this->simulateRequest($router, 'GET', '/'));
        $this->assertEquals('About Page', $this->simulateRequest($router, 'GET', '/about'));
        $this->assertNull($this->simulateRequest($router, 'GET', '/contact')); // Assuming /contact route is not defined
    }

    private function simulateRequest(Router $router, $method, $uri)
    {
        ob_start(); // Start output buffering
        $router->handle($this->createRequestMock($method, $uri));
        $output = ob_get_clean(); // Get the output buffer content and clean the buffer
        return $output ?: null; // Return null if output is empty
    }

    private function createRequestMock($method, $uri)
    {
        return new class($method, $uri)
        {
            private $method;
            private $uri;

            public function __construct($method, $uri)
            {
                $this->method = $method;
                $this->uri = $uri;
            }

            public function getMethod()
            {
                return $this->method;
            }

            public function getUri()
            {
                return $this->uri;
            }
        };
    }
}

