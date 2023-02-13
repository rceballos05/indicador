<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('mostrar_valores_uf');
    }
}
