<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\User; // Import your User model
use Tymon\JWTAuth\Facades\JWTAuth; // Import the JWTAuth facade

class TransactionsControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateTransactions()
    {
        // Create a user with JWT token
        $user = factory(User::class)->create();
        $token = JWTAuth::fromUser($user);

        // Set up the request with the JWT token and necessary data
        $requestData = [
            'Meses' => [
                [
                    'mesID' => 1,
                    'pagamentoMensal' => 100,
                    'multa' => 50,
                    'desconto' => 10,
                ],
                // Add more data as needed
            ],
            'esquecerMulta' => false, // Or true if you want to test the esquecerMulta condition
            'classeID' => 1, // Add other required fields here
            'studentID' => 1,
            'anolectivoID' => 1,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('api/Admin/EstudantePayment', $requestData);

        // Perform your assertions on the response as needed
        $response->assertStatus(200);

        // You can also assert that the data was saved in the database if applicable
        $this->assertDatabaseHas('transactions', [
            'payment_id' => 12,
            'classID' => 1,
            'studentID' => 1,
            'anolectivoID' => 1,
            // Add other expected data here
        ]);
    }
}
