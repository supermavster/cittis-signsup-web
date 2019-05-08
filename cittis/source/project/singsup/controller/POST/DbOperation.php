<?php

class DbOperation
{
    protected $connection;
    protected $generalConnection;

    public function __construct($connection, $generalConnection)
    {
        $this->connection = $connection;
        $this->generalConnection = $generalConnection;
    }

    //adding a record to database

    public function createSignals($signal)
    {
        // Variables Null
        $typeSigal = "";
        $Lado = "";
        $Ubicacion = "";
        $Carril = "";
        $Latitud = "";
        $Longitud = "";
        $TipoP = "";
        $Size = "";
        $Senal = "";
        $EstadoPost = "";
        $EstadoSe = "";
        $checkElements = false;
        // Fix Element
        $fixElement = str_replace('[', '', $signal);
        $elementSignal = str_replace(']', '', $fixElement);
        $decodeJSON = JsonHandler::decode($elementSignal, true);

        if (isset($decodeJSON["CittusListSignal"])) {
            // List Signal
            $listSignal = $decodeJSON["CittusListSignal"];
            //  Municipalities
            $municipalities = $listSignal["Municipalities"];

            // Ids
            $IdInventario = $municipalities["idInventario"];
            $IdSignal = $municipalities["idMaxSignal"];

            // Check Max ID

            // Id Municipy
            $sql = QueriesDAO::getMaxIdSignalMoreOne();
            $IdSignalTemp = $this->connection->db_exec('fetch_row', $sql)[0];

            if ($IdSignalTemp >= $IdSignal) {
                $IdSignal = $IdSignalTemp;
            }

            // Id Municipy
            $sql = QueriesDAO::getIdMunicipio($municipalities["nameMunicipal"]);
            $idMunicipio = self::getGeneralConnection()->db_exec('fetch_row', $sql)[0];

            // Add Inventario
            $sql = QueriesDAO::addInventario($IdInventario, $idMunicipio);
            //if (!$this->connection->db_exec('query', $sql)) {$checkElements = true;}

            // Signal
            $cittusISV = $listSignal["CittusISV"];

            $sizeArray = (Int)count($cittusISV) - 1;
            $cittusISV = $cittusISV[$sizeArray];

            $signal = $cittusISV["CittusSignal"];

            // Signal Image
            $signalImage = $cittusISV["ImagenSignalCode"];


            // Lat & Lon
            $Latitud = $signal["latitude"];
            $Longitud = $signal["longitude"];

            // Id Type Signal
            // TypeSignal
            $typeSigalTemp = $cittusISV["typeSignal"];
            $sql = QueriesDAO::getIdTypeSignal($typeSigalTemp);
            $typeSigal = $this->connection->db_exec('fetch_row', $sql)[0];

            // Check
            if ($typeSigalTemp === 'Horizontal') {
                // Horizontal Signal
                $horizontalSignal = $cittusISV["HorizontalSignal"];

                //Carril
                $Carril = str_replace("Carril ", "", $horizontalSignal['rail']);

                // Location
                $Ubicacion = $horizontalSignal["locationOnTheWay"];

                //Location
                $sql = QueriesDAO::getIdLocation($Ubicacion);
                //$Ubicacion = $this->connection->db_exec('fetch_row', $sql)[0];
                $signalTemp = $horizontalSignal;
            } else {
                // Vertical Signal
                $verticalSignal = $cittusISV["VerticalSignal"];
                // Id Lado (NS , OE)
                $lado = str_replace(" ", "", $signal['location']);
                $sql = QueriesDAO::getIdLado($lado);
                //$Lado = $this->connection->db_exec('fetch_row', $sql)[0];

                // Get Type Fijation
                $typeP = $verticalSignal['postTypeSignal'];
                $arr = explode(' ', trim($typeP));
                $sql = QueriesDAO::getIdTipoFijacion($arr[0]);
                //$TipoP = $this->connection->db_exec('fetch_row', $sql)[0];

                // Size
                $Size = str_replace(" ", "", $verticalSignal['sizeSignal']);
                $sql = QueriesDAO::getIdSize($Size);
                //$Size = $this->connection->db_exec('fetch_row', $sql)[0];

                // State Post
                $EstadoPost = self::getValueState($verticalSignal['statePost']);

                $signalTemp = $verticalSignal;
            }
            // State Signal
            $EstadoSe = self::getValueState($signalTemp['stateSingal']);

            // Codigo
            $Senal = $signalImage['codeImagen'];

            // Add Signal
            !$this->connection->db_exec('query', "SET FOREIGN_KEY_CHECKS=0;");
            $sql = QueriesDAO::addSiganl($IdSignal, $typeSigal, $Lado, $Ubicacion, $Carril, $Latitud, $Longitud, $TipoP, $Size, $Senal, $EstadoPost, $EstadoSe);
            //if (!$this->connection->db_exec('query', $sql)) {$checkElements = true;}


            $sql = QueriesDAO::addListSignal($IdInventario, $IdSignal);
            //if (!$this->connection->db_exec('query', $sql)) {$checkElements = true;}
        }
        return $checkElements;
    }

    protected function getGeneralConnection()
    {
        return $this->generalConnection;
    }

    private function getValueState($state)
    {
        $EstadoPost = 0;
        // Convert Value
        $state = (float)$state;

        if ($state > 2.0 && $state <= 3.0) {
            $EstadoPost = 1;
        } elseif ($state > 1.0 && $state <= 2.0) {
            $EstadoPost = 2;
        } else {
            $EstadoPost = 3;
        }

        return $EstadoPost . "";
    }

    protected function getDataBase()
    {
        return $this->connection;
    }
}