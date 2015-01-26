<?php

        namespace Clocanth;

        class Request
        {
            const DELIM_PATH = '/';
            const DELIM_SCHEME = '://';

            public static function getFullPath()
            {
                return $_SERVER['REQUEST_SCHEME'] . self::DELIM_SCHEME . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            }

            public static function getPath()
            {
                $splitParts = explode('/', $_SERVER['PHP_SELF']);
                array_pop($splitParts);
                return str_replace(implode('/', $splitParts), '', self::removeSlashDigits(strtok($_SERVER['REQUEST_URI'], '?')));
            }

            public static function getMethod()
            {
                return $_SERVER['REQUEST_METHOD'];
            }

            public static function getParts()
            {
                $splitParts = explode('/', $_SERVER['PHP_SELF']);
                array_pop($splitParts);
                $splitParts = explode('/', str_replace(implode('/', $splitParts), '', self::removeSlashDigits(strtok($_SERVER['REQUEST_URI'], '?'))));

                $validParts = array();

                foreach ($splitParts as $part)
                {
                    if (strlen($part) > 0)
                    {
                        $validParts[] = $part;
                    }
                }
                return $validParts;
            }

            private static function removeSlashDigits($string)
            {
                return preg_replace("/\/[0-9]+/", "", $string);
            }

            public static function referer($removeQs = true)
            {
                if (!isset($_SERVER['HTTP_REFERER']))
                    return null;

                if ($removeQs)
                    return strtok($_SERVER['HTTP_REFERER'], '?');
                else
                    return $_SERVER['HTTP_REFERER'];
            }
        }
