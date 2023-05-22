<?php

namespace App\Controllers;
use App\Controllers\BaseController;

class Facturacion extends BaseController
{
    
    public function crear()
    {
        $titulo = "Facturacion";
        return view('Facturacion/crearFact', ['titulo' => $titulo]);
    }
    
    public function ver()
    {
        $titulo = "Facturacion";
        return view('Facturacion/verFact', ['titulo' => $titulo]);
    }
}