<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\Categorias;
use App\Models\Productos;

class Categoria extends BaseController{
    public function index(){
        $categoria = new Categorias();
        $data['categorias'] = $categoria->orderBy('id','ASC')->findAll();
        $titulo = "Categorias";
        $data['titulo'] = $titulo;
        return view('productos/categorias', $data);
    }

    public function guardar(){
        $categoria = new Categorias();
        $descripcion = $this->request->getVar('descripcion');
        $estado = 1;
        

        $validacion = $this->validate([
            'descripcion'=>'required|min_length[4]',
        ]);
        if(!$validacion){
            $session = session();
            $session->setFlashData('mensaje','Revise la información');
            //return $this->response->redirect(site_url('/listar'));
            return redirect()->back()->withInput();
        }
        $datos=[
            'descripcion'=>$this->request->getVar('descripcion'),
            'estado'=>$estado
        ];
        $categoria->insert($datos);
        return $this->response->redirect(site_url('categorias'));
    }

    public function actualizar(){
        $categoria = new Categorias();
        $id = $this->request->getVar('id');
        $descripcion = $this->request->getVar('descripcion');
        $estado = $this->request->getVar('estado');


        $data=[
            'descripcion'=>$descripcion,
            'estado'=>$estado
        ];

        $validacion = $this->validate([
            'descripcion'=>'required|min_length[4]'
        ]);
        if(!$validacion){
            $session = session();
            $session->setFlashData('mensaje','Revise la información');
            //return $this->response->redirect(site_url('/listar'));
            return redirect()->back()->withInput();
        }

        $categoria->update($id,$data);

        return $this->response->redirect(site_url('categorias'));
    }
    
    public function eliminar($id = null) {

        
        $producto = new Productos();
        //validar si hay categorias con productos relacionados
        $producto_cat = $producto->where('id_categoria', $id)->findAll();
        if(!empty($producto_cat)){
            $session = session();
            $session->setFlashData('mensaje','No se puede eliminar la categoria ya que tiene productos relacionados.');
            return $this->response->redirect(site_url('categorias'));
        } else {
            $categoria = new Categorias();
            $categoria->where('id', $id)->delete($id);
            return $this->response->redirect(site_url('categorias'));
        }
    }

    


}