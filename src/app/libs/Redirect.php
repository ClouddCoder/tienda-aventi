<?php

namespace Tienda\App\Libs;

class Redirect extends Session
{
    public function route($route)
    {
        header('location: ' . $route);
        return $this;
    }
}
