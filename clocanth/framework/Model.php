<?php

        namespace Clocanth;

        use Clocanth\Entity;
        use Clocanth\Sql;
        use Clocanth\Config;

        class Model
        {
            protected $primary_key = 'id';

            protected $table = null;

            protected $attributes = null;

            private $sql;

            private $entity;

            protected $dbconfig;

            private $operators = array(
                'LARGER_THAN' => '>',
                'SMALLER_THAN' => '<',
                'EQUALS' => '=',
                'LIKE' => 'LIKE',
                'IS' => 'IS',
                'ASC' => 'ASC',
                'DESC' => 'DESC'
            );

            const LARGER_THAN = '>';
            const EQUALS = '=';
            const IS = 'IS';
            const ASC = 'ASC';
            const DESC = 'DESC';

            public function __construct()
            {
                if (!$this->table)
                    user_error('Attempted to create a Model but no table specified', E_USER_ERROR);

                $this->sql = new Sql();

                $this->dbconfig = Config::database();

                $this->createEntity();
            }

            private function createEntity()
            {
                if (!$this->table)
                    return false;

                $dbName = $this->dbconfig['database'];
                $columns = $this->sql->query("DESCRIBE `$dbName`.`$this->table`");

                if (!$columns)
                    user_error('Attempted to initialize Model entity but could not. Ensure that you have set the correct table name.', E_USER_ERROR);

                $this->entity = new Entity($columns);
            }

            public function byId($id, $encrypted_fields = null, $enc_key = null)
            {
                $id = $this->sql->escape($id);
                $dbName = $this->sql->escape($this->dbconfig['database']);

                $fields = '*';

                if ($encrypted_fields && $enc_key)
                {
                    $fields = array();

                    $attributes = $this->entity->getAttributes();
                    foreach ($attributes as $attribute)
                    {
                        if (in_array($attribute, $encrypted_fields))
                            $fields[] = "AES_DECRYPT(`$attribute`, '$enc_key') AS `$attribute`";
                        else
                            $fields[] = "`$attribute`"; 
                    }

                    $fields = implode(',', $fields);
                }

                $result = $this->sql->query("SELECT $fields FROM `$dbName`.`$this->table` WHERE `$this->primary_key` = '$id'");

                if ($result)
                    return $result->fetch_array(MYSQLI_ASSOC);

                return null;
            }

            public function all($encrypted_fields = null, $enc_key = null, $order_by = null)
            {
                $dbName = $this->sql->escape($this->dbconfig['database']);

                $fields = '*';

                if ($encrypted_fields && $enc_key)
                {
                    $fields = array();

                    $attributes = $this->entity->getAttributes();
                    foreach ($attributes as $attribute)
                    {
                        if (in_array($attribute, $encrypted_fields))
                            $fields[] = "AES_DECRYPT(`{$this->sql->escape($attribute)}`, '{$this->sql->escape($enc_key)}') AS `{$this->sql->escape($attribute)}`";
                        else
                            $fields[] = "`{$this->sql->escape($attribute)}`"; 
                    }

                    $fields = implode(',', $fields);
                }

                $result = $this->sql->query("SELECT $fields FROM `$dbName`.`$this->table` {$this->getOrderBy($order_by)}");

                if (is_null($result))
                    return array();

                $results_array = array();
                while ($row = $result->fetch_assoc()) {
                    $results_array[] = $row;
                }

                return $results_array;
            }

            public function find($options, $field_names = null, $encrypted_fields = null, $enc_key = null, $order_by = null)
            {
                $where = array();
                foreach ($options as $key=>$value)
                {
                    $whereData = $this->correctValue($value);

                    $operator = is_array($value) ? $this->correctOperator($value['operator']) : self::EQUALS;

                    if ($encrypted_fields && $key && in_array($key, $encrypted_fields))
                        $where[] =  "AES_DECRYPT(`{$this->sql->escape($key)}`, '{$this->sql->escape($enc_key)}') $operator $whereData";
                    else
                        $where[] =  "`{$this->sql->escape($key)}` $operator $whereData";
                }
                $where = implode(' AND ', $where);

                $dbName = $this->sql->escape($this->dbconfig['database']);

                $fields = '*';

                if ($field_names)
                {
                    $fields = array();
                    foreach ($field_names as $key=>$value)
                    {
                        if (is_array($value) && isset($value['decrypt']) && $value['decrypt'] == true)
                        {
                            $fields[] = "AES_DECRYPT(`{$this->sql->escape($value['value'])}`, '{$value['key']}') AS `{$this->sql->escape($value['value'])}`";
                        }
                        else
                        {
                            $fields[] = "`{$this->sql->escape($value)}`";
                        }
                    }
                    $fields = implode(',', $fields);
                }
                else if ($encrypted_fields && $enc_key)
                {
                    $fields = array();

                    $attributes = $this->entity->getAttributes();
                    foreach ($attributes as $attribute)
                    {
                        if (in_array($attribute, $encrypted_fields))
                            $fields[] = "AES_DECRYPT(`{$this->sql->escape($attribute)}`, '{$this->sql->escape($enc_key)}') AS `{$this->sql->escape($attribute)}`";
                        else
                            $fields[] = "`{$this->sql->escape($attribute)}`"; 
                    }

                    $fields = implode(',', $fields);
                }

                $result = $this->sql->query("SELECT $fields FROM `$dbName`.`$this->table` WHERE $where {$this->getOrderBy($order_by)}");

                if (is_null($result))
                    return array();

                $results_array = array();
                while ($row = $result->fetch_assoc()) {
                    $results_array[] = $row;
                }

                return $results_array;
            }

            public function insert($options)
            {
                $keys = array();
                $values = array();

                foreach ($options as $key=>$value)
                {
                    $keys[] =  "`{$this->sql->escape($key)}`";

                    if (is_array($value) && isset($value['encrypt']) && $value['encrypt'] == true)
                        $values[] = "AES_ENCRYPT({$this->correctValue($value)}, '{$value['key']}')";
                    else
                        $values[] = $this->correctValue($value);
                }

                $keys = implode(',', $keys);
                $values = implode(',', $values);

                $dbName = $this->sql->escape($this->dbconfig['database']);

                $result = $this->sql->query("INSERT INTO `$dbName`.`$this->table` ($keys) VALUES ($values)");

                return $this->sql->insert_id();
            }

            public function update($id, $options)
            {
                $id = $this->sql->escape($id);

                $set = array();

                foreach ($options as $key=>$value)
                {
                    $setData = null;

                    if (is_array($value) && isset($value['encrypt']) && $value['encrypt'] == true)
                        $setData = "AES_ENCRYPT({$this->correctValue($value)}, '{$value['key']}')";
                    else
                        $setData = $this->correctValue($value);

                    $set[] =  "`{$this->sql->escape($key)}` = $setData";
                }

                $set = implode(',', $set);

                $dbName = $this->sql->escape($this->dbconfig['database']);
                $result = $this->sql->query("UPDATE `$dbName`.`$this->table` SET $set WHERE `$this->primary_key` = $id");

                if (is_null($result))
                    return 0;

                return $result;
            }

            public function count($options = null)
            {
                $dbName = $this->sql->escape($this->dbconfig['database']);

                $where = '';
                if ($options)
                {
                    $where = array();
                    foreach ($options as $key=>$value)
                    {
                        $whereData = $this->correctValue($value);

                        $operator = is_array($value) ? $this->correctOperator($value['operator']) : self::EQUALS;

                        $where[] =  "`{$this->sql->escape($key)}` $operator $whereData";
                    }

                    $where = 'WHERE' . implode(' AND ', $where);
                }

                $result = $this->sql->query("SELECT COUNT(`$this->primary_key`) AS count_value FROM `$dbName`.`$this->table` $where");

                if ($result)
                {
                    $result = $result->fetch_array(MYSQLI_ASSOC);
                    return $result['count_value'];
                }

                return 0;
            }

            public function delete($id)
            {
                $id = $this->sql->escape($id);

                $dbName = $this->sql->escape($this->dbconfig['database']);
                $result = $this->sql->query("DELETE FROM `$dbName`.`$this->table` WHERE `$this->primary_key` = $id");

                return $result;
            }

            private function correctValue($value)
            {
                if (is_array($value))
                    return $this->correctValue($value['value']);
                
                $returnVal = '';

                if ($value === true)
                    $returnVal = 1;
                if ($value === false)
                    $returnVal = 0;
                if ($value === 'CURRENT_TIMESTAMP')
                    $returnVal = 'CURRENT_TIMESTAMP';
                else if (is_null($value))
                    $returnVal = 'NULL';
                else if (strlen($value) === 0) 
                    $returnVal = "''";
                else if (strlen($value) == 1 && is_numeric($value) && (intval($value) === 0 || intval($value) === 1))
                    $returnVal = intval($value);
                else 
                    $returnVal = "'{$this->sql->escape($value)}'";

                return $returnVal;
            }

            private function correctOperator($value)
            {
                foreach ($this->operators as $key=>$operator)
                {
                    if (strtolower($operator) == strtolower($value))
                        return $value;
                }

                user_error('Invalid query operator: ' . $value . ' used when attempted to query the database');

                return null;
            }

            private function getOrderBy($order_by = null)
            {
                if (is_null($order_by))
                    return '';

                if (!is_array($order_by))
                {
                    return "ORDER BY `{$this->sql->escape($order_by)}` " . self::ASC;
                }
                else if (isset($order_by['operator']))
                {
                    return "ORDER BY `{$this->sql->escape($order_by['field'])}` {$this->correctOperator($order_by['operator'])}"; 
                }
                else
                {
                    $order = array();
                    foreach ($order_by as $key=>$value)
                    {
                        if (is_array($value))
                            $order[] = "`{$this->sql->escape($value['field'])}` {$this->correctOperator($value['operator'])}"; 
                        else
                            $order[] = "`{$this->sql->escape($value)}` " . self::ASC; 
                    }
                    return 'ORDER BY ' . implode(',', $order);
                }

                return '';
            }
        }
