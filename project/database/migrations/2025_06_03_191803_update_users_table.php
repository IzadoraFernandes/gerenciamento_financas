<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Renomear 'id' para 'id_usuario'
            $table->renameColumn('id', 'id_usuario');

            // Adiciona saldo
            $table->decimal('saldo', 10, 2)->default(0.00);

            // Renomear created_at para data_criacao
            $table->renameColumn('created_at', 'data_criacao');


        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
