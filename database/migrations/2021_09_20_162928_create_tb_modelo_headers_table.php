<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbModeloHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_modelo_headers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_area_fk');
            $table->foreignId('id_subarea_fk');
            $table->foreignId('id_parametro_fk');
            $table->char('data', 10)->nullable();
            $table->enum('ativo', ['1', '0']);
            $table->foreignId('id_usuario_fk');
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
        Schema::dropIfExists('tb_modelo_headers');
    }
}
