<?php

namespace Tienda\App\Libs;

class Session
{
    public function __construct()
    {
        session_start();
    }
}
