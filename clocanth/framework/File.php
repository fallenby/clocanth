<?php

        namespace Clocanth;

        class File
        {
            public static function xml($filename)
            {
                return simplexml_load_file(__DIR__ . "/../../app/files/$filename");
            }
        }
