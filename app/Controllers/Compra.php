<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\Compras;
use App\Models\Personas;
use App\Models\Productos;
use App\Models\DetalleCompras;

class Compra extends BaseController{
    
    public function index(){
        $compra = new Compras();
        $data['compras'] = $compra->select('compras.*, personas.nombres as persona')
                                    ->join('personas', 'personas.id = compras.id_per_prov', 'left')
                                    ->orderBy('compras.id', 'ASC')
                                    ->findAll();
                                    $detalleCompra = new DetalleCompras();
        foreach ($data['compras'] as &$compra) {
            $cantidadRegistros = $detalleCompra->where('id_compra', $compra['id'])->countAllResults();
            $compra['cantidad_registros'] = $cantidadRegistros;
        }
        $titulo = "Compras";
        $data['titulo'] = $titulo;
        return view('compras/index', $data);
    }

    public function crear(){
        $persona = new Personas();
        $data['personas'] = $persona->where('es_proveedor', '1')
                                    ->orderBy('id', 'ASC')
                                    ->findAll();
        $producto = new Productos();
        $data['productos'] = $producto->where('estado', '1')
                                    ->orderBy('id', 'ASC')
                                    ->findAll();
        $titulo = "Compras";
        $data['titulo'] = $titulo;
        return view('compras/crear', $data);
    }

    public function nuevo(){
        $persona = new Personas();
        $data['personas'] = $persona->where('es_proveedor', '1')
                                    ->orderBy('id', 'ASC')
                                    ->findAll();
        $producto = new Productos();
        $data['productos'] = $producto->where('estado', '1')
                                    ->orderBy('id', 'ASC')
                                    ->findAll();

        $titulo = "Compras";
        $data['titulo'] = $titulo;
        return view('compras/nuevo', $data);
    }

    public function guardar(){
        $compra = new Compras();
        $num_fact = $this->request->getVar('num_fact');
        $autorizacion_fact = $this->request->getVar('autorizacion_fact');
        $id_per_prov = $this->request->getVar('id_per_prov');
        $fecha_doc = $this->request->getVar('fecha_doc');
        $doc_adjunto = $this->request->getVar('doc_adjunto');
        $subtotal_compra = $this->request->getVar('subtotal_compra');
        $val_descuento = $this->request->getVar('val_descuento');
        $val_iva = $this->request->getVar('val_iva');
        $descripcion = $this->request->getVar('descripcion');
        $total = $this->request->getVar('total');
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

        $datos=[
            'num_fact'=>$num_fact,
            'autorizacion_fact'=>$autorizacion_fact,
            'id_per_prov'=>$id_per_prov,
            'fecha_doc'=>$fecha_doc,
            'doc_adjunto'=>$doc_adjunto,
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
        //$this->actualizarStockIngreso($lastInsertId);
        return $this->response->redirect(site_url('Compras'));
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
        foreach ($detalle_ingreso as $detalle) {
            $productos = new Productos();
            $producto = $productos->find($detalle['id_producto']);
            $nuevoStock = $producto['stock'] + $detalle['cantidad'];
            $productos->update($detalle['id_producto'], ['stock' => $nuevoStock]);
        }
    }
    
    public function eliminar($id = null) {
        $compra = new Compras();
        //validar si hay categorias con productos relacionados
        $compra->where('id', $id)->delete($id);
        $this->reversarStockIngreso($id);
        $this->eliminarIngresoDetalle($id);
        return $this->response->redirect(site_url('Compras'));
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
            $nuevoStock = $producto['stock'] - $detalle['cantidad'];
            $productos->update($detalle['id_producto'], ['stock' => $nuevoStock]);
        }
    }

}