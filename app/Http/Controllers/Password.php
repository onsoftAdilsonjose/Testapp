<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;


class Password extends Controller
{
    



 /**
 * actualizar Password
 * @OA\Post (
 *     path="/api/Passwordupdate",
 *     tags={"Usuário autenticado"},
 *      security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="current_password", type="string"),
 *                 @OA\Property(property="new_password", type="string"),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="password actualizado com sucesso",
 *         @OA\JsonContent(
 *             @OA\Property(property="current_password", type="string", example="current_password"),
 *             @OA\Property(property="new_password", type="string", example="new_password"),
 *
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid data",
 *         @OA\JsonContent(
 *             @OA\Property(property="msg", type="string", example="Falha na validação"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Creation failed",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Falha na actualização"),
 *         )
 *     )
 * )
 */


     // Update the user's password
    public function Passwordupdate(Request $request)
    {
        $user = $request->user();

        // Validate the request data
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8',
        ]);

        // Check if the current password matches the user's password
        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 401);
        }

        // Update the user's password
        $user->update([
            'password' => Hash::make($request->input('new_password')),
        ]);

        return response()->json(['message' => 'Password updated successfully']);
    }








 /**
 * actualizar Contactos Email Telefone
 * @OA\Post (
 *     path="/api/updateContacto",
 *     tags={"Usuário autenticado"},
 *      security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(property="email", type="string"),
 *                 @OA\Property(property="numeroDotelefone", type="string"),
                   @OA\Property(property="telefoneAlternativo", type="string"),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Contactos actualizado com sucesso",
 *         @OA\JsonContent(
 *             @OA\Property(property="email", type="string", example="adilson2012jose@gmail.com"),
 *             @OA\Property(property="numeroDotelefone", type="string", example="915882240"),
               @OA\Property(property="telefoneAlternativo", type="string", example="926551976"),
 *
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid data",
 *         @OA\JsonContent(
 *             @OA\Property(property="msg", type="string", example="Falha na validação"),
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Creation failed",
 *         @OA\JsonContent(
 *             @OA\Property(property="error", type="string", example="Falha na actualização"),
 *         )
 *     )
 * )
 */


    public function updateContacto(Request $request)
    {
        $user = $request->user();

        // Validate the request data
        $request->validate([
            'email' => 'required|string',
            'numeroDotelefone' => 'required|string|min:8',
             'telefoneAlternativo' => 'required|string|min:8',
        ]);

        // Check if the current password matches the user's password
 
        // Update the user's password
        $user->update([
           'email'=>$request->email,
           'telefoneAlternativo'=>$request->telefoneAlternativo,
           'numeroDotelefone' =>$request->numeroDotelefone,
        ]);

        return response()->json(['message' => 'Password updated successfully']);
    }





    
}
