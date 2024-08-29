<?php

declare(strict_types=1);

// 10 de Octubre del 2014
// Bootload.php
// @brief esta clase sirve para alistar los boot

class Bootload
{

    public static function load(string $view): void
    {
        if (!isset($_GET['view'])) {
            require_once "core/modules/" . Module::$module . "/boot/{$view}/boot-default.php";
        } else {
            if (self::isValid()) {
                $fullpath = "core/modules/" . Module::$module . "/boot/{$_GET['view']}/boot-default.php";
                require_once $fullpath;
            } else {
                self::Error("<b>404 NOT FOUND</b> Boot <b>{$_GET['view']}</b> folder  !!");
            }
        }
    }

    /**
     * @function isValid
     * @brief valida la existencia de una vista
     **/
    public static function isValid(): bool
    {
        return file_exists("core/modules/" . Module::$module . "/boot/{$_GET['view']}/boot-default.php");
    }

    public static function Error(string $message): void
    {
        echo $message;
    }
}
