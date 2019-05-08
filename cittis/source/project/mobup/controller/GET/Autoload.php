<?php

class Autoload
{
    protected $connection;
    protected $generalConnection;

    public function __construct($connection, $generalConnection)
    {
        $this->connection = $connection;
        $this->generalConnection = $generalConnection;
        self::initProcess();
    }

    protected function initProcess()
    {
        $content = "";
        switch (true) {
            case getRequest("test"):
                $content = "ok";
                break;
        }
        showElements($content);
    }


    /** Get Data Main **/
    protected function getDataBase()
    {
        return $this->connection;
    }

    protected function getGeneralConnection()
    {
        return $this->generalConnection;
    }
}

new Autoload(self::getConnection(), self::getGeneralConnection());