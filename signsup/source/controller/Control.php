<?php

// Add Library
require_once 'Settings.php';

class Control extends Settings
{
    private $signIn;
    private $signUp;

    public function __construct()
    {
        parent::__construct();
        self::initProcess();
    }

    protected function initProcess()
    {

    }

    public function makeIndex()
    {
        # echo "Is Session: ".isSession." Is login: ".(isLogin?'true':'false')."<br/>";
        self::makeWebsite();
    }



    public function initSignInSignUp()
    {
        # echo "Is Session: ".isSession." Is login: ".(isLogin?'true':'false')."<br/>";
        if(!isset($_POST)){
            self::makeWebsite();
        }
    }

    public function finishSession()
    {
        self::getSignIn()->exit();
    }

    /**
     * Make Elemenst
     **/
    protected function makeWebSite()
    {
        $tempFile = array("body", "head", "html");
        for ($i = 0; $i < count($tempFile); $i++) {
            require_once AS_TEMPLATE . $tempFile[$i] . ".php";
        }
        parent::finish();
    }

    /**
     * Get Elemenst
     */

    public function getSignIn()
    {
        return $this->signIn;
    }
}

// Start Process
if (!isset($ctrl)) {
    $ctrl = new Control();
}
