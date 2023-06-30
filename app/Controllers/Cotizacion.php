<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\Cotizaciones;
use App\Models\Personas;
use App\Models\Productos;
use App\Models\DetalleCotizacion;

class Cotizacion extends BaseController{
    
    public function index(){
        $cotizacion = new Cotizaciones();
        //$data['categorias'] = $categoria->orderBy('id','ASC')->paginate(10);
        $paginador = $cotizacion->pager;
        $data['paginador']=$paginador;
        $titulo = "Cotizaciones";
        $data['titulo'] = $titulo;
        return view('ventas/cotizaciones', $data);
    }
    
    public function crear()
    {
        date_default_timezone_set('America/Mexico_City');
        
        $cotizacion = new Cotizaciones();
        $ultimasecuencia = $cotizacion->obtenerUltimaSecuencia();

        $persona = new Personas();
        $data['personas'] = $persona->where('es_cliente', '1')
                                    ->orderBy('id', 'ASC')
                                    ->findAll();
        $producto = new Productos();
        $data['productos'] = $producto->where('estado', '1')
                                    ->orderBy('id', 'ASC')
                                    ->findAll();
        $titulo = "Cotizaciones";
        $data['titulo'] = $titulo;
        $data['num_cot'] = $ultimasecuencia+1;
        return view('ventas/crearCotizacion', $data);
    }

    public function guardar(){
        $cotizacion = new Cotizaciones();
        $ultimasecuencia = $cotizacion->obtenerUltimaSecuencia();


        $num_cot = $ultimasecuencia+1;
        $id_cliente = $this->request->getVar('id_cliente');
        $fecha_doc = $this->request->getVar('fecha_doc');
        $descripcion = $this->request->getVar('descripcion');
        $subtotal_cotizacion = $this->request->getVar('subtotal_cotizacion');
        $val_descuento = $this->request->getVar('val_descuento');
        $val_iva = $this->request->getVar('val_iva');
        $total = $this->request->getVar('total');
        $pagado = 0;
        $estado = 1;
        $valor_pagado = 0;
        $aprobado = 0;

        $validacion = $this->validate([
            'num_cot'=>'required|numeric',
            'id_cliente'=>'required|numeric',
            'fecha_doc' => 'required',
            'descripcion' => 'max_length[250]',
            'subtotal_cotizacion' => 'required|numeric',
            'val_descuento' => 'numeric',
            'val_iva' => 'required|numeric',
            'total' => 'required|numeric',
        ]);
        if(!$validacion){
            $session = session();
            $session->setFlashData('mensaje','Revise la información');
            return redirect()->back()->withInput();
        }
        
        $datos=[
            'num_cot'=>$num_cot,
            'id_cliente'=>$id_cliente,
            'fecha_doc'=>$fecha_doc,
            'descripcion'=>$descripcion,
            'subtotal_cotizacion'=>$subtotal_cotizacion,
            'val_descuento'=>$val_descuento,
            'val_iva'=>$val_iva,
            'total'=>$total,
            'pagado'=>$pagado,
            'valor_pagado'=>$valor_pagado,
            'aprobado'=>$aprobado,
            'estado'=>$estado
        ];
        $cotizacion->insert($datos);
        return $this->response->redirect(site_url('Cotizaciones'));
        
    }
}