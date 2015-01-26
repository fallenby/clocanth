<?php
        namespace Clocanth;

        use Clocanth\RouteCollection;
        use Clocanth\Request;

        class RouteManager
        {
            protected $routeCollections; /* Array of RouteCollection */

            protected static $instance;

            protected function __construct()
            {
                $this->routeCollections = array();
            }

            public static function instance()
            {
                if (!self::$instance)
                    self::$instance = new RouteManager();

                return self::$instance;
            }

            public function addRoute($method, $route)
            {
                $collection = $this->getCollectionForMethod($method);

                if ($collection)
                {
                    $collection->add($route);
                    return;
                }

                $rc = new RouteCollection($method); 
                $rc->add($route);
                $this->routeCollections[] = $rc;
            }

            public function routeRequest()
            {
                $collection = $this->getCollectionForMethod(Request::getMethod());

                if (!$collection)
                {
                    Response::code('404 Not Found');
                    return 'No valid route collection found for request.';
                }

                $route = $collection->getRouteForRequest();

                if (!$route)
                {
                    Response::code('404 Not Found');
                    return 'No valid route found for request.';
                }

                return $route->execute();
            }

            protected function getCollectionForMethod($method)
            {
                foreach ($this->routeCollections as $collection)
                {
                    if ($collection->getMethod() == 'any')
                    {
                        $route = $collection->getRouteForRequest();
                        if ($route)
                        {
                            return $collection;
                        }
                    } else if ($collection->getMethod() == strtolower($method))
                    {
                        return $collection;
                    }
                }

                return null;
            }

            private function __clone()
            {
            }

            private function __wakeup()
            {
            }
        }
