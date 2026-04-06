<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'operational_start', 'value' => '08:00'],
            ['key' => 'operational_end', 'value' => '23:00'],
            ['key' => 'store_name', 'value' => 'UP Cireng'],
            ['key' => 'store_phone', 'value' => '6285189014426'],
            ['key' => 'store_email', 'value' => 'upcireng@example.com'],
            ['key' => 'store_instagram', 'value' => '@upcireng'],
            ['key' => 'store_address', 'value' => 'Purbalingga, Jawa Tengah'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }
    }
}
