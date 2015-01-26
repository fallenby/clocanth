<?php

        namespace Clocanth;

        class Config
        {
            public static $database;
            public static $environment;
            public static $crypto;
            public static $logging;

            public static function database($options = null)
            {
                if (!$options)
                    return self::$database;

                if (!is_array($options))
                    return self::$database[$options];

                self::$database = $options;
            }
            
            public static function environment($options = null)
            {
                if (!$options)
                    return self::$environment;

                if (!is_array($options))
                    return self::$environment[$options];

                self::$environment = $options;
            }

            public static function crypto($options = null)
            {
                if (!$options)
                    return self::$crypto;

                if (!is_array($options))
                    return self::$crypto[$options];

                self::$crypto = $options;
            }

            public static function logging($options = null)
            {
                if (!$options)
                    return self::$logging;

                if (!is_array($options))
                    return self::$logging[$options];

                self::$logging = $options;
            }
        }
