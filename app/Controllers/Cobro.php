<?php
namespace App\Controllers;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Controllers\BaseController;
use App\Models\Cobros;
use App\Models\Personas;
use App\Models\Bancos;
use App\Models\Users;
use App\Models\Cotizaciones;

class Cobro extends BaseController{
    
    public function index(){
        $cobro = new Cobros();

        $cliente = new Personas();
        $data['clientes'] = $cliente->orderBy('id','ASC')->findAll();

        $banco = new Bancos();
        $data['bancos'] = $banco->orderBy('id','ASC')->findAll();

        $cotizacion = new Cotizaciones();
        $data['cotizaciones'] = $cotizacion->orderBy('id','ASC')->findAll();

        $titulo = "Cobros";
        $data['titulo'] = $titulo;

        $num_cobro = $this->request->getVar('num_cobro');
        $clienteFiltro = $this->request->getVar('clienteFiltro');
        $forma_pago = $this->request->getVar('forma_pago');
        $num_movimiento = $this->request->getVar('num_movimiento');
        $bancoFiltro = $this->request->getVar('bancoFiltro');
        $num_cot = $this->request->getVar('num_cot');
        $fecha_inicio = $this->request->getVar('fecha_inicio');
        $fecha_fin = $this->request->getVar('fecha_fin');

        $data['num_cobro']=$num_cobro;
        $data['clienteFiltro']=$clienteFiltro;
        $data['forma_pago']=$forma_pago;
        $data['num_movimiento']=$num_movimiento;
        $data['bancoFiltro']=$bancoFiltro;
        $data['num_cot']=$num_cot;
        $data['fecha_inicio']=$fecha_inicio;
        $data['fecha_fin']=$fecha_fin;

        if (  $num_cobro != null || $clienteFiltro != null || $forma_pago != null || $num_movimiento != null || $bancoFiltro != null || $num_cot != null || ($fecha_inicio != null && $fecha_fin != null)) {
            $cobro->orderBy('id', 'ASC');

            if ($clienteFiltro != null) {
                $cobro->where('cobros.id_cliente', $clienteFiltro);
            }

            if ($forma_pago != null) {
                $cobro->where('forma_pago', $forma_pago);
            }

            if ($num_movimiento != null) {
                $cobro->where('num_movimiento', $num_movimiento);
            }

            if ($bancoFiltro != null) {
                $cobro->where('id_banco', $bancoFiltro);
            }

            if ($num_cobro != null) {
                $cobro->where('cobros.id', $num_cobro);
            }

            if ($num_cot != null) {
                $cobro->where('id_cotizacion', $num_cot);
            }

            if ($fecha_inicio != null && $fecha_fin != null) {
                $cobro->where('cobros.fecha_registro >=', $fecha_inicio);
                $cobro->where('cobros.fecha_registro <=', $fecha_fin);
            }
            

            $data['cobros'] = $cobro->select('cobros.*, personas.nombres as persona, cotizaciones.num_cot as cotizacion')
                                ->join('personas', 'personas.id = cobros.id_cliente', 'left')
                                ->join('cotizaciones', 'cotizaciones.id = cobros.id_cotizacion', 'left')
                                ->orderBy('cobros.id', 'DESC')
                                ->orderBy('id','DESC')
                                ->paginate(10);

            $paginador = $cobro->pager;
            $data['paginador'] = $paginador;
            return view('cobros/index', $data);
        }


        $data['cobros'] = $cobro->select('cobros.*, personas.nombres as persona, cotizaciones.num_cot as cotizacion')
                                ->join('personas', 'personas.id = cobros.id_cliente', 'left')
                                ->join('cotizaciones', 'cotizaciones.id = cobros.id_cotizacion', 'left')
                                ->orderBy('cobros.id', 'DESC')
                                ->orderBy('id','DESC')
                                ->paginate(10);
        $paginador = $cobro->pager;
        $data['paginador']=$paginador;

        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2,3])) {
            return view('cobros/index', $data);
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
    }
    
    public function crear(){
        $titulo = "Cobros";
        $persona = new Personas();
        $data['personas'] = $persona->where('es_proveedor', '1')
                                    ->orderBy('id', 'ASC')
                                    ->findAll();
        $banco = new Bancos();
        $data['bancos'] = $banco->orderBy('id', 'ASC')
                                ->findAll();

        $data['titulo'] = $titulo;

        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2,3])) {
            return view('cobros/crear', $data);
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
    }
    
    public function registrar($id=null){
        $titulo = "Cotizaciones";
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
        
        $banco = new Bancos();
        $data['bancos'] = $banco->orderBy('id', 'ASC')
                                ->findAll();
                                
        $cotizacion = new Cotizaciones();
        $data['cotizaciones'] = $cotizacion->where('id',$id)->first();

        $data['titulo'] = $titulo;

        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2,3])) {
            return view('cobros/crear', $data);
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
    }

    public function guardar(){
        $cobros = new Cobros();
        $id_cliente = $this->request->getVar('id_cliente'); 
        $id_vendedor = $this->request->getVar('id_vendedor'); 
        $fecha_registro = $this->request->getVar('fecha_registro'); 
        $forma_pago = $this->request->getVar('forma_pago'); 
        $num_movimiento = $this->request->getVar('num_movimiento'); 
        $id_banco = $this->request->getVar('id_banco'); 
        $fecha_movimiento = $this->request->getVar('fecha_movimiento'); 
        $doc_adjunto = $this->request->getFile('doc_adjunto'); 
        $id_cotizacion = $this->request->getVar('id_cotizacion'); 
        $valor_cotizacion = $this->request->getVar('valor_cotizacion'); 
        $valor_pagado = $this->request->getVar('valor_pagado'); 
        $valor_vencer = $this->request->getVar('valor_vencer');
        $omitir_validar_mov = $this->request->getVar('omitir_validar_mov');
    
        $omitir_validar_mov = $omitir_validar_mov == '1' ? '1' : '0';
        
        
        if($valor_pagado > $valor_vencer ){
            $session = session();
            $session->setFlashData('mensaje','El valor del pago es mayor al valor pendiente');
            return redirect()->back()->withInput();
        }
        
        if($forma_pago != 'Efectivo' && $forma_pago != 'Cheque' && $forma_pago != 'Transferencia'){
            $session = session();
            $session->setFlashData('mensaje','Forma de pago invalida');
            return redirect()->back()->withInput();
        }
        
        if($forma_pago == 'Efectivo'){
            $num_movimiento = 'Efectivo';
            $id_banco = null;
            $fecha_movimiento = null;
            $omitir_validar_mov = '1';
        } else {
            if( $omitir_validar_mov == '0'){
                if($forma_pago == 'Cheque'){
                    $validacion = $this->validate([
                        'num_movimiento'=>'required|numeric',
                        'id_banco'=>'required|numeric',
                        'fecha_movimiento'=>'required|date',
                    ]);
                    if(!$validacion){
                        $session = session();
                        $session->setFlashData('mensaje','Revise la información');
                        //return $this->response->redirect(site_url('/listar'));
                        return redirect()->back()->withInput();
                    }
                    
                    $anio = date('Y', strtotime($fecha_movimiento));
                    $existeChequeMismoAnio = $cobros->where('num_movimiento', $num_movimiento)
                                                    ->where('id_banco', $id_banco)
                                                    ->where('YEAR(fecha_movimiento)', $anio)
                                                    ->first();
            
                    if ($existeChequeMismoAnio !== null) {
                        $session = session();
                        $session->setFlashData('mensaje', 'Número de cheque duplicado');
                        return redirect()->back()->withInput();
                    }
                } else if($forma_pago == 'Transferencia'){
                    $validacion = $this->validate([
                        'num_movimiento'=>'required|numeric',
                        'id_banco'=>'required|numeric',
                        'fecha_movimiento'=>'required|date',
                    ]);
                    if(!$validacion){
                        $session = session();
                        $session->setFlashData('mensaje','Revise la información');
                        //return $this->response->redirect(site_url('/listar'));
                        return redirect()->back()->withInput();
                    }
    
                    $anio = date('Y', strtotime($fecha_movimiento));
                    $existeTransferenciaMismoAnio = $cobros->where('num_movimiento', $num_movimiento)
                                                    ->where('id_banco', $id_banco)
                                                    ->where('YEAR(fecha_movimiento)', $anio)
                                                    ->first();
            
                    if ($existeTransferenciaMismoAnio !== null) {
                        $session = session();
                        $session->setFlashData('mensaje', 'Número de transferencia o deposito duplicado');
                        return redirect()->back()->withInput();
                    }
                }
            }
        }

        $validacion = $this->validate([
            'id_cliente'=>'required|numeric',
            'id_vendedor'=>'required|numeric',
            'fecha_registro' => 'required',
            'valor_pagado' => 'required|numeric',
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
            
            $ruta = 'public/docs/cobros/';
            $doc_adjunto = $this->request->getFile('doc_adjunto');
            $nombreRandom = $doc_adjunto->getRandomName();
            $nuevoNombre = $ruta . $nombreRandom;
            $doc_adjunto->move($ruta, $nombreRandom);

            $datos=[
                'id_cliente'=>$id_cliente,
                'fecha_registro'=>$fecha_registro,
                'id_vendedor'=>$id_vendedor,
                'forma_pago'=>$forma_pago,
                'num_movimiento'=>$num_movimiento,
                'id_banco'=>$id_banco,
                'fecha_movimiento'=>$fecha_movimiento,
                'omitir_validar_mov'=>$omitir_validar_mov,
                'id_cotizacion'=>$id_cotizacion,
                'valor_cotizacion'=>$valor_cotizacion,
                'valor_pagado'=>$valor_pagado,
                'doc_adjunto'=>$nuevoNombre
            ];
            $cobros->insert($datos);
            $lastInsertId = $cobros->insertID();
            $this->sumarCobro($id_cotizacion,$valor_pagado);
            #return $this->response->redirect(site_url('Cobros'));
            return redirect()->to(base_url('consultarCobro/'.$lastInsertId))->with('exito', 'Cobro Registrado Exitosamente');
        }

        $datos=[
            'id_cliente'=>$id_cliente,
            'id_vendedor'=>$id_vendedor,
            'fecha_registro'=>$fecha_registro,
            'forma_pago'=>$forma_pago,
            'num_movimiento'=>$num_movimiento,
            'id_banco'=>$id_banco,
            'fecha_movimiento'=>$fecha_movimiento,
            'omitir_validar_mov'=>$omitir_validar_mov,
            'id_cotizacion'=>$id_cotizacion,
            'valor_cotizacion'=>$valor_cotizacion,
            'valor_pagado'=>$valor_pagado,
            'doc_adjunto'=>""
        ];

        $cobros->insert($datos);
        $lastInsertId = $cobros->insertID();
        $this->sumarCobro($id_cotizacion,$valor_pagado);

        #return $this->response->redirect(site_url('Cobros'));
        return redirect()->to(base_url('consultarCobro/'.$lastInsertId))->with('exito', 'Cobro Registrado Exitosamente');
    }

    protected function sumarCobro($id_cotizacion,$valor_pagado){
        // Obtén el objeto del modelo de compras
        $cotizaciones = new Cotizaciones(); // Asegúrate de que estás utilizando el modelo correcto aquí
        $cotizacion = $cotizaciones->find($id_cotizacion);
        if ($cotizacion) {
            if (array_key_exists('valor_pagado', $cotizacion)) {
                $nuevoValor = $cotizacion['valor_pagado'] + $valor_pagado;
                if ($nuevoValor == $cotizacion['total']) {
                    $nuevo_estado_pago = "1";
                }else{
                    $nuevo_estado_pago = "0";
                }
            }
            $datos=[
                'valor_pagado' => $nuevoValor,
                'pagado'=>$nuevo_estado_pago,
            ];
            $cotizaciones->update($id_cotizacion,$datos);
        }
    }

    public function eliminar($id = null) {
        $cobro = new Cobros();
        $cobros = $cobro->find($id);
        $doc_adjunto = $cobros['doc_adjunto'];
        // Verificar y eliminar el documento de respaldo
        if (!empty($doc_adjunto) && file_exists($doc_adjunto) && $doc_adjunto != '') {
            unlink($doc_adjunto);
        }
        
        // Restar el valor pagado al eliminar el cobro
        $id_cotizacion = $cobros['id_cotizacion'];
        $valor_pagado = $cobros['valor_pagado'];

        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2])) {
            $this->restarCobro($id_cotizacion, $valor_pagado);
            $cobro->where('id', $id)->delete($id);
            return redirect()->to(base_url('Cobros'))->with('exito', 'Compra Eliminada Exitosamente');
            #return $this->response->redirect(site_url('Cobros'));
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
    }

    public function restarCobro($id_cotizacion, $valor_pagado) {
        $cotizaciones = new Cotizaciones();
        $cotizacion = $cotizaciones->find($id_cotizacion);
    
        if ($cotizacion) {
            if (array_key_exists('valor_pagado', $cotizacion)) {
                $nuevoValor = $cotizacion['valor_pagado'] - $valor_pagado;
                if ($nuevoValor < $cotizacion['total']) {
                    $nuevo_estado_pago = "0";
                }
            } else {
                $nuevoValor = 0;
            }
            $datos=[
                'valor_pagado' => $nuevoValor,
                'pagado'=>$nuevo_estado_pago,
            ];
            $cotizaciones->update($id_cotizacion,$datos);
        }
    }
    
    public function editar($id=null){
        $cobro = new Cobros();
        $data['cobro'] = $cobro->select('cobros.*, cotizaciones.num_cot as cotizacion,cotizaciones.total as  total_cotizacion, cotizaciones.valor_pagado as pagado')
                                ->join('cotizaciones', 'cotizaciones.id = cobros.id_cotizacion', 'left')
                                ->where('cobros.id',$id)->first();
        $titulo = "Cobros";
        $persona = new Personas();
        $data['personas'] = $persona->where('es_cliente', '1')
                                    ->orderBy('id', 'ASC')
                                    ->findAll();
        
        $banco = new Bancos();
        $data['bancos'] = $banco->orderBy('id', 'ASC')
                                ->findAll();

        $data['titulo'] = $titulo;

        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2])) {
            return view('cobros/editar', $data);
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
    }

    public function actualizar(){
        $cobro = new Cobros();
        $id = $this->request->getVar('id'); 
        $valorCobroAnterior = $cobro->find($id); // Obtén el pago anterior antes de la actualización
        $fecha_registro = $this->request->getVar('fecha_registro'); 
        $forma_pago = $this->request->getVar('forma_pago'); 
        $num_movimiento = $this->request->getVar('num_movimiento'); 
        $id_banco = $this->request->getVar('id_banco'); 
        $fecha_movimiento = $this->request->getVar('fecha_movimiento'); 
        $doc_adjunto = $this->request->getFile('doc_adjunto'); 
        $valor_pagado = $this->request->getVar('valor_pagado'); 
        $omitir_validar_mov = $this->request->getVar('omitir_validar_mov');
    
        $omitir_validar_mov = $omitir_validar_mov == '1' ? '1' : '0';
        
        if($forma_pago != 'Efectivo' && $forma_pago != 'Cheque' && $forma_pago != 'Transferencia'){
            $session = session();
            $session->setFlashData('mensaje','Forma de pago invalida');
            return redirect()->back()->withInput();
        }
        
        if($forma_pago == 'Efectivo'){
            $num_movimiento = 'Efectivo';
            $id_banco = null;
            $fecha_movimiento = null;
        } else if(($forma_pago == 'Cheque') || ($forma_pago == 'Transferencia')) {
            $validacion = $this->validate([
                'num_movimiento'=>'required|numeric',
                'id_banco'=>'required|numeric',
                'fecha_movimiento'=>'required|date',
            ]);
            if(!$validacion){
                $session = session();
                $session->setFlashData('mensaje','Revise la información');
                //return $this->response->redirect(site_url('/listar'));
                return redirect()->back()->withInput();
            }
        }
        
        if( $omitir_validar_mov == '0'){
            $anio = date('Y', strtotime($fecha_movimiento));
            $existeTransferenciaMismoAnio = $cobro->where('id !=', $id)
                                            ->where('forma_pago', $forma_pago)
                                            ->where('num_movimiento', $num_movimiento)
                                            ->where('id_banco', $id_banco)
                                            ->where('YEAR(fecha_movimiento)', $anio)
                                            ->first();
    
            if ($existeTransferenciaMismoAnio !== null) {
                $session = session();
                $session->setFlashData('mensaje', 'Número movimiento duplicado');
                return redirect()->back()->withInput();
            }
        }



        $validacion = $this->validate([
            'fecha_registro' => 'required',
            'valor_pagado' => 'required|numeric',
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
            
            $ruta = 'public/docs/cobros/';
            $doc_adjunto = $this->request->getFile('doc_adjunto');
            $nombreRandom = $doc_adjunto->getRandomName();
            $nuevoNombre = $ruta . $nombreRandom;
            $doc_adjunto->move($ruta, $nombreRandom);

            $documentoActual = $cobro->find($id);
            $documentoAnterior = $documentoActual['doc_adjunto'];
        
            if (!empty($documentoAnterior) && file_exists($documentoAnterior) && $documentoAnterior != '') {
                unlink($documentoAnterior);
            }

            // Restar el valor pagado anterior
            $id_cotizacion = $valorCobroAnterior['id_cotizacion'];
            $valor_pagado_anterior = $valorCobroAnterior['valor_pagado'];
            $this->restarCobro($id_cotizacion, $valor_pagado_anterior);
            $datos=[
                'fecha_registro'=>$fecha_registro,
                'forma_pago'=>$forma_pago,
                'num_movimiento'=>$num_movimiento,
                'id_banco'=>$id_banco,
                'fecha_movimiento'=>$fecha_movimiento,
                'omitir_validar_mov'=>$omitir_validar_mov,
                'valor_pagado'=>$valor_pagado,
                'doc_adjunto'=>$nuevoNombre
            ];
            $cobro->update($id,$datos);
            $this->sumarCobro($id_cotizacion, $valor_pagado);
            return $this->response->redirect(site_url('Cobros'));
        }
        
        // Restar el valor pagado anterior
        $id_cotizacion = $valorCobroAnterior['id_cotizacion'];
        $valor_pagado_anterior = $valorCobroAnterior['valor_pagado'];
        $this->restarCobro($id_cotizacion, $valor_pagado_anterior);

        $datos=[
            'fecha_registro'=>$fecha_registro,
            'forma_pago'=>$forma_pago,
            'num_movimiento'=>$num_movimiento,
            'id_banco'=>$id_banco,
            'fecha_movimiento'=>$fecha_movimiento,
            'omitir_validar_mov'=>$omitir_validar_mov,
            'valor_pagado'=>$valor_pagado
        ];
        $cobro->update($id,$datos);

        $this->sumarCobro($id_cotizacion, $valor_pagado);

        return $this->response->redirect(site_url('Cobros'));
    }
    
    public function consultar($id=null){
        $pago = new Cobros();
        $data['cobro'] = $pago->select('cobros.*, cotizaciones.num_cot as cotizacion,cotizaciones.total as  total_cotizacion, cotizaciones.valor_pagado as pagado')
                                ->join('cotizaciones', 'cotizaciones.id = cobros.id_cotizacion', 'left')
                                ->where('cobros.id',$id)->first();
        $titulo = "Cobro";
        $persona = new Personas();
        $data['personas'] = $persona->where('es_proveedor', '1')
                                    ->orderBy('id', 'ASC')
                                    ->findAll();
        
        $banco = new Bancos();
        $data['bancos'] = $banco->orderBy('id', 'ASC')
                                ->findAll();

        $data['titulo'] = $titulo;

        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2,3])) {
            return view('cobros/consultar', $data);
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
    }

    public function generarExcel($num_cobro, $clienteFiltro, $forma_pago, $num_movimiento, $bancoFiltro, $num_cot, $fecha_inicio, $fecha_fin){

        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2])) {
            $cobro = new Cobros();
            // Aplica los filtros solo si se han seleccionado valores
            if (  $num_cobro != 'none' || $clienteFiltro != 'none' || $forma_pago != 'none' || $num_movimiento != 'none' || $bancoFiltro != 'none' || $num_cot != 'none' || $fecha_inicio != 'none' || $fecha_fin != 'none') {
                $cobro->orderBy('id', 'ASC');
    
                if ($clienteFiltro != 'none') {
                    $cobro->where('cobros.id_cliente', $clienteFiltro);
                }
    
                if ($forma_pago != 'none') {
                    $cobro->where('cobros.forma_pago', $forma_pago);
                }
    
                if ($bancoFiltro != 'none') {
                    $cobro->where('cobros.id_banco', $bancoFiltro);
                }
    
                if ($num_cobro != 'none') {
                    $cobro->where('cobros.id', $num_cobro);
                }
    
                if ($num_cot != 'none') {
                    $cobro->where('cobros.id_cotizacion', $num_cot);
                }
    
                if ($fecha_inicio != 'none' && $fecha_fin != 'none') {
                    $cobro->where('cobros.fecha_registro >=', $fecha_inicio);
                    $cobro->where('cobros.fecha_registro <=', $fecha_fin);
                }
    
            $data['cobros'] = $cobro->select('cobros.*, personas.nombres as persona, compras.num_fact as compra, bancos.nombre as banco')
                                    ->join('personas', 'personas.id = cobros.id_cliente', 'left')
                                    ->join('compras', 'compras.id = cobros.id_cotizacion', 'left')
                                    ->join('bancos', 'bancos.id = cobros.id_banco', 'left')
                                    ->findAll();
            } else {
                $data['cobros'] = $cobro->select('cobros.*, personas.nombres as persona, compras.num_fact as compra, bancos.nombre as banco')
                                        ->join('personas', 'personas.id = cobros.id_cliente', 'left')
                                        ->join('compras', 'compras.id = cobros.id_cotizacion', 'left')
                                        ->join('bancos', 'bancos.id = cobros.id_banco', 'left')
                                        ->findAll();
            }
    
            // Crear un nuevo objeto Spreadsheet
            $spreadsheet = new Spreadsheet();
    
            // Obtener la hoja activa
            $sheet = $spreadsheet->getActiveSheet();
    
            $sheet->getColumnDimension('A')->setWidth(10);
            $sheet->getColumnDimension('B')->setWidth(30);
            $sheet->getColumnDimension('C')->setWidth(17);
            $sheet->getColumnDimension('D')->setWidth(15);
            $sheet->getColumnDimension('E')->setWidth(15);
            $sheet->getColumnDimension('F')->setWidth(15);
            $sheet->getColumnDimension('G')->setWidth(15);
            $sheet->getColumnDimension('H')->setWidth(20);
            $sheet->getColumnDimension('I')->setWidth(20);
            $sheet->getColumnDimension('J')->setWidth(20);
            $sheet->getColumnDimension('K')->setWidth(15);
            // Agregar encabezados
            $sheet->setCellValue('A1', 'Num. Pago');
            $sheet->setCellValue('B1', 'Proveedor');
            $sheet->setCellValue('C1', 'Compra');
            $sheet->setCellValue('D1', 'Valor Compra');
            $sheet->setCellValue('E1', 'Valor Pago');
            $sheet->setCellValue('F1', 'Fecha Registro');
            $sheet->setCellValue('G1', 'Forma de Pago');
            $sheet->setCellValue('H1', 'Num. Cheque');
            $sheet->setCellValue('I1', 'Num. Transferencia');
            $sheet->setCellValue('J1', 'Banco');
            $sheet->setCellValue('K1', 'Fecha Mov.');
            // ... Agregar más columnas según tus necesidades
    
            // Agregar datos de las personas al archivo Excel
            $row = 2;
            foreach ($data['cobros'] as $cobro) {
                $sheet->setCellValue('A' . $row, $cobro['id']);
                $sheet->setCellValue('B' . $row, $cobro['persona']);
                $sheet->setCellValue('C' . $row, $cobro['compra']);
                $sheet->setCellValue('D' . $row, '$' . $cobro['valor_compra']);
                $sheet->setCellValue('E' . $row, '$' . $cobro['valor_pagado']);
                $sheet->setCellValue('F' . $row, $cobro['fecha_registro']);
                $sheet->setCellValue('G' . $row, $cobro['forma_pago']);
                $sheet->setCellValue('H' . $row, $cobro['num_cheque']);
                $sheet->setCellValue('I' . $row, $cobro['num_transferencia']);
                $sheet->setCellValue('J' . $row, $cobro['id_banco']);
                $sheet->setCellValue('K' . $row, $cobro['banco']);
                // ... Agregar más columnas según tus necesidades
    
                $row++;
            }
    
            // Guardar el archivo Excel
            $writer = new Xlsx($spreadsheet);
            $filename = 'export_pagos.xlsx';
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