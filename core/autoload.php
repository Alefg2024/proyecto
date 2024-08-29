<?php
declare(strict_types=1);

require_once "controller/Core.php";
require_once "controller/View.php";
require_once "controller/Module.php";
require_once "controller/Database.php";
require_once "controller/Executor.php";
require_once "controller/Lb.php";
require_once "controller/Model.php";
require_once "controller/Bootload.php";
require_once "controller/Action.php";

require_once "controller/class.upload.php";
// librerias para generar codigos qr
require_once "controller/phpqrcode/qrlib.php";
// librerias para enviar correos
require_once "controller/class.phpmailer.php";
require_once "controller/class.smtp.php";
require_once "controller/class.pop3.php";
