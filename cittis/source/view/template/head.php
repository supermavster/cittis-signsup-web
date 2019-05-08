<?php
$head = new HTMLTag('head');
//Title
$title = new HTMLTag('title');
$title->innerHTML(TITLE_WEB);
$head->appendChild($title);
//Favicon
$link = new HTMLTag('link', array(
    "href" => AS_IMAGES . "logos/logo_main.png",
    "rel" => "shortcut icon",
    "type" => "image/x-icon",
));
$head->appendChild($link);

//Style
require_once 'styleWebHead.php';