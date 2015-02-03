<?php

        namespace App\Controller;

        use App\ClocanthView;

        use Clocanth\Config;

        class IndexController extends \Clocanth\Controller
        {
            public function index()
            {
                return ClocanthView::get('index', array(
                    'name' => 'Andrew',
                    'links' => array(
                        array('title'=>'Users', 'url'=>Config::environment('base_url').'users/all'),
                        array('title'=>'Google', 'url'=>'http://www.google.com')
                    )
                ));
            }
        }
