<?php

namespace App\Controllers;
use App\Controllers\BaseController;

class Error404Controller extends BaseController
{
    public function index(){
            $data['titulo'] = 'Error 404';
            return view('errors/html/error_404', $data);
    }
}
?>