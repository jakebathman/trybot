<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;

class UpdateOverbuffCommand extends Command
{
    public $curl;
    
    protected $signature = 'overbuff:update';
    protected $description = 'Update stats for users on Overbuff.com';

    public function __construct()
    {
        parent::__construct();
        
        $this->curl = new Curl;
    }

    public function handle()
    {
        $users = [
            'jakebathman' => ['psn'],
            'GTfanat1c2010' => ['psn'],
            'GTfanat1c-1164' => ['pc'],
            'scnoi_' => ['psn'],
            'Havoc_btw' => ['psn'] ,
            'TheReal210Kidddd' => ['psn'],
            'Real210Kiddd-1474' => ['pc'],
            'King_Schultzyy' => ['psn'],
            'ironrectangle' => ['psn'],
            'H_voc' => ['psn'],
            'scnoi' => ['psn'],
            'Zirockz-1528' => ['pc'],
            'ToffeeFromSATX' => ['psn'],
        ];

        $this->info("Starting Overbuff Update...");

        foreach ($users as $user => $platforms) {
            foreach ($platforms as $platform) {
                Log::info("Updating Overbuff stats for {$user} on {$platform}");

                $response = Curl::to("https://www.overbuff.com/players/{$platform}/{$user}/refresh")
                    ->withTimeout(15)
                    ->allowRedirect()
                    ->returnResponseObject()
                    ->asJsonResponse(true)
                    ->post();

                Log::info("  Result: " . $response->status . "  |  Updated? " . (Arr::get($response->content, 'updated') ? "YES" : "NO"));
            }
        }
    }
}
