<?php
/**
* @author evilnapsis
**/

declare(strict_types=1);

define("ROOT", __DIR__);

$debug = false;
if ($debug) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
}

require_once "core/autoload.php";
ob_start();
session_start();

// si quieres que se muestre las consultas SQL debes descomentar la siguiente línea
// Core::$debug_sql = true;

$lb = new Lb();
$lb->start();
