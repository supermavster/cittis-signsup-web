<?php

require_once 'CittisImages.php';
require_once 'Signal.php';

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
            case getRequest("departments"):
            case getRequest("departamentos"):
                $content = self::getDepartments();
                break;
            case getRequest("municipalities"):
            case getRequest("municipios"):
                $content = self::getMunicipalities();
                break;
            case getRequest("maxID"):
                $content = self::getMaxID();
                break;
            case getRequest("signal"):
                $content = self::getSignal();
                break;
            case getRequest('uploadAll'):
                $content = self::uploadAllSignals();
                break;
            case getRequest('upload'):
                $content = self::uploadSignal();
                break;
        }
        showElements($content);
    }

    protected function getDepartments()
    {
        $tempValues = array();
        if (getRequest("departments")) {
            $name = getRequest("departments");
        } elseif (getRequest("departamentos")) {
            $name = getRequest("departamentos");
        } else {
            $name = "all";
        }
        $tempElements = self::getGeneralConnection()->db_exec('fetch_array', MainQueriesDAO::getDepartments($name));
        foreach ($tempElements as $key => $value) {
            array_push($tempValues, $value['nameDepartment']);
        }
        return ($tempValues);
    }

    protected function getGeneralConnection()
    {
        return $this->generalConnection;
    }

    protected function getMunicipalities()
    {
        if (getRequest("municipalities")) {
            $name = getRequest("municipalities");
        } elseif (getRequest("municipios")) {
            $name = getRequest("municipios");
        } else {
            $name = "all";
        }

        $tempValues = array();
        $tempElements = self::getGeneralConnection()->db_exec('fetch_array', MainQueriesDAO::getMunicipalityByDepartment($name));
        $i = 0;
        foreach ($tempElements as $key => $value) {
            array_push($tempValues, $value['nameMunicipality']);
        }
        return ($tempValues);
    }

    protected function getMaxID()
    {
        $sql = "";
        switch (getRequest("maxID")) {
            case 'inventory':
            case 'inventario':
                $sql = QueriesDAO::getMaxIDInventory();
                break;
            case 'list':
            case 'lista':
                $sql = QueriesDAO::getMaxIDList();
                break;
            case 'signal':
            case 'señal':
                $sql = QueriesDAO::getMaxIDSignal();
                break;
        }
        $tempValue = self::getGeneralConnection()->db_exec('fetch_row', $sql)[0];
        if (!isset($tempValue) || empty($tempValue)) {
            $tempValue = 1;
        }
        //response array
        return $tempValue;
    }

    protected function getSignal()
    {
        $signal = new Signal(self::getDataBase(), self::getGeneralConnection());
        $signal->getSignal(getRequest('signal'));
        return $signal->printValue();
    }

    protected function uploadAllSignals()
    {
        $signal = new Signal(self::getDataBase(), self::getGeneralConnection());
        return $signal->uploadAllSignals();
        //return $signal->printValue();
    }

    protected function uploadSignal()
    {
        $signal = new Signal(self::getDataBase(), self::getGeneralConnection());
        return $signal->uploadSignal();
        //return $signal->printValue();
    }

    /** Get Data Main **/
    protected function getDataBase()
    {
        return $this->connection;
    }

}

new Autoload(self::getConnection(), self::getGeneralConnection());