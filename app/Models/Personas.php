<?php 
namespace App\Models;

use CodeIgniter\Model;

class Personas extends Model{
    protected $table      = 'personas';
    // Uncomment below if you want add primary key
    protected $primaryKey = 'id';
    protected $allowedFields = ['identificacion','es_extranjero','nombres','correo','telefono','es_empleado','id_cargo','es_cliente','es_proveedor','estado'];

    public function obtenerDNI($identificacion)
    {
        $query = $this->select('*')->where('identificacion', $identificacion)->get();
        return $query->getRowArray();
    }

    

    

}