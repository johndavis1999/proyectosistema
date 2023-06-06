<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\Productos;
use App\Models\Categorias;
use App\Models\DetalleCompras;

class Producto extends BaseController{
    public function index(){
        $producto = new Productos();
        $data['productos'] = $producto->select('productos. *, categoria_producto.descripcion as categoria')
                                    ->join('categoria_producto', 'categoria_producto.id = productos.id_categoria', 'left')
                                    ->orderBy('id','ASC')
                                    ->findAll();


        $titulo = "Productos";
        $data['titulo'] = $titulo;
        return view('productos/index', $data);
    }
    
    public function crear(){
        $categoria = new Categorias();
        $data['categorias'] = $categoria->orderBy('id','ASC')->findAll();
        $titulo = "Productos";
        $data['titulo'] = $titulo;
        return view('productos/crear', $data);
    }

    public function guardar(){
        $producto = new Productos();
        $codigo = $this->request->getVar('codigo');
        $nombre = $this->request->getVar('nombre');
        $id_categoria = $this->request->getVar('id_categoria');
        $es_inventariable = $this->request->getVar('es_inventariable');
        $porcentaje_iva = $this->request->getVar('porcentaje_iva');
        $precio_venta = $this->request->getVar('precio_venta');
        $precio_compra = $this->request->getVar('precio_compra');
        $descuento = $this->request->getVar('descuento');
        $stock = 0;
        $estado = 1;
        
        $descuento = $descuento == null ? 0 : $this->request->getVar('descuento');
        $es_inventariable = $es_inventariable == 1 ? 1 : 0;

        if( $porcentaje_iva != '12' && $porcentaje_iva != '0'){
            $session = session();
            $session->setFlashData('mensaje','Valor de IVA no admitido');
            //return $this->response->redirect(site_url('/listar'));
            return redirect()->back()->withInput();
        }
        

        $validacion = $this->validate([
            'codigo'=>'required|min_length[4]',
            'nombre'=>'required|min_length[4]',
            'id_categoria'=>'required',
            'porcentaje_iva'=>'required',
            'precio_venta' => 'required|numeric',
            'precio_compra' => 'required|numeric',
            'descuento' => 'numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        ]);
        if(!$validacion){
            $session = session();
            $session->setFlashData('mensaje','Revise la información');
            //return $this->response->redirect(site_url('/listar'));
            return redirect()->back()->withInput();
        }
        $datos=[
            'codigo'=>$codigo,
            'nombre'=>$nombre,
            'id_categoria'=>$id_categoria,
            'es_inventariable'=>$es_inventariable,
            'porcentaje_iva'=>$porcentaje_iva,
            'precio_venta'=>$precio_venta,
            'precio_compra'=>$precio_compra,
            'descuento'=>$descuento,
            'stock'=>$stock,
            'estado'=>$estado
        ];
        $producto->insert($datos);
        return $this->response->redirect(site_url('productos'));
    }
    
    public function eliminar($id = null) {
        $detalles = new DetalleCompras();
        //validar si hay categorias con productos relacionados
        $detalle_compra = $detalles->where('id_producto', $id)->findAll();
        if(!empty($detalle_compra)){
            $session = session();
            $session->setFlashData('mensaje','No se puede eliminar el producto ya que tiene transacciones relacionadas.');
            return $this->response->redirect(site_url('productos'));
        }else{
            $producto = new Productos();
            $datos = $producto->where('id', $id)->first();
            $producto->where('id', $id)->delete($id);
            return $this->response->redirect(site_url('productos'));
        }

        

    }

    public function editar($id=null){
        $producto = new Productos();
        $data['producto'] = $producto->where('id',$id)->first();
        $categoria = new Categorias();
        $data['categorias'] = $categoria->orderBy('id','ASC')->findAll();
        $titulo = "Productos";
        $data['titulo'] = $titulo;
        return view('productos/editarProducto', $data);
    }

    public function actualizarProducto(){
        $producto = new Productos();
        $id = $this->request->getVar('id');
        $nombre = $this->request->getVar('nombre');
        $codigo = $this->request->getVar('codigo');
        $id_categoria = $this->request->getVar('id_categoria');
        $es_inventariable = $this->request->getVar('es_inventariable');
        $porcentaje_iva = $this->request->getVar('porcentaje_iva');
        $precio_venta = $this->request->getVar('precio_venta');
        $precio_compra = $this->request->getVar('precio_compra');
        $descuento = $this->request->getVar('descuento');
        $estado = $this->request->getVar('estado');


        $data=[
            'nombre'=>$nombre,
            'codigo'=>$codigo,
            'id_categoria'=>$id_categoria,
            'es_inventariable'=>$es_inventariable,
            'porcentaje_iva'=>$porcentaje_iva,
            'precio_venta'=>$precio_venta,
            'precio_compra'=>$precio_compra,
            'descuento'=>$descuento,
            'estado'=>$estado
        ];

        $validacion = $this->validate([
            'codigo'=>'required|min_length[4]',
            'nombre'=>'required|min_length[4]',
            'id_categoria'=>'required',
            'porcentaje_iva'=>'required',
            'precio_venta' => 'required|numeric',
            'precio_compra' => 'required|numeric',
            'descuento' => 'numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
        ]);
        if(!$validacion){
            $session = session();
            $session->setFlashData('mensaje','Revise la información');
            return redirect()->back()->withInput();
        }

        $producto->update($id,$data);

        return $this->response->redirect(site_url('productos'));
    }

}