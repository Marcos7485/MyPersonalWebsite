<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->string('email')->index();
            $table->unsignedBigInteger('empresa_id')->nullable()->index();
            $table->unsignedTinyInteger('aplicacion')->default(1); // 1 iQ Athletic, 2 Ecommerce
            $table->unsignedBigInteger('plan_id')->nullable();
            $table->string('plan_nombre')->nullable();
            $table->unsignedInteger('monto');
            $table->string('moneda', 3)->default('ARS');
            $table->string('metodo')->nullable();
            $table->string('referencia')->nullable();
            $table->string('status')->default('pendiente'); // pendiente | aprobado | rechazado | reembolsado
            $table->timestamp('pagado_en')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
