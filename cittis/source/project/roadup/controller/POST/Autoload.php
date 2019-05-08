<?php

class Autoload
{
    protected $connection;
    protected $generalConnection;

    //response array
    protected $response = array();

    public function __construct($connection, $generalConnection)
    {
        $this->connection = $connection;
        $this->generalConnection = $generalConnection;
        self::initProcess();
    }

    protected function initProcess()
    {
        switch (true) {
            case getRequest("test"):
                $this->response['error'] = false;
                $this->response['message'] = 'ok';
                break;
            default:
                $this->response['error'] = true;
                $this->response['message'] = 'No existe operacion para este proceso';
        }
        showElements($this->response);
    }


    /** Get Data Main **/
    protected function getConnection()
    {
        return $this->connection;
    }

    protected function getGeneralConnection()
    {
        return $this->generalConnection;
    }

}

new Autoload(self::getConnection(), self::getGeneralConnection());