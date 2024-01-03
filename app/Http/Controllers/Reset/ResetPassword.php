<?php

namespace App\Http\Controllers\Reset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Throwable;

class ResetPassword extends Controller
{
    






public function ResetPassword($RegistrationNumber)
{
    $user = DB::table('users')
        ->where('reg_Numero', '=', $RegistrationNumber)
        ->select('id', 'reg_Numero')
        ->first();

    if (!$user) {
        return response()->json(['error' => 'Usuário não encontrado.'], 404);
    }

    $user->update([
        'password' => Hash::make($RegistrationNumber),
    ]);

    return response()->json(['user' => $user]);
}









 
public function updatePassword(Request $request)
{
    // Validate the request
    $request->validate([
        'current_password' => 'required',
        'password' => 'required|string|min:6|confirmed', // The 'confirmed' rule requires a 'password_confirmation' field in the request
    ]);

    // Get the authenticated user
    $user = Auth::guard('api')->user();

    // Check if the current password matches the user's stored password
    if (!password_verify($request->input('current_password'), $user->password)) {
        return response()->json(['error' => 'Current password is incorrect'], 422);
    }

    // Update the user's password
    $user->update([
        'password' => bcrypt($request->input('password')),
    ]);

    return response()->json(['message' => 'Password updated successfully'],200);
}

















}
