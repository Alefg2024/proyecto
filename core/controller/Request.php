<?php

declare(strict_types=1);

class Request
{
    public function __get(string $value)
    {
        if (!$this->exist($value)) {
            throw new RuntimeException("REQUEST ERROR: El parametro {$value} que intentas llamar no existe!");
        }
        return $_REQUEST[$value];
    }

    public function exist(string $value): bool
    {
        return isset($_REQUEST[$value]);
    }
}
