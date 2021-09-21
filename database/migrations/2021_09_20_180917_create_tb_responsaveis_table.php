<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbResponsaveisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_responsaveis', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 30);
            $table->foreignId('id_unidade_fk');
            $table->foreignId('id_subarea_fk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_responsaveis');
    }
}
