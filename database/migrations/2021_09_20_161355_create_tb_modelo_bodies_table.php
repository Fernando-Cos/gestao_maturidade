<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbModeloBodiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_modelo_bodies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_modelo_header_fk')->unique();
            $table->foreignId('id_pergunta_fk');
            $table->foreignId('id_resposta_fk');
            $table->foreignId('id_atividade_fk');
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
        Schema::dropIfExists('tb_modelo_bodies');
    }
}
