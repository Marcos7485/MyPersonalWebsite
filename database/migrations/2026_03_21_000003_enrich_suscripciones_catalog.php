<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('suscripciones', function (Blueprint $table) {
            $table->unsignedInteger('precio')->default(0)->after('duracion');
            $table->unsignedInteger('precio_tachado')->nullable()->after('precio');
            $table->string('moneda', 3)->default('ARS')->after('precio_tachado');
            $table->json('niveles')->nullable()->after('moneda');
            $table->string('badge')->nullable()->after('niveles');
            $table->boolean('destacado')->default(false)->after('badge');
            $table->text('descripcion')->nullable()->after('destacado');
        });

        $now = now();

        DB::table('suscripciones')->where('id', 1)->update([
            'nombre' => 'Progresivo',
            'periodo' => 'month',
            'duracion' => 1,
            'precio' => 50000,
            'precio_tachado' => null,
            'moneda' => 'ARS',
            'niveles' => json_encode([
                ['clave' => 'nivel1Hasta20', 'alumnos_max' => 20, 'precio' => 50000],
                ['clave' => 'nivel2De21a40', 'alumnos_max' => 40, 'precio' => 150000],
                ['clave' => 'nivel3MasDe40', 'alumnos_max' => null, 'precio' => 250000],
            ]),
            'badge' => 'porCrecimiento',
            'destacado' => false,
            'descripcion' => 'pagasSegunAlumnosActivos',
            'active' => true,
            'updated_at' => $now,
        ]);

        DB::table('suscripciones')->where('id', 2)->update([
            'nombre' => 'Mensual',
            'periodo' => 'month',
            'duracion' => 1,
            'precio' => 250000,
            'precio_tachado' => null,
            'moneda' => 'ARS',
            'niveles' => null,
            'badge' => null,
            'destacado' => false,
            'descripcion' => null,
            'active' => true,
            'updated_at' => $now,
        ]);

        DB::table('suscripciones')->where('id', 3)->update([
            'nombre' => 'Anual',
            'periodo' => 'year',
            'duracion' => 1,
            'precio' => 2500000,
            'precio_tachado' => 3000000,
            'moneda' => 'ARS',
            'niveles' => null,
            'badge' => 'recomendadoDescuento',
            'destacado' => true,
            'descripcion' => null,
            'active' => true,
            'updated_at' => $now,
        ]);
    }

    public function down(): void
    {
        Schema::table('suscripciones', function (Blueprint $table) {
            $table->dropColumn([
                'precio',
                'precio_tachado',
                'moneda',
                'niveles',
                'badge',
                'destacado',
                'descripcion',
            ]);
        });
    }
};
