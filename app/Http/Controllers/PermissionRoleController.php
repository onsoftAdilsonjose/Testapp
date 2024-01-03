<?php

namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
class PermissionRoleController extends Controller
{




    public function addPermissionToRole(Request $request)
    {
        $role = Role::find($request->roleId);
        $permission = Permission::find($request->permissionId);

        if (!$role || !$permission) {
            return response()->json(['messagem' => 'Função ou permissão não encontrada.'], 404);
        }

        if ($role->permissions->contains($permission)) {
            return response()->json(['messagem' => 'Permissão já adicionada à função.'], 422);
        }

        $role->permissions()->attach($permission);

        return response()->json(['messagem' => 'Permissão adicionada à função com sucesso.'], 200);
    }





    public function removePermissionFromRole($roleId, $permissionId)
    {
        $role = Role::find($roleId);
        $permission = Permission::find($permissionId);

        if (!$role || !$permission) {
            return response()->json(['message' => 'Role or permission not found.'], 404);
        }

        $role->permissions()->detach($permission);

        return response()->json(['message' => 'Permission removed from the role successfully.'], 200);
    }

    // public function updatePermissionForRole($roleId, $oldPermissionId, $newPermissionId)
    // {
    //     $role = Role::find($roleId);
    //     $oldPermission = Permission::find($oldPermissionId);
    //     $newPermission = Permission::find($newPermissionId);

    //     if (!$role || !$oldPermission || !$newPermission) {
    //         return response()->json(['message' => 'Role or old permission or new permission not found.'], 404);
    //     }

    //     $role->permissions()->detach($oldPermission);
    //     $role->permissions()->attach($newPermission);

    //     return response()->json(['message' => 'Permission updated for the role successfully.'], 200);
    // }

    public function showRolePermissions($roleId)
    {
        $role = Role::find($roleId);

        if (!$role) {
            return response()->json(['message' => 'Role not found.'], 404);
        }



		// $count = count([$role]);

		// if ($count == 0) {
		// return response()->json(['Role' => 'O Grupo Requisitado de Momento não tem nenhuma permissão'], 200);
		// }



        $permissions = $role->permissions;

        return response()->json(['permissions' => $permissions], 200);
    }
}
