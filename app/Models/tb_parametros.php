<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_parametros extends Model
{

    protected $table = "tb_parametros";
    public $timestamps = true;

    use HasFactory;


    public static function parametrosAtuais(){

        $parametros = tb_parametros::orderBy('id','desc')->first();

        return $parametros;
    }

    public static function parametrosUsado($id_parametro){

        $parametros = tb_parametros::findOrFail($id_parametro);

        return $parametros;
    }

    public static function ValorMaximoPontosEmParametros($id_parametro){

        $parametros = tb_parametros::findOrFail($id_parametro);

        return $parametros->maximo_pontos;

    }

    public static function QtdPerguntasEmParametros($id_parametro){

        $parametros = tb_parametros::findOrFail($id_parametro);

        return $parametros->qtd_perguntas;

    }

    public static function QtdRespostasEmParametros($id_parametro){

        $parametros = tb_parametros::findOrFail($id_parametro);

        return $parametros->qtd_respostas;

    }
}
