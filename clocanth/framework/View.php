<?php
        namespace Clocanth;

        use Clocanth\Renderable;

        class View implements Renderable
        {
            protected $name;

            protected $twig_handler;

            public function __construct($name)
            {
                global $twig;
                $this->twig_handler = $twig;

                $this->name = $name . '.twig.html';
                $twig->loadTemplate($this->name);
            }

            public static function get($name, $options = array())
            {
                $v = new self($name);
                return $v->render($options);
            }

            public function render($options = array())
            {
                return $this->twig_handler->render($this->name, $options);
            }
        }
