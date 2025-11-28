<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('living_model_configs', function (Blueprint $table) {
            $table->id();
            $table->string('model_class');
            $table->json('attributes')->nullable();
            $table->json('calculations')->nullable();
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('quantity')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('living_model_configs');
    }
};
