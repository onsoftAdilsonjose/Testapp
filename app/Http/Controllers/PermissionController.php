<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class PermissionController extends Controller
{






    public function index()
    {
        $permissions = Permission::all();
        return response()->json(['permissions' => $permissions], 200);
    }









    public function show($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['message' => 'Permission not found.'], 404);
        }

        return response()->json(['permission' => $permission], 200);
    }







    public function StorePermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:permissions',
            'description' => 'nullable|string',
            'slug' => 'required|string|unique:permissions',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

          $permission = Permission::create($request->all());

         return response()->json(['permission' => $permission, 'message' => 'Permissão criada com sucesso.'], 201);
    }

    public function ActualizarPermission(Request $request, $id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['message' => 'Permissão não encontrada.'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                Rule::unique('permissions')->ignore($id),
            ],
            'description' => 'nullable|string',
            'slug' => [
                'required',
                'string',
                Rule::unique('permissions')->ignore($id),
            ],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $permission->update($request->all());

        return response()->json(['permission' => $permission, 'message' => 'Permissão atualizada com sucesso.'], 200);
    }

    public function destroy($id)
    {
        $permission = Permission::find($id);

        if (!$permission) {
            return response()->json(['message' => 'Permissão não encontrada.'], 404);
        }

        $permission->delete();

        return response()->json(['message' => 'Permissão excluída com sucesso.'], 200);
    }
}
