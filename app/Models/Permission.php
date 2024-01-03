<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;

class Permission extends Model
{
    use HasFactory,Authorizable;

    protected $fillable = ['name','description','slug',];


  public function roles() {

   return $this->belongsToMany(Role::class,'permission_role');
       
}





// public function users() {

//    return $this->belongsToMany(User::class,'users_permissions');
       
// }



  public function users()
    {
        return $this->belongsToMany(User::class, 'users_permissions', 'permission_id', 'user_id');
    }


}
