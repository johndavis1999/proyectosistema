<?php
namespace App\Models;

use CodeIgniter\Model;

class Cotizaciones extends Model{
    protected $table      = 'cotizaciones';
    // Uncomment below if you want add primary key
    protected $primaryKey = 'id';
    protected $allowedFields = ['num_cot','id_cliente','id_vendedor','fecha_doc','fecha_registro','descripcion','subtotal_cotizacion','val_descuento','val_iva','total','pagado','valor_pagado','aprobado','estado'];

    public function obtenerUltimaSecuencia()
    {
        $query = $this->selectMax('num_cot')->get();

        if ($query->getNumRows() > 0) {
            $row = $query->getRow();
            return $row->num_cot;
        }

        return null;
    }
}
