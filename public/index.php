<?php
        error_reporting(E_ALL | E_STRICT);
        ini_set('display_errors', 1);

        require __DIR__.'/../clocanth/autoload.php';

        echo Clocanth\App::run();
