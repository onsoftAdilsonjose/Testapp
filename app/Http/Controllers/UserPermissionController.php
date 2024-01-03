<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserPermissionController extends Controller
{



    public function addPermissionToUser(Request $request)
    {
        $user = User::find($request->user_id);


        $permission = Permission::find($request->permission_id);

        if (!$user || !$permission) {
            return response()->json(['message' => 'Usuário ou permissão não encontrados.'], 404);
        }

        if ($user->permissions->contains($permission)) {
            return response()->json(['message' => 'Permissão já adicionada ao usuário.'], 422);
        }

        $user->permissions()->attach($permission);

        return response()->json(['message' => 'Permissão adicionada ao usuário com sucesso.'], 200);
    }







    public function removePermissionFromUser($userId, $permissionId)
    {
        $user = User::find($userId);
        $permission = Permission::find($permissionId);

        if (!$user || !$permission) {
            return response()->json(['message' => 'Usuário ou permissão não encontrados.'], 404);
        }

        $user->permissions()->detach($permission);

        return response()->json(['message' => 'Permissão removida do usuário com sucesso.'], 200);
    }











    // public function updatePermissionForUser($userId, $oldPermissionId, $newPermissionId)
    // {
    //     $user = User::find($userId);
    //     $oldPermission = Permission::find($oldPermissionId);
    //     $newPermission = Permission::find($newPermissionId);

    //     if (!$user || !$oldPermission || !$newPermission) {
    //         return response()->json(['message' => 'User or old permission or new permission not found.'], 404);
    //     }

    //     $user->permissions()->detach($oldPermission);
    //     $user->permissions()->attach($newPermission);

    //     return response()->json(['message' => 'Usuário ou permissão antiga ou nova permissão não encontrada.'], 200);
    // }





    public function showUserPermissions($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'Usuário não encontrado.'], 404);
        }

        $permissions = $user->permissions;
        $count = count($permissions);

         if ($count == 0) {
      return response()->json(['permissions' => 'Usuario Requisitado de Momento não tem nenhuma permissão'], 200);
         }


        return response()->json(['permissions' => $permissions], 200);
    }
}
