<?php
$html = new HTMLTag('html', array(
    "view-source" => 'no',
    "viewsource" => 'no',
    "lang" => "es",
    "xmlns" => 'http://www.w3.org/1999/xhtml',
    "xmlns:b" => '//www.google.com/2005/gml/b',
    "xmlns:data" => '//www.google.com/2005/gml/data',
    "xmlns:expr" => '//www.google.com/2005/gml/expr',
    "prefix" => 'og: http://ogp.me/ns#',
    "class" => "perfect-scrollbar-on"
));

//Head
$html->appendChild($head);

//Body
$html->appendChild($body);

//Show
header('Content-Type: text/html; charset=' . constant('charMain'));
echo "<!DOCTYPE html>" . PHP_EOL . $html->asHTML();
unset($head);
unset($body);
unset($html);