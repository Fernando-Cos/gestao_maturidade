<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbUnidadesareasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_unidadesareas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_unidade_fk')->unique();
            $table->foreignId('id_area_fk')->unique();
            $table->foreignId('id_subarea_fk')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tb_unidadesareas');
    }
}
