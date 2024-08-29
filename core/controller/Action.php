<?php

declare(strict_types=1);

class Action
{

    public static function load(string $action): void
    {
        if (!isset($_GET['action'])) {
            require_once "core/app/action/{$action}-action.php";
        } else {
            if (self::isValid()) {
                require_once "core/app/action/{$_GET['action']}-action.php";
            } else {
                self::Error("<b>404 NOT FOUND</b> Action <b>{$_GET['action']}</b> folder  !! - <a href='http://evilnapsis.com/legobox/help/' target='_blank'>Help</a>");
            }
        }
    }

    /**
     * @function isValid
     * @brief valida la existencia de una vista
     **/
    public static function isValid(): bool
    {
        return file_exists("core/app/action/{$_GET['action']}-action.php");
    }

    public static function Error(string $message): void
    {
        echo $message;
    }

    public function execute(string $action, array $params): void
    {
        $fullpath = "core/app/action/{$action}-action.php";
        if (file_exists($fullpath)) {
            require_once $fullpath;
        } else {
            assert(false, "wtf");
        }
    }
}
