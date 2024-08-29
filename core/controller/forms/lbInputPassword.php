<?php
declare(strict_types=1);

class lbInputPassword {
    public function __construct(array $config)
    {
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function render(): string
    {
        return "<input type='password' name='{$this->name}' id='{$this->name}'>";
    }

    public function renderLabel(): string
    {
        return $this->config['label'] ?? '';
    }
}
