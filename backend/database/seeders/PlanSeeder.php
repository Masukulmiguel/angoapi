<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'BI',
                'slug' => 'bi',
                'description' => 'Leitura/validação de BI',
                'price_monthly' => 3000,
                'price_annual' => 36000,
                'request_limit_monthly' => 10000,
                'features' => ['Validação de BI', 'Sandbox API', 'Documentação'],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'NIF Empresa',
                'slug' => 'nif-empresa',
                'description' => 'Leitura/validação de NIF de empresas',
                'price_monthly' => 5000,
                'price_annual' => 60000,
                'request_limit_monthly' => 20000,
                'features' => ['Validação de NIF', 'Sandbox API', 'Documentação'],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'BI + NIF',
                'slug' => 'bi-nif',
                'description' => 'BI + NIF Empresa',
                'price_monthly' => 10000,
                'price_annual' => 120000,
                'request_limit_monthly' => 50000,
                'features' => ['Validação de BI', 'Validação de NIF', 'Sandbox API', 'Documentação', 'Suporte prioritário'],
                'is_active' => true,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
