<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbRespostasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_respostas', function (Blueprint $table) {
            $table->id();
            $table->text('descricao', 200);
            $table->tinyInteger('nota');
            $table->foreignId('id_pergunta_fk');
            $table->char('ativo', 3)->nullable();
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
        Schema::dropIfExists('tb_respostas');
    }
}
