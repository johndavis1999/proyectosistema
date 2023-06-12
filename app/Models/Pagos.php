<?php
namespace App\Models;

use CodeIgniter\Model;

class Pagos extends Model{
    protected $table = 'pagos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_compra', 'id_proveedor', 'valor_pagado', 'fecha', 'doc_evidencia', 'forma_pago', 'num_cheque', 'num_transferencia', 'banco', 'fecha_movimiento'];
}
