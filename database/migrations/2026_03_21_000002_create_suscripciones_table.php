<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suscripciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('periodo'); // month | year
            $table->unsignedSmallInteger('duracion')->default(1);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        $now = now();

        DB::table('suscripciones')->insert([
            [
                'id' => 1,
                'nombre' => 'Progresivo',
                'periodo' => 'month',
                'duracion' => 1,
                'active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'nombre' => 'Mensual',
                'periodo' => 'month',
                'duracion' => 1,
                'active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'nombre' => 'Anual',
                'periodo' => 'year',
                'duracion' => 1,
                'active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('suscripciones');
    }
};
