<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbNivelMaturidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_nivel_maturidades', function (Blueprint $table) {
            $table->id();
            $table->text('descricao');
            $table->decimal('intervalo_ini', 10,3)->nullable();
            $table->decimal('intervalo_fim', 10,3)->nullable();
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
        Schema::dropIfExists('tb_nivel_maturidades');
    }
}
