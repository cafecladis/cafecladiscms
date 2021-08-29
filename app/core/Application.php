<?php

declare(strict_types=1);

/**
 * Class Application
 *
 * @package CafeCladisCMS
 * @author Frau Cladis <hey@fraucladis.de>
 * @version 0.0.1
 * @copyright Copyright (c) 2021, Café Cladis
 * @license GPL
 */
class Application
{

    /** @var string|object Controller class name or instance of controller class */
    private string|object $controller;
    /** @var string Method name */
    private string $method;
    /** @var array Additional parameters */
    private array $parameters = [];

    /**
     * Constructor method
     * @throws Exception
     */
    public function __construct()
    {
        // Get elements of the request url
        $request = $this->parseRequestUri();

        // If a controller is called without a method exit with 404 NOT FOUND
        if (!empty($request[0]) && empty($request[1])) {
            // TODO: Handle HTTP 404 response
            exit('404 NOT FOUND');
        }

        // Set controller
        switch ($request[0]) {
            case '':
            case 'page':
                $this->controller = 'Page';
                break;
            default:
                exit('404 NOT FOUND');
        }

        unset($request[0]);

        $this->loadController();

        // Set method
        $this->method = !empty($request[1]) ? $request[1] : 'home';

        // Valid methods
        $validMethods = match (get_class($this->controller)) {
            'Page' => ['home']
        };

        // If method is not allowed or method doesn't exist exit with 404 NOT FOUND
        if (!in_array($this->method, $validMethods) || !method_exists($this->controller, $this->method)) {
            exit('404 NOT FOUND');
        }

        unset($request[1]);

        // Remaining parts of the request url are additional parameters
        // TODO: Handle too many parameters in request url
        $this->parameters = $request ? array_values($request) : [];

        unset($request);

        // Call method in controller class and pass additional parameters
        call_user_func_array([$this->controller, $this->method], $this->parameters);
    }

    /**
     * Splits parts of the request url into an array for further usage.
     *
     * @return array Elements of request uri
     */
    private function parseRequestUri(): array
    {
        // Sanitize content of $_SERVER['REQUEST_URI']
        $requestUri = filter_input(
            INPUT_SERVER,
            'REQUEST_URI',
            FILTER_SANITIZE_URL
        );
        // Delete slashes from beginning and end of $_SERVER['REQUEST_URI']
        $requestUri = trim($requestUri, '/');
        // Split request parts into an array seperated by /
        $requestUri = explode('/', $requestUri);
        // Remove paths from filename strings and return sanitized array
        return array_map('basename', $requestUri);
    }

    /**
     * Loads controller class file and makes a new instance of controller class.
     *
     * @throws Exception Controller file not found, Controller class doesn't exist
     * @return void
     */
    private function loadController(): void
    {
        // Path to controller file
        $controllerFile = APP_ROOT . '/controller/' . $this->controller . '.php';

        // Check if controller file exists
        if (!is_file($controllerFile)) {
            throw new Exception('Controller class file not found: ' . $controllerFile);
        }

        // Load controller file
        require $controllerFile;

        // Check if class exists
        if (!class_exists($this->controller)) {
            throw new Exception('Missing controller class:' . $this->controller);
        }

        // Instantiate class
        $this->controller = new $this->controller();
    }
}
