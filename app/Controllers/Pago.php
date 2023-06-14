<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\Pagos;
use App\Models\Personas;
use App\Models\Bancos;

class Pago extends BaseController{
    
    public function index(){
        $pago = new Pagos();
        $data['pagos'] = $pago->select('pagos.*, personas.nombres as persona')
                                ->join('personas', 'personas.id = pagos.id_proveedor', 'left')
                                ->orderBy('pagos.id', 'ASC')
                                ->orderBy('id','ASC')
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
        
        $banco = new Bancos();
        $data['bancos'] = $banco->orderBy('id', 'ASC')
                                ->findAll();

        $data['titulo'] = $titulo;
        return view('pagos/crear', $data);
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
        $valor_total = $this->request->getVar('valor_total'); 
        
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

        $validacion = $this->validate([
            'id_proveedor'=>'required|numeric',
            'fecha_registro' => 'required',
            'valor_total' => 'required|numeric',
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
                'valor_total'=>$valor_total,
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
            'valor_total'=>$valor_total
        ];

        $pago->insert($datos);
        return $this->response->redirect(site_url('Pagos'));
    }

    public function eliminar($id = null) {
        $pago = new Pagos();
        $compra = $pago->find($id);
        $doc_adjunto = $compra['doc_adjunto'];
        // Verificar y eliminar el documento de respaldo
        if (!empty($doc_adjunto) && file_exists($doc_adjunto) && $doc_adjunto != '') {
            unlink($doc_adjunto);
        }
        $pago->where('id', $id)->delete($id);
        return $this->response->redirect(site_url('Pagos'));
    }
}