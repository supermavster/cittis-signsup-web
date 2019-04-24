<?php
$body = new HTMLTag('body');
$body->setComment(true);
// Check if is Login
if(isLogin) {
    require_once AS_VIEW.'index/index.php';
} else {
    //require_once (AS_PLUGINS.'login/base/form.php');
}
// Elements Base (END)
require_once 'complements.php';