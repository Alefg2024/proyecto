<?php

declare(strict_types=1);

class View
{
    public static function load(string $view): void
    {
        if (!isset($_GET['view'])) {
            require_once "core/app/view/{$view}-view.php";
        } else {
            if (self::isValid()) {
                require_once "core/app/view/{$_GET['view']}-view.php";
            } else {
                self::Error("<b>404 NOT FOUND</b> View <b>{$_GET['view']}</b> folder !! - <a href='http://evilnapsis.com/legobox/help/' target='_blank'>Help</a>");
            }
        }
    }

    public static function isValid(): bool
    {
        if (isset($_GET["view"])) {
            return file_exists("core/app/view/{$_GET['view']}-view.php");
        }
        return false;
    }

    public static function Error(string $message): void
    {
        echo $message;
    }
}
