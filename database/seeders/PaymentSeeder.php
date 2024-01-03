<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Payment;
class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        


      Payment::create([
            'ValorPago' => 20970,
            'info' => 'Payment information',
            'studentID' => 258,
            'classID' => 11,
            'anolectivoID' => 1,
            'description' => 'Payment description',
            'paymentOrder' => 1,
            'FocionarioID' => 1,
            'Descount' => 0,
            'Cancelar' => false,
            'TipodePagementoID' => 1,
            'bancoid' => 1,
            // Add other fields as needed
        ]);




    }
}
