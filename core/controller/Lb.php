<?php

declare(strict_types=1);

class Lb
{
    public function __construct() {}

    public function start(): void
    {
        require_once "core/app/autoload.php";
        require_once "core/app/init.php";
    }
}
