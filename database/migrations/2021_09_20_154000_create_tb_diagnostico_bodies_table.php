<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbDiagnosticoBodiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_diagnostico_bodies', function (Blueprint $table) {
            $table->id();
            $table->integer('id_diagnostico_header_fk');
            $table->integer('id_pergunta_fk');
            $table->integer('id_resposta_fk');
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
        Schema::dropIfExists('tb_diagnostico_bodies');
    }
}
