<?php 
namespace App\Models;

use CodeIgniter\Model;

class Bancos extends Model{
    protected $table      = 'bancos';
    // Uncomment below if you want add primary key
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre'];
}