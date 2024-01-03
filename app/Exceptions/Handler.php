<?php

// app/Exceptions/Handler.php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth; // Import the JWTAuth facade
use Throwable;

class Handler extends ExceptionHandler
{
    // ...

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
       // Handle 422 Unprocessable Entity
        // if ($exception instanceof \Illuminate\Validation\ValidationException) {
        //     return response()->json(['errors' => $exception->errors()], 422);
        // }

        // Handle 403 Forbidden (Access Denied)
        // if ($exception instanceof AccessDeniedHttpException) {
        //     return response()->json(['message' => 'Você não está autorizado a executar esta ação.'], 403);
        // }

        // // Handle 500 Internal Server Error
        // if ($exception instanceof \Exception) {
        //     return response()->json(['message' => 'Erro do Servidor Interno'], 500);
        // }
        try {
            // Attempt to get the authenticated user
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['message' => 'token expirado'], 401);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['message' => 'Token invalido'], 401);
        } 
        catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['message' => 'Token ausente'], 401);
        }

        return parent::render($request, $exception);
    }

    // ...
}
