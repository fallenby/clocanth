<?php
        namespace Clocanth;

        use Clocanth\RouteManager;

        class App
        {
            protected function __construct() { }

            public static function run()
            {
                return RouteManager::instance()->routeRequest();
            }
        }
