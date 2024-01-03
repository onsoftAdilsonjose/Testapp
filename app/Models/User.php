<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Foundation\Auth\Access\Authorizable;
class User extends Authenticatable implements JWTSubject
{
    use Notifiable,Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 
        'password',
        'usertype',
        'banned_until',
        'primeiro_nome',
        'encarregadoID',
        'ultimo_nome',
        'nomePai',
        'nomeMae',
        'status',
        'pessoa_id',
        'dataofbirth',
        'reg_Numero',
        'telefoneAlternativo',
        'numeroDotelefone',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */


public function pessoa()
{
    return $this->belongsTo(Pessoa::class, 'pessoa_id');
}
    

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }



     public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }



    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'user_role');
    // }

    public function hasRole($roleName)
    {
        return $this->roles->contains('name', $roleName);
    }

    public function hasPermission($permissionName)
    {
        foreach ($this->roles as $role) {
            if ($role->permissions->contains('name', $permissionName)) {
                return true;
            }
        }
        return false;
    }


     public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'users_permissions', 'user_id', 'permission_id');
    }


      public function curso()
    {
        return $this->belongsToMany(Curso::class);
    }
}