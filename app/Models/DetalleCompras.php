<?php 
namespace App\Models;

use CodeIgniter\Model;

class DetalleCompras extends Model{
    protected $table      = 'detalle_compras';
    // Uncomment below if you want add primary key
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_compra','id_producto','cantidad','precio','descuento','iva','subtotal','total'];
}