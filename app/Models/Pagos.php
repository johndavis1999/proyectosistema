<?php
namespace App\Models;

use CodeIgniter\Model;

class Pagos extends Model{
    protected $table = 'pagos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_proveedor', 'fecha_registro', 'fecha_creacion', 'doc_adjunto', 'forma_pago', 'num_cheque', 'num_transferencia', 'id_banco', 'fecha_movimiento','valor_total'];
}
