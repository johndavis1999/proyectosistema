<?php
namespace App\Models;

use CodeIgniter\Model;

class Cobros extends Model{
    protected $table = 'cobros';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_cliente', 'id_vendedor', 'fecha_registro', 'fecha_creacion', 'doc_adjunto', 'forma_pago', 'num_movimiento', 'id_banco', 'fecha_movimiento','id_cotizacion','valor_cotizacion','valor_pagado'];

    public function consultarCobro($id_cotizacion){
        $query = $this->where('id_coti$id_cotizacion', $id_cotizacion)->first();
        return $query;
    }
}
