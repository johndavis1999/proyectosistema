<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\Facturas;

class Facturacion extends BaseController{
    
    public function index(){
        $factura = new Facturas();
        //$data['categorias'] = $categoria->orderBy('id','ASC')->paginate(10);
        $paginador = $factura->pager;
        $data['paginador']=$paginador;
        $titulo = "Facturas";
        $data['titulo'] = $titulo;
        return view('ventas/index', $data);
    }
    
    public function crear()
    {
        $titulo = "Facturacion";
        return view('ventas/crearFact', ['titulo' => $titulo]);
    }
    
    public function ver()
    {
        $titulo = "Facturacion";
        return view('Facturacion/verFact', ['titulo' => $titulo]);
    }
}