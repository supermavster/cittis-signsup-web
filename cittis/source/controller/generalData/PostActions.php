<?php

if (basename($_SERVER['PHP_SELF']) !== basename(__FILE__)) {
    if (isset($_SERVER['REQUEST_METHOD']) && (($_SERVER['REQUEST_METHOD'] === 'POST'))) {
        if ((isset($_POST) && !empty($_POST))) {
                // Add Queries All - Project (API)
                require_once AS_PROJECTS . constant("nameProject") . '/QueriesDAO.php';
            require_once AS_PROJECTS . constant("nameProject") . '/controller/POST/Autoload.php';
        }
    }
} else {
    header("Location: /");
}