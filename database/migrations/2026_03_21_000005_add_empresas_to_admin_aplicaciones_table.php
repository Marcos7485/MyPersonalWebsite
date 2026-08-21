<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('admin_aplicaciones', function (Blueprint $table) {
            // { "1": { id, nombre, email, telefono, direccion }, "2": { ... } }
            $table->json('empresas')->nullable()->after('aplicacion');
        });
    }

    public function down(): void
    {
        Schema::table('admin_aplicaciones', function (Blueprint $table) {
            $table->dropColumn('empresas');
        });
    }
};
