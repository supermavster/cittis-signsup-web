<?php

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class Login
{
    protected $email;
    protected $password;
    protected $user;
    protected $name = "";
    protected $userName = "";
    protected $passwordHash = "";

    public function __construct()
    {
        self::checkProcess();
    }

    protected function checkProcess()
    {
        if (basename($_SERVER['PHP_SELF']) !== basename(__FILE__)) {
            if (isset($_SERVER['REQUEST_METHOD']) && ($_SERVER['REQUEST_METHOD'] === 'POST')) {
                if (isset($_POST) && !empty($_POST)) {
                    self::initProcess();
                } else {
                    self::exitAll("No ha ingresado datos");
                }
            } else {
                self::exitAll("NO es un método POST, lo que solicito");
            }
        } else {
            self::exitAll("El archivo no puede ser solicitado");
        }
    }

    protected function initProcess()
    {
        // Init Process
        self::getData();
        self::initFirebase();
        self::verifyData();
    }

    protected function getData()
    {
        $this->email = $_POST['email'];
        $this->password = $_POST['password'];
    }

    protected function initFirebase()
    {

        // Get Data from JSON file
        $serviceAccount = ServiceAccount::fromJsonFile(AS_ADMIN . '/secret/cittis-tracking-firebase-adminsdk-fcewa-c609e6e17c.json');
        // Init Class
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        // Check Auth
        $this->auth = $firebase->getAuth();
    }

    protected function verifyData()
    {
        try {
            $this->user = $this->auth->verifyPassword($this->email, $this->password);
            $tempUser = JsonHandler::decode(JsonHandler::encode($this->user));
            if (isset($tempUser)) {
                $this->name = $tempUser->displayName;
                $this->userName = $tempUser->uid;
                $this->passwordHash = $tempUser->passwordHash;
                self::loginUser();
            } else {
                self::exitSession();
            }
        } catch (Kreait\Firebase\Exception\Auth\InvalidPassword $e) {
            self::exitSession();
            echo $e->getMessage();
        }
    }

    protected function loginUser()
    {
        mt_srand(time());
        $rand = mt_rand(1000000, 9999999);
        setcookie("COOKIE_INDEFINED_SESSION", true, $rand, "/");
        setcookie("COOKIE_DATA_INDEFINED_SESSION[name]", $this->name, $rand, "/");
        setcookie("COOKIE_DATA_INDEFINED_SESSION[username]", $this->userName, $rand, "/");
        setcookie("COOKIE_DATA_INDEFINED_SESSION[password]", $this->passwordHash, $rand, "/");
        setcookie("marca", true, $rand, "/");
        $_SESSION['marca'] = $rand;
        /* Sesión iniciada, si se desea, se puede redireccionar desde el servidor */
        $_SESSION['name'] = $this->name;
        $_SESSION['user'] = $this->userName;
        $_SESSION['state'] = 'Autenticado';
        //Si los datos no son correctos, o están vacíos, muestra un error
        //Además, hay un script que vacía los campos con la clase "acceso" (formulario)
    }

    public function exitSession()
    {
        // Remove Cookies
        removeCookie("COOKIE_INDEFINED_SESSION");
        removeCookie("COOKIE_DATA_INDEFINED_SESSION[name]");
        removeCookie("COOKIE_DATA_INDEFINED_SESSION[userName]");
        removeCookie("COOKIE_DATA_INDEFINED_SESSION[password]");
        // Remove Session
        unset($_SESSION);
        session_unset();
        session_destroy();
        session_start();
        // Return Home
        header("Location: /");
        die();
    }

    private function exitAll($message)
    {
        exit ('<b>' . str_replace(".php", "", basename($_SERVER['PHP_SELF'])) . '</b>: ' . $message);
    }

}


if (!isset($login)) {
    $login = new Login();
}
