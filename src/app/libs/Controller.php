<?php

namespace Tienda\App\Libs;

class Controller extends Session
{
    protected function render(string $path): void
    {
        if ($this->verifyPath($path)) {
            require_once __DIR__ . '/../../views/' . $path . '.phtml';
        }
    }

    private function verifyPath($path): bool
    {
        if (file_exists(__DIR__ . '/../../views/' . $path . '.phtml')) {
            return true;
        } else {
            throw new \Exception("No existe la vista que desea renderizar", 1);
        }
    }
}
