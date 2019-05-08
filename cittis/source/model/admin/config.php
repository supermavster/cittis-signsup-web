<?php

/**
 * Config for all Web
 *
 * @package    Cittis
 * @copyright  2019 Cittis
 */

// Debug Project (Only Admin)
const debug = true;

#Config Main
const limitTime = 300;
//visualcontrast
const nameProject = "singsup";

#Names Main
const Title = 'SignsUp - Cittis';
const version = 'v 2.2.2';
const Slogan = 'Slogan';
const Description = Title . " Descripción " . PHP_EOL . PHP_EOL . "-" . Slogan . "-  ";
# Tags
const KeysWeb = "Programming";

#Social
const YouTube = 'ID';
const idVideo = 'VIDEO_ID';
const Twitter = 'ID';
const Facebook = 'ID';
const GooglePlus = 'ID';
const Github = 'ID';

#Char Main
const charMain = 'UTF-8';

#Location - Time
const TimeZone = 'America/Bogota';
const GMT = "GMT-5";
const formatDate = 'Y-m-d H:i:s';

#Data Base
const DB_Host = 'localhost';//'cittis.com.co';
const DB_Name = 'cittisco_' . nameProject;
const DB_User = 'cittisco_SystemsTeamCittis';
const DB_Password = 'S@eKQ^?3a&vm';
const DB_Char = 'utf8';

#Login
const auth = "Autenticado";

#Google
//Captcha
const keyCaptcha = "KEY";
//Search
const idVerificationGoogle = "KEY";
//Analitycs
const idAnalitycs = "ID";


#Emails
// E-mail main for loging etc...
const mainEmail = 'contact@cittis.com.co';
// E-mail for notification of contact
const contactEmail = mainEmail;
// E-mail used when no e-mail is given
const noreplyEmail = mainEmail;
// E-mail for notification of report FAIL messages
const reportLinksEmail = mainEmail;
// E-mail for notification of new comments
const notificationEmail = mainEmail;

#Extends / Pluggins -> Keys
const jqueryVersion = "3.3.1";

# Values Temp
const valuesLogin = array(
    //Header Header
    'SINGUP' => "Registrarse",
    'SINGIN' => "Ingresar",
    'FORUM' => "Nuestro Foro",
    'WHY-MAVS' => "Actualizaciones de Mavsters",
    //LOGIN SINGUP
    'LOGIN-TITLE' => "Ingresa tú cuenta",
    'LOGIN-SUBTITLE' => "Ingresa tú cuenta y disfruta del contenido, links y archivos exclusivos.",
    'LOGIN-USER' => "Usuario",
    'LOGIN-PASSWORD' => "Contraseña",
    'LOGIN-REMEMBER' => "Recordar mi Contraseña",
    'LOGIN-FORGET-PASSWORD' => "¿Olvidaste tú Contraseña? <a href='#forgetPassword' class='forgetPassword primary'>¡Clic aquí!</a>",
    'LOGIN-NOW' => "Ingresa <span class='primary'>Ahora</span>!",
    'LOGIN-FACEBOOK' => "Ingresa con Facebook",
    'LOGIN-TWITTER' => "Ingresa con Twitter",

    'SINGUP-TITLE' => "Crear una Cuenta",
    'SINGUP-SUBTITLE' => "¡Registrate ahora y disfruta de todo el contenido de Mavsters!",
    'SINGUP-EMAIL' => "Correo Electrónico",
    'SINGUP-SUBEMAIL' => "Ingresa tu Correo Electrónico aquí...",
    'SINGUP-USER' => "Usuario",
    'SINGUP-USER-TITLE' => "Ingresa tú Usuario aquí...",
    'SINGUP-PASSWORD' => "Contraseña",
    'SINGUP-PASSWORD-TITLE' => "Repetir Contraseña",
    'SINGUP-PASSWORD-REPEAT' => "Repite tú Contraseña aquí...",
    'SINGUP-NOW' => "¡Registrarse <span class='primary'>Ahora</span>!",

    //Login
    'LOGIN-ERRO-1' => "Debes ingresar información válida",
    'LOGIN-ERRO-2' => "Usuario Incorrecto",
    'LOGIN-ERRO-3' => "Contraseña Incorrecta",

    //Signup
    'SIGNUP-ERRO-1' => "Debes ingresar información válida",
    'SIGNUP-ERRO-2' => "Upss .. Este Ya existe email",
    'SIGNUP-ERRO-3' => 'Perdón, pero este usuario ya existe',
    'SIGNUP-ERRO-4' => "Las contraseñas no coinciden",
);