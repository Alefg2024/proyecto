<?php

declare(strict_types=1);

class Cookie
{
    public function __get(string $value)
    {
        if (!$this->exist($value)) {
            throw new RuntimeException("COOKIE ERROR: El parametro {$value} que intentas llamar no existe!");
        }
        return $_COOKIE[$value];
    }

    public function exist(string $value): bool
    {
        return isset($_COOKIE[$value]);
    }
}
