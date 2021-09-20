<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbDiagnosticoHeadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_diagnostico_headers', function (Blueprint $table) {
            $table->id();
            $table->integer('id_unidade_fk');
            $table->integer('id_modelo_header_fk');
            $table->integer('total_pontos');
            $table->decimal('nivel_maturidade', 10,4);
            $table->integer('id_usuario_fk');
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
        Schema::dropIfExists('tb_diagnostico_headers');
    }
}
