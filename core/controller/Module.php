<?php

declare(strict_types=1);

class Module
{


    public static $module;

    public static function loadLayout(): void
    {
        require_once "core/app/layouts/layout.php";
    }
}
