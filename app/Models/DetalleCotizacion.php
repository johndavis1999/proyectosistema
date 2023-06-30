<?php 
namespace App\Models;

use CodeIgniter\Model;

class DetalleCotizacion extends Model{
    protected $table      = 'detalle_cotizacion';
    // Uncomment below if you want add primary key
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_cotizacion','id_producto','cantidad','precio','descuento','iva','subtotal','total'];
}