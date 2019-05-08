<?php

// Verification

if (basename($_SERVER['REQUEST_URI']) !== basename(__FILE__)) {


    class Subscribe

    {


        //privateS

        private $emails;

        private $char;

        private $email;

        private $nickname;

        private $priority;

        private $subject;

        private $message;

        private $elements;


        private $messagesNotification;

        private $messagesNotificationComplete;


        protected $lang;


        public function __construct()

        {


            //Config

            //Main

            $this->from = "no-reply@supermavster.com"; //Main Mail

            $this->nickname = "Supermavster.com"; //Name to show


            //Contacts

            $this->emails = "";

            //Characters

            $this->char = "iso-8859-1"; //iso-8859-1 or uft-8


            //Priority of message

            $this->priority = 1; //Values: 1 = High, 3 = Normal, 5 = Low


            //Mail

            $this->subject = "";

            $this->message = "";

            $this->elements = "";


        }


        public function sendMails($cases, $para, $elements = null, $lang = "en")

        {

            $this->emails = $para;

            $this->elements = $elements;

            $this->lang = $lang;

            //Choose

            switch ($cases) {

                case 0:

                    //Report Link

                    $this->link();

                    break;

                case 1:

                    //Subscribe User

                    $this->user();

                    break;

                case 2:

                    //Subscribe Email

                    $this->email();

                    break;

                case 3:

                    //Subscribe Email

                    $this->forgetEmail();

                    break;

                case 4:

                    //Post

                    $this->post();

                    break;

            }

            // $this->sendEMails();

        }


        protected function post()

        {

            if ($this->lang === "es") {

                $this->subject = '😄 Nuevo Post 😉 - ';

            } else {

                $this->subject = '😄 New Post 😉 - ';

            }

            $this->subject .= $this->elements['title'];

            $divMain = $this->makeBody($this->elements['content'], $this->elements['url']);

            // Cabeceras adicionales

            $this->nickname = $this->subject;

            $this->from = "no-reply@mavsters.com";

            $this->message = $divMain->asHTML();

            echo $this->message;

        }


        protected function link()

        {

            if ($this->lang === "es") {

                $this->messagesNotification = "Reporte Enviado";

                $this->messagesNotificationComplete = "Gracias, Lo arreglaremos tan pronto como se nos sea posible.";

                // Asunto

                $this->subject = 'Atención: Se ha reportado un enlace';

                // Cuerpo o mensaje

                $div = new HTMLTag('div');

                $p = new HTMLTag('p');

                $p->innerHTML("Link: ");

                $a = new HTMLTag('a', array(

                    "href" => "http://www.mavsters.com/post/" . $this->elements,

                ));

                $a->innerHTML("Ver: " . $this->elements);

                $p->appendChild($a);

                $div->appendChild($p);


                $p = new HTMLTag('p');

                $p->innerHTML("¡Este mensaje es para dar la notificación de que un usuario a reportado un enlace!");

                $div->appendChild($p);

                $p = new HTMLTag('p');

                $p->innerHTML("Revise el enlace lo más pronto. Gracias");

                $div->appendChild($p);

            } else {

                $this->messagesNotification = "Report Submitted";

                $this->messagesNotificationComplete = "Thanks, We'll fix it as soon as we can.";

                // Asunto

                $this->subject = 'Attention: A link has been reported';

                // Cuerpo o mensaje

                $div = new HTMLTag('div');

                $p = new HTMLTag('p');

                $p->innerHTML("Link: ");

                $a = new HTMLTag('a', array(

                    "href" => "http://www.mavsters.com/post/" . $this->elements,

                ));

                $a->innerHTML("See: " . $this->elements);

                $p->appendChild($a);

                $div->appendChild($p);


                $p = new HTMLTag('p');

                $p->innerHTML("¡This message is to give notification that a user has reported a link!");

                $div->appendChild($p);

                $p = new HTMLTag('p');

                $p->innerHTML("Check the link as soon as possible. Thank you");

                $div->appendChild($p);

            }

            $divMain = $this->makeBody($div->asHTML());

            // Cabeceras adicionales

            $this->nickname = "Mavsters - Verify";

            $this->from = "report-link@mavsters.com";

            $this->emails = "report-link@mavsters.com";

            $this->message = $divMain->asHTML();

        }


        protected function email()

        {

            if ($this->lang === "es") {

                $this->messagesNotification = "🙈 Gracias por suscribirte 🙊";

                $this->messagesNotificationComplete = "¡Gracias por suscribirte a Mavsters, seras el primero en recibir nuestros nuevos contenidos. Gracias nuevamente!.";

                //asunto

                $this->subject = 'Suscripción a Mavsters';

                // Cuerpo o mensaje

                $divMain = $this->makeBody("Gracias por ser parte de Mavsters, ahora puedes disfrutar de los nuevos contenidos que estaremos ofreciendo a lo largo de la actividad de Mavsters. (Posts cada semana).");

            } else {

                $this->messagesNotification = "🙈 Thanks for subscribing 🙊";

                $this->messagesNotificationComplete = "Thank you for subscribing to Mavsters, you will be the first to receive our new content. Thanks again!.";

                //asunto

                $this->subject = 'Subscription to Mavsters';

                // Cuerpo o mensaje

                $divMain = $this->makeBody("Thanks for being part of Mavsters, now you can enjoy the new content we will be offering throughout the Mavsters activity. (Posts every week).");

            }

            // Cabeceras adicionales

            $this->nickname = "Mavsters - Verify";

            $this->from = "no-reply@mavsters.com";

            $this->message = $divMain->asHTML();

        }


        protected function user()

        {


            $makeUrls = $this->makeUrlVerify($this->elements);

            if ($this->lang === "es") {

                $this->messagesNotification = "Revisa tú correo";

                $this->messagesNotificationComplete = "Gracias por suscribirte a Mavsters, verifica tú correo.";

                //echo $this->messagesNotificationComplete;

                // Asunto

                $this->subject = 'Verificación: Nueva cuenta en Mavsters';

                // Cuerpo o mensaje

                $divMain = $this->makeBody("Gracias por ser parte de Mavsters, ahora como Mavster presiona el botón '<b>Dame Clic</b>' el cual lo tienes abajo para verificar tú cuenta.<br/>", $makeUrls);

            } else {

                $this->messagesNotification = "Check your email";

                $this->messagesNotificationComplete = "Thanks for subscribing to Mavsters, check your email.";

                //echo $this->messagesNotificationComplete;

                // Asunto

                $this->subject = 'Verification: New Mavsters account';

                // Cuerpo o mensaje

                $divMain = $this->makeBody("Thanks for being part of Mavsters, now as Mavster press the button '<b> Click Here!</b>' which you have below to verify your account.<br/>", $makeUrls);

            }

            // Cabeceras adicionales

            $this->nickname = "Mavsters - Verify";

            $this->from = "no-reply@mavsters.com";

            $this->message = $divMain->asHTML();

        }


        protected function forgetEmail()

        {

            $makeUrls = $this->makeUrlVerify($this->elements);

            if ($this->lang === "es") {

                $this->messagesNotification = "Contraseña Olvidada";

                $this->messagesNotificationComplete = "Revisa tú correo";

                // Asunto

                $this->subject = 'Clave: Nueva clave para tú cuenta en Mavsters';

                // Cuerpo o mensaje

                $divMain = $this->makeBody("Este enlace te ayuda a cambiar tú clave de una cuenta en Mavsters.<br/>Por favor NO contestar o replicar este mensaje ya que no se responderá y no se tomara.<br/>Para continuar presiona el botón 'Dame Clic' el cual lo tienes abajo para verificar la nueva clave de tú cuenta.", $makeUrls);

            } else {

                $this->messagesNotification = "Forgotten password";

                $this->messagesNotificationComplete = "Check your email";

                // Asunto

                $this->subject = 'Password: New password for your Mavsters account';

                // Cuerpo o mensaje

                $divMain = $this->makeBody("This link helps you to change your account password in Mavsters. <br/> Please DO NOT answer or reply to this message as it will not be answered and will not be taken. <br/> To continue press the '<b>Click Here!</b>' button which you have below to verify the new password of your account.", $makeUrls);

            }

            // Cabeceras adicionales

            $this->nickname = "Mavsters - Forget Password";

            $this->from = "no-reply@mavsters.com";

            $this->message = $divMain->asHTML();

        }


        protected function makeUrlVerify($elementos)

        {

            $urlnew = "activation/";

            $urlnew .= $elementos[0]['code'];

            return $urlnew;

        }


        protected function makeBody($messageMain = "", $makeUrls = "")

        {

            // Cuerpo o mensaje

            $divMain = new HTMLTag('div', array("id" => "Thanks"));

            $center = new HTMLTag('center', array(

                "style" => "font-size: 0.875em;line-height: 1.71429em;",

            ));


            $divpopUp = new HTMLTag('div', array(

                "style" => "background-color: #fff;width: -webkit-fill-available;",

            ));

            $h2 = new HTMLTag('h1', array(

                "style" => "font-size: xx-large;color: white;font-weight: 700;background:url(http://mavsters.com/mavsters/themes/images/section_headline_bg.png) no-repeat center #1cbdf9;line-height:90px;height:88px;border-radius:35px 35px 0 0",

            ));

            $h2->innerHTML($this->subject);

            $divpopUp->appendChild($h2);

            $span = new HTMLTag('span', array("style" => "font-size: large;font-family: sans-serif;"));

            $span->innerHTML($messageMain);

            $divpopUp->appendChild($span);

            if (isset($makeUrls)) {

                $a = new HTMLTag('a', array(

                    "style" => "width: 270px;height: 60px;line-height: 60px;font-size: 1.25em;padding: 17px;background-color: #2b373a;border-radius: 4px;color: #fff;text-decoration: none;",

                    "rel" => 'noreferrer',

                    "href" => "http://mavsters.com/" . $makeUrls,

                ));

                $span = new HTMLTag('span', array(

                    "style" => "color: #16ffd8;",

                ));

                if ($this->lang === "es") {

                    $a->innerHTML("¡Dame ");

                    $span->innerHTML("Click!");

                } else {

                    $a->innerHTML("Click ");

                    $span->innerHTML("Here!");

                }

                $a->appendChild($span);

                $divpopUp->appendChild($a);

            }

            $divpopUp->appendChild(new HTMLTag('hr'));

            $divpopUp->appendChild(new HTMLTag('br'));

            $span = new HTMLTag('span', array(

                "style" => 'color: #888;font-size: 10px;line-height: 1.71429em;font-weight: 600;',

            ));

            if ($this->lang === "es") {

                $span->innerHTML("Este enlace te ayuda a la validación de tu cuenta en Mavsters, de omitir este paso tu cuenta no podrá ser registrada en nuestros dominios y no podrás disfrutar de las ventajas de ser un usuario en Mavsters<br/>Por favor no contestar o replicar este mensaje ya que no se responderá y no se tomara.");

            } else {

                $span->innerHTML("This link helps you to validate your account in Mavsters, by skipping this step your account will not be able to be registered in our domains and you will not be able to enjoy the advantages of being a user in Mavsters <br/> Please do not answer or reply this message because it will not be answered and will not be taken.");

            }

            $divpopUp->appendChild($span);

            $divpopUp->appendChild(new HTMLTag('br'));

            $a = new HTMLTag('a', array(

                "class" => 'button',

                "rel" => 'noreferrer',

                "href" => "http://mavsters.com/",

                "target" => '_blank',

            ));

            $img = new HTMLTag('img', array(

                "src" => '//mavsters.com/mavsters/themes/images/logo.png',

            ));

            $a->appendChild($img);

            if ($this->lang === "es") {

                $a->appendInnerHTML('🙈 Mavsters - Rompiendo Barreras - 🙊');

            } else {

                $a->appendInnerHTML('🙈 Mavsters - Breaking barriers - 🙊');

            }

            $divpopUp->appendChild($a);


            $center->appendChild($divpopUp);

            $divMain->appendChild($center);

            return $divMain;

        }


        private function sendEMails()

        {

            //Send

            if (is_array($this->emails)) {

                foreach ($this->emails as $email) {

                    $this->checkMail($email['email']);

                }

            } else {

                $this->checkMail($this->emails);

            }


        }

        private function getPriority()

        {

            /*

            "X-Priority" (values: 1 to 5- from the highest[1] to lowest[5]),

            "X-MSMail-Priority" (values: High, Normal, or Low),

            "Importance" (values: High, Normal, or Low).

             */

            $temp = "";

            $tempTwo = "";

            switch ($this->priority) {

                case 1:

                    $temp = "1 (Highest)";

                    $tempTwo = "High";

                    break;

                case 3:

                    $temp = "3 (Normal)";

                    $tempTwo = "Normal";

                    break;

                case 5:

                    $temp = "5 (Lowest)";

                    $tempTwo = "Low";

                    break;

            }

            $headers = "X-Priority:" . $temp . "\n";

            $headers .= "X-MSMail-Priority:" . $tempTwo . "\n";

            $headers .= "Importance: " . $tempTwo . "\n";

            return $headers;

        }


        protected function checkMail($email)

        {

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                // Cabecera que especifica que es un HMTL

                $headers = "MIME-Version: 1.0" . "\n";

                $headers .= "Content-Type: text/html; charset=\"" . $this->char . "\"" . "\n";

                $headers .= "From:  " . $this->nickname . " <" . $this->from . ">" . "\r\n";

                $headers .= $this->getPriority();


                //Config MS

                $style = new HTMLTag('style', array(

                    "type" => "text/css",

                ));

                $style->innerHTML("

                    body{background-color: #2b373a;}*{margin: 0;padding: 0;outline: none;border: none;box-sizing: border-box;}.ht-banner-content{font-size: 1.75em;} p{color: #888;font-size: 0.875em;line-height: 1.71429em;font-weight: 600;}.text-header{color: #2b373a;font-weight: 700;line-height: 1em;}.button{width: 242px;margin-top: 42px;height: 42px;line-height: 42px;font-size: 0.875em;background: #2b373a;position: relative;display: block;text-decoration: none;border-radius: 4px;color: #fff;font-family: 'Titillium Web', sans-serif;font-weight: 700;text-align: center;}.primary {color: #16ffd8;}");

                $ms = $style->asHTML() . $this->message;

                // enviamos el correo!

                //echo $email.", ".$this->subject.", ". $ms.", ".$headers;

                if (mail($email, $this->subject, $ms, $headers)) {

                    $div = new HTMLTag('div', array(

                        "id" => "thanks",

                    ));

                    $center = new HTMLTag('center');

                    $h2 = new HTMLTag('h2', array(

                        "style" => 'color:black !important;'));

                    $h2->appendInnerHTML($this->messagesNotification);

                    $p = new HTMLTag('p');

                    $p->appendInnerHTML($this->messagesNotificationComplete);

                    $center->appendChild($h2);

                    $center->appendChild($p);

                    $div->appendChild($center);

                    echo $div->asHTML();

                } else {

                    die("😪 Hay un problema con tú correo ($email), por favor rectificalo. 😢");

                }

            }

        }

    }

}