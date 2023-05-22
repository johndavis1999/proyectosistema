<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class VerificarSesion implements FilterInterface {
    public function before(RequestInterface $request, $arguments = null): ?ResponseInterface {
        // Verificar si el usuario ha iniciado sesión
        if (!session()->get('usuario')) {
            // Redirigir al usuario a la página de inicio de sesión
            return redirect()->to(base_url('login'));
        }
        return null;
    }
    
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null): ?ResponseInterface {
        // No es necesario implementar nada en este método
        return $response;
    }    
    
}
