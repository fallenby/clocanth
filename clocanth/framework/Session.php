<?php

        namespace Clocanth;

        class Session
        {
            public static function start()
            {
                return session_start();
            }

            public static function end()
            {
                $_SESSION = array();

                if (ini_get("session.use_cookies")) {
                    $params = session_get_cookie_params();
                    setcookie(session_name(), '', time() - 42000,
                        $params["path"], $params["domain"],
                        $params["secure"], $params["httponly"]
                    );
                }

                session_destroy();
            }

            public static function get($key)
            {
                if (isset($_SESSION[$key]))
                    return $_SESSION[$key];

                return null;
            }

            public static function set($key, $value)
            {
                $_SESSION[$key] = $value;
            }

            public static function delete($key)
            {
                $_SESSION[$key] = null;
            }
        }
