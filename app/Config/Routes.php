<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Login');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
//$routes->set404Override();
$routes->setAutoRoute(true);
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */


function validarSesion() {
    $session = session();
    if (!$session->has('usuario')) {
        return redirect()->to(base_url(''));
    }
}





$routes->set404Override(function() {
    
    $data['titulo'] = 'Error 404'; // Puedes asignar aquí el valor que deseas para la variable $titulo
    if (!validarSesion()) {
        return view('errors/html/error_404', $data);
    }
    return view('errors/html/error_404_nolog', $data);
});


#$routes->set404Override(['Error404Controller::index']);


// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('login', 'Login::index');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

#login
$routes->get('registro', 'Login::registro');
$routes->post('acceder', 'Login::acceder');
$routes->get('salir', 'Login::cerrarSesion');




#usuarios

#$routes->get('usuarios', 'User::index');
$routes->get('usuarios', 'User::index', ['filter' => 'verificarSesion']);
#$routes->get('UsuariosCrear', 'User::crear');
$routes->get('UsuariosCrear', 'User::crear', ['filter' => 'verificarSesion']);
#$routes->post('guardarUsuario', 'User::guardar');
$routes->post('guardarUsuario', 'User::guardar', ['filter' => 'verificarSesion']);
$routes->get('eliminarUsuario/(:num)', 'User::eliminar/$1', ['filter' => 'verificarSesion']);
$routes->get('editarUsuario/(:num)', 'User::editar/$1', ['filter' => 'verificarSesion']);
$routes->post('actualizarUsuario', 'User::actualizar', ['filter' => 'verificarSesion']);
$routes->post('resetearPwd', 'User::resetear', ['filter' => 'verificarSesion']);




#Personas
$routes->get('personas', 'Persona::index', ['filter' => 'verificarSesion']);
$routes->get('crearPersona', 'Persona::crear', ['filter' => 'verificarSesion']);
$routes->post('guardarPersona', 'Persona::guardar', ['filter' => 'verificarSesion']);
$routes->get('eliminarPersona/(:num)', 'Persona::eliminar/$1', ['filter' => 'verificarSesion']);
$routes->get('editarPersona/(:num)', 'Persona::editar/$1', ['filter' => 'verificarSesion']);
$routes->post('actualizarPersona', 'Persona::actualizar', ['filter' => 'verificarSesion']);
$routes->get('exportar/(:segment)?/(:segment)?/(:segment)?/(:segment)?/(:segment)?', 'Persona::generarExcel/$1/$2/$3/$4/$5', ['filter' => 'verificarSesion']);





#productos
$routes->get('productos', 'Producto::index', ['filter' => 'verificarSesion']);
$routes->post('guardarProducto', 'Producto::guardar', ['filter' => 'verificarSesion']);
$routes->get('crearProducto', 'Producto::crear', ['filter' => 'verificarSesion']);
$routes->get('editarProducto/(:num)', 'Producto::editar/$1', ['filter' => 'verificarSesion']);
$routes->post('actualizarProducto', 'Producto::actualizarProducto', ['filter' => 'verificarSesion']);
$routes->get('eliminarProducto/(:num)', 'Producto::eliminar/$1', ['filter' => 'verificarSesion']);
$routes->get('exportarProductos/(:segment)?/(:segment)?/(:segment)?/(:segment)?/(:segment)?/(:segment)?/(:segment)?', 'Producto::generarExcel/$1/$2/$3/$4/$5/$6/$7', ['filter' => 'verificarSesion']);


#categorias
$routes->get('categorias', 'Categoria::index', ['filter' => 'verificarSesion']);
$routes->post('guardarCategoria', 'Categoria::guardar', ['filter' => 'verificarSesion']);
$routes->post('actualizarCategoria', 'Categoria::actualizar', ['filter' => 'verificarSesion']);
$routes->get('eliminarCategoria/(:num)', 'Categoria::eliminar/$1', ['filter' => 'verificarSesion']);


#compras
$routes->get('Compras', 'Compra::index', ['filter' => 'verificarSesion']);
$routes->get('comprasCrear', 'Compra::Crear', ['filter' => 'verificarSesion']);
$routes->get('nuevo', 'Compra::nuevo', ['filter' => 'verificarSesion']);
$routes->post('guardarCompra', 'Compra::guardar', ['filter' => 'verificarSesion']);
$routes->get('eliminarCompra/(:num)', 'Compra::eliminar/$1', ['filter' => 'verificarSesion']);
$routes->get('editarCompra/(:num)', 'Compra::editar/$1', ['filter' => 'verificarSesion']);
$routes->get('consultarCompra/(:num)', 'Compra::consultar/$1', ['filter' => 'verificarSesion']);
$routes->post('actualizarCompra', 'Compra::actualizar', ['filter' => 'verificarSesion']);
$routes->get('exportarCompras/(:segment)?/(:segment)?/(:segment)?/(:segment)?/(:segment)?/(:segment)?/(:segment)?/(:segment)?', 'Compra::generarExcel/$1/$2/$3/$4/$5/$6/$7/$8', ['filter' => 'verificarSesion']);


#ventas
$routes->get('Cotizaciones', 'Cotizacion::index', ['filter' => 'verificarSesion']);
$routes->get('cotizacionCrear', 'Cotizacion::crear', ['filter' => 'verificarSesion']);
$routes->post('guardarCotizacion', 'Cotizacion::guardar', ['filter' => 'verificarSesion']);
$routes->get('eliminarCotizacion/(:num)', 'Cotizacion::eliminar/$1', ['filter' => 'verificarSesion']);
$routes->get('consultarCotizacion/(:num)', 'Cotizacion::consultar/$1', ['filter' => 'verificarSesion']);
$routes->get('editarCotizacion/(:num)', 'Cotizacion::editar/$1', ['filter' => 'verificarSesion']);
$routes->post('actualizarCotizacion', 'Cotizacion::actualizar', ['filter' => 'verificarSesion']);

$routes->get('exportarCotizacion/(:segment)?/(:segment)?/(:segment)?/(:segment)?/(:segment)?/(:segment)?/(:segment)?/(:segment)?/(:segment)?/(:segment)?', 'Cotizacion::generarExcel/$1/$2/$3/$4/$5/$6/$7/$8/$9/$10', ['filter' => 'verificarSesion']);

$routes->get('crearFact', 'Facturacion::crear', ['filter' => 'verificarSesion']);
$routes->get('  ', 'Facturacion::ver', ['filter' => 'verificarSesion']);


#cobros
$routes->get('Cobros', 'Cobro::index', ['filter' => 'verificarSesion']);
$routes->get('registraCobro/(:num)', 'Cobro::registrar/$1', ['filter' => 'verificarSesion']);
$routes->post('guardarCobro', 'Cobro::guardar', ['filter' => 'verificarSesion']);
$routes->get('eliminarCobro/(:num)', 'Cobro::eliminar/$1', ['filter' => 'verificarSesion']);
$routes->get('editarCobro/(:num)', 'Cobro::editar/$1', ['filter' => 'verificarSesion']);
$routes->post('actualizarCobro', 'Cobro::actualizar', ['filter' => 'verificarSesion']);
$routes->get('consultarCobro/(:num)', 'Cobro::consultar/$1', ['filter' => 'verificarSesion']);
$routes->get('exportarCobros/(:segment)?/(:segment)?/(:segment)?/(:segment)?/(:segment)?/(:segment)?/(:segment)?/(:segment)?', 'Cobro::generarExcel/$1/$2/$3/$4/$5/$6/$7/$8', ['filter' => 'verificarSesion']);

#pagos
$routes->get('Pagos', 'Pago::index', ['filter' => 'verificarSesion']);
$routes->get('registraPago/(:num)', 'Pago::registrar/$1', ['filter' => 'verificarSesion']);
$routes->post('guardarPago', 'Pago::guardar', ['filter' => 'verificarSesion']);
$routes->get('consultarPago/(:num)', 'Pago::consultar/$1', ['filter' => 'verificarSesion']);
$routes->get('editarPago/(:num)', 'Pago::editar/$1', ['filter' => 'verificarSesion']);
$routes->post('actualizarPago', 'Pago::actualizar', ['filter' => 'verificarSesion']);
$routes->get('eliminarPago/(:num)', 'Pago::eliminar/$1', ['filter' => 'verificarSesion']);
$routes->get('exportarPagos/(:segment)?/(:segment)?/(:segment)?/(:segment)?/(:segment)?/(:segment)?/(:segment)?', 'Pago::generarExcel/$1/$2/$3/$4/$5/$6/$7', ['filter' => 'verificarSesion']);