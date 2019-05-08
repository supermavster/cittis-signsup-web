<?php

if (basename($_SERVER['PHP_SELF']) !== basename(__FILE__)) {
    if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] === 'POST')) {
        if (isset($_POST) && !empty($_POST)) {
            if (checkRequest(true) == "POST") {
                // Add Queries All - Project (API)
                require_once AS_PROJECTS . constant("nameProject") . '/QueriesDAO.php';
                require_once AS_PROJECTS . constant("nameProject") . '/controller/' . checkRequest(true) . '/Autoload.php';
            }
        }
    }
} else {
    header("Location: /");
}