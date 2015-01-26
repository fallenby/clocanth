<?php

        namespace Clocanth;

        class Input
        {
            public static function get($options)
            {
                if (is_array($options))
                {
                    $variables = array();

                    foreach ($options as $key=>$value)
                    {
                        $v = $_REQUEST[$value];

                        if (!isset($v) || is_null($v))
                            return null;

                        $variables[$value] = $v;
                    }

                    return $variables;
                } else {
                    if (isset($_REQUEST[$options]))
                    {
                        return $_REQUEST[$options];
                    }

                    return null;
                }

                return null;
            }

            public static function base64($key)
            {
                return base64_decode(self::get($key));
            }

            public static function checkbox($key)
            {
                $var = Input::get($key);

                if ($var)
                    $var = ($var == 'on') ? 1 : 0;
                else
                    $var = 0;

                return $var;
            }
        }
