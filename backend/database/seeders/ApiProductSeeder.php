<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ApiProduct;
use App\Models\Plan;

class ApiProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Validação de NIF',
                'slug' => 'nif-validation',
                'description' => 'API para validação e consulta de NIF de empresas angolanas',
                'endpoint_base' => '/api/v1/gateway/nif',
                'version' => '1.0.0',
                'is_active' => true,
                'requires_subscription' => true,
                'environment' => 'sandbox',
                'metadata' => ['method' => 'POST', 'rate_limit' => 100],
            ],
            [
                'name' => 'Validação de BI',
                'slug' => 'bi-validation',
                'description' => 'API para validação e leitura de Bilhete de Identidade',
                'endpoint_base' => '/api/v1/gateway/identity',
                'version' => '1.0.0',
                'is_active' => true,
                'requires_subscription' => true,
                'environment' => 'sandbox',
                'metadata' => ['method' => 'POST', 'rate_limit' => 100],
            ],
            [
                'name' => 'Verificação de Identidade',
                'slug' => 'identity-verification',
                'description' => 'API para verificação completa de identidade',
                'endpoint_base' => '/api/v1/gateway/identity/verify',
                'version' => '1.0.0',
                'is_active' => true,
                'requires_subscription' => true,
                'environment' => 'sandbox',
                'metadata' => ['method' => 'POST', 'rate_limit' => 50],
            ],
            [
                'name' => 'Consulta de Localização',
                'slug' => 'location-lookup',
                'description' => 'API para consulta de províncias e municípios de Angola',
                'endpoint_base' => '/api/v1/gateway/location',
                'version' => '1.0.0',
                'is_active' => true,
                'requires_subscription' => false,
                'environment' => 'production',
                'metadata' => ['method' => 'GET', 'rate_limit' => 200],
            ],
            [
                'name' => 'Validação de Telefone',
                'slug' => 'phone-validation',
                'description' => 'API para validação de números de telefone angolanos (+244)',
                'endpoint_base' => '/api/v1/gateway/phone',
                'version' => '1.0.0',
                'is_active' => true,
                'requires_subscription' => true,
                'environment' => 'sandbox',
                'metadata' => ['method' => 'POST', 'rate_limit' => 150],
            ],
            [
                'name' => 'Serviço OTP',
                'slug' => 'otp-service',
                'description' => 'API para geração e validação de códigos OTP',
                'endpoint_base' => '/api/v1/gateway/otp',
                'version' => '1.0.0',
                'is_active' => true,
                'requires_subscription' => true,
                'environment' => 'sandbox',
                'metadata' => ['method' => 'POST', 'rate_limit' => 30],
            ],
            [
                'name' => 'Gateway SMS',
                'slug' => 'sms-gateway',
                'description' => 'API para envio de SMS',
                'endpoint_base' => '/api/v1/gateway/sms',
                'version' => '1.0.0',
                'is_active' => true,
                'requires_subscription' => true,
                'environment' => 'sandbox',
                'metadata' => ['method' => 'POST', 'rate_limit' => 20],
            ],
            [
                'name' => 'Gateway Email',
                'slug' => 'email-gateway',
                'description' => 'API para envio de emails',
                'endpoint_base' => '/api/v1/gateway/email',
                'version' => '1.0.0',
                'is_active' => true,
                'requires_subscription' => true,
                'environment' => 'sandbox',
                'metadata' => ['method' => 'POST', 'rate_limit' => 10],
            ],
        ];

        foreach ($products as $product) {
            ApiProduct::create($product);
        }

        // Attach products to plans via pivot
        $biPlan = Plan::where('slug', 'bi')->first();
        $nifPlan = Plan::where('slug', 'nif-empresa')->first();
        $biNifPlan = Plan::where('slug', 'bi-nif')->first();

        $biProducts = ['bi-validation', 'identity-verification', 'location-lookup', 'phone-validation'];
        $nifProducts = ['nif-validation', 'identity-verification', 'location-lookup', 'phone-validation'];
        $allProducts = ['nif-validation', 'bi-validation', 'identity-verification', 'location-lookup', 'phone-validation', 'otp-service', 'sms-gateway', 'email-gateway'];

        foreach ($biProducts as $slug) {
            $product = ApiProduct::where('slug', $slug)->first();
            if ($product) {
                $biPlan->apiProducts()->attach($product->id);
            }
        }

        foreach ($nifProducts as $slug) {
            $product = ApiProduct::where('slug', $slug)->first();
            if ($product) {
                $nifPlan->apiProducts()->attach($product->id);
            }
        }

        foreach ($allProducts as $slug) {
            $product = ApiProduct::where('slug', $slug)->first();
            if ($product) {
                $biNifPlan->apiProducts()->attach($product->id);
            }
        }
    }
}
