<?php 
namespace App\Models;

use CodeIgniter\Model;

class Categorias extends Model{
    protected $table      = 'categoria_producto';
    // Uncomment below if you want add primary key
    protected $primaryKey = 'id';
    protected $allowedFields = ['descripcion', 'estado'];
}