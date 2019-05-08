<?php


class MakeActions
{
    private $connection;
    private $generalConnection;

    public function __construct($connection, $generalConnection)
    {
        $this->connection = $connection;
        $this->generalConnection = $generalConnection;
        self::checkProcess();
    }

    protected function checkProcess()
    {
        if (basename($_SERVER['PHP_SELF']) !== basename(__FILE__)) {
            if (isset($_SERVER['REQUEST_METHOD'])) {
                if (checkRequest()) {
                    self::initProcess();
                } else {
                    self::exitAll("No ha ingresado datos");
                }
            } else {
                self::exitAll("NO es un método POST, lo que solicito");
            }
        } else {
            self::exitAll("El archivo no puede ser solicitado");
        }
    }

    protected function initProcess()
    {
        require_once AS_PROJECTS . '/MainQueriesDAO.php';

        switch (checkRequest(true)) {
            case "GET":
                require_once AS_CONTROL . 'generalData/GetActions.php';
                break;
            case "GETPOST":
            case "POSTGET":
            case "POST":
                require_once AS_CONTROL . 'generalData/PostActions.php';
                break;
            default:
                echo checkRequest(true);
                break;
        }
    }

    private function exitAll($message)
    {
        exit ('<b>' . str_replace(".php", "", basename($_SERVER['PHP_SELF'])) . '</b>: ' . $message);
    }

    public function getConnection()
    {
        return $this->connection;
    }

    public function getGeneralConnection()
    {
        return $this->generalConnection;
    }
}