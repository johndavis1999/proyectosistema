<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\Pagos;
use App\Models\Compras;
use App\Models\Personas;
use App\Models\Bancos;

class Pago extends BaseController{
    
    public function index(){
        $pago = new Pagos();

        $nombre = $this->request->getVar('nombre');
        $codigo = $this->request->getVar('codigo');
        $estado = $this->request->getVar('estado');
        $inventariable = $this->request->getVar('inventariable');
        $iva = $this->request->getVar('iva');
        $descuento = $this->request->getVar('descuento');
        $categoriaFiltro = $this->request->getVar('categoriaFiltro');

        $data['nombre']=$nombre;
        $data['codigo']=$codigo;
        $data['estado']=$estado;
        $data['inventariable']=$inventariable;
        $data['iva']=$iva;
        $data['descuento']=$descuento;
        $data['categoriaFiltro']=$categoriaFiltro;

        /*
        if ($nombre != null || $codigo != null || $estado != null || $inventariable != null || $iva != null || $descuento != null || $categoriaFiltro != null) {
            $producto->orderBy('id', 'ASC');

            if ($nombre != null) {
                $producto->like('nombre', $nombre);
            }

            if ($codigo != null) {
                $producto->like('codigo', $codigo);
            }

            if ($estado != null) {
                $producto->where('productos.estado', $estado);
            }

            if ($inventariable != null) {
                $producto->where('es_inventariable', $inventariable);
            }

            if ($iva != null) {
                $producto->where('porcentaje_iva', $iva);
            }

            if ($descuento != null) {
                if ($descuento != '0'){
                    $producto->where('descuento <>', 0);
                }
                else{
                    $producto->where('descuento', $descuento);
                }
            }

            if ($categoriaFiltro != null) {
                $producto->where('id_categoria', $categoriaFiltro);
            }

            $data['productos'] = $producto->select('productos. *, categoria_producto.descripcion as categoria')
                                    ->join('categoria_producto', 'categoria_producto.id = productos.id_categoria', 'left')
                                    ->paginate(10);

            $paginador = $producto->pager;
            $data['paginador'] = $paginador;
            return view('pagos/index', $data);
        }
        */

        $data['pagos'] = $pago->select('pagos.*, personas.nombres as persona')
                                ->join('personas', 'personas.id = pagos.id_proveedor', 'left')
                                ->orderBy('pagos.id', 'DESC')
                                ->orderBy('id','DESC')
                                ->paginate(10);
        $paginador = $pago->pager;
        $data['paginador']=$paginador;
        $titulo = "Pagos";
        $data['titulo'] = $titulo;
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
}