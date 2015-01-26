<?php

        namespace App\Controller;

        use Clocanth\View;

        class HelloController extends \Clocanth\Controller
        {
            public function hello()
            {
                return View::get('hello',
                    array(
                        'name' => 'Frank Allenby',
                        'guests' => array(
                            'Martin',
                            'Chris',
                            'Lyle',
                            'Moses'
                        )
                    )
                );
            }
        }
