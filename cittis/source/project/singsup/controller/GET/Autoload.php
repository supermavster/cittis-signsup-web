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
        $tempElements = self::getGeneralConnection()->db_exec('fetch_array', QueriesDAO::getDepartments($name));
        $i = 0;
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
        $tempElements = self::getGeneralConnection()->db_exec('fetch_array', QueriesDAO::getMunicipalityByDepartment($name));
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

    /** Get Data Main **/
    protected function getDataBase()
    {
        return $this->connection;
    }

}

new Autoload(self::getConnection(), self::getGeneralConnection());

class Signal
{

    protected $connection;
    protected $generalConnection;

    // Location Folder
    protected $arrayImages = array();
    private $locationFolderMainImages = array(
        "stretchsigns" => "horizontal-tramo",
        "intersectionsigns" => "horizontal-intre",
        "ciclorutasigns" => "senales-de-transito-ciclorruta-fotos",
        "worksigns" => "senales-de-transito-de-obra",
        "informativesigns" => "senales-de-transito-informativas-fotos",
        "preventivesigns" => "senales-de-transito-preventivas-fotos",
        "regulatorysigns" => "senales-de-transito-reglamentarias-fotos",
        "touristsigns" => "senales-informativas-turisticas-fotos",
    );
    private $tablesImagesDB = array(
        "stretchsigns",
        "intersectionsigns",
        "ciclorutasigns",
        "worksigns",
        "informativesigns",
        "preventivesigns",
        "regulatorysigns",
        "servicessigns",
        "touristsigns",
    );

    public function __construct($connection, $generalConnection)
    {
        $this->connection = $connection;
        $this->generalConnection = $generalConnection;


    }

    public function getSignal($signal)
    {
        $response = array();
        // Main Names Table
        foreach ($this->tablesImagesDB as $key => $value) {
            if (like_match("%" . $signal . "%", $value)) {
                // Name of the Table & Folder Location Images
                $response = array(
                    "tableMain" => $value,
                    "location" => $this->locationFolderMainImages[$value],
                );
                break;
            }
        }

        // Get ID To Search
        $tempID = getRequest("code") ? getRequest("code") : "";
        // Send Table and ID
        $table = $response['tableMain'];
        $idTable = self::getGeneralConnection()->db_exec('fetch_row', QueriesDAO::getIDTable($table))[0];
        $countElements = self::getDataBase()->db_exec('num_rows', QueriesDAO::checkID($table, $idTable, $tempID));

        if ($countElements > 0) {
            $tempElements = self::getDataBase()->db_exec('fetch_array', QueriesDAO::checkID($table, $idTable, $tempID));

            foreach ($tempElements as $key => $value) {
                $response['code'] = $value[$idTable];
                $response['idImage'] = $value[$idTable] . '.png';
                array_push($this->arrayImages, makeImagesMain($response));
            }

        }

        // Get Signals From - To
        self::fromTo();

        // Get Signals ID or Image
        self::signalsByIDorImage();
    }

    protected function getGeneralConnection()
    {
        return $this->generalConnection;
    }

    /** Get Data Main **/
    protected function getDataBase()
    {
        return $this->connection;
    }

    protected function fromTo()
    {
        if (isset($_GET['from']) || isset($_GET['to'])) {
            // Init Variables - TEMP
            $arrayTempMain = array();
            // Get location
            $init = -1;
            $end = -1;
            for ($i = 0; $i < count($this->arrayImages); $i++) {
                if (isset($_GET['from']) && ($_GET['from'] == $this->arrayImages[$i]->code)) {
                    $init = $i;
                }
                if (isset($_GET['to']) && ($_GET['to'] == $this->arrayImages[$i]->code)) {
                    $end = $i;
                }
            }

            // Fix Location
            if (!isset($_GET['from'])) {
                $init = 0;
            }

            if (!isset($_GET['to'])) {
                $end = count($this->arrayImages);
            }

            for ($i = 0; $i < count($this->arrayImages); $i++) {
                if ($init >= 0) {
                    if ($i >= $init && $i <= $end) {
                        array_push($arrayTempMain, $this->arrayImages[$i]);
                    }
                }
            }
            // Set elements
            $this->arrayImages = $arrayTempMain;
        }
    }

    protected function signalsByIDorImage()
    {
        if (isset($_GET['id']) || isset($_GET['img'])) {
            $arrayTempMain = array();
            $arrayTemp = array();
            for ($i = 0; $i < count($this->arrayImages); $i++) {
                $imagesMain = $this->arrayImages[$i];
                // Show Data
                if (isset($_GET['id']) && $_GET['id']) {
                    // Get Element Base (ID)
                    $arrayTemp['id'] = $imagesMain->code;
                }

                // Show Data
                if (isset($_GET['img']) && $_GET['img']) {
                    // Get Element Base (image_url)
                    $arrayTemp['img'] = $imagesMain->image_url;
                }
                array_push($arrayTempMain, $arrayTemp);
            }
            $this->arrayImages = $arrayTempMain;
        }
    }

    public function printValue()
    {

        // Print Elements
        //showElements($this->arrayImages);
        return $this->arrayImages;
    }

}

function makeImagesMain($response)
{
    // Make Elemnts
    $imagesMain = new IttusImages();
    $imagesMain->projectLocation = full_url($_SERVER) . $imagesMain->projectLocation;
    // Data - Database
    $imagesMain->code = $response['code'];
    $imagesMain->tableMain = $response['tableMain'];
    // Data location
    $imagesMain->location = $response['location'];
    $imagesMain->idImage = $response['idImage'];
    // Make Location Image
    $imagesMain->makeImageLocation();
    return $imagesMain;
}

class IttusImages
{

    public $projectLocation = "cittis";
    public $locationFolder = "source/project/singsup/wwwroot/images";

    public $code = "";
    public $idImage = "";
    public $location = "";
    public $tableMain = "";
    public $image_url = "";

    public function makeImageLocation()
    {
        $this->image_url = $this->projectLocation . "/" . $this->locationFolder . "/" . $this->location . "/" . $this->idImage;

    }

}
