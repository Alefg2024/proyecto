<?php

declare(strict_types=1);

class Session
{
    public function __get(string $value)
    {
        if (!$this->exist($value)) {
            throw new RuntimeException("SESSION ERROR: El parametro {$value} que intentas llamar no existe!");
        }
        return $_SESSION[$value];
    }

    public function exist(string $value): bool
    {
        return isset($_SESSION[$value]);
    }
}
