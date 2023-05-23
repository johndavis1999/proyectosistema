<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\Personas;
use App\Models\Productos;

class Dashboard extends BaseController
{
    public function index(){
        $persona = new Personas();
        $producto = new Productos();
        $totalClientes = $persona->where('es_cliente', 1)
                                ->where('estado', 1)
                                ->countAllResults();
        $data['totalClientes'] = $totalClientes;
        $totalProductos = $producto->where('estado', 1)
                                ->countAllResults();
        $data['totalProductos'] = $totalProductos;
        $titulo = "Dashboard";
        $data['titulo'] = $titulo;

        return view('Dashboard/index', $data);
    }

}
