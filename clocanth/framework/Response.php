<?php

        namespace Clocanth;

        use Clocanth\Config;

        class Response
        {
            public static function json($data)
            {
                self::contentType('application/json');
                return json_encode($data);
            }

            public static function base64($data)
            {
                return base64_encode($data);
            }

            public static function error($code, $message)
            {
                return array(
                    'error' => array(
                        'code' => $code,
                        'message' => $message
                    )
                );
            }

            public static function asset($type, $file)
            {
                $path = realpath(__DIR__ . "/../../public/$type/$file");
                
                if (!$path)
                {
                    Response::code('404 Not Found');
                    return null;
                }

                // Set the correct Content-Type header depending on the file we're returning
                switch ($type)
                {
                case "css":
                    self::contentType('text/css');
                    break;
                case "js":
                    self::contentType('application/javascript');
                    break;
                case "img":
                    self::contentType(image_type_to_mime_type(exif_imagetype($path)));
                    break;
                }

                return file_get_contents($path);
            }

            public static function contentType($value)
            {
                self::setHeader('Content-Type', $value);
            }

            public static function code($value)
            {
                header($_SERVER['SERVER_PROTOCOL'] . ' ' . $value);
            }
            
            public static function setHeader($key, $value)
            {
                header($key . ': ' . $value);
            }

            public static function redirect($url, $params = null)
            {
                if (isset($params))
                {
                    $url .= '?';
                    $urlParams = array();
                    foreach ($params as $key=>$value)
                    {
                        $urlParams[] = $key . '=' . $value;
                    }

                    $urlParams = implode('&', $urlParams);
                    $url .= $urlParams;
                }

                header('Location: ' . $url);
                exit;
            }
        }
