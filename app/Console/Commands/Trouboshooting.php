<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\MyCustomFuctions\Key;
use App\Models\Center\Key\Provider\Service\Keygerate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
class Trouboshooting extends Command
{
     protected $signature = 'insert:data';
    protected $description = 'Insert data into the database on a certain date';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
public function handle()
{
    $encryptedData = Key::keys();

    // Prepare data for the HTTP POST request
    $data = [
        'meses' => 1,
        'product_name' => 'Gestao Escolar',
        'reg_Numero' => 'PARCEIRO-2023092131111',
        'license_key' => $encryptedData,
        'customer_name' => 'Monte Sinai',
        'Cliente' => 'Antonio Joao',
    ];

    try {
        // Send the HTTP POST request
     
         $response = Http::post('https://controllincesesystem-production.up.railway.app/api/receive-data', $data);

        if ($response->successful()) {
            // Response from the external server indicates success
            // You can now insert data into the database
            DB::table('serialicense')->insert([
                'Meses' => 1,
                'key' => $encryptedData,
                'activated' => 0,
                // Add other columns and data as needed
            ]);

            $this->info('Data inserted successfully.');
        } else {
            // Response from the external server indicates failure
            $this->error('Failed to send data to the external server.');
        }
    } catch (\Exception $e) {
        // An exception occurred during the HTTP request
        $this->error('An error occurred: ' . $e->getMessage());
    }
}

}