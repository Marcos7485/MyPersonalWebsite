<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cards')) {
            Schema::table('cards', function (Blueprint $table) {
                if (! Schema::hasColumn('cards', 'icon')) {
                    $table->string('icon')->nullable()->after('image');
                }
            });

            return;
        }

        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->string('project');
            $table->unsignedInteger('card')->default(1);
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->string('hover_text')->nullable();
            $table->string('component')->nullable();
            $table->text('descripcion')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('cards') && Schema::hasColumn('cards', 'icon')) {
            Schema::table('cards', function (Blueprint $table) {
                $table->dropColumn('icon');
            });
        }
    }
};
