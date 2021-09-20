<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbPlanoAcaosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_plano_acaos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_atividade_fk');
            $table->foreignId('id_diagnostico_body_fk');
            $table->foreignId('id_responsavel_fk');
            $table->date('data_realizada');
            $table->date('data_prevista');
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
        Schema::dropIfExists('tb_plano_acaos');
    }
}
