<?php

declare(strict_types=1);

class Post
{
    public function __get(string $value)
    {
        if (!$this->exist($value)) {
            throw new RuntimeException("POST ERROR: El parametro {$value} que intentas llamar no existe!");
        }
        return $_POST[$value];
    }

    public function exist(string $value): bool
    {
        return isset($_POST[$value]);
    }
}
