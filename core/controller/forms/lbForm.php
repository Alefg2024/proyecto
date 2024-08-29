<?php
declare(strict_types=1);

class lbForm {
    private array $field = [];

    public function __construct()
    {
    }

    public function addField(string $name, array $field): void
    {
        $this->field[$name] = $field;
    }

    public function render(string $field): string
    {
        return $this->getField($field)->render();
    }

    public function label(string $field): string
    {
        return $this->getField($field)->renderLabel();
    }

    public function getField(string $name): object
    {
        $field = $this->field[$name]['type'];
        $field->setName($name);
        return $field;
    }
}
