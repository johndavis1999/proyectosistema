<?php 
namespace App\Models;

use CodeIgniter\Model;

class Compras extends Model{
    protected $table      = 'compras';
    // Uncomment below if you want add primary key
    protected $primaryKey = 'id';
    protected $allowedFields = ['num_fact','autorizacion_fact','id_per_prov','fecha_doc','fecha_registro','doc_adjunto','subtotal_compra','val_descuento','val_iva','descripcion','total','pagado','valor_pagado','estado'];
}