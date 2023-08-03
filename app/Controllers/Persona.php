<?php
namespace App\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


use App\Controllers\BaseController;
use App\Models\Personas;
use App\Models\Cargos;
use App\Models\Users;
use App\Models\Compras;
use App\Models\Cotizaciones;

class Persona extends BaseController{
    public function index(){
        $cargo = new Cargos();
        $data['cargos'] = $cargo->orderBy('id','ASC')->findAll();
        $persona = new Personas();
        $estado = $this->request->getVar('estado');
        $extranjero = $this->request->getVar('extranjero');
        $rol = $this->request->getVar('rol');
        $nombre = $this->request->getVar('nombre');
        $cargo = $this->request->getVar('cargo');
        $titulo = "Personas";
        $data['titulo'] = $titulo;
        $data['estado']=$estado;
        $data['extranjero']=$extranjero;
        $data['rol']=$rol;
        $data['nombre']=$nombre;
        $data['cargo']=$cargo;

        $rolCampos = [
            'empleado' => 'es_empleado',
            'proveedor' => 'es_proveedor',
            'cliente' => 'es_cliente',
        ];
        
        if (array_key_exists($rol, $rolCampos)) {
            $campoRol = $rolCampos[$rol];
            $persona->where($campoRol, 1);
        }
        
        // Aplica los filtros solo si se han seleccionado valores
        if ($estado != null || $extranjero != null || $rol != null || $nombre != null || $cargo != null) {
            $persona->orderBy('id', 'ASC');

            if ($estado != null) {
                $persona->where('estado', $estado);
            }

            if ($extranjero != null) {
                $persona->where('es_extranjero', $extranjero);
            }

            if ($rol != null) {
                $rolCampos = [
                    'empleado' => 'es_empleado',
                    'proveedor' => 'es_proveedor',
                    'cliente' => 'es_cliente',
                ];
        
                if (array_key_exists($rol, $rolCampos)) {
                    $campoRol = $rolCampos[$rol];
                    $persona->where($campoRol, 1);
                }
            }

            if ($nombre != null) {
                $persona->like('nombres', $nombre);
            }

            if ($cargo != null) {
                $persona->like('id_cargo', $cargo);
            }

            $data['personas'] = $persona->paginate(10);
            $paginador = $persona->pager;
            $data['paginador'] = $paginador;
            return view('personas/index', $data);
        }

        $data['personas'] = $persona->paginate(10);

        $paginador = $persona->pager;
        $data['paginador'] = $paginador;
        
        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2])) {
            return view('personas/index', $data);
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
    }

    public function generarExcel($estado, $rol, $nombre, $cargo, $extranjero){
        
        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2])) {

            $persona = new Personas();
    
            $rolCampos = [
                'empleado' => 'es_empleado',
                'proveedor' => 'es_proveedor',
                'cliente' => 'es_cliente',
            ];
            
            if (array_key_exists($rol, $rolCampos)) {
                $campoRol = $rolCampos[$rol];
                $persona->where($campoRol, 1);
            }
            
            // Aplica los filtros solo si se han seleccionado valores
            if ($estado != 'none' || $extranjero != 'none' || $rol != 'none' || $nombre != 'none' || $cargo != 'none') {
                $persona->orderBy('id', 'ASC');
    
                if ($estado != 'none') {
                    $persona->where('estado', $estado);
                }
    
                if ($extranjero != 'none') {
                    $persona->where('es_extranjero', $extranjero);
                }
    
                if ($rol != 'none') {
                    $rolCampos = [
                        'empleado' => 'es_empleado',
                        'proveedor' => 'es_proveedor',
                        'cliente' => 'es_cliente',
                    ];
            
                    if (array_key_exists($rol, $rolCampos)) {
                        $campoRol = $rolCampos[$rol];
                        $persona->where($campoRol, 1);
                    }
                }
    
                if ($nombre != 'none') {
                    $persona->like('nombres', $nombre);
                }
    
                if ($cargo != 'none') {
                    $persona->like('id_cargo', $cargo);
                }
    
                $data['personas'] = $persona->select('personas.*, cargos.descripcion as cargo_empleado')->join('cargos', 'cargos.id = personas.id_cargo', 'left')->findAll();
            } else {
                $data['personas'] = $persona->select('personas.*, cargos.descripcion as cargo_empleado')->join('cargos', 'cargos.id = personas.id_cargo', 'left')->findAll();
            }
    
            // Crear un nuevo objeto Spreadsheet
            $spreadsheet = new Spreadsheet();
    
            // Obtener la hoja activa
            $sheet = $spreadsheet->getActiveSheet();
    
            // Agregar encabezados
            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Nombres');
            $sheet->setCellValue('C1', 'Estado');
            $sheet->setCellValue('D1', 'Es Extranjero');
            $sheet->setCellValue('E1', 'Cliente');
            $sheet->setCellValue('F1', 'Proveedor');
            $sheet->setCellValue('G1', 'Empleado');
            $sheet->setCellValue('H1', 'Cargo');
            // ... Agregar más columnas según tus necesidades
    
            // Agregar datos de las personas al archivo Excel
            $row = 2;
            foreach ($data['personas'] as $persona) {
                $sheet->setCellValue('A' . $row, $persona['id']);
                $sheet->setCellValue('B' . $row, $persona['nombres']);
                $sheet->setCellValue('C' . $row, $persona['estado'] == '1' ? 'Activo' : 'Inactivo');
                $sheet->setCellValue('D' . $row, $persona['es_extranjero'] == '1' ? 'Si' : 'No');
                $sheet->setCellValue('E' . $row, $persona['es_cliente'] == '1' ? 'Si' : 'No');
                $sheet->setCellValue('F' . $row, $persona['es_proveedor'] == '1' ? 'Si' : 'No');
                $sheet->setCellValue('G' . $row, $persona['es_empleado'] == '1' ? 'Si' : 'No');
                $sheet->setCellValue('H' . $row, $persona['cargo_empleado']);
                // ... Agregar más columnas según tus necesidades
    
                $row++;
            }
    
            // Guardar el archivo Excel
            $writer = new Xlsx($spreadsheet);
            $filename = 'export_personas.xlsx';
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

    public function crear(){
        $titulo = "Personas";
        $cargo = new Cargos();
        $data['cargos'] = $cargo->orderBy('id','ASC')->findAll();
        $data['titulo'] = $titulo;
        
        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2])) {
            return view('personas/crear', $data);
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
    }

    function validar_identificacion_ecuador($identificacion) {
        // Eliminar cualquier caracter que no sea dígito
        $identificacion = preg_replace('/[^0-9]/', '', $identificacion);
    
        // Preguntamos si la identificación consta de 10 dígitos
        if(strlen($identificacion) == 10) {
            // Obtenemos el dígito de la región, que son los dos primeros dígitos
            $digito_region = substr($identificacion, 0, 2);
    
            // Preguntamos si la región existe (Ecuador se divide en 24 regiones)
            if($digito_region >= 1 && $digito_region <= 24) {
                // Extraemos el último dígito
                $ultimo_digito = substr($identificacion, 9, 1);
    
                // Agrupamos todos los pares y los sumamos
                $pares = intval($identificacion[1]) + intval($identificacion[3]) + intval($identificacion[5]) + intval($identificacion[7]);
    
                // Agrupamos los impares, los multiplicamos por un factor de 2
                // Si el número resultante es mayor que 9, le restamos 9 al resultado
                $numero1 = intval($identificacion[0]) * 2;
                if ($numero1 > 9) {
                    $numero1 -= 9;
                }
    
                $numero3 = intval($identificacion[2]) * 2;
                if ($numero3 > 9) {
                    $numero3 -= 9;
                }
    
                $numero5 = intval($identificacion[4]) * 2;
                if ($numero5 > 9) {
                    $numero5 -= 9;
                }
    
                $numero7 = intval($identificacion[6]) * 2;
                if ($numero7 > 9) {
                    $numero7 -= 9;
                }
    
                $numero9 = intval($identificacion[8]) * 2;
                if ($numero9 > 9) {
                    $numero9 -= 9;
                }
    
                $impares = $numero1 + $numero3 + $numero5 + $numero7 + $numero9;
    
                // Suma total
                $suma_total = $pares + $impares;
    
                // Extraemos el primer dígito de la suma (sumaPares + sumaImpares)
                $primer_digito_suma = intval(substr(strval($suma_total), 0, 1));
    
                // Obtenemos la decena inmediata
                $decena = ($primer_digito_suma + 1) * 10;
    
                // Obtenemos la resta de la decena inmediata - suma
                // Si la suma nos da 10, el décimo dígito es cero
                $digito_validador = $decena - $suma_total;
                if ($digito_validador == 10) {
                    $digito_validador = 0;
                }
    
                // Validamos que el dígito validador sea igual al último dígito de la identificación
                if ($digito_validador === intval($ultimo_digito)) {
                    return true; // Identificación válida
                } else {
                    return false; // Identificación inválida
                }
            } else {
                return false; // Región no válida
            }
        } else {
             false; // Longitud incorrecta de la identificación
        }
    }

    public function guardar(){
        $persona = new Personas();
        $nombres = $this->request->getVar('nombres');
        $es_extranjero = $this->request->getVar('es_extranjero');
        $identificacion = $this->request->getVar('identificacion');
        $telefono = $this->request->getVar('telefono');
        $correo = $this->request->getVar('correo');
        $es_empleado = $this->request->getVar('es_empleado');
        $id_cargo = $this->request->getVar('id_cargo');
        $es_cliente = $this->request->getVar('es_cliente');
        $es_proveedor = $this->request->getVar('es_proveedor');
        $cotizacion = $this->request->getVar('cotizacion');

        $estado = 1;
    
        $es_extranjero = $es_extranjero == '1' ? '1' : '0';
        $es_empleado = $es_empleado == 1 ? 1 : 0;
        if($es_empleado == '1'){
            
            $es_cliente = '1';
        }
        $es_cliente = $es_cliente == 1 ? 1 : 0;
        $es_proveedor = $es_proveedor == 1 ? 1 : 0;   

        if($es_empleado == 0 && $es_cliente == 0 && $es_proveedor == 0){
            $session = session();
            $session->setFlashData('mensaje','La persona debe tener al menos un rol seleccionado');
            //return $this->response->redirect(site_url('/listar'));
            return redirect()->back()->withInput();
        }

        $personaExise = $persona->obtenerDNI($identificacion);
        if (is_array($personaExise) && count($personaExise) > 0) {
            //return redirect()->back()->withInput()->with('mensaje', 'La persona seleccionada ya tiene un usuario creado');
            $session = session();
            $session->setFlashData('mensaje','Ya existe una persona con esa identificación');
            return $this->response->redirect(base_url('crearPersona'));
        }

        if($es_extranjero != 1){
            $cedulaValida = $this->validar_identificacion_ecuador($identificacion);
            if ( $cedulaValida != true ) {
                //return redirect()->back()->withInput()->with('mensaje', 'La persona seleccionada ya tiene un usuario creado');
                $session = session();
                $session->setFlashData('mensaje','Cedula Invalida' .$es_extranjero);
                return redirect()->back()->withInput();
            }
        }

        $validacion = $this->validate([
            'nombres'=>'required|min_length[8]',
            'identificacion' => 'required|numeric',
            'telefono' => 'required|numeric',
            'correo'=>'required|min_length[5]',
        ]);
        if(!$validacion){
            $session = session();
            $session->setFlashData('mensaje','Revise la información');
            //return $this->response->redirect(site_url('/listar'));
            return redirect()->back()->withInput();
        }
        $datos=[
            'nombres'=>$this->request->getVar('nombres'),
            'identificacion'=>$identificacion,
            'es_extranjero'=>$es_extranjero,
            'telefono'=>$telefono,
            'correo'=>$correo,
            'es_empleado'=>$es_empleado,
            'id_cargo'=>$id_cargo,
            'es_cliente'=>$es_cliente,
            'es_proveedor'=>$es_proveedor,
            'estado'=>$estado
        ];
        $persona->insert($datos);

        if($cotizacion=='1'){
            return $this->response->redirect(site_url('cotizacionCrear'));
        }else{
            return $this->response->redirect(site_url('personas'));
        }
    }
    
    public function eliminar($id = null) {
        

        $compras = new Compras();
        //validar si hay categorias con productos relacionados
        $personaCompra = $compras->where('id_per_prov', $id)->findAll();
        if(!empty($personaCompra)){
            $session = session();
            $session->setFlashData('mensaje','No se puede eliminar la persona ya que tiene transacciones relacionadas.');
            return $this->response->redirect(site_url('personas'));
        }
        

        $cotizaciones = new Cotizaciones();
        $personaCot = $cotizaciones->where('id_cliente', $id)->findAll();
        if(!empty($personaCot)){
            $session = session();
            $session->setFlashData('mensaje','No se puede eliminar la persona ya que tiene transacciones relacionadas.');
            return $this->response->redirect(site_url('personas'));
        }
        $vendedorCot = $cotizaciones->where('id_vendedor', $id)->findAll();
        if(!empty($vendedorCot)){
            $session = session();
            $session->setFlashData('mensaje','No se puede eliminar la persona ya que tiene transacciones relacionadas.');
            return $this->response->redirect(site_url('personas'));
        }

        $usuario = new Users();
        $persona_usuario = $usuario->where('id_persona', $id)->findAll();
        if(!empty($persona_usuario)){
            $session = session();
            $session->setFlashData('mensaje','No se puede eliminar a la persona ya que tiene un usuario creado.');
            return $this->response->redirect(site_url('personas'));
        } else {
            $persona = new Personas();
            $datos = $persona->where('id', $id)->first();
            $persona->where('id', $id)->delete($id);
            return $this->response->redirect(site_url('personas'));
        }
    }

    public function editar($id=null){
        $persona = new Personas();
        $data['persona'] = $persona->where('id',$id)->first();
        $cargo = new Cargos();
        $data['cargos'] = $cargo->orderBy('id','ASC')->findAll();
        $titulo = "Personas";
        $data['titulo'] = $titulo;
        
        //validacion rol permisos
        $session = session();
        if ($session->has('id_rol')) {
            $rol_usuario = $session->get('id_rol');
        }
        if (in_array($rol_usuario, [1,2])) {
            return view('personas/editar', $data);
        }else{
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
        }
    }

    public function actualizar(){
        $persona = new Personas();
        
        $id = $this->request->getVar('id');
        $nombres = $this->request->getVar('nombres');
        $es_extranjero = $this->request->getVar('es_extranjero');
        $identificacion = $this->request->getVar('identificacion');
        $telefono = $this->request->getVar('telefono');
        $correo = $this->request->getVar('correo');
        $es_empleado = $this->request->getVar('es_empleado');
        $id_cargo = $this->request->getVar('id_cargo');
        $es_cliente = $this->request->getVar('es_cliente');
        $es_proveedor = $this->request->getVar('es_proveedor');
        $estado = $this->request->getVar('estado');

        
        $personaExistente = $persona->obtenerDNI($identificacion);
        if (is_array($personaExistente) && count($personaExistente) > 0 && $personaExistente['id'] != $id) {
            $session = session();
            $session->setFlashData('mensaje', 'Ya existe una persona con esa identificación');
            return redirect()->back()->withInput();
        }


        if($es_extranjero != 1){
            $cedulaValida = $this->validar_identificacion_ecuador($identificacion);
            if ( $cedulaValida != true ) {
                //return redirect()->back()->withInput()->with('mensaje', 'La persona seleccionada ya tiene un usuario creado');
                $session = session();
                $session->setFlashData('mensaje','Cedula Invalida');
                return redirect()->back()->withInput();
            }
        }
    
        $es_extranjero = $es_extranjero == '1' ? '1' : '0';
        $es_empleado = $es_empleado == 1 ? 1 : 0;
        if($es_empleado == '1'){
            
            $es_cliente = '1';
        }
        $es_cliente = $es_cliente == 1 ? 1 : 0;
        $es_proveedor = $es_proveedor == 1 ? 1 : 0;     

        if($es_empleado == 0 && $es_cliente == 0 && $es_proveedor == 0){
            $session = session();
            $session->setFlashData('mensaje','La persona debe tener al menos un rol seleccionado');
            return redirect()->back()->withInput();
        }

        $data=[
            'nombres'=>$nombres,
            'identificacion'=>$identificacion,
            'es_extranjero'=>$es_extranjero,
            'telefono'=>$telefono,
            'correo'=>$correo,
            'es_empleado'=>$es_empleado,
            'id_cargo'=>$id_cargo,
            'es_cliente'=>$es_cliente,
            'es_proveedor'=>$es_proveedor,
            'estado'=>$estado
        ];

        $validacion = $this->validate([
            'nombres'=>'required|min_length[8]',
            'identificacion' => 'required|numeric',
            'telefono' => 'required|numeric',
            'correo'=>'required|min_length[5]',
        ]);
        if(!$validacion){
            $session = session();
            $session->setFlashData('mensaje','Revise la información');
            //return $this->response->redirect(site_url('/listar'));
            return redirect()->back()->withInput();
        }

        $persona->update($id,$data);

        return $this->response->redirect(site_url('personas'));
    }
}