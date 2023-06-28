<?php
namespace App\Controllers;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Controllers\BaseController;
use App\Models\Pagos;
use App\Models\Compras;
use App\Models\Personas;
use App\Models\Bancos;

class Pago extends BaseController{
    
    public function index(){
        $pago = new Pagos();
        $proveedor = new Personas();
        $data['proveedores'] = $proveedor->orderBy('id','ASC')->findAll();
        $banco = new Bancos();
        $data['bancos'] = $banco->orderBy('id','ASC')->findAll();
        $compra = new Compras();
        $data['compras'] = $compra->orderBy('id','ASC')->findAll();
        $titulo = "Pagos";
        $data['titulo'] = $titulo;

        $proveedorFiltro = $this->request->getVar('proveedorFiltro');
        $forma_pago = $this->request->getVar('forma_pago');
        $bancoFiltro = $this->request->getVar('bancoFiltro');
        $num_pago = $this->request->getVar('num_pago');
        $fecha_inicio = $this->request->getVar('fecha_inicio');
        $fecha_fin = $this->request->getVar('fecha_fin');
        $num_compra = $this->request->getVar('num_compra');

        $data['proveedorFiltro']=$proveedorFiltro;
        $data['forma_pago']=$forma_pago;
        $data['bancoFiltro']=$bancoFiltro;
        $data['num_pago']=$num_pago;
        $data['fecha_inicio']=$fecha_inicio;
        $data['fecha_fin']=$fecha_fin;
        $data['num_compra']=$num_compra;

        if (  $proveedorFiltro != null || $forma_pago != null || $bancoFiltro != null || $num_pago != null || $num_compra != null || ($fecha_inicio != null && $fecha_fin != null)) {
            $pago->orderBy('id', 'ASC');

            if ($proveedorFiltro != null) {
                $pago->where('id_proveedor', $proveedorFiltro);
            }

            if ($forma_pago != null) {
                $pago->where('forma_pago', $forma_pago);
            }

            if ($bancoFiltro != null) {
                $pago->where('id_banco', $bancoFiltro);
            }

            if ($num_pago != null) {
                $pago->where('pagos.id', $num_pago);
            }

            if ($num_compra != null) {
                $pago->where('id_compra', $num_compra);
            }

            if ($fecha_inicio != null && $fecha_fin != null) {
                $pago->where('fecha_registro >=', $fecha_inicio);
                $pago->where('fecha_registro <=', $fecha_fin);
            }
            

            $data['pagos'] = $pago->select('pagos.*, personas.nombres as persona')
                                    ->join('personas', 'personas.id = pagos.id_proveedor', 'left')
                                    ->orderBy('pagos.id', 'DESC')
                                    ->orderBy('id','DESC')
                                    ->paginate(10);

            $paginador = $pago->pager;
            $data['paginador'] = $paginador;
            return view('pagos/index', $data);
        }

        $data['pagos'] = $pago->select('pagos.*, personas.nombres as persona')
                                ->join('personas', 'personas.id = pagos.id_proveedor', 'left')
                                ->orderBy('pagos.id', 'DESC')
                                ->orderBy('id','DESC')
                                ->paginate(10);
        $paginador = $pago->pager;
        $data['paginador']=$paginador;
        return view('pagos/index', $data);
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
        return view('pagos/crear', $data);
    }
    
    public function registrar($id=null){
        $titulo = "Pagos";
        $persona = new Personas();

        $data['personas'] = $persona->where('es_proveedor', '1')
                                    ->orderBy('id', 'ASC')
                                    ->findAll();
        
        $banco = new Bancos();
        $data['bancos'] = $banco->orderBy('id', 'ASC')
                                ->findAll();
                                
        $compra = new Compras();
        $data['compras'] = $compra->where('id',$id)->first();

        $data['titulo'] = $titulo;
        return view('pagos/registrar', $data);
    }

    public function guardar(){
        $pago = new Pagos();
        $id_proveedor = $this->request->getVar('id_proveedor'); 
        $fecha_registro = $this->request->getVar('fecha_registro'); 
        $forma_pago = $this->request->getVar('forma_pago'); 
        $num_cheque = $this->request->getVar('num_cheque'); 
        $num_transferencia = $this->request->getVar('num_transferencia'); 
        $id_banco = $this->request->getVar('id_banco'); 
        $fecha_movimiento = $this->request->getVar('fecha_movimiento'); 
        $doc_adjunto = $this->request->getFile('doc_adjunto'); 
        $id_compra = $this->request->getVar('id_compra'); 
        $valor_compra = $this->request->getVar('valor_compra'); 
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
                
                $anio = date('Y', strtotime($fecha_movimiento));
                $existeChequeMismoAnio = $pago->where('num_cheque', $num_cheque)
                                                ->where('id_banco', $id_banco)
                                                ->where('YEAR(fecha_movimiento)', $anio)
                                                ->first();
        
                if ($existeChequeMismoAnio !== null) {
                    $session = session();
                    $session->setFlashData('mensaje', 'Número de cheque duplicado');
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

                $anio = date('Y', strtotime($fecha_movimiento));
                $existeTransferenciaMismoAnio = $pago->where('num_transferencia', $num_transferencia)
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
            'id_proveedor'=>'required|numeric',
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

            $datos=[
                'id_proveedor'=>$id_proveedor,
                'fecha_registro'=>$fecha_registro,
                'forma_pago'=>$forma_pago,
                'num_cheque'=>$num_cheque,
                'num_transferencia'=>$num_transferencia,
                'id_banco'=>$id_banco,
                'fecha_movimiento'=>$fecha_movimiento,
                'id_compra'=>$id_compra,
                'valor_compra'=>$valor_compra,
                'valor_pagado'=>$valor_pagado,
                'doc_adjunto'=>$nuevoNombre
            ];
            $pago->insert($datos);
            return $this->response->redirect(site_url('Pagos'));
        }

        $datos=[
            'id_proveedor'=>$id_proveedor,
            'fecha_registro'=>$fecha_registro,
            'forma_pago'=>$forma_pago,
            'num_cheque'=>$num_cheque,
            'num_transferencia'=>$num_transferencia,
            'id_banco'=>$id_banco,
            'fecha_movimiento'=>$fecha_movimiento,
            'doc_adjunto'=>'',
            'id_compra'=>$id_compra,
            'valor_compra'=>$valor_compra,
            'valor_pagado'=>$valor_pagado
        ];

        $pago->insert($datos);

        $this->sumarPago($id_compra,$valor_pagado);

        return $this->response->redirect(site_url('Pagos'));
    }

    protected function sumarPago($id_compra,$valor_pagado){
        // Obtén el objeto del modelo de compras
        $modeloCompras = new Compras(); // Asegúrate de que estás utilizando el modelo correcto aquí
        $compra = $modeloCompras->find($id_compra);
        if ($compra) {
            if (array_key_exists('valor_pagado', $compra)) {
                $nuevoValor = $compra['valor_pagado'] + $valor_pagado;
            }
            $modeloCompras->update($id_compra, ['valor_pagado' => $nuevoValor]);
        }
    }

    public function eliminar($id = null) {
        $pago = new Pagos();
        $pagos = $pago->find($id);
        $doc_adjunto = $pagos['doc_adjunto'];
        // Verificar y eliminar el documento de respaldo
        if (!empty($doc_adjunto) && file_exists($doc_adjunto) && $doc_adjunto != '') {
            unlink($doc_adjunto);
        }
        
        // Restar el valor pagado al eliminar el pago
        $id_compra = $pagos['id_compra'];
        $valor_pagado = $pagos['valor_pagado'];
        $this->restarPago($id_compra, $valor_pagado);
        $pago->where('id', $id)->delete($id);
        return $this->response->redirect(site_url('Pagos'));
    }

    public function restarPago($id_compra, $valor_pagado) {
        $modeloCompras = new Compras();
        $compra = $modeloCompras->find($id_compra);
    
        if ($compra) {
            if (array_key_exists('valor_pagado', $compra)) {
                $nuevoValor = $compra['valor_pagado'] - $valor_pagado;
            } else {
                $nuevoValor = 0;
            }
            // Actualiza el campo valor_pagado en la tabla compras
            $modeloCompras->update($id_compra, ['valor_pagado' => $nuevoValor]);
        }
    }
    
    public function editar($id=null){
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
        return view('pagos/editar', $data);
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