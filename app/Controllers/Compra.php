<?php
namespace App\Controllers;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Controllers\BaseController;
use App\Models\Compras;
use App\Models\Pagos;
use App\Models\Personas;
use App\Models\Productos;
use App\Models\DetalleCompras;

class Compra extends BaseController{
    
    public function index(){
        $compra = new Compras();
        $proveedor = new Personas();
        $data['proveedores'] = $proveedor->where('es_proveedor', '1')
                                    ->where('estado', '1')
                                    ->orderBy('id', 'DESC')
                                    ->findAll();
        $titulo = "Compras";
        $data['titulo'] = $titulo;

        $num_fact = $this->request->getVar('num_fact');
        $proveedorFiltro = $this->request->getVar('proveedorFiltro');
        $fecha_inicio = $this->request->getVar('fecha_inicio');
        $fecha_fin = $this->request->getVar('fecha_fin');
        $iva = $this->request->getVar('iva');
        $pagado = $this->request->getVar('pagado');
        $descuento = $this->request->getVar('descuento');
        $estado = $this->request->getVar('estado');

        $data['num_fact']=$num_fact;
        $data['proveedorFiltro']=$proveedorFiltro;
        $data['fecha_inicio']=$fecha_inicio;
        $data['fecha_fin']=$fecha_fin;
        $data['iva']=$iva;
        $data['pagado']=$pagado;
        $data['descuento']=$descuento;
        $data['estado']=$estado;

        if ($num_fact != null || $proveedorFiltro != null || $iva != null || $pagado != null || $descuento != null  || $estado != null || ($fecha_inicio != null && $fecha_fin != null)) {
            $compra->orderBy('id', 'ASC');

            if ($num_fact != null) {
                $compra->like('num_fact', $num_fact);
            }

            if ($proveedorFiltro != null) {
                $compra->like('id_per_prov', $proveedorFiltro);
            }

            if ($fecha_inicio != null && $fecha_fin != null) {
                $compra->where('fecha_doc >=', $fecha_inicio);
                $compra->where('fecha_doc <=', $fecha_fin);
            }

            if ($iva != null) {
                if($iva == '0'){
                    $compra->where('val_iva ='. 0);
                } else{
                    $compra->where('val_iva >', 0);
                }
            }

            if ($estado != null) {
                $compra->where('compras.estado', $estado);
            }

            if ($descuento != null) {
                if ($descuento == 'Si'){
                    $compra->where('val_descuento >', 0);
                }
                else{
                    $compra->where('val_descuento =', 0);
                }
            }

            if ($pagado != null) {
                if($pagado == "Pagado"){
                    $compra->where('total = valor_pagado');
                } else {
                    $compra->where('total != valor_pagado');
                }
            }
            

            $data['compras'] = $compra->select('compras.*, personas.nombres as persona')
                                    ->join('personas', 'personas.id = compras.id_per_prov', 'left')
                                    ->orderBy('compras.id', 'DESC')
                                    ->paginate(10);

            $paginador = $compra->pager;
            $data['paginador'] = $paginador;
            
            $detalleCompra = new DetalleCompras();

            foreach ($data['compras'] as &$compra) {
                $cantidadRegistros = $detalleCompra->where('id_compra', $compra['id'])->countAllResults();
                $compra['cantidad_registros'] = $cantidadRegistros;
            }
            
            return view('compras/index', $data);
        }
        
        $data['compras'] = $compra->select('compras.*, personas.nombres as persona')
                                    ->join('personas', 'personas.id = compras.id_per_prov', 'left')
                                    ->orderBy('compras.id', 'DESC')
                                    ->paginate(10);
        $paginador = $compra->pager;
        $data['paginador']=$paginador;
        $detalleCompra = new DetalleCompras();

        foreach ($data['compras'] as &$compra) {
            $cantidadRegistros = $detalleCompra->where('id_compra', $compra['id'])->countAllResults();
            $compra['cantidad_registros'] = $cantidadRegistros;
        }

        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2])) {
            return view('compras/index', $data);
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
    }

    public function crear(){
        $persona = new Personas();
        $data['personas'] = $persona->where('es_proveedor', '1')
                                    ->where('estado', '1')
                                    ->orderBy('id', 'DESC')
                                    ->findAll();
        $producto = new Productos();
        $data['productos'] = $producto->where('estado', '1')
                                    ->orderBy('id', 'DESC')
                                    ->findAll();
        $titulo = "Compras";
        $data['titulo'] = $titulo;

        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2])) {
            return view('compras/crear', $data);
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
    }

    public function nuevo(){
        $persona = new Personas();
        $data['personas'] = $persona->where('es_proveedor', '1')
                                    ->where('estado', '1')
                                    ->orderBy('id', 'DESC')
                                    ->findAll();
        $producto = new Productos();
        $data['productos'] = $producto->where('estado', '1')
                                    ->orderBy('id', 'DESC')
                                    ->findAll();

        $titulo = "Compras";
        $data['titulo'] = $titulo;

        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2])) {
            return view('compras/nuevo', $data);
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
    }

    public function guardar(){
        $compra = new Compras();
        $num_fact = $this->request->getVar('num_fact');
        $autorizacion_fact = $this->request->getVar('autorizacion_fact');
        $id_per_prov = $this->request->getVar('id_per_prov');
        $fecha_doc = $this->request->getVar('fecha_doc');
        $subtotal_compra = $this->request->getVar('subtotal_compra');
        $val_descuento = $this->request->getVar('val_descuento');
        $val_iva = $this->request->getVar('val_iva');
        $descripcion = $this->request->getVar('descripcion');
        $total = $this->request->getVar('total');
        $doc_adjunto = $this->request->getFile('doc_adjunto');
        $pagado = 0;
        $estado = 1;
        
        $idProducto = $this->request->getVar('id_producto');
        
        // Verificar si se recibió al menos un item
        if (empty($idProducto)) {
            $session = session();
            $session->setFlashData('mensaje', 'Debe seleccionar al menos un producto');
            return redirect()->back()->withInput();
        }

        
        $anio = date('Y', strtotime($fecha_doc));
        $existeCompraMismoAnio = $compra->where('num_fact', $num_fact)
                                        ->where('id_per_prov', $id_per_prov)
                                        ->where('YEAR(fecha_doc)', $anio)
                                        ->first();

        if ($existeCompraMismoAnio !== null) {
            $session = session();
            $session->setFlashData('mensaje', 'Ya existe un documento con la misma secuencia y proveedor en el mismo año');
            return redirect()->back()->withInput();
        }

        $validacion = $this->validate([
            'num_fact'=>'required|exact_length[17]',
            'autorizacion_fact' => 'required|numeric|exact_length[11,50]',
            'id_per_prov'=>'required|numeric',
            'fecha_doc' => 'required',
            'descripcion' => 'max_length[250]',
            'subtotal_compra' => 'required|numeric',
            'val_descuento' => 'numeric',
            'val_iva' => 'required|numeric',
            'total' => 'required|numeric',
        ]);
        if(!$validacion){
            $session = session();
            $session->setFlashData('mensaje','Revise la información');
            //return $this->response->redirect(site_url('/listar'));
            return redirect()->back()->withInput();
        }

        if ($doc_adjunto != '') {
            $validarDocumento = $this->validate([
                'doc_adjunto' => [
                    'uploaded[doc_adjunto]',
                    'max_size[doc_adjunto,2048]',
                    'ext_in[doc_adjunto,jpg,jpeg,png,pdf,docx,xlsx,rar,zip]',
                ]
            ]);
            if(!$validarDocumento){
                $session = session();
                $session->setFlashData('mensaje','Formato o tamaño del documento no admitido, el formato debe ser JPG, PNG, JPEG, PDF, DOCX, XLS RAR, ZIP con tamaño máximo de 2mb');
                return redirect()->back()->withInput();
            }
            
        
            
            $ruta = 'public/docs/compras/';
            $doc_adjunto = $this->request->getFile('doc_adjunto');
            $nombreRandom = $doc_adjunto->getRandomName();
            $nuevoNombre = $ruta . $nombreRandom;
            $doc_adjunto->move($ruta, $nombreRandom);
            

            // Registrar compra
            
            $datos=[
                'num_fact'=>$num_fact,
                'autorizacion_fact'=>$autorizacion_fact,
                'id_per_prov'=>$id_per_prov,
                'fecha_doc'=>$fecha_doc,
                'doc_adjunto'=>$nuevoNombre,
                'subtotal_compra'=>$subtotal_compra,
                'val_descuento'=>$val_descuento,
                'val_iva'=>$val_iva,
                'descripcion'=>$descripcion,
                'total'=>$total,
                'pagado'=>$pagado,
                'estado'=>$estado
            ];
            $compra->insert($datos);
            $lastInsertId = $compra->insertID();
            $this->guardarDetalle($lastInsertId);
            $this->actualizarStockIngreso($lastInsertId);
            return redirect()->to(base_url('consultarCompra/'.$lastInsertId))->with('exito', 'Compra Creada Exitosamente');
            //return $this->response->redirect(site_url('Compras'));
        }
        $datos=[
            'num_fact'=>$num_fact,
            'autorizacion_fact'=>$autorizacion_fact,
            'id_per_prov'=>$id_per_prov,
            'fecha_doc'=>$fecha_doc,
            'doc_adjunto'=>'',
            'subtotal_compra'=>$subtotal_compra,
            'val_descuento'=>$val_descuento,
            'val_iva'=>$val_iva,
            'descripcion'=>$descripcion,
            'total'=>$total,
            'pagado'=>$pagado,
            'estado'=>$estado
        ];
        $compra->insert($datos);
        $lastInsertId = $compra->insertID();
        $this->guardarDetalle($lastInsertId);
        $this->actualizarStockIngreso($lastInsertId);
        return redirect()->to(base_url('consultarCompra/'.$lastInsertId))->with('exito', 'Compra Creada Exitosamente');
        
    }

    private function guardarDetalle($lastInsertId) {
        $detalle = new DetalleCompras();
        // Recorremos los detalles de ingreso y los guardamos en la base de datos
        for ($i = 0; $i < count($this->request->getVar('id_producto')); $i++) {
            $data = [
                'id_compra' => $lastInsertId,
                'id_producto' => $this->request->getVar('id_producto')[$i],
                'cantidad' => $this->request->getVar('cantidad_compra')[$i],
                'precio' => $this->request->getVar('precio_compra')[$i],
                'descuento' => $this->request->getVar('descuento_item')[$i],
                'iva' => $this->request->getVar('iva_producto')[$i],
                'subtotal' => $this->request->getVar('monto_subtotal_item')[$i],
                'total' => $this->request->getVar('monto_total_item')[$i]
            ];
            $detalle->insert($data);
        }
    }

    private function actualizarStockIngreso($lastInsertId) {
        // Selecciona los productos y las cantidades que se ingresaron en la compra
        $detalles = new DetalleCompras();
        $detalle_ingreso = $detalles->where('id_compra', $lastInsertId)->findAll();
        // Incrementa el stock de cada producto ingresado capturando 'cantidad' de cada producto
        $productos = new Productos();
        foreach ($detalle_ingreso as $detalle) {
            $productos = new Productos();
            $producto = $productos->find($detalle['id_producto']);
            // Validación: Solo se itera si el producto es inventariable
            if ($producto['es_inventariable'] == 1) {
                $nuevoStock = $producto['stock'] + $detalle['cantidad'];
                $productos->update($detalle['id_producto'], ['stock' => $nuevoStock]);
            }
        }
    }
    
    public function eliminar($id = null) {
        $compras = new Compras();
        $compra = $compras->find($id);
        $pagos = new Pagos();
        $pago = $pagos->consultarCompra($id);
        if ($pago) {
            $session = session();
            $session->setFlashData('mensaje','No se puede eliminar el documento ya que cuenta con pagos relacionados');
            //return $this->response->redirect(site_url('/listar'));
            return redirect()->back()->withInput();
        }
        $doc_adjunto = $compra['doc_adjunto'];
        //validar si hay categorias con productos relacionados
        // Verificar y eliminar el documento de respaldo
        if (!empty($doc_adjunto) && file_exists($doc_adjunto) && $doc_adjunto != '') {
            unlink($doc_adjunto);
        }
        $this->reversarStockIngreso($id);
        $this->eliminarIngresoDetalle($id);
        $compras->where('id', $id)->delete($id);
        return redirect()->to(base_url('Compras'))->with('exito', 'Compra Eliminada Exitosamente');
        #return $this->response->redirect(site_url('Compras'));
    }
    
    public function eliminarIngresoDetalle($id_compra){
        $detalles = new DetalleCompras();
        $detalles->where('id_compra', $id_compra)->delete();
    }
    
    private function reversarStockIngreso($lastInsertId) {
        // Selecciona los productos y las cantidades que se ingresaron en la compra
        $detalles = new DetalleCompras();
        $detalle_ingreso = $detalles->where('id_compra', $lastInsertId)->findAll();
        // Incrementa el stock de cada producto ingresado capturando 'cantidad' de cada producto
        foreach ($detalle_ingreso as $detalle) {
            
            $productos = new Productos();
            $producto = $productos->find($detalle['id_producto']);
            // Validación: Solo se itera si el producto es inventariable
            if ($producto['es_inventariable'] == 1) {
                $nuevoStock = $producto['stock'] - $detalle['cantidad'];
                $productos->update($detalle['id_producto'], ['stock' => $nuevoStock]);
            }
/*
            $productos = new Productos();
            $producto = $productos->find($detalle['id_producto']);
            $nuevoStock = $producto['stock'] - $detalle['cantidad'];
            $productos->update($detalle['id_producto'], ['stock' => $nuevoStock]);*/
        }
    }
    
    public function consultar($id=null){
        $compra = new Compras();
        $data['compra'] = $compra->where('id',$id)->first();
        $persona = new Personas();
        $data['personas'] = $persona->where('es_proveedor', '1')
                                    ->where('estado', '1')
                                    ->orderBy('id', 'ESTADO')
                                    ->findAll();
        $producto = new Productos();
        $data['productos'] = $producto->where('estado', '1')
                                    ->orderBy('id', 'ESTADO')
                                    ->findAll();
        $titulo = "Compras";
        $data['titulo'] = $titulo;
        $data['detalles'] = $this->obtenerItemsCompra($id);

        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2])) {
            return view('compras/consultar', $data);
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
    }

    private function obtenerItemsCompra($id){
        $detalles = new DetalleCompras();
        return $detalles->select('detalle_compras.*, productos.id as producto')
            ->join('productos', 'productos.id = detalle_compras.id_producto', 'left')
            ->where('id_compra', $id)
            ->orderBy('detalle_compras.id', 'ASC')
            ->findAll();
    }

    public function editar($id=null){
        $compra = new Compras();
        $data['compra'] = $compra->where('id',$id)->first();
        $persona = new Personas();
        $data['personas'] = $persona->where('es_proveedor', '1')
                                    ->where('estado', '1')
                                    ->orderBy('id', 'DESC')
                                    ->findAll();
        $producto = new Productos();
        $data['productos'] = $producto->where('estado', '1')
                                    ->orderBy('id', 'DESC')
                                    ->findAll();
        $titulo = "Compras";
        $data['titulo'] = $titulo;
        $data['detalles'] = $this->obtenerItemsCompra($id);

        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2])) {
            return view('compras/editar', $data);
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
    }

    
    public function actualizar(){
        $compra = new Compras();
        $id = $this->request->getVar('id');
        $num_fact = $this->request->getVar('num_fact');
        $autorizacion_fact = $this->request->getVar('autorizacion_fact');
        $id_per_prov = $this->request->getVar('id_per_prov');
        $fecha_doc = $this->request->getVar('fecha_doc');
        $subtotal_compra = $this->request->getVar('subtotal_compra');
        $val_descuento = $this->request->getVar('val_descuento');
        $val_iva = $this->request->getVar('val_iva');
        $descripcion = $this->request->getVar('descripcion');
        $total = $this->request->getVar('total');
        $doc_adjunto = $this->request->getFile('doc_adjunto');
        $pagado = 0;
        $estado = $this->request->getVar('estado');
        $estadoOriginal = $this->request->getVar('estado_original');

        $idProducto = $this->request->getVar('id_producto');
        
        // Verificar si se recibió al menos un item
        if (empty($idProducto)) {
            $session = session();
            $session->setFlashData('mensaje', 'Debe seleccionar al menos un producto');
            return redirect()->back()->withInput();
        }


        $registroExistente = $compra->find($id);

        
        if ($registroExistente['num_fact'] !== $num_fact) {
            $anio = date('Y', strtotime($fecha_doc));
            $existeCompraMismoAnio = $compra->where('num_fact', $num_fact)
                                            ->where('id_per_prov', $id_per_prov)
                                            ->where('YEAR(fecha_doc)', $anio)
                                            ->first();

            if ($num_fact !== $existeCompraMismoAnio['num_fact']) {
                if ($existeCompraMismoAnio !== null) {
                    $session = session();
                    $session->setFlashData('mensaje', 'Ya existe un documento con la misma secuencia y proveedor en el mismo año');
                    return redirect()->back()->withInput();
                }
            }
        }

        $validacion = $this->validate([
            'num_fact'=>'required|exact_length[17]',
            'autorizacion_fact' => 'required|numeric|exact_length[11,50]',
            'id_per_prov'=>'required|numeric',
            'fecha_doc' => 'required',
            'descripcion' => 'max_length[250]',
            'subtotal_compra' => 'required|numeric',
            'val_descuento' => 'numeric',
            'val_iva' => 'required|numeric',
            'total' => 'required|numeric',
        ]);
        if(!$validacion){
            $session = session();
            $session->setFlashData('mensaje','Revise la información');
            //return $this->response->redirect(site_url('/listar'));
            return redirect()->back()->withInput();
        }
        
        if ($doc_adjunto != '') {
            $validarDocumento = $this->validate([
                'doc_adjunto' => [
                    'uploaded[doc_adjunto]',
                    'max_size[doc_adjunto,2048]',
                    'ext_in[doc_adjunto,jpg,jpeg,png,pdf,docx,xlsx,rar,zip]',
                ]
            ]);

            if(!$validarDocumento){
                $session = session();
                $session->setFlashData('mensaje','Formato o tamaño del documento no admitido, el formato debe ser JPG, PNG, JPEG, PDF, DOCX, XLS RAR, ZIP con tamaño máximo de 2mb');
                return redirect()->back()->withInput();
            }
            
            $ruta = 'public/docs/compras/';
            $doc_adjunto = $this->request->getFile('doc_adjunto');
            $nombreRandom = $doc_adjunto->getRandomName();
            $nuevoNombre = $ruta . $nombreRandom;
            $doc_adjunto->move($ruta, $nombreRandom);
        
            $documentoActual = $compra->find($id);
            $documentoAnterior = $documentoActual['doc_adjunto'];
        
            if (!empty($documentoAnterior) && file_exists($documentoAnterior) && $documentoAnterior != '') {
                unlink($documentoAnterior);
            }
        
            $datos=[
                'num_fact'=>$num_fact,
                'autorizacion_fact'=>$autorizacion_fact,
                'id_per_prov'=>$id_per_prov,
                'fecha_doc'=>$fecha_doc,
                'subtotal_compra'=>$subtotal_compra,
                'val_descuento'=>$val_descuento,
                'val_iva'=>$val_iva,
                'descripcion'=>$descripcion,
                'total'=>$total,
                'doc_adjunto'=>$nuevoNombre,
                'pagado'=>$pagado,
                'estado'=>$estado
            ];
        
            $compra->update($id,$datos);

            /*
            $this->reversarStockIngreso($id);
            $this->eliminarIngresoDetalle($id);
            $this->guardarDetalle($id);
            $this->actualizarStockIngreso($id);
            */

            if($estado == '0'){
                if($estadoOriginal == '0'){
                    #$this->reversarStockEgreso($id);
                    $this->eliminarIngresoDetalle($id);
                    $this->guardarDetalle($id);
                    #$this->actualizarStockEgreso($id);
                }
            }
            
            if($estado == '1'){
                if($estadoOriginal == '0'){
                    #$this->reversarStockEgreso($id);
                    $this->eliminarIngresoDetalle($id);
                    $this->guardarDetalle($id);
                    $this->actualizarStockIngreso($id);
                }
            }
            
            if($estado == '0'){
                if($estadoOriginal == '1'){
                    $this->reversarStockIngreso($id);
                    $this->eliminarIngresoDetalle($id);
                    $this->guardarDetalle($id);
                    #$this->actualizarStockEgreso($id);
                }
            }
            
            if($estado == '1'){
                if($estadoOriginal == '1'){
                    $this->reversarStockIngreso($id);
                    $this->eliminarIngresoDetalle($id);
                    $this->guardarDetalle($id);
                    $this->actualizarStockIngreso($id);
                }
            }
            return redirect()->to(base_url('consultarCompra/'.$id))->with('exito', 'Compra Actualizada Exitosamente');
        }









        $datos=[
            'num_fact'=>$num_fact,
            'autorizacion_fact'=>$autorizacion_fact,
            'id_per_prov'=>$id_per_prov,
            'fecha_doc'=>$fecha_doc,
            'subtotal_compra'=>$subtotal_compra,
            'val_descuento'=>$val_descuento,
            'val_iva'=>$val_iva,
            'descripcion'=>$descripcion,
            'total'=>$total,
            'pagado'=>$pagado,
            'estado'=>$estado
        ];
        
        $compra->update($id,$datos);

        /*
        $this->reversarStockIngreso($id);
        $this->eliminarIngresoDetalle($id);
        $this->guardarDetalle($id);
        $this->actualizarStockIngreso($id);
        */

        if($estado == '0'){
            if($estadoOriginal == '0'){
                #$this->reversarStockEgreso($id);
                $this->eliminarIngresoDetalle($id);
                $this->guardarDetalle($id);
                #$this->actualizarStockEgreso($id);
            }
        }
        
        if($estado == '1'){
            if($estadoOriginal == '0'){
                #$this->reversarStockEgreso($id);
                $this->eliminarIngresoDetalle($id);
                $this->guardarDetalle($id);
                $this->actualizarStockIngreso($id);
            }
        }
        
        if($estado == '0'){
            if($estadoOriginal == '1'){
                $this->reversarStockIngreso($id);
                $this->eliminarIngresoDetalle($id);
                $this->guardarDetalle($id);
                #$this->actualizarStockEgreso($id);
            }
        }
        
        if($estado == '1'){
            if($estadoOriginal == '1'){
                $this->reversarStockIngreso($id);
                $this->eliminarIngresoDetalle($id);
                $this->guardarDetalle($id);
                $this->actualizarStockIngreso($id);
            }
        }
        return redirect()->to(base_url('consultarCompra/'.$id))->with('exito', 'Compra Actualizada Exitosamente');
    }

    public function generarExcel($num_fact, $proveedorFiltro, $fecha_inicio, $fecha_fin, $iva, $pagado, $descuento, $estado){

        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2])) {
            $compra = new Compras();
            // Aplica los filtros solo si se han seleccionado valores
            if (  $num_fact != 'none' || $proveedorFiltro != 'none' || $fecha_inicio != 'none' || $fecha_fin != 'none' || $iva != 'none' || $pagado != 'none' || $descuento != 'none' || $estado != 'none') {
                $compra->orderBy('id', 'DESC');
    
                if ($num_fact != 'none') {
                    $compra->where('compras.num_fact', $num_fact);
                }
    
                if ($proveedorFiltro != 'none') {
                    $compra->where('compras.id_per_prov', $proveedorFiltro);
                }
    
                if ($fecha_inicio != 'none' && $fecha_fin != 'none') {
                    $compra->where('compras.fecha_doc >=', $fecha_inicio);
                    $compra->where('compras.fecha_doc <=', $fecha_fin);
                }
    
                if ($iva != 'none') {
                    if($iva == '0'){
                        $compra->where('val_iva ='. 0);
                    } else{
                        $compra->where('val_iva >', 0);
                    }
                }
    
                if ($estado != 'none') {
                    $compra->where('compras.estado', $estado);
                }
    
                if ($descuento != 'none') {
                    if ($descuento == 'Si'){
                        $compra->where('val_descuento >', 0);
                    }
                    else{
                        $compra->where('val_descuento =', 0);
                    }
                }
    
                if ($pagado != 'none') {
                    if($pagado == "Pagado"){
                        $compra->where('total = valor_pagado');
                    } else {
                        $compra->where('total != valor_pagado');
                    }
                }
                $data['compras'] = $compra->select('compras.*, personas.nombres as persona')
                                        ->join('personas', 'personas.id = compras.id_per_prov', 'left')
                                        ->orderBy('compras.id', 'DESC')
                                    ->findAll();
            } else {
                $data['compras'] = $compra->select('compras.*, personas.nombres as persona')
                                        ->join('personas', 'personas.id = compras.id_per_prov', 'left')
                                        ->orderBy('compras.id', 'DESC')
                                        ->findAll();
            }
    
            // Crear un nuevo objeto Spreadsheet
            $spreadsheet = new Spreadsheet();
    
            // Obtener la hoja activa
            $sheet = $spreadsheet->getActiveSheet();
            
            $sheet->getColumnDimension('B')->setWidth(17);
            $sheet->getColumnDimension('C')->setWidth(13);
            $sheet->getColumnDimension('D')->setWidth(35);
            $sheet->getColumnDimension('E')->setWidth(10);
            $sheet->getColumnDimension('F')->setWidth(14);
            $sheet->getColumnDimension('G')->setWidth(14);
            $sheet->getColumnDimension('H')->setWidth(14);
            $sheet->getColumnDimension('I')->setWidth(14);
            $sheet->getColumnDimension('J')->setWidth(14);
            $sheet->getColumnDimension('K')->setWidth(12);
    
            // Agregar encabezados
            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Numero Doc.');
            $sheet->setCellValue('C1', 'Autorizacion');
            $sheet->setCellValue('D1', 'Proveedor');
            $sheet->setCellValue('E1', 'Fecha');
            $sheet->setCellValue('F1', 'Val. Subtotal');
            $sheet->setCellValue('G1', 'Val. Descuento');
            $sheet->setCellValue('H1', 'Val. Iva');
            $sheet->setCellValue('I1', 'Val. Total');
            $sheet->setCellValue('J1', 'Val. Pagado');
            $sheet->setCellValue('K1', 'Estado Pago');
            $sheet->setCellValue('L1', 'Estado');
            // ... Agregar más columnas según tus necesidades
    
            // Agregar datos de las personas al archivo Excel
            $row = 2;
            foreach ($data['compras'] as $compra) {
                $sheet->setCellValue('A' . $row, $compra['id']);
                $sheet->setCellValue('B' . $row, $compra['num_fact']);
                $sheet->setCellValue('C' . $row, $compra['autorizacion_fact']);
                $sheet->setCellValue('D' . $row, $compra['persona']);
                $sheet->setCellValue('E' . $row, $compra['fecha_doc']);
                $sheet->setCellValue('F' . $row, "$" . $compra['subtotal_compra']);
                $sheet->setCellValue('G' . $row, "$" . $compra['val_descuento']);
                $sheet->setCellValue('H' . $row, "$" . $compra['val_iva']);
                $sheet->setCellValue('I' . $row, "$" . $compra['total']);
                $sheet->setCellValue('J' . $row, "$" . $compra['valor_pagado']);
                $sheet->setCellValue('K' . $row, $compra['total'] == $compra['valor_pagado'] ? 'Pagado' : 'Pendiente');
                $sheet->setCellValue('L' . $row, $compra['estado'] == '1' ? 'Activo' : 'Anulado');
                // ... Agregar más columnas según tus necesidades
    
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