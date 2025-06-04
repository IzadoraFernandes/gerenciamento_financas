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
        Schema::create('transacaos', function (Blueprint $table) {

            $table->id('id_transacao');
            $table->text('descricao');
            $table->float('valor');
            $table->date('data');
            $table->enum('tipo', ['Receita', 'Despesa']);
            $table->string('categoria');

            $table->unsignedBigInteger('id_usuario');
            $table->foreign('id_usuario')->references('id_usuario')->on('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transacaos');
    }
};
