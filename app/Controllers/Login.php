<?php

namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\Users;

class Login extends BaseController
{
    public function index()
    {
        if (!$this->validarSesion()) {
            return redirect()->to(base_url('dashboard'));
        }
        $titulo = "Login";
        return view('login/index',['titulo' => $titulo]);
    }

    public function acceder()
    {
        $usuario = $this->request->getPost('usuario');
        $password = $this->request->getPost('password');
        $Usuario = new Users();
    
        $datosUsuario = $Usuario->obtenerUsuario($usuario);
    
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        if ($datosUsuario != null && password_verify($password, $datosUsuario['password']))
        {
            $nombreUsuario = $datosUsuario['usuario']; // Nueva línea para obtener el nombre del usuario
            $data = [
                'usuario' => $datosUsuario['usuario'],
                'id' => $datosUsuario['id'],
                'id_rol' => $datosUsuario['id_rol'],
                'id_persona' => $datosUsuario['id_persona'],
                'imagen' => $datosUsuario['imagen'],
                'estado' => $datosUsuario['estado'],
                'nombreUsuario' => $nombreUsuario // Nueva línea para guardar el nombre del usuario en la sesión
            ];
    
            $session = session();
            $session->set($data);
            return redirect()->to(base_url('/dashboard'));
    
        }
        $session = session();
        $session->setFlashData('mensaje','Usuario o clave incorrectos '. $usuario . ' - ' . $passwordHash);
        return $this->response->redirect(site_url(''));
    }

    public function cerrarSesion(){
        $session = session();
        $session->destroy();
        return redirect()->to(base_url('/'));
    }
}
