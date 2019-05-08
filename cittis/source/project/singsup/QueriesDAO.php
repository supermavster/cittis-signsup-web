<?php


class QueriesDAO
{

    // Check ID
    final public static function checkID($table, $idFromtheTable, $id)
    {
        $withID = "";
        if (isset($id) && !empty($id)) $withID = "WHERE `" . $idFromtheTable . "` LIKE '%" . $id . "%'";
        $sql = "Select * FROM `" . $table . "` " . $withID;
        return $sql;
    }

    // Add Image
    final public static function addImageValues($table, $id, $values)
    {
        $sql = "INSERT INTO `$table` (`$id`) VALUES ";//", `Ruta`) VALUES ";
        $sql .= self::addValues($values);
        return $sql;
    }

    // Add Values
    private function addValues($values, $three = false)
    {
        $sql = "";
        if (is_array($values)) {
            $i = 0;
            foreach ($values as $key => $value) {
                $sql .= "('" . $value['id'] . "'";//, '".$value['ruta']."'";
                /*if($three){
                    $sql .= ",'".$value['ruta']."'";
                }*/
                $sql .= ")";
                if ($i++ != count($values) - 1) {
                    $sql .= ",";
                } else {
                    $sql .= ";";
                }
            }
        }
        return $sql;
    }

    /** Get Max ID **/
    // Inventory
    final public static function getMaxIDInventory()
    {
        return "SELECT (MAX(idSignal)+1) IDMax FROM listsignal";
    }

    final public static function getMaxIdSignalMoreOne()
    {
        return "SELECT (MAX(IdSignal)+1) FROM signalmain";
    }

    final public static function getIdMunicipio($name)
    {
        return "SELECT idMunicipality FROM municipalities WHERE nameMunicipality LIKE '%$name%'";
    }

    final public static function addInventario($idInventario, $idMunicipio)
    {
        return "REPLACE INTO `inventario` (`IdInS`,`IdMuni`) VALUES ('$idInventario','$idMunicipio');";
    }

    final public static function addSiganl($IdS, $TipS, $Lado, $Ubicacion, $Carril, $Latitud, $Longitud, $TipoP, $Tamaño, $Senal, $EstadoPost, $EstadoSe)
    {
        return "INSERT INTO señal (IdS,TipS,Lado,Ubicacion,Carril,Latitud,Longitud,TipoP,Tamaño,Señal,EstadoPost,EstadoSe)
        VALUES ('$IdS','$TipS','$Lado','$Ubicacion','$Carril','$Latitud','$Longitud','$TipoP','$Tamaño','$Senal','$EstadoPost','$EstadoSe');";
    }

    final public static function getIdTypeSignal($name)
    {
        return "SELECT IdTS FROM tiposeñal WHERE NomTS LIKE '%$name%'";
    }

    final public static function getIdLado($name)
    {
        return "SELECT IdLad FROM lado WHERE NomLad LIKE '%$name%'";
    }

    final public static function getIdLocation($name)
    {
        return "SELECT IdUbi FROM ubicacion WHERE NomUbi LIKE '%$name%'";
    }


    final public static function getIdTipoFijacion($name)
    {
        return "SELECT IdP FROM poste WHERE NomP LIKE '%$name%'";
    }

    final public static function getIdSize($name)
    {
        return "SELECT IdTa FROM `tamañosv` WHERE NombTa LIKE '%$name%'";
    }

    final public static function addListSignal($IdIn, $IdSen)
    {
        return "INSERT INTO listasen (IdIn,IdSen) VALUES ('$IdIn','$IdSen');";
    }
}

new QueriesDAO();