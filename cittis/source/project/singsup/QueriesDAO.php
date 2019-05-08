<?php


class QueriesDAO
{
    // Get Departments
    final public static function getDepartments($name)
    {
        $sql = "";
        if (isset($name) && !empty($name) && $name != 'all') {
            $sql = "WHERE `nameDepartment` LIKE '%$name%'";
        }
        return "SELECT `nameDepartment` FROM `department` $sql";
    }

    // Get Departments
    final public static function getMunicipalityByDepartment($nameDepartment)
    {
        $sql = "";
        if (isset($nameDepartment) && !empty($nameDepartment) && $nameDepartment != 'all') {
            $sql = "WHERE department.nameDepartment LIKE '%$nameDepartment%'";
        }
        $sql = "SELECT `nameMunicipality` nameMunicipality FROM `department` department inner JOIN `municipalities` municipalities ON municipalities.idDepartment = department.idDepartment " . $sql;
        return $sql;
    }

    // Get ID Table
    final public static function getIDTable($table)
    {
        return "SELECT COLUMN_NAME  
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE COLUMN_NAME LIKE '%Id%' AND TABLE_NAME LIKE '%$table%' LIMIT 1";
    }

    // Check ID
    final public static function checkID($table, $idFromtheTable, $id)
    {
        $withID = "";
        if (isset($id) && !empty($id)) $withID = "LIKE '%" . $id . "%'";
        return "Select * FROM `" . $table . "` WHERE `" . $idFromtheTable . "` $withID";
    }

    /** Get Max ID **/
    // Inventory
    final public static function getMaxIDInventory()
    {
        return "SELECT (MAX(IdInS)+1) IDMax FROM inventario";
    }
}

new QueriesDAO();