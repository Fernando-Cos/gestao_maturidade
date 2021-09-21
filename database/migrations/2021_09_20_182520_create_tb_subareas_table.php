<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbSubareasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_subareas', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 30);
            $table->foreignId('id_area_fk');
            $table->string('imagem');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void 
     */
    public function down()
    {
        Schema::dropIfExists('tb_subareas');
    }
}
