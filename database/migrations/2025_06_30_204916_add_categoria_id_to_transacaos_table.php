<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transacaos', function (Blueprint $table) {
            $table->foreignId('id_categoria')
                ->nullable()
                ->constrained('categorias')
                ->onDelete('set null');

            $table->dropColumn('categoria');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transacaos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('id_categoria');

            $table->string('categoria')->nullable();
        });
    }
};
