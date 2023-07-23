<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\Personas;
use App\Models\Productos;
use App\Models\Compras;

class Dashboard extends BaseController
{
    public function index(){
        $persona = new Personas();
        $producto = new Productos();
        $compra = new Compras();
        $totalClientes = $persona->where('es_cliente', 1)
                                ->where('estado', 1)
                                ->countAllResults();
        $data['totalClientes'] = $totalClientes;
        $totalProductos = $producto->where('estado', 1)
                                ->countAllResults();
        $data['totalProductos'] = $totalProductos;
        $totalCompras = $compra->where('estado', 1)
                                ->countAllResults();
        $data['totalCompras'] = $totalCompras;
        
        
        $db = $compra->db;
        $query = $db->table('compras')
        ->selectSum('total', 'totalSum')
        ->where('estado', 1)
        ->get();

        $row = $query->getRow();
        $valorEgresoCompras = $row ? $row->totalSum : 0;
        $data['valorEgresoCompras'] = $valorEgresoCompras;

        $titulo = "Dashboard";
        $data['titulo'] = $titulo;
        
        // validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }

        if (in_array($rol_usuario, [1, 2, 3])) {
            return view('dashboard/index', $data);
        } else {
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
    }

}
