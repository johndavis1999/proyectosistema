<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\Cotizaciones;
use App\Models\Personas;
use App\Models\Users;
use App\Models\Productos;
use App\Models\DetalleCotizacion;

class Cotizacion extends BaseController{
    
    public function index(){
        $cotizacion = new Cotizaciones();
        //$data['categorias'] = $categoria->orderBy('id','ASC')->paginate(10);
        $data['cotizaciones'] = $cotizacion->select('cotizaciones.*, cli.nombres as persona, vend.nombres as vendedor')
                                           ->join('personas as cli', 'cli.id = cotizaciones.id_cliente', 'left')
                                           ->join('personas as vend', 'vend.id = cotizaciones.id_vendedor', 'left')
                                           ->orderBy('cotizaciones.id', 'DESC')
                                           ->paginate(10);
        $paginador = $cotizacion->pager;
        $data['paginador']=$paginador;
        $titulo = "Cotizaciones";
        $data['titulo'] = $titulo;
        $detalleCotizacion = new DetalleCotizacion();
        

        foreach ($data['cotizaciones'] as &$cotizacion) {
            $cantidadRegistros = $detalleCotizacion->where('id_cotizacion', $cotizacion['id'])->countAllResults();
            $cotizacion['cantidad_registros'] = $cantidadRegistros;
        }
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

        $usuarios = new Users();
        $usuario = session('id');
        $usuario = $usuarios->where('id', $usuario)->first();
        $personaID = $usuario['id_persona'];
        
        $data['vendedor'] = $persona->select('personas.*')
                                    ->where('id', $personaID)
                                    ->first();

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
        $id_vendedor = $this->request->getVar('id_vendedor');
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
            #'num_cot'=>'numeric',
            'id_cliente'=>'required|numeric',
            'id_vendedor'=>'required|numeric',
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
            'id_vendedor'=>$id_vendedor,
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
        $lastInsertId = $cotizacion->insertID();
        $this->guardarDetalle($lastInsertId);
        $this->actualizarStockEgreso($lastInsertId);
        #return $this->response->redirect(site_url('consultarCotizacion/'.$lastInsertId));
        
        return redirect()->to(base_url('consultarCotizacion/'.$lastInsertId))->with('exito', 'Cotizacion Creada Exitosamente');
       
        
    }

    private function guardarDetalle($lastInsertId) {
        $detalle = new DetalleCotizacion();
        // Recorremos los detalles de ingreso y los guardamos en la base de datos
        for ($i = 0; $i < count($this->request->getVar('id_producto')); $i++) {
            $data = [
                'id_cotizacion' => $lastInsertId,
                'id_producto' => $this->request->getVar('id_producto')[$i],
                'cantidad' => $this->request->getVar('cantidad_venta')[$i],
                'precio' => $this->request->getVar('precio_venta')[$i],
                'descuento' => $this->request->getVar('descuento_item')[$i],
                'iva' => $this->request->getVar('iva_producto')[$i],
                'subtotal' => $this->request->getVar('monto_subtotal_item')[$i],
                'total' => $this->request->getVar('monto_total_item')[$i]
            ];
            $detalle->insert($data);
        }
    }

    private function actualizarStockEgreso($lastInsertId) {
        // Selecciona los productos y las cantidades que se ingresaron en la cotizacion
        $detalles = new DetalleCotizacion();
        $detalle_egreso = $detalles->where('id_cotizacion', $lastInsertId)->findAll();
        // Incrementa el stock de cada producto ingresado capturando 'cantidad' de cada producto
        $productos = new Productos();
        foreach ($detalle_egreso as $detalle) {
            $productos = new Productos();
            $producto = $productos->find($detalle['id_producto']);
            // Validación: Solo se itera si el producto es inventariable
            if ($producto['es_inventariable'] == 1) {
                $nuevoStock = $producto['stock'] - $detalle['cantidad'];
                $productos->update($detalle['id_producto'], ['stock' => $nuevoStock]);
            }
        }
    }
    
    public function eliminar($id = null) {
        $cotizaciones = new Cotizaciones();
        $cotizacion = $cotizaciones->find($id);

        $cotizaciones->where('id', $id)->delete($id);
        $this->reversarStockEgreso($id);
        $this->eliminarEgresoDetalle($id);
        return $this->response->redirect(site_url('Cotizaciones'));
    }
    
    private function reversarStockEgreso($id_cotizacion) {
        // Selecciona los productos y las cantidades que se egresaron en la cotizacion
        $detalles = new DetalleCotizacion();
        $detalle_egreso = $detalles->where('id_cotizacion', $id_cotizacion)->findAll();
        // Incrementa el stock de cada producto ingresado capturando 'cantidad' de cada producto
        foreach ($detalle_egreso as $detalle) {
            
            $productos = new Productos();
            $producto = $productos->find($detalle['id_producto']);
            // Validación: Solo se itera si el producto es inventariable
            if ($producto['es_inventariable'] == 1) {
                $nuevoStock = $producto['stock'] + $detalle['cantidad'];
                $productos->update($detalle['id_producto'], ['stock' => $nuevoStock]);
            }
        }
    }
    
    public function eliminarEgresoDetalle($id_cotizacion){
        $detalles = new DetalleCotizacion();
        $detalles->where('id_cotizacion', $id_cotizacion)->delete();
    }
    
