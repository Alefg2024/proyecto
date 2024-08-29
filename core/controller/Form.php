<?php

declare(strict_types=1);

class Form
{
    public static function exists(string $formname): bool
    {
        $fullpath = self::getFullpath($formname);
        return file_exists($fullpath);
    }

    public static function getFullpath(string $formname): string
    {
        return "core/modules/" . Module::$module . "/forms/" . $formname . ".php";
    }
}
