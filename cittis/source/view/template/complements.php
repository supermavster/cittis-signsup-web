<?php
$body->appendInnerHTML("<!-- Scripts -->");

$scripts = array(
    "core/jquery.min.js",
    "core/popper.min.js",
    "core/bootstrap-material-design.min.js",
    "plugins/moment.min.js",
    "plugins/perfect-scrollbar.jquery.min.js",
    "plugins/bootstrap-datetimepicker.min.js",
    "plugins/nouislider.min.js",
    "plugins/bootstrap-tagsinput.js",
    "plugins/bootstrap-selectpicker.js",
    "plugins/jasny-bootstrap.min.js",
    "plugins/bootstrap-notify.js",
    "material-dashboard.min.js?v=2.1.1"
);


foreach ($scripts as $value) {
    $body->appendChild(new HTMLTag('script', array(
        "type" => "text/javascript",
        'src' => AS_ASSETS . 'js/' . $value,
    )));
}

$body->appendInnerHTML('<!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/5.10.0/firebase-app.js"></script>
<!-- Add Firebase products that you want to use -->
  <script src="https://www.gstatic.com/firebasejs/5.10.0/firebase-auth.js"></script>
  <script src="https://www.gstatic.com/firebasejs/5.10.0/firebase-database.js"></script>
  <script src="' . URLWEB_FULL . 'signsup/source/model/admin/config.js"></script>
');

//Complements
//require_once AS_VIEW.'complements/all.php';
