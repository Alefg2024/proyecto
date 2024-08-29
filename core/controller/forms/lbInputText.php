<?php
declare(strict_types=1);

class lbInputText {
    private array $config;
    private string $name;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function render(): string
    {
        return "<input type='text' name='{$this->name}' id='{$this->name}'>";
    }

    public function renderLabel(): string
    {
        return $this->config['label'] ?? '';
    }
}
