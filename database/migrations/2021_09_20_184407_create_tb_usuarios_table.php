<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbUsuariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 30);
            $table->string('email', 20)->unique();
            $table->integer('cpf')->unique();
            $table->foreignId('id_unidade_fk');
            $table->foreignId('id_area_fk');
            $table->foreignId('id_subarea_fk');
            $table->foreignId('id_permissao_fk');
            $table->tinyInteger('ativo');
            $table->string('password');
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
        Schema::dropIfExists('tb_usuarios');
    }
}
