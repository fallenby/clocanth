<?php
        use Clocanth\Config;
        use Clocanth\Request;

        Twig_Autoloader::register();

        $twig_loader = new Twig_Loader_Filesystem(__DIR__.'/../../app/views');
        $twig = new Twig_Environment($twig_loader);

        $config = Config::environment();

        $twig->addGlobal('base_url', $config['base_url']);
        $twig->addGlobal('current_url', Request::getPath());
