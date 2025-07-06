<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tipo_investimentos', function (Blueprint $table) {
            $table->string('tipo')->after('id');
        });
    }

    public function down()
    {
        Schema::table('tipo_investimentos', function (Blueprint $table) {
            $table->dropColumn('tipo');
        });
    }

};
