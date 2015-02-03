<?php

        namespace App;

        use Clocanth\View;
        use Clocanth\Session;

        class ClocanthView extends View
        {
            public static function get($view, $options = array())
            {
                $meta = array(
                    array('name'=>'robots', 'content'=>'index')
                );

                $styles = array(
                    'custom.css',
                    'index.css'
                );

                $scripts = array(
                    'custom.js',
                    'index.js',
                    'style.js'
                );

                $viewVars = array(
                    'metas' => $meta,
                    'styles' => $styles,
                    'scripts' => $scripts
                );

                return View::get($view, array_merge($options, $viewVars));
            }
        }
