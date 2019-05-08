<?php


class MainQueriesDAO
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

    // Truncate Table
    final public static function truncateTable($table)
    {
        return "TRUNCATE `$table`;";
    }
}

new MainQueriesDAO();