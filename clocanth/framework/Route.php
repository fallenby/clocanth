<?php
        namespace Clocanth;

        class Route
        {
            protected $controller; /* Controller */

            protected $handler; /* string */

            protected $prefix; /* String */

            public function __construct($prefix, $controller, $handler)
            {
                if ($controller)
                {
                    $c = "App\\Controller\\$controller";
                    $this->controller = new $c();
                }
                $this->handler = $handler;
                $this->prefix = $prefix;
            }

            public function execute()
            {
                return $this->controller->{$this->handler}();
            }

            public function getPrefix()
            {
                return $this->prefix;
            }

            protected static function commit($method, $prefix, $signature)
            {
                global $ROUTE_LEVEL;
                $parts = Request::getParts();

                if (self::isInvalidRoute($prefix, $parts))
                {
                    return;
                } else
                {
                    array_splice($parts, $ROUTE_LEVEL + 1);
                    $prefix = '/' . implode('/', $parts);
                }

                $splitSig = explode('@', $signature, 2);

                $r = new Route($prefix, $splitSig[0], $splitSig[1]);

                RouteManager::instance()->addRoute(strtolower($method), $r);
            }

            public static function group($prefix, $handler)
            {
                global $ROUTE_LEVEL;
                $parts = Request::getParts();

                if (self::isInvalidRoute($prefix, $parts))
                {
                    return;
                }

                $route = new RouteGroup($prefix, null, $handler);
                RouteManager::instance()->addRoute('any', $route);
                $route->execute();
            }

            protected static function isInvalidRoute($prefix, $parts)
            {
                if ($prefix == '/')
                    return false;

                global $ROUTE_LEVEL;
                return (!isset($parts[$ROUTE_LEVEL]) || (($parts[$ROUTE_LEVEL] ? $parts[$ROUTE_LEVEL] : null) != $prefix));
            }

            public static function any($prefix, $signature)
            {
                self::commit('any', $prefix, $signature);
            }

            public static function get($prefix, $signature)
            {
                self::commit('get', $prefix, $signature);
            }

            public static function post($prefix, $signature)
            {
                self::commit('post', $prefix, $signature);
            }

            public static function put($prefix, $signature)
            {
                self::commit('put', $prefix, $signature);
            }
        }
