<?php 
namespace App\Models;

use CodeIgniter\Model;

class Productos extends Model{
    protected $table      = 'productos';
    // Uncomment below if you want add primary key
    protected $primaryKey = 'id';
    protected $allowedFields = ['codigo','nombre','id_categoria','es_inventariable','stock','porcentaje_iva','precio_venta','precio_compra','descuento','estado'];

    

}