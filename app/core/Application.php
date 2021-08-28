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
        if(!empty($request[0]) && !isset($request[1])) {
            echo '404 NOT FOUND';
            exit;
        }

        $controllerFileName = !empty($request[0]) ? ucwords($request[0]) : 'Page';
        $controllerFile = APP_ROOT . '/controller/' . $controllerFileName . '.php';

        // Set controller
        if (is_file($controllerFile)) {
            $this->controller = $controllerFileName;
            unset($request[0]);

            require $controllerFile;

            if (class_exists($this->controller)) {
                $this->controller = new $this->controller();
            }
        }

        // Set method
        $this->method = !empty($request[1]) ? $request[1] : 'home';
        unset($request[1]);

        // If method doesn't exist exit with 404 NOT FOUND
        if (!method_exists($this->controller, $this->method)) {
            echo '404 NOT FOUND';
            exit;
        }

        // Set parameters
        $this->parameters = $request ? array_values($request) : [];

        // Call method in controller and pass additional parameters
        call_user_func_array([$this->controller, $this->method], $this->parameters);
    }

    /**
     * Splits parts of the request url into an array for further usage.
     *
     * @return array Parts of request uri
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
}
