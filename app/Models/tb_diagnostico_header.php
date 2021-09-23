<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tb_diagnostico_header extends Model
{
    use HasFactory;

    protected $table = "tb_diagnostico_header";
    public $timestamps = true;



    //checa se o modelo contém a qtd de perguntas e respostas suficientes para ser utilizada
    public static function modeloCompleto($id_area,$id_subarea){

        $qtd_perguntas = 0;

        $modelo_header = tb_modelo_header::where('id_area_fk',$id_area)
            ->where('id_subarea_fk',$id_subarea)
            ->where('ativo','Sim')->first();

        if(!empty($modelo_header)) {//se existir modelos para as sub áreas

            $perguntas = tb_modelo_body::where('id_modelo_header_fk', $modelo_header->id)->distinct()->select('id_pergunta_fk')->get();
            $qtd_perguntas = count($perguntas);

            $qtd_perguntas_ideal = tb_parametros::QtdPerguntasEmParametros($modelo_header->id_parametro_fk);
            $qtd_respostas_ideal = tb_parametros::QtdRespostasEmParametros($modelo_header->id_parametro_fk);

            if ($qtd_perguntas < $qtd_perguntas_ideal) {
                return 0;
            } else {

                 foreach ($perguntas as $p) {
                    $qtd_respostas = tb_modelo_body::where('id_modelo_header_fk', $modelo_header->id)
                        ->where('id_pergunta_fk', $p->id_pergunta_fk)
                        ->distinct()->select('id_resposta_fk')->count();


                    if ($qtd_respostas < $qtd_respostas_ideal) {
                        return 0;
                        break;
                    }else{
                        return 1;
                    }
                }
            }

        }else{
            return 0;
        }
    }



}