    public function consultar($id=null){
        $cotizaciones = new Cotizaciones();
        $data['cotizacion'] = $cotizaciones->where('id',$id)->first();
        $persona = new Personas();
        $data['personas'] = $persona->where('es_cliente', '1')
                                    ->orderBy('id', 'ASC')
                                    ->findAll();

        $data['vendedores'] = $persona->where('es_empleado', '1')
                                      ->where('id_cargo', '3')
                                      ->orderBy('id', 'ASC')
                                      ->findAll();


        $producto = new Productos();
        $data['productos'] = $producto->where('estado', '1')
                                    ->orderBy('id', 'ASC')
                                    ->findAll();
        $titulo = "Cotizaciones";
        $data['titulo'] = $titulo;
        $data['detalles'] = $this->obtenerItemsCotizacion($id);

        return view('ventas/consultarCotizacion', $data);
    }

    private function obtenerItemsCotizacion($id){
        $detalles = new DetalleCotizacion();
        return $detalles->select('detalle_cotizacion.*, productos.id as producto')
            ->join('productos', 'productos.id = detalle_cotizacion.id_producto', 'left')
            ->where('id_cotizacion', $id)
            ->orderBy('detalle_cotizacion.id', 'ASC')
            ->findAll();
    }
    
    public function editar($id=null){
        $cotizaciones = new Cotizaciones();
        $data['cotizacion'] = $cotizaciones->where('id',$id)->first();
        $persona = new Personas();
        $data['personas'] = $persona->where('es_cliente', '1')
                                    ->orderBy('id', 'ASC')
                                    ->findAll();

        $data['vendedores'] = $persona->where('es_empleado', '1')
                                      ->where('id_cargo', '3')
                                      ->orderBy('id', 'ASC')
                                      ->findAll();

        $producto = new Productos();
        $data['productos'] = $producto->where('estado', '1')
                                    ->orderBy('id', 'ASC')
                                    ->findAll();
        $titulo = "Cotizaciones";
        $data['titulo'] = $titulo;
        $data['detalles'] = $this->obtenerItemsCotizacion($id);

        return view('ventas/editarCotizacion', $data);
    }

    public function actualizar($id = null){
        
        $cotizacion = new Cotizaciones();


        $id = $this->request->getVar('id');
        $id_cliente = $this->request->getVar('id_cliente');
        $id_vendedor = $this->request->getVar('id_vendedor');
        $fecha_doc = $this->request->getVar('fecha_doc');
        $descripcion = $this->request->getVar('descripcion');
        $subtotal_cotizacion = $this->request->getVar('subtotal_cotizacion');
        $val_descuento = $this->request->getVar('val_descuento');
        $val_iva = $this->request->getVar('val_iva');
        $total = $this->request->getVar('total');
        $estado = $this->request->getVar('estado');
        $aprobado = $this->request->getVar('aprobado');
        $estadoOriginal = $this->request->getVar('estado_original');

        $validacion = $this->validate([
            #'num_cot'=>'numeric',
            'id_cliente'=>'required|numeric',
            'id_vendedor'=>'required|numeric',
            'fecha_doc' => 'required',
            'descripcion' => 'max_length[250]',
            'subtotal_cotizacion' => 'numeric',
            'val_descuento' => 'numeric',
            'val_iva' => 'required|numeric',
            'total' => 'required|numeric',
            'estado' => 'required|numeric',
            'aprobado' => 'required|numeric',
        ]);
        if(!$validacion){
            $session = session();
            $session->setFlashData('mensaje','Revise la información');
            return redirect()->back()->withInput();
        }

        
        
        $datos=[
            'id_cliente'=>$id_cliente,
            'id_vendedor'=>$id_vendedor,
            'fecha_doc'=>$fecha_doc,
            'descripcion'=>$descripcion,
            'subtotal_cotizacion'=>$subtotal_cotizacion,
            'val_descuento'=>$val_descuento,
            'val_iva'=>$val_iva,
            'total'=>$total,
            'aprobado'=>$aprobado,
            'estado'=>$estado
        ];
    
        $cotizacion->update($id,$datos);
        
        if($estado == '0'){
            if($estadoOriginal == '0'){
                #$this->reversarStockEgreso($id);
                $this->eliminarEgresoDetalle($id);
                $this->guardarDetalle($id);
                #$this->actualizarStockEgreso($id);
            }
        }
        
        if($estado == '1'){
            if($estadoOriginal == '0'){
                #$this->reversarStockEgreso($id);
                $this->eliminarEgresoDetalle($id);
                $this->guardarDetalle($id);
                $this->actualizarStockEgreso($id);
            }
        }
        
        if($estado == '0'){
            if($estadoOriginal == '1'){
                $this->reversarStockEgreso($id);
                $this->eliminarEgresoDetalle($id);
                $this->guardarDetalle($id);
                #$this->actualizarStockEgreso($id);
            }
        }
        
        if($estado == '1'){
            if($estadoOriginal == '1'){
                $this->reversarStockEgreso($id);
                $this->eliminarEgresoDetalle($id);
                $this->guardarDetalle($id);
                $this->actualizarStockEgreso($id);
            }
        }
        return redirect()->to(base_url('consultarCotizacion/'.$id))->with('exito', 'Cotizacion Actualizada exitosamente');
        
    }
}