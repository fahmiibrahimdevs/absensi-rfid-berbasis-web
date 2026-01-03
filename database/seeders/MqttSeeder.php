<?php

namespace Database\Seeders;

use App\Models\Mqtt;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MqttSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'host'                => '192.168.18.123',
                'port'                => '9001',
                'username'            => 'midragon',
                'password'            => 'admin.admin',
            ],
        ];

        Mqtt::insert($data);
    }
}
