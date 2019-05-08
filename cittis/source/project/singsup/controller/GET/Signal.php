<?php


class Signal
{

    protected $connection;
    protected $generalConnection;

    // Location Folder
    protected $locationFileImages = "source/wwwroot/assets/Imagenes";
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

        $ittusImage = new CittisImages();
        $this->locationFileImages = getPath() . $ittusImage->getLocation();
    }

    public function uploadAllSignals()
    {
        if (isset($_GET['uploadAll']) && $_GET['uploadAll']) {
            // Get Images
            $folderImagesTemp = array();
            $files = getListFiles($this->locationFileImages);
            foreach ($files as $key => $value) {
                if ($value['Size'] == 0) {
                    $newVal = str_replace($this->locationFileImages, '', $value['Name']);
                    $newVal = str_replace("/", "", $newVal);
                    array_push($folderImagesTemp, $newVal);
                }
            }
            foreach ($folderImagesTemp as $keyMain => $valueMain) {
                $fileFolder = $this->locationFileImages . "/" . $valueMain;
                $files = getListFiles($this->locationFileImages . "/" . $valueMain);
                // Rename Files
                if (isset($_GET['rename']) && $_GET['rename']) {
                    foreach ($files as $key => $value) {
                        $oldName = $value['Name']; //str_replace($locationFileImages, '', $value['Name']);
                        $newName = (str_replace("ñ", "n", $oldName));
                        $newName = (str_replace("ã±", "n", $newName));
                        $newName = strtoupper(str_replace(" ", "-", $newName));
                        $newName = (str_replace(".PNG", ".png", $newName));
                        rename($oldName, $newName);
                    }
                }
                uploadDataIMG($valueMain, $fileFolder, $files, $this->locationFolderMainImages, self::getDataBase());
            }
            // Fix Lado
            //$tableSelected = "lado";
            //$sqlMain = QueriesDAO::addLado();
            !self::getDataBase()->db_exec('query', "SET FOREIGN_KEY_CHECKS=0;");
            //$sqlMain = QueriesDAO::truncateTable($tableSelected);//!self::getDataBase()->db_exec('query', $sql);
            //if (!self::getDataBase()->db_exec('query', $sqlMain)) {
            self::getDataBase()->db_exec('query', "SET FOREIGN_KEY_CHECKS=1;");
            return "Datos subidos";
            //}
        }
    }

    /** Get Data Main **/
    protected function getDataBase()
    {
        return $this->connection;
    }

    public function uploadSignal()
    {
        $valueMain = $_GET['upload'];
        $fileFolder = $this->locationFileImages . "/" . $valueMain;
        $files = getListFiles($this->locationFileImages . "/" . $valueMain);
        // Rename Files
        if (isset($_GET['rename']) && $_GET['rename']) {
            foreach ($files as $key => $value) {
                $oldName = $value['Name'];
                $newName = (str_replace("ñ", "n", $oldName));
                $newName = (str_replace("ã±", "n", $newName));
                $newName = strtoupper(str_replace(" ", "-", $newName));
                $newName = (str_replace(".PNG", ".png", $newName));
                rename($oldName, $newName);
            }
        }
        uploadDataIMG($valueMain, $fileFolder, $files, $this->locationFolderMainImages, self::getDataBase());
        return "Subido Correctamente";
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
        $idTable = self::getGeneralConnection()->db_exec('fetch_row', MainQueriesDAO::getIDTable($table))[0];
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

/* General Complements */

function makeImagesMain($response)
{
    // Make Elemnts
    $imagesMain = new CittisImages();
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

function getListFiles($directory, $recursive = false)
{
    $res = array();
    if (substr($directory, -1) != "/") {
        $directory .= "/";
    }
    $dir = @dir($directory) or die("error al abrir");
    while (($file = $dir->read()) !== false) {
        if ($file[0] == ".") {
            continue;
        }

        $directionFile = $directory . $file;
        if (is_dir($directionFile)) {
            $res[] = array(
                "Name" => $directionFile . "/",
                "Size" => 0,
                "Modify" => fileatime($directionFile . "/"),
            );
            if ($recursive && is_readable($directionFile . "/")) {
                $directoryInterior = $directionFile . "/";
                $res = array_merge($res, getListFiles($directoryInterior, true));
            }
        } elseif (is_readable($directionFile)) {
            $res[] = array(
                "Name" => $directionFile,
                "Size" => filesize($directionFile),
                "Modify" => fileatime($directionFile),
            );
        }
    }
    $dir->close();
    return $res;
}

function uploadDataIMG($upload, $fileFolder, $files, $locationFolderMainImages, $connection)
{
    //Check Folder
    $tableSelected = "";
    foreach ($locationFolderMainImages as $keyMain => $value) {
        if ($value == $upload) {
            $tableSelected = $keyMain;
        }
    }

    $arrayValues = array();
    //Get table imgs on DB
    foreach ($files as $key => $valueTemp) {
        $name = $valueTemp['Name'];
        $newName = str_replace($fileFolder, '', $name);
        $newName = str_replace("/", "", $newName);
        $extension = (strtolower(pathinfo($newName, PATHINFO_EXTENSION)));
        $withoutParametes = str_replace($extension, '', $newName);
        $withoutParametes = str_replace(".", "", $withoutParametes);

        // Get Data Main

        $arrayTemp = array(
            "id" => $withoutParametes,
            //"ruta" => $newName,
        );
        array_push($arrayValues, $arrayTemp);

    }
    // Set values
    $idTable = $connection->db_exec('fetch_row', MainQueriesDAO::getIDTable($tableSelected))[0];
    // GEt data
    if ($tableSelected == 'interpng') {
        $sqlMain = QueriesDAO::addInter($arrayValues);
    } elseif ($tableSelected == 'tramopng') {
        $sqlMain = QueriesDAO::addTramo($arrayValues);

    } else {
        $sqlMain = QueriesDAO::addImageValues($tableSelected, $idTable, $arrayValues);
    }
    //truncate Table
    !$connection->db_exec('query', "SET FOREIGN_KEY_CHECKS=0;");
    $sql = MainQueriesDAO::truncateTable($tableSelected);
    !$connection->db_exec('query', $sql);

    if (!$connection->db_exec('query', $sqlMain)) {
        $connection->db_exec('query', "SET FOREIGN_KEY_CHECKS=1;");
        echo "Datos subidos $sqlMain <br/><hr/>";
    }
}