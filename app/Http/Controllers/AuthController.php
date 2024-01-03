<?php

namespace App\Http\Controllers;


use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

         public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }
 
/**
 * Login
 * @OA\Post (
 *     path="/api/login",
 *     tags={"Login"}, 
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                      type="object",
 *                      @OA\Property(
 *                          property="email",
 *                          type="string",
 *                          example="Admin@gmail.com"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          type="string",
     *                          example="AD2372428231"
     *                      )
     *                 ),
     *                 example={
     *                     "email":"Admin@gmail.com",
     *                     "password":"AD2372428231"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="token", type="string",                                                                                                         example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjEsImlhdCI6MTY2OTM3NTQ4MCwiZXhwIjoxNjY5NDYxODgwfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="Invalid credentials")
     *          )
     *      )
     * )
     */
public function login(Request $request)
{
    // Check if email is provided; if not, use 'reg_Numero' as the login field
    $loginField = $request->has('email') ? 'email' : 'reg_Numero';

    // Prepare the credentials array based on the provided field
    $credentials = $request->only($loginField, 'password');

    if (!($token = Auth::guard('api')->attempt($credentials))) {
        return response()->json(['error' => 'Credenciais inválidas'], 401);
    }

    // Retrieve the authenticated user
    $user = Auth::guard('api')->user();

    // Add 'usertype' to the token payload
    $customClaims = ['usertype' => $user->usertype];
    $tokenWithClaims = JWTAuth::claims($customClaims)->fromUser($user);

    // Generate a new refresh token
    $refreshToken = JWTAuth::setToken($tokenWithClaims)->refresh();

    return response()->json([
        'token' => $token, 
        'Usuario' => $user,
        'refresh_token' => $refreshToken 
    ]);
}
 
 /**
 * Refresh token
 * @OA\Post (
 *     path="/api/refresh",
 *     tags={"Usuário autenticado"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *          response=200,
 *          description="success",
 *          @OA\JsonContent(
 *              @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOjEsImlhdCI6MTY2OTM3NTQ4MCwiZXhwIjoxNjY5NDYxODgwfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c")
 *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="Token inválido")
     *          )
     *      )
     * )
     */


public function refresh(Request $request)
{
    // Get the current user
    $user = Auth::guard('api')->user();

    // Add any custom claims you want to the new token
    $customClaims = ['usertype' => $user->usertype];

    // Invalidate the old token and generate a new one with custom claims
    $newToken = JWTAuth::claims($customClaims)->fromUser($user);

    return response()->json([
        'token' => $newToken,
    ]);
}


 /**
 * Logout
 * @OA\Post (
 *     path="/api/logout",
 *     tags={"Usuário autenticado"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Response(
 *          response=200,
 *          description="success",
 *          @OA\JsonContent(
 *              @OA\Property(property="message", type="string", example="Desconectado com sucesso")
 *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="Token inválido")
     *          )
     *      )
     * )
     */


    public function logout()
    {  
          Auth::logout();
        return response()->json([
            'message' => 'Desconectado com sucesso!!',
        ]);

        //// LOGOUT SO FUNCIONA COM O REFRESH TOKEN refresh_token
        // try {
        //     JWTAuth::invalidate(JWTAuth::getToken()); // Invalidate the token
        //     return response()->json(['message' => 'Logged out successfully']);
        // } catch (JWTException $e) {
        //     return response()->json(['error' => 'Failed to logout'], 401);
        // }
    }



}
