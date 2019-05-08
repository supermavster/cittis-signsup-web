<?php

if (basename($_SERVER['PHP_SELF']) !== basename(__FILE__)) {
    if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] === 'GET')) {
        if (isset($_GET) && !empty($_GET)) {
            if (checkRequest(true) == "GET") {
                // Add Queries All - Project (API)
                require_once AS_PROJECTS . constant("nameProject") . '/QueriesDAO.php';
                require_once AS_PROJECTS . constant("nameProject") . '/controller/' . checkRequest(true) . '/Autoload.php';
            }
        }
    }
} else {
    header("Location: /");
}