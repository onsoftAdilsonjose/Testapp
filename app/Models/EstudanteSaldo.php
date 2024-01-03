<?php

namespace App\Models;

 
use Illuminate\Database\Eloquent\Model;

class EstudanteSaldo extends Model
{
          protected $primaryKey = 'id';
        protected $table = 'saldo';
        protected $fillable = [
        'saldo_amount','student_id',];
}
