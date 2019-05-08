<?php


class CittisImages
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

    public function getLocation()
    {
        return $this->projectLocation . "/" . $this->locationFolder;
    }
}