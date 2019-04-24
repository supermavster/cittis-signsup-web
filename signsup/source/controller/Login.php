<?php

require_once '../controller/Control.php';
require_once AS_ROOT.'vendor/autoload.php';


use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

$serviceAccount = ServiceAccount::fromJsonFile(AS_ADMIN. '/secret/cittis-tracking-firebase-adminsdk-fcewa-c609e6e17c.json');

$firebase = (new Factory)
    ->withServiceAccount($serviceAccount)
    ->create();

$auth = $firebase->getAuth();

$users = $auth->listUsers($defaultMaxResults = 1000, $defaultBatchSize = 1000);

$email = 'mtorres@cittus.com';
$password = "Mavster.1";

try {
    $user = $auth->verifyPassword($email, $password);
    echo (highlight_string(var_export($user, true) . ";\n"));
} catch (Kreait\Firebase\Exception\Auth\InvalidPassword $e) {
    echo $e->getMessage();
}