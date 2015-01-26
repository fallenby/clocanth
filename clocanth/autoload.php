<?php
        define('CLOCANTH_START_TIME', microtime(true));

        require __DIR__.'/../vendor/autoload.php';

        /*
         * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
         * !!!                                WARNING                                !!!
         * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
         * !!!                                                                       !!!
         * !!! Be very careful about changing the order of the below items!          !!!
         * !!!                                                                       !!!
         * !!! Some items depend on others, and changing the order might violate     !!!
         * !!! those dependencies and cause fun errors you don't want to deal with.  !!!
         * !!!                                                                       !!!
         * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
         * !!!                                WARNING                                !!!
         * !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
         */

        require __DIR__.'/loaders/database.php';
        require __DIR__.'/loaders/environment.php';
        require __DIR__.'/loaders/crypto.php';
        require __DIR__.'/loaders/routes.php';
        require __DIR__.'/loaders/twig.php';
        require __DIR__.'/loaders/logging.php';
