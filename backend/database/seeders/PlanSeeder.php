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
                'name' => 'Starter',
                'slug' => 'starter',
                'description' => 'Para quem está a começar',
                'price_monthly' => 500,
                'price_annual' => 5000,
                'request_limit_monthly' => 100,
                'features' => ['Validação de BI', 'Validação de Telefone', 'Dados básicos', 'Sandbox para testes', 'Documentação completa'],
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Essencial',
                'slug' => 'essencial',
                'description' => 'Ideal para pequenas empresas',
                'price_monthly' => 2000,
                'price_annual' => 20000,
                'request_limit_monthly' => 500,
                'features' => ['Validação de BI e NIF', 'Validação de Telefone', 'Envio de SMS', 'Dados completos', 'Suporte por email', 'Sandbox para testes', 'Dashboard básico'],
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Profissional',
                'slug' => 'profissional',
                'description' => 'Para negócios em crescimento',
                'price_monthly' => 5000,
                'price_annual' => 50000,
                'request_limit_monthly' => 2000,
                'features' => ['Validação de BI e NIF', 'Validação de Telefone', 'Envio de SMS e Email', 'OTP (Gerar e Verificar)', 'Geração de QR Code', 'Verificação de Identidade', 'Suporte prioritário', 'Webhooks', 'Dashboard avançado', 'Rate limits elevados'],
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'Para grandes volumes',
                'price_monthly' => 15000,
                'price_annual' => 150000,
                'request_limit_monthly' => 10000,
                'features' => ['Todas as APIs disponíveis', 'Validação de BI, NIF, Telefone', 'SMS, Email, OTP, QR Code', 'Verificação de Identidade', 'Localização (Províncias/Municípios)', 'Suporte 24/7', 'Webhooks personalizados', 'SLA garantido', 'Rate limits ilimitados', 'Conta dedicada'],
                'is_active' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::create($plan);
        }
    }
}
