<?php

namespace App\Controllers;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Controllers\BaseController;
use App\Models\Cotizaciones;
use App\Models\Personas;
use App\Models\Users;
use App\Models\Productos;
use App\Models\DetalleCotizacion;
use App\Models\Cobros;

class Cotizacion extends BaseController{
    
    public function index(){
        $cotizacion = new Cotizaciones();
        $titulo = "Cotizaciones";
        $data['titulo'] = $titulo;
        $cliente = new Personas();
        $data['clientes'] = $cliente->where('es_cliente', '1')
                                      ->orderBy('id', 'ASC')
                                      ->findAll();
        $data['vendedores'] = $cliente->where('es_empleado', '1')
                                      ->orderBy('id', 'ASC')
                                      ->findAll();

        $num_cot = $this->request->getVar('num_cot');
        $clienteFiltro = $this->request->getVar('clienteFiltro');
        $vendedorFiltro = $this->request->getVar('vendedorFiltro');
        $fecha_inicio = $this->request->getVar('fecha_inicio');
        $fecha_fin = $this->request->getVar('fecha_fin');
        $iva = $this->request->getVar('iva');
        $pagado = $this->request->getVar('pagado');
        $aprobado = $this->request->getVar('aprobado');
        $descuento = $this->request->getVar('descuento');
        $estado = $this->request->getVar('estado');

        $data['num_cot']=$num_cot;
        $data['clienteFiltro']=$clienteFiltro;
        $data['vendedorFiltro']=$vendedorFiltro;
        $data['fecha_inicio']=$fecha_inicio;
        $data['fecha_fin']=$fecha_fin;
        $data['iva']=$iva;
        $data['pagado']=$pagado;
        $data['aprobado']=$aprobado;
        $data['descuento']=$descuento;
        $data['estado']=$estado;

        if ($num_cot != null || $clienteFiltro != null || $vendedorFiltro != null || $iva != null || $pagado != null || $descuento != null  || $estado != null || $aprobado != null || ($fecha_inicio != null && $fecha_fin != null)) {
            $cotizacion->orderBy('id', 'ASC');

            if ($num_cot != null) {
                $cotizacion->like('num_cot', $num_cot);
            }

            if ($clienteFiltro != null) {
                $cotizacion->like('id_cliente', $clienteFiltro);
            }

            if ($vendedorFiltro != null) {
                $cotizacion->like('id_vendedor', $vendedorFiltro);
            }

            if ($fecha_inicio != null && $fecha_fin != null) {
                $cotizacion->where('fecha_doc >=', $fecha_inicio);
                $cotizacion->where('fecha_doc <=', $fecha_fin);
            }

            if ($iva != null) {
                if($iva == '0'){
                    $cotizacion->where('val_iva ='. 0);
                } else{
                    $cotizacion->where('val_iva >', 0);
                }
            }

            if ($estado != null) {
                $cotizacion->where('cotizaciones.estado', $estado);
            }

            if ($aprobado != null) {
                $cotizacion->where('cotizaciones.aprobado', $aprobado);
            }

            if ($descuento != null) {
                if ($descuento == 'Si'){
                    $cotizacion->where('val_descuento >', 0);
                }
                else{
                    $cotizacion->where('val_descuento =', 0);
                }
            }

            if ($pagado != null) {
                if($pagado == "Pagado"){
                    $cotizacion->where('total = valor_pagado');
                } else {
                    $cotizacion->where('total != valor_pagado');
                }
            }

            $data['cotizaciones'] = $cotizacion->select('cotizaciones.*, cli.nombres as persona, vend.nombres as vendedor')
                                        ->join('personas as cli', 'cli.id = cotizaciones.id_cliente', 'left')
                                        ->join('personas as vend', 'vend.id = cotizaciones.id_vendedor', 'left')
                                        ->orderBy('cotizaciones.id', 'DESC')
                                        ->paginate(10);

            $paginador = $cotizacion->pager;
            $data['paginador'] = $paginador;

            $detalleCotizacion = new DetalleCotizacion();
            foreach ($data['cotizaciones'] as &$cotizacion) {
                $cantidadRegistros = $detalleCotizacion->where('id_cotizacion', $cotizacion['id'])->countAllResults();
                $cotizacion['cantidad_registros'] = $cantidadRegistros;
            }
            
            return view('ventas/cotizaciones', $data);
        }
        //$data['categorias'] = $categoria->orderBy('id','ASC')->paginate(10);
        $data['cotizaciones'] = $cotizacion->select('cotizaciones.*, cli.nombres as persona, vend.nombres as vendedor')
                                           ->join('personas as cli', 'cli.id = cotizaciones.id_cliente', 'left')
                                           ->join('personas as vend', 'vend.id = cotizaciones.id_vendedor', 'left')
                                           ->orderBy('cotizaciones.id', 'DESC')
                                           ->paginate(10);
        $paginador = $cotizacion->pager;
        $data['paginador']=$paginador;

        $detalleCotizacion = new DetalleCotizacion();
        foreach ($data['cotizaciones'] as &$cotizacion) {
            $cantidadRegistros = $detalleCotizacion->where('id_cotizacion', $cotizacion['id'])->countAllResults();
            $cotizacion['cantidad_registros'] = $cantidadRegistros;
        }

        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2,3])) {
            return view('ventas/cotizaciones', $data);
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
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

        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2,3])) {
            return view('ventas/crearCotizacion', $data);
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
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
        

        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2,3])) {
            return redirect()->to(base_url('consultarCotizacion/'.$lastInsertId))->with('exito', 'Cotizacion Creada Exitosamente');
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
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
        
        $cobros = new Cobros();
        $cobro = $cobros->consultarCobro($id);
        if ($cobro) {
            $session = session();
            $session->setFlashData('mensaje','No se puede eliminar el documento ya que cuenta con cobros relacionados');
            return redirect()->back()->withInput();
        }

        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2])) {
            $this->reversarStockEgreso($id);
            $this->eliminarEgresoDetalle($id);
            $cotizaciones->where('id', $id)->delete($id);
            return redirect()->to(base_url('Cotizaciones'))->with('exito', 'Cotizacion Eliminada exitosamente');
            #return $this->response->redirect(site_url('Cotizaciones'));
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
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

        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2,3])) {
            return view('ventas/consultarCotizacion', $data);
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
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

        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2])) {
            return view('ventas/editarCotizacion', $data);
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
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

    public function generarExcel($num_cot, $clienteFiltro, $vendedorFiltro, $fecha_inicio, $fecha_fin, $iva, $pagado, $descuento, $estado, $aprobado){

        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2])) {
            $cotizacion = new Cotizaciones();
            // Aplica los filtros solo si se han seleccionado valores
            if (  $num_cot != 'none' || $clienteFiltro != 'none' || $vendedorFiltro != 'none' || $fecha_inicio != 'none' || $fecha_fin != 'none' || $iva != 'none' || $pagado != 'none' || $descuento != 'none' || $estado != 'none' || $aprobado != 'none') {
                $cotizacion->orderBy('id', 'ASC');
    
                if ($num_cot != 'none') {
                    $cotizacion->where('cotizaciones.num_cot', $num_cot);
                }
    
                if ($clienteFiltro != 'none') {
                    $cotizacion->where('cotizaciones.id_cliente', $clienteFiltro);
                }
    
                if ($vendedorFiltro != 'none') {
                    $cotizacion->where('cotizaciones.id_vendedor', $vendedorFiltro);
                }
    
                if ($fecha_inicio != 'none' && $fecha_fin != 'none') {
                    $cotizacion->where('cotizaciones.fecha_doc >=', $fecha_inicio);
                    $cotizacion->where('cotizaciones.fecha_doc <=', $fecha_fin);
                }
    
                if ($iva != 'none') {
                    if($iva == '0'){
                        $cotizacion->where('val_iva ='. 0);
                    } else{
                        $cotizacion->where('val_iva >', 0);
                    }
                }
    
                if ($estado != 'none') {
                    $cotizacion->where('cotizaciones.estado', $estado);
                }
    
                if ($descuento != 'none') {
                    if ($descuento == 'Si'){
                        $cotizacion->where('val_descuento >', 0);
                    }
                    else{
                        $cotizacion->where('val_descuento =', 0);
                    }
                }
    
                if ($aprobado != 'none') {
                    $cotizacion->where('aprobado', $aprobado);
                }
    
                if ($pagado != 'none') {
                    if($pagado == "Pagado"){
                        $cotizacion->where('total = valor_pagado');
                    } else {
                        $cotizacion->where('total != valor_pagado');
                    }
                }

                $data['cotizaciones'] = $cotizacion->select('cotizaciones.*, cli.nombres as persona, vend.nombres as vendedor')
                                                            ->join('personas as cli', 'cli.id = cotizaciones.id_cliente', 'left')
                                                            ->join('personas as vend', 'vend.id = cotizaciones.id_vendedor', 'left')
                                                            ->orderBy('cotizaciones.id', 'DESC')
                                                            ->findAll();
            } else {
                
                    $data['cotizaciones'] = $cotizacion->select('cotizaciones.*, cli.nombres as persona, vend.nombres as vendedor')
                    ->join('personas as cli', 'cli.id = cotizaciones.id_cliente', 'left')
                    ->join('personas as vend', 'vend.id = cotizaciones.id_vendedor', 'left')
                    ->orderBy('cotizaciones.id', 'DESC')
                    ->findAll();
            }
    
            // Crear un nuevo objeto Spreadsheet
            $spreadsheet = new Spreadsheet();
    
            // Obtener la hoja activa
            $sheet = $spreadsheet->getActiveSheet();
            
            $sheet->getColumnDimension('B')->setWidth(13);
            $sheet->getColumnDimension('C')->setWidth(20);
            $sheet->getColumnDimension('D')->setWidth(20);
            $sheet->getColumnDimension('E')->setWidth(11);
            $sheet->getColumnDimension('F')->setWidth(15);
            $sheet->getColumnDimension('G')->setWidth(14);
            $sheet->getColumnDimension('H')->setWidth(14);
            $sheet->getColumnDimension('I')->setWidth(14);
            $sheet->getColumnDimension('J')->setWidth(14);
            $sheet->getColumnDimension('K')->setWidth(12);
    
            // Agregar encabezados
            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Numero Cot.');
            $sheet->setCellValue('C1', 'Cliente');
            $sheet->setCellValue('D1', 'Vendedor');
            $sheet->setCellValue('E1', 'Fecha');
            $sheet->setCellValue('F1', 'Val. Subtotal');
            $sheet->setCellValue('G1', 'Val. Descuento');
            $sheet->setCellValue('H1', 'Val. Iva');
            $sheet->setCellValue('I1', 'Val. Total');
            $sheet->setCellValue('J1', 'Val. Pagado');
            $sheet->setCellValue('K1', 'Estado Pago');
            $sheet->setCellValue('L1', 'Aprobado');
            $sheet->setCellValue('M1', 'Estado');
            // ... Agregar más columnas según tus necesidades
    
            // Agregar datos de las personas al archivo Excel
            $row = 2;
            foreach ($data['cotizaciones'] as $cotizacion) {
                $sheet->setCellValue('A' . $row, $cotizacion['id']);
                $sheet->setCellValue('B' . $row, $cotizacion['num_cot']);
                $sheet->setCellValue('C' . $row, $cotizacion['persona']);
                $sheet->setCellValue('D' . $row, $cotizacion['vendedor']);
                $sheet->setCellValue('E' . $row, $cotizacion['fecha_doc']);
                $sheet->setCellValue('F' . $row, "$" . $cotizacion['subtotal_cotizacion']);
                $sheet->setCellValue('G' . $row, "$" . $cotizacion['val_descuento']);
                $sheet->setCellValue('H' . $row, "$" . $cotizacion['val_iva']);
                $sheet->setCellValue('I' . $row, "$" . $cotizacion['total']);
                $sheet->setCellValue('J' . $row, "$" . $cotizacion['valor_pagado']);
                $sheet->setCellValue('K' . $row, $cotizacion['total'] == $cotizacion['valor_pagado'] ? 'Pagado' : 'Pendiente');
                $sheet->setCellValue('L' . $row, $cotizacion['aprobado'] == '1' ? 'Aprobado' : 'Pendiente');                // ... Agregar más columnas según tus necesidades
                $sheet->setCellValue('M' . $row, $cotizacion['estado'] == '1' ? 'Activo' : 'Anulado');                // ... Agregar más columnas según tus necesidades
    
                $row++;
            }
    
            // Guardar el archivo Excel
            $writer = new Xlsx($spreadsheet);
            $filename = 'export_compras.xlsx';
            $writer->save($filename);
    
            // Descargar el archivo Excel
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $filename . '"');
            header('Cache-Control: max-age=0');
            $writer->save('php://output');
            exit;
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
    }
}