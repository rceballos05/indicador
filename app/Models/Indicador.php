<?php 
namespace App\Models;

use CodeIgniter\Model;

class Indicador extends Model{
    protected $table = 'indicador';
    // Uncomment below if you want add primary key
    protected $primaryKey = 'id';
    protected $allowedFields = ['valor','fecha','unidad','nombre','codigo'];
    protected $createdField = 'created_at';
    protected $updateField = 'updated_at';
}