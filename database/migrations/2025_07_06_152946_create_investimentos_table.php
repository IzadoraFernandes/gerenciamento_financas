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
        Schema::create('investimentos', function (Blueprint $table) {
            $table->id('id_investimento');

            $table->unsignedBigInteger('id_tipo_investimento');
            $table->unsignedBigInteger('id_usuario');

            $table->string('instituicao');
            $table->date('data');
            $table->decimal('valor', 15, 2);
            $table->decimal('rendimento_esperado', 5, 2);

            $table->timestamps();

            $table->foreign('id_tipo_investimento')->references('id')->on('tipo_investimentos');
            $table->foreign('id_usuario')->references('id_usuario')->on('users');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investimentos');
    }
};
