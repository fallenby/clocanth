<?php
        namespace Clocanth;

        use Clocanth\Route;

        class RouteCollection
        {
            public $method; /* String */

            public $routes; /* Array of Route */

            public function __construct($method, $route = null)
            {
                $this->method = strtolower($method);

                $routes = array();

                if ($route)
                    $this->routes[] = $route;
            }

            public function add($route)
            {
                $this->routes[] = $route;
            }

            public function getMethod()
            {
                return $this->method;
            }

            public function getRouteForRequest()
            {
                $path = Request::getPath();

                foreach ($this->routes as $route)
                {
                    if (strtolower($route->getPrefix()) == $path)
                    {
                        return $route;
                    }
                }

                return null;
            }
        }
