<?php

declare(strict_types=1);

class Get
{
    public function __get(string $value)
    {
        if (!$this->exist($value)) {
            throw new RuntimeException("GET ERROR: El parametro {$value} que intentas llamar no existe!");
        }
        return $_GET[$value];
    }

    public function exist(string $value): bool
    {
        return isset($_GET[$value]);
    }
}
