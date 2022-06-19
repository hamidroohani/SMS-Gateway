<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Provider::query()->insert([
            [
                "name" => "kaveh-negar",
                "number" => "10001515",
                "info" => [
                    'api_key' => '123'
                ],
            ],
            [
                "name" => "qasedak",
                "number" => "10001515",
                "info" => [
                    'api_key' => '123',
                    'url' => 'http://api.iransmsservice.com/v2/sms/send/simple',
                    'status_url' => 'http://api.iransmsservice.com/v2/sms/status',
                ],
            ]
        ]);
    }
}
