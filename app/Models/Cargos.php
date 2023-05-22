<?php 
namespace App\Models;

use CodeIgniter\Model;

class Cargos extends Model{
    protected $table      = 'cargos';
    // Uncomment below if you want add primary key
    protected $primaryKey = 'id';
    protected $allowedFields = ['descripcion'];
}