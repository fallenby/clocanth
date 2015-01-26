<?php

        namespace Clocanth;

        use Clocanth\Config;

        class Sql
        {
            protected $ref;

            protected function init()
            {
                $dbconfig = Config::database();

                $this->ref = new \mysqli(
                    $dbconfig['host'],
                    $dbconfig['username'],
                    $dbconfig['password'],
                    $dbconfig['database']
                );
            }

            public function query($sql)
            {
                if (!$this->ref)
                    $this->init();

                if ($this->ref->connect_errno)
                    return null;

                $result = $this->ref->query($sql);

                if (!$result)
                    return null;

                return $result;
            }

            public function insert_id()
            {
                return $this->ref->insert_id;
            }

            public function escape($value)
            {
                return $this->ref->real_escape_string($value);
            }
        }
