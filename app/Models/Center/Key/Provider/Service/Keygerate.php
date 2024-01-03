<?php

namespace App\Models\Center\Key\Provider\Service;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keygerate extends Model
{
    use HasFactory;

        protected $primaryKey = 'id';
		protected $table = 'serialicense';
		  public $timestamps = true; // Indica se o modelo deve registrar automaticamente as datas de criação/atualização
		protected $fillable = [
		'activated',
		'Meses',
		'startday',
		'endday',
		'key',
 


 

];



    protected $hidden = [
        // 'key',
    ];
}
 