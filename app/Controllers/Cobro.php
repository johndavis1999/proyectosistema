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

        $proveedor = new Personas();
        $data['proveedores'] = $proveedor->orderBy('id','ASC')->findAll();

        $banco = new Bancos();
        $data['bancos'] = $banco->orderBy('id','ASC')->findAll();

        $cotizacion = new Cotizaciones();
        $data['cotizaciones'] = $cotizacion->orderBy('id','ASC')->findAll();

        $titulo = "Cobros";
        $data['titulo'] = $titulo;

        $data['cobros'] = $cobro->select('cobros.*, personas.nombres as persona')
                                ->join('personas', 'personas.id = cobros.id_cliente', 'left')
                                ->orderBy('cobros.id', 'DESC')
                                ->orderBy('id','DESC')
                                ->paginate(10);
        $paginador = $cobro->pager;
        $data['paginador']=$paginador;
        return view('cobros/index', $data);
    }
    
    public function crear(){
        $titulo = "Pagos";
        $persona = new Personas();
        $data['personas'] = $persona->where('es_proveedor', '1')
                                    ->orderBy('id', 'ASC')
                                    ->findAll();
        $compra = new Compras();
        $data['compras'] = $compra->orderBy('id', 'ASC')
                                    ->findAll();
        
        $banco = new Bancos();
        $data['bancos'] = $banco->orderBy('id', 'ASC')
                                ->findAll();

        $data['titulo'] = $titulo;
        return view('cobros/crear', $data);
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
        return view('cobros/crear', $data);
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
            $num_movimiento = null;
            $id_banco = null;
            $fecha_movimiento = null;
        } else {
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
                'id_cotizacion'=>$id_cotizacion,
                'valor_cotizacion'=>$valor_cotizacion,
                'valor_pagado'=>$valor_pagado,
                'doc_adjunto'=>$nuevoNombre
            ];
            $cobros->insert($datos);
            $this->sumarCobro($id_cotizacion,$valor_pagado);
            return $this->response->redirect(site_url('Cobros'));
        }

        $datos=[
            'id_cliente'=>$id_cliente,
            'id_vendedor'=>$id_vendedor,
            'fecha_registro'=>$fecha_registro,
            'forma_pago'=>$forma_pago,
            'num_movimiento'=>$num_movimiento,
            'id_banco'=>$id_banco,
            'fecha_movimiento'=>$fecha_movimiento,
            'id_cotizacion'=>$id_cotizacion,
            'valor_cotizacion'=>$valor_cotizacion,
            'valor_pagado'=>$valor_pagado,
            'doc_adjunto'=>""
        ];

        $cobros->insert($datos);

        $this->sumarCobro($id_cotizacion,$valor_pagado);

        return $this->response->redirect(site_url('Cobros'));
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
        $this->restarCobro($id_cotizacion, $valor_pagado);
        $cobro->where('id', $id)->delete($id);
        return $this->response->redirect(site_url('Cobros'));
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
        $data['cobro'] = $cobro->select('cobros.*, cotizaciones.num_cot as cotizaciones,cotizaciones.total as  total_cotizacion, cotizaciones.valor_pagado as pagado')
                                ->join('cotizaciones', 'cotizaciones.id = cobros.id_cotizacion', 'left')
                                ->where('cobros.id',$id)->first();
        $titulo = "Cobros";
        $persona = new Personas();
        $data['personas'] = $persona->where('es_proveedor', '1')
                                    ->orderBy('id', 'ASC')
                                    ->findAll();
        
        $banco = new Bancos();
        $data['bancos'] = $banco->orderBy('id', 'ASC')
                                ->findAll();

        $data['titulo'] = $titulo;
        return view('cobros/editar', $data);
    }
    public function actualizar(){
        $pago = new Pagos();
        $id = $this->request->getVar('id'); 
        $valorPagoAnterior = $pago->find($id); // Obtén el pago anterior antes de la actualización
        $fecha_registro = $this->request->getVar('fecha_registro'); 
        $forma_pago = $this->request->getVar('forma_pago'); 
        $num_cheque = $this->request->getVar('num_cheque'); 
        $num_transferencia = $this->request->getVar('num_transferencia'); 
        $id_banco = $this->request->getVar('id_banco'); 
        $fecha_movimiento = $this->request->getVar('fecha_movimiento'); 
        $doc_adjunto = $this->request->getFile('doc_adjunto'); 
        $valor_pagado = $this->request->getVar('valor_pagado'); 
        
        if($forma_pago != 'Efectivo' && $forma_pago != 'Cheque' && $forma_pago != 'Transferencia'){
            $session = session();
            $session->setFlashData('mensaje','Forma de pago invalida');
            return redirect()->back()->withInput();
        }
        
        if($forma_pago == 'Efectivo'){
            $num_cheque = null;
            $num_transferencia = null;
            $id_banco = null;
            $fecha_movimiento = null;
        } else {
            if($forma_pago == 'Cheque'){
                $num_transferencia = null;
                $validacion = $this->validate([
                    'num_cheque'=>'required|numeric',
                    'id_banco'=>'required|numeric',
                    'fecha_movimiento'=>'required|date',
                ]);
                if(!$validacion){
                    $session = session();
                    $session->setFlashData('mensaje','Revise la información');
                    //return $this->response->redirect(site_url('/listar'));
                    return redirect()->back()->withInput();
                }
            } else if($forma_pago == 'Transferencia'){
                $num_cheque = null;
                $validacion = $this->validate([
                    'num_transferencia'=>'required|numeric',
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
        }
        $anio = date('Y', strtotime($fecha_movimiento));
        $existeTransferenciaMismoAnio = $pago->where('id !=', $id)
                                        ->where('num_transferencia', $num_transferencia)
                                        ->where('id_banco', $id_banco)
                                        ->where('YEAR(fecha_movimiento)', $anio)
                                        ->first();

        if ($existeTransferenciaMismoAnio !== null) {
            $session = session();
            $session->setFlashData('mensaje', 'Número de transferencia o deposito duplicado');
            return redirect()->back()->withInput();
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
            
            $ruta = 'public/docs/pagos/';
            $doc_adjunto = $this->request->getFile('doc_adjunto');
            $nombreRandom = $doc_adjunto->getRandomName();
            $nuevoNombre = $ruta . $nombreRandom;
            $doc_adjunto->move($ruta, $nombreRandom);

            $documentoActual = $pago->find($id);
            $documentoAnterior = $documentoActual['doc_adjunto'];
        
            if (!empty($documentoAnterior) && file_exists($documentoAnterior) && $documentoAnterior != '') {
                unlink($documentoAnterior);
            }

            $datos=[
                'fecha_registro'=>$fecha_registro,
                'forma_pago'=>$forma_pago,
                'num_cheque'=>$num_cheque,
                'num_transferencia'=>$num_transferencia,
                'id_banco'=>$id_banco,
                'fecha_movimiento'=>$fecha_movimiento,
                'valor_pagado'=>$valor_pagado,
                'doc_adjunto'=>$nuevoNombre
            ];
            $pago->update($id,$datos);
            return $this->response->redirect(site_url('Pagos'));
        }
        
        // Restar el valor pagado anterior
        $id_compra = $valorPagoAnterior['id_compra'];
        $valor_pagado_anterior = $valorPagoAnterior['valor_pagado'];
        $this->restarPago($id_compra, $valor_pagado_anterior);

        $datos=[
            'fecha_registro'=>$fecha_registro,
            'forma_pago'=>$forma_pago,
            'num_cheque'=>$num_cheque,
            'num_transferencia'=>$num_transferencia,
            'id_banco'=>$id_banco,
            'fecha_movimiento'=>$fecha_movimiento,
            'valor_pagado'=>$valor_pagado
        ];
        $pago->update($id,$datos);

        $this->sumarPago($id_compra, $valor_pagado);

        return $this->response->redirect(site_url('Pagos'));
    }
    
    public function consultar($id=null){
        $pago = new Pagos();
        $data['pago'] = $pago->select('pagos.*, compras.num_fact as compra,compras.total as  total_compra, compras.valor_pagado as pagado')
                                ->join('compras', 'compras.id = pagos.id_compra', 'left')
                                ->where('pagos.id',$id)->first();
        $titulo = "Pagos";
        $persona = new Personas();
        $data['personas'] = $persona->where('es_proveedor', '1')
                                    ->orderBy('id', 'ASC')
                                    ->findAll();
        
        $banco = new Bancos();
        $data['bancos'] = $banco->orderBy('id', 'ASC')
                                ->findAll();

        $data['titulo'] = $titulo;
        return view('pagos/consultar', $data);
    }

    public function generarExcel($proveedorFiltro, $forma_pago, $bancoFiltro, $num_pago, $num_compra, $fecha_inicio, $fecha_fin){
        $pago = new Pagos();
        // Aplica los filtros solo si se han seleccionado valores
        if (  $proveedorFiltro != 'none' || $forma_pago != 'none' || $bancoFiltro != 'none' || $num_pago != 'none' || $num_compra != 'none' || $fecha_inicio != 'none' || $fecha_fin != 'none') {
            $pago->orderBy('id', 'ASC');

            if ($proveedorFiltro != 'none') {
                $pago->where('pagos.id_proveedor', $proveedorFiltro);
            }

            if ($forma_pago != 'none') {
                $pago->where('pagos.forma_pago', $forma_pago);
            }

            if ($bancoFiltro != 'none') {
                $pago->where('pagos.id_banco', $bancoFiltro);
            }

            if ($num_pago != 'none') {
                $pago->where('pagos.id', $num_pago);
            }

            if ($num_compra != 'none') {
                $pago->where('pagos.id_compra', $num_compra);
            }

            if ($fecha_inicio != 'none' && $fecha_fin != 'none') {
                $pago->where('pagos.fecha_registro >=', $fecha_inicio);
                $pago->where('pagos.fecha_registro <=', $fecha_fin);
            }

        $data['pagos'] = $pago->select('pagos.*, personas.nombres as persona, compras.num_fact as compra, bancos.nombre as banco')
                                ->join('personas', 'personas.id = pagos.id_proveedor', 'left')
                                ->join('compras', 'compras.id = pagos.id_compra', 'left')
                                ->join('bancos', 'bancos.id = pagos.id_banco', 'left')
                                ->findAll();
        } else {
            $data['pagos'] = $pago->select('pagos.*, personas.nombres as persona, compras.num_fact as compra, bancos.nombre as banco')
                                    ->join('personas', 'personas.id = pagos.id_proveedor', 'left')
                                    ->join('compras', 'compras.id = pagos.id_compra', 'left')
                                    ->join('bancos', 'bancos.id = pagos.id_banco', 'left')
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
        foreach ($data['pagos'] as $pago) {
            $sheet->setCellValue('A' . $row, $pago['id']);
            $sheet->setCellValue('B' . $row, $pago['persona']);
            $sheet->setCellValue('C' . $row, $pago['compra']);
            $sheet->setCellValue('D' . $row, '$' . $pago['valor_compra']);
            $sheet->setCellValue('E' . $row, '$' . $pago['valor_pagado']);
            $sheet->setCellValue('F' . $row, $pago['fecha_registro']);
            $sheet->setCellValue('G' . $row, $pago['forma_pago']);
            $sheet->setCellValue('H' . $row, $pago['num_cheque']);
            $sheet->setCellValue('I' . $row, $pago['num_transferencia']);
            $sheet->setCellValue('J' . $row, $pago['id_banco']);
            $sheet->setCellValue('K' . $row, $pago['banco']);
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
    }
}