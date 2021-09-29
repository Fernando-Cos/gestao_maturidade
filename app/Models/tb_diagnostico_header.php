<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class tb_diagnostico_header extends Model
{
    use HasFactory;

    protected $table = "tb_diagnostico_header";
    public $timestamps = true;



    protected $fillable = [
        'id_unidade_fk',
        'id_modelo_header_fk',
        'nivel_maturidade',
        'total_pontos',
        'id_usuario_fk',
    ];

    public $messages = [
        'id_unidade_fk.required' => 'O campo UNIDADE é obrigatório!',
        'id_modelo_header_fk.required' => 'O campo MODELO é obrigatório!',
        'id_usuario_fk.required' => 'O USUÁRIO é obrigatório!',
    ];

    public $rules =  [
        'id_unidade_fk'=> 'required',
        'id_modelo_header_fk'=> 'required',
        'nivel_maturidade' => 'nullable',
        'total_pontos' => 'nullable',
        'id_usuario_fk'=> 'required',

    ];




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

    //salva a pontuação dos níveis sumarizados para cada área e unidade corrente
    public static function salvarNiveis($id_unidade,$id_modelo_header_fk,$id_area, $id_parametro){

        //recupera a área e QTD de subareas que a unidade possui
        $sql = "SELECT id_area_fk, COUNT(id_subarea_fk) as qtd
                        FROM tb_unidadesareas
                        WHERE id_unidade_fk = ".$id_unidade."
                        GROUP by id_area_fk";

        $area_unidade = DB::select($sql);

        $qtd_total_subareas = 0; //qtd de subareas que a unidade possui
        foreach ($area_unidade as $q){
            $qtd_total_subareas = $qtd_total_subareas + $q->qtd;
        }

        //para calcular o total máximo de pontos, recupera o parâmetro que foi utilizado no diagnostico
        $parametros = \App\Models\tb_parametros::parametrosUsado($id_parametro);

        //quantidade de perguntas * total maximo de cada pergunta =>total máximo pontos que podem ser atingidos por subarea
        $total_maximo_pontos_subarea = $parametros->qtd_perguntas * $parametros->maximo_pontos;

        //total maximo de pontos por unidade
        $total_maximo_pontos_unidade = $total_maximo_pontos_subarea*$qtd_total_subareas;


        //armazena os índices somados por cada área
        $nivel_area = array();

        $qtd_subareas_com_diagnostico =0;//qtd. de sub áreas que já realizaram o diagnostico
        $total_pontos_alcancados_unidade = 0;

        /* calcular o total dos pontos de cada sub area de acordo com a área */
        foreach($area_unidade as $a){

            $total_alcancados_subarea = 0;
            $qtd_subareas_por_area = 0;

            //recupera todos os ids das subareas para cada area
            $subareas = \App\Models\tb_unidadesareas::where('id_unidade_fk',$id_unidade)
                ->where('id_area_fk',$a->id_area_fk)->get(); //areas e subareas da unidade

            foreach($subareas as $id_subarea){//soma todos os pontos das subareas, onde o resultado será o total da área que pertence

                $sql = "SELECT a.total_pontos as total
                          FROM tb_diagnostico_header a, tb_modelo_header b
                         WHERE a.id_unidade_fk = ".$id_unidade."
                           AND a.id_modelo_header_fk = b.id
                           AND b.ativo = 'Sim'
                           AND b.id_area_fk = ".$a->id_area_fk."
                           AND b.id_subarea_fk = ".$id_subarea->id_subarea_fk."
                           AND b.id_parametro_fk = ".$id_parametro."
                           ORDER BY a.id desc
                           LIMIT 1 ";

                $total_pontos_alcancados_por_subarea = DB::select($sql);

                if (count($total_pontos_alcancados_por_subarea)>0) {
                    foreach ($total_pontos_alcancados_por_subarea as $t) {
                        $total_alcancados_subarea = $total_alcancados_subarea + $t->total;
                    }
                    $qtd_subareas_com_diagnostico = $qtd_subareas_com_diagnostico + 1;
                }

                $qtd_subareas_por_area = $qtd_subareas_por_area + 1;

            }

            //calcula a pontuação por área baseada na soma anterior de suas sub areas

            $pontos_maximo_por_area = $qtd_subareas_por_area*$total_maximo_pontos_subarea;

            $pontos_alcancados_por_area = $total_alcancados_subarea;

            $nivel_maturidade_por_area = round(($pontos_alcancados_por_area/$pontos_maximo_por_area)*100,2);

            //vai acumulando todos os pontos para gerar o da unidade
            $total_pontos_alcancados_unidade = $total_pontos_alcancados_unidade + $pontos_alcancados_por_area;

            //armazena os índices por cada área para salvar posteriormente (código mais abaixo)
            array_push($nivel_area, ["id_area"=>$a->id_area_fk,"valor_nivel_area"=>$nivel_maturidade_por_area]);

        }

        $nivel_maturidade_por_unidade = round(($total_pontos_alcancados_unidade/$total_maximo_pontos_unidade)*100,2);

        try {

            if ($qtd_subareas_com_diagnostico == $qtd_total_subareas)//atualiza o indice da unidade somente quando todas as sub áreas fizerem o diagnostico
                $salvou_indice_unidade = \App\Models\tb_diagnostico_header::salvarNivelUnidade($id_unidade,$nivel_maturidade_por_unidade);

            foreach ($nivel_area as $key => $valor) {
                if ($id_area == $valor['id_area']) {//$id_area_atualizar: atualiza somente a área que foi feito o diagóstivo
                    $salvou_indice_area = \App\Models\tb_diagnostico_header::salvarNivelArea($id_unidade, $valor['id_area'], $valor['valor_nivel_area']);
                    break;
                }
            }

            return true;

        } catch (\Exception $e) {
                return $e;
        }

    }



    public static function salvarNivelArea ($id_unidade, $id_area, $valor_nivel_area){

        $nivel = new tb_nivel_area();
        $nivel->id_unidade_fk = $id_unidade;
        $nivel->id_area_fk = $id_area;
        $nivel->valor_nivel_area = $valor_nivel_area;

        try{
            $nivel->save();
            DB::commit();
            return 1;
        }
        catch (\Exception $e){
            return $e;
        }
    }

    public static function salvarNivelUnidade ($id_unidade, $valor_nivel_unidade){

        $nivel = new tb_nivel_unidade();
        $nivel->id_unidade_fk = $id_unidade;
        $nivel->valor_nivel_unidade = $valor_nivel_unidade;

        try{
            $nivel->save();
            DB::commit();
            return 1;
        }
        catch (\Exception $e){
            return $e;
        }
    }


}
