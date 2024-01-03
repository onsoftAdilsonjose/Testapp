<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

use Illuminate\Support\Facades\Http;

class SmsController extends Controller
{
    public function sendSms()
    {
        $apiUrl = 'http://smsshortcode.com.br/app/modulo/api/index.php';
        $token = '33206d2fb0817e6600512c89c700f45c';
        $tipo = 3;
        $msg = 'msg de teste';
        $numbers = '244926551976';

        try {
            $response = Http::post($apiUrl, [
                'action' => 'sendsms',
                'token' => $token,
                'tipo' => $tipo,
                'msg' => $msg,
                'numbers' => $numbers,
            ]);

            // Check if the request was successful (status code 2xx)
            if ($response->successful()) {
                $responseData = $response->json();

                // Process the response as needed
                // For example, you can return a response to the user
                return response()->json([
                    'status' => 'success',
                    'message' => 'SMS sent successfully!',
                    'response' => $responseData,
                ]);
            } else {
                // Handle non-successful response (status code other than 2xx)
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to send SMS: ' . $response->body(),
                ], $response->status());
            }
        } catch (\Exception $e) {
            // Handle any errors that might occur during the API request
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to send SMS: ' . $e->getMessage(),
            ], 500);
        }
    }
}
