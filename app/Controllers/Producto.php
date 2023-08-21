<?php
namespace App\Controllers;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Controllers\BaseController;
use App\Models\Productos;
use App\Models\Categorias;
use App\Models\DetalleCompras;
use App\Models\DetalleCotizacion;

class Producto extends BaseController{
    public function index(){
        $categoria = new Categorias();
        $data['categorias'] = $categoria->orderBy('id','ASC')->findAll();
        $producto = new Productos();
        $titulo = "Productos";
        $data['titulo'] = $titulo;

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
                                    ->orderBy('id','DESC')
                                    ->paginate(10);

            $paginador = $producto->pager;
            $data['paginador'] = $paginador;
            return view('productos/index', $data);
        }



        $data['productos'] = $producto->select('productos. *, categoria_producto.descripcion as categoria')
                                    ->join('categoria_producto', 'categoria_producto.id = productos.id_categoria', 'left')
                                    ->orderBy('id','DESC')
                                    ->paginate(10);
        $paginador = $producto->pager;
        $data['paginador']=$paginador;
        
        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2,3])) {
            return view('productos/index', $data);
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
    }
    
    public function crear(){
        $categoria = new Categorias();
        $data['categorias'] = $categoria->where('estado', 1)
                                        ->orderBy('id','ASC')->findAll();
        $titulo = "Productos";
        $data['titulo'] = $titulo;
        
        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2])) {
            return view('productos/crear', $data);
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
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
        }

        $detallesVenta = new DetalleCotizacion();
        //validar si hay categorias con productos relacionados
        $detallesCot = $detallesVenta->where('id_producto', $id)->findAll();
        if(!empty($detallesCot)){
            $session = session();
            $session->setFlashData('mensaje','No se puede eliminar el producto ya que tiene transacciones relacionadas.');
            return $this->response->redirect(site_url('productos'));
        }

        $producto = new Productos();
        $datos = $producto->where('id', $id)->first();
        
        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2])) {
            $producto->where('id', $id)->delete($id);
            return $this->response->redirect(site_url('productos'));
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
    }

    public function editar($id=null){
        $producto = new Productos();
        $data['producto'] = $producto->where('id',$id)->first();
        $categoria = new Categorias();
        $data['categorias'] = $categoria->orderBy('id','ASC')->findAll();
        $titulo = "Productos";
        $data['titulo'] = $titulo;
        
        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2])) {
            return view('productos/editarProducto', $data);
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
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
    
    public function generarExcel($nombre, $codigo, $estado, $inventariable, $iva, $descuento, $categoriaFiltro){
        
        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2])) {
        
            $producto = new Productos();
            // Aplica los filtros solo si se han seleccionado valores
            if ($nombre != 'none' || $codigo != 'none' || $estado != 'none' || $inventariable != 'none' || $iva != 'none' || $descuento != 'none' || $categoriaFiltro != 'none') {
                $producto->orderBy('id', 'ASC');
    
                if ($nombre != 'none') {
                    $producto->like('nombre', $nombre);
                }
    
                if ($codigo != 'none') {
                    $producto->like('codigo', $codigo);
                }
    
                if ($estado != 'none') {
                    $producto->where('productos.estado', $estado);
                }
    
                if ($inventariable != 'none') {
                    $producto->where('es_inventariable', $inventariable);
                }
    
                if ($iva != 'none') {
                    $producto->where('porcentaje_iva', $iva);
                }
    
                
                if ($descuento != 'none') {
                    if ($descuento != '0'){
                        $producto->where('descuento !=', '0');
                    }
                    else{
                        $producto->where('descuento', $descuento);
                    }
                }
    
                if ($categoriaFiltro != 'none') {
                    $producto->where('id_categoria', $categoriaFiltro);
                }
    
    
                $data['productos'] = $producto->select('productos. *, categoria_producto.descripcion as categoria')
                                        ->join('categoria_producto', 'categoria_producto.id = productos.id_categoria', 'left')
                                        ->findAll();
            } else {
                $data['productos'] = $producto->select('productos. *, categoria_producto.descripcion as categoria')
                                        ->join('categoria_producto', 'categoria_producto.id = productos.id_categoria', 'left')
                                        ->findAll();
            }
    
            // Crear un nuevo objeto Spreadsheet
            $spreadsheet = new Spreadsheet();
    
            // Obtener la hoja activa
            $sheet = $spreadsheet->getActiveSheet();
    
            $sheet->getColumnDimension('C')->setWidth(35);
            $sheet->getColumnDimension('D')->setWidth(20);  
            $sheet->getColumnDimension('E')->setWidth(15);  
            $sheet->getColumnDimension('H')->setWidth(15);  
            $sheet->getColumnDimension('I')->setWidth(15);  
            $sheet->getColumnDimension('J')->setWidth(20);  
            // Agregar encabezados
            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Codigo');
            $sheet->setCellValue('C1', 'Nombre');
            $sheet->setCellValue('D1', 'Categoría');
            $sheet->setCellValue('E1', 'Inventariable');
            $sheet->setCellValue('F1', 'Stock');
            $sheet->setCellValue('G1', 'IVA');
            $sheet->setCellValue('H1', 'Precio Venta');
            $sheet->setCellValue('I1', 'Precio Compra');
            $sheet->setCellValue('J1', 'Porcentaje Descuento');
            $sheet->setCellValue('K1', 'Estado');
            // ... Agregar más columnas según tus necesidades
    
            // Agregar datos de las personas al archivo Excel
            $row = 2;
            foreach ($data['productos'] as $producto) {
                $sheet->setCellValue('A' . $row, $producto['id']);
                $sheet->setCellValue('B' . $row, $producto['codigo']);
                $sheet->setCellValue('C' . $row, $producto['nombre']);
                $sheet->setCellValue('D' . $row, $producto['categoria']);
                $sheet->setCellValue('E' . $row, $producto['es_inventariable'] == '1' ? 'Si' : 'No' );
                $sheet->setCellValue('F' . $row, $producto['stock']);
                $sheet->setCellValue('G' . $row, $producto['porcentaje_iva'].'%');
                $sheet->setCellValue('H' . $row, $producto['precio_venta']);
                $sheet->setCellValue('I' . $row, $producto['precio_compra']);
                $sheet->setCellValue('J' . $row, $producto['descuento']);
                $sheet->setCellValue('K' . $row, $producto['estado'] == '1' ? 'Activo' : 'Inactivo');
                // ... Agregar más columnas según tus necesidades
    
                $row++;
            }
    
            // Guardar el archivo Excel
            $writer = new Xlsx($spreadsheet);
            $filename = 'export_productos.xlsx';
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