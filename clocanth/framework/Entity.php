<?php

        namespace Clocanth;

        class Entity 
        {
            private $attributes;

            public function __construct($attributes = null /* mysqli_result of 'DEFINE <table>' statement */)
            {
                while ($attr = $attributes->fetch_array())
                {
                    $this->attributes[] = $attr['Field'];
                }
            }

            public function getAttributes()
            {
                return $this->attributes;
            }

            public function get($key)
            {
                return $this->getAttributeByKey($key);
            }

            public function set($key, $value)
            {
                return $this->setAttributeByKey($key, $value);
            }

            protected function setAttributeByKey($key, $value)
            {
                if (!$this->attributes)
                    $this->attributes = array();

                $this->attributes[$key] = $value;

                return true;
            }

            protected function getAttributeByKey($key)
            {
                if (!$this->attributes)
                    return null;

                if (!isset($this->attributes[$key]))
                    return null;

                return $this->attributes[$key];
            }
        }
