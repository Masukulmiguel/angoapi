<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('api_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('endpoint_base');
            $table->string('version')->default('v1');
            $table->boolean('is_active')->default(true);
            $table->boolean('requires_subscription')->default(true);
            $table->enum('environment', ['SANDBOX', 'PRODUCTION'])->default('SANDBOX');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('api_products');
    }
};
