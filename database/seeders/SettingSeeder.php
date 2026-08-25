<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'platform_name', 'value' => 'AngoAPI', 'type' => 'string', 'group' => 'general'],
            ['key' => 'platform_description', 'value' => 'Plataforma de APIs para Angola', 'type' => 'string', 'group' => 'general'],
            ['key' => 'platform_url', 'value' => 'https://angoapi.com', 'type' => 'string', 'group' => 'general'],
            ['key' => 'support_email', 'value' => 'suporte@angoapi.com', 'type' => 'string', 'group' => 'general'],
            ['key' => 'default_currency', 'value' => 'AOA', 'type' => 'string', 'group' => 'payment'],
            ['key' => 'sandbox_mode', 'value' => 'true', 'type' => 'boolean', 'group' => 'api'],
            ['key' => 'rate_limit_per_minute', 'value' => '60', 'type' => 'integer', 'group' => 'api'],
            ['key' => 'max_api_keys_per_user', 'value' => '5', 'type' => 'integer', 'group' => 'api'],
            ['key' => 'require_payment_approval', 'value' => 'true', 'type' => 'boolean', 'group' => 'payment'],
            ['key' => 'maintenance_mode', 'value' => 'false', 'type' => 'boolean', 'group' => 'general'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
