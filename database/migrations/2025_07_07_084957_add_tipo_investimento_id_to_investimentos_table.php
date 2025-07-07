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
        Schema::table('investimentos', function (Blueprint $table) {
            $table->foreignId('tipo_investimento_id')
                ->nullable()
                ->constrained('tipo_investimentos')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('investimentos', function (Blueprint $table) {
            $table->dropForeign(['tipo_investimento_id']);
            $table->dropColumn('tipo_investimento_id');
        });
    }
};
