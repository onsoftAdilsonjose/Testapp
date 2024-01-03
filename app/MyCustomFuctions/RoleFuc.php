<?php

namespace App\MyCustomFuctions;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Validator;


class RoleFuc
{
   

public static function getuserrole($userId){






$user = User::with('roles')->find($userId);
$roleIds = $user->roles->pluck('id')->toArray();



return $roleIds;

}

}


 