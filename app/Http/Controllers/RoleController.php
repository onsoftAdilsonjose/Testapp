<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class RoleController extends Controller
{
    


    public function UsersRoles()
    {
    

        $Role = Role::all();

        return response()->json([
            'Roles' =>$Role,
        ]);
    }



}
