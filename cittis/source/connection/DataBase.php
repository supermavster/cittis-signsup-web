<?php

class DataBase
{
    public $connections;
    public $nameDatabase;

    public function __construct($selectDB = "")
    {
        $this->connections = null;
        $this->nameDatabase = $this->nameDatabase = (isset($selectDB) && !empty($selectDB)) ? $selectDB : constant('DB_Name');
        self::startDB();
    }

    protected function startDB()
    {
        try {
            $this->connections = mysqli_connect(
                constant('DB_Host'),
                constant('DB_User'),
                constant('DB_Password'),
                $this->nameDatabase);
            self::config();
        } catch (Exception $e) {
            exit($e->getMessage());
        }
    }

    protected function config()
    {
        // Check connection
        if (mysqli_connect_errno()) {
            exit("Failed to connect to MySQL: " . mysqli_connect_error());
        }

        if ($this->connections === false) {
            mysqli_connect_error();
        } else {
            self::db_exec("query", "SET NAMES '" . constant('DB_Char') . "'");
            @mysqli_set_charset(self::getDB(), constant('DB_Char'));
            self::db_exec("query", "SET CHARACTER SET " . constant('DB_Char'));
        }
    }

    public function db_exec($type, $query)
    {
        $data = array();
        $result = null;
        if ($type != "escape_string" && $type != "real_escape_string") {
            $result = mysqli_query(self::getDB(), trim($query)) or exit('Error al  ejecutar la consulta:' . "<br/>" . PHP_EOL . ($query) . "<br/>" . PHP_EOL . "<br/>" . PHP_EOL . "En:" . getPath() . '<b>' . basename(__FILE__) . '</b>');
        }

        switch ($type) {
            case 'num_rows':
                $data = mysqli_num_rows($result);
                break;
            case 'num_fields':
                $data = mysqli_num_fields($result);
                break;
            case 'fetch_array':
                while ($row = mysqli_fetch_array($result)) {
                    $data[] = $row;
                }
                break;
            case 'fetch_row':
                $data = mysqli_fetch_row($result);
                break;
            case 'fetch_assoc':
                $data = mysqli_fetch_assoc($result);
                break;
            case 'fetch_field':
                $data = mysqli_fetch_field($result);
                break;
            case 'escape_string':
            case 'real_escape_string':
                $data = mysqli_real_escape_string(self::getDB(), trim($query)) or exit('Error al  ejecutar la consulta:' . "<br/>" . PHP_EOL . ($query) . "<br/>" . PHP_EOL . "<br/>" . PHP_EOL . "En:" . getPath() . '<b>' . basename(__FILE__) . '</b>');
                break;
        }
        return $data;
    }

    public function getDB()
    {
        return $this->connections;
    }

    public function close()
    {
        mysqli_close(self::getDB());
        unset($this->connections);
    }

    public function getNameDB()
    {
        return $this->nameDatabase;
    }
}
