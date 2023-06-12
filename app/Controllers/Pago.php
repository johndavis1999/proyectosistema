<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\Pagos;

class Pago extends BaseController{
    
    public function index(){
        $titulo = "Pagos";
        $data['titulo'] = $titulo;
        return view('pagos/index', $data);
    }
}