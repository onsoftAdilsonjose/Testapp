<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faltas extends Model
{
    use HasFactory;








        protected $primaryKey = 'id';
		protected $table = 'attendance';
		protected $fillable = [
        'idattendance_types','studentID','classeID','anolectivoID','attendance_date','disciplinaID',

 

];





}
