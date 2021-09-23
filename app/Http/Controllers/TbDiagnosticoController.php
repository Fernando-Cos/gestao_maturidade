<?php

namespace App\Http\Controllers;

use App\Models\tb_diagnostico_body;
use App\Models\tb_diagnostico_header;
use App\Models\tb_modelo_header;
use App\Models\tb_parametros;
use App\Models\tb_respostas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class TbDiagnosticoController extends Controller {


    public function __construct()
    {

        $this->middleware('auth');

    }

    public function index()
    {
        //

    }

    public function show($id_unidade)
    {

        return view('diagnostico.formularioProgressoNivel',compact('id_unidade'));

    }

    public function atualizarIndices($atualizar,$id_area_atualizar)//qual area será atualizada com o novo índice
    {

        return view('diagnostico.formularioVisaoGeral',compact('atualizar','id_area_atualizar'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       //
    }

    public function resultDiagnostico()
    {


        $sql = "SELECT a.id as id_modelo,c.nome as area, d.nome as subarea, a.data, a.ativo
                    FROM tb_modelo_header a, tb_area c, tb_subarea d
                    WHERE d.id = a.id_subarea_fk
                      and c.id = d.id_area_fk
                    ORDER BY c.nome ";


        $modelos = DB::select($sql);

        return Datatables::of($modelos)->make(true);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */

    //exibir as perguntas da subarea
    public function exibirPerguntas(Request $request)
    {

        $subarea = $request->subareas;

        return view('diagnostico.formularioDiagnostico', compact('subarea'));


    }


    //salva as respostas da unidade
    public function salvarRespostas(Request $request)
    {
        //$this->authorize(ConstUtil::NOME_MODULO.':'.ConstUtil::PERMISSAO_ADMIN_GERAL);

        $diagnosticoheader = new tb_diagnostico_header();
        $diagnosticoheader->id_unidade_fk = $request->id_unidade_fk;
        $diagnosticoheader->id_modelo_header_fk = $request->id_modelo_header_fk;
        $diagnosticoheader->id_usuario_fk = $request->id_usuario_fk;

        $validator = Validator::make($request->all(), $diagnosticoheader->rules, $diagnosticoheader->messages);

        DB::beginTransaction();

        try {
            if ($validator->fails()) {
                return redirect()->action('DiagnosticoController@create')
                    ->withErrors($validator)
                    ->WithInput();
            }else{

                $diagnosticoheader->save();

                DB::commit();

                $total = 0;
                $respostas = $request->array_respostas;

                foreach ($request->array_perguntas as $key=>$value){        //salva as perguntas e respostas
                    $diagnosticobody = new tb_diagnostico_body();
                    $diagnosticobody->id_diagnostico_header_fk = $diagnosticoheader->id;
                    $diagnosticobody->id_perguntas_fk = $value;
                    $diagnosticobody->id_respostas_fk =  $respostas[$key];
                    $diagnosticobody->save();

                    //total pontos de acordo com as respostas
                    $pontos_respostas = tb_respostas::findOrFail($respostas[$key]);
                    $total = $total + $pontos_respostas->nota;
                }

                //atualiza o total de pontos alcançados para a subarea
                $diagnosticoheader2 = tb_diagnostico_header::findOrFail($diagnosticoheader->id);
                $diagnosticoheader2->total_pontos = $total;

                //recupera os parametros do modelo usado, para calcular o nível de maturidade
                $modelo_header = tb_modelo_header::findOrFail($request->id_modelo_header_fk);
                $parametros = tb_parametros::parametrosUsado($modelo_header->id_parametro_fk);
                $total_maximo_pontos = $parametros->qtd_perguntas * $parametros->maximo_pontos; //quantidade de perguntas * total maximo de cada pergunta

                $nivel_maturidade = ($total/$total_maximo_pontos)*100; //calcula o nivel de maturidade em porcentagem

                $diagnosticoheader2->nivel_maturidade = $nivel_maturidade;
                $diagnosticoheader2->update();

                //recupera a descrição do nível de maturidade de acordo com o calculado anteriormente
                $sql = "SELECT descricao, cast(".$nivel_maturidade." as decimal(10,2)) as nivel_maturidade,
                                (100.00 - cast(".$nivel_maturidade." as decimal(10,2))) as valor_melhorar ,
                                ".$modelo_header->id_parametro_fk." as id_parametro_fk FROM tb_nivel_maturidade WHERE ".$nivel_maturidade." BETWEEN intervalo_ini AND intervalo_fim";

                $descricao_nivel = DB::select($sql);


                DB::commit();

                return response()->json($descricao_nivel);

            }
        }

        catch (\Exception $e)
        {
            DB::rollback();
            echo $e; //fallha ao salvar
        }

    }

    //recupera os pontos fortes e fracos de acordo com as resposta no diagnostico
    public function consultarPontosFortesFracos(Request $request)
    {

        $montar_id_respostas = '';

        foreach ($request->array_respostas as $key=>$value){        //salva as perguntas e respostas

            if ($montar_id_respostas == ''){
                $montar_id_respostas = $value;
            }else{
                $montar_id_respostas .= ','.$value;
            }
        }

        $montar_id_respostas = '('.$montar_id_respostas.')';

        //recupera a descrição do nível de maturidade de acordo com o calculado anteriormente
        $sql = "SELECT b.descricao as atividade, c.nota as nota_resposta,
                              ( select DISTINCT maximo_pontos
                                          from tb_parametros, tb_modelo_header
                                         where tb_modelo_header.id = a.id_modelo_header_fk
                                           and tb_parametros.id = tb_modelo_header.id_parametro_fk) as ponto_maximo
                        FROM tb_modelo_body a, tb_atividades b, tb_respostas c
                        WHERE a.id_modelo_header_fk = " .$request->id_modelo_header_fk. "
                        AND   a.id_respostas_fk in " .$montar_id_respostas. "
                        AND   b.id = a.id_atividades_fk
                        AND   c.id = a.id_respostas_fk
                        ORDER BY c.nota DESC";

        $pontosfortefracos = DB::select($sql);

        return response()->json($pontosfortefracos);

    }


    //recupera o modelo de perguntas e respostas a ser usado para a sub área selecionada para o diagnostico
    public function resultModeloArea(Request $request)
    {

        $sql = "SELECT a.id as id_modelo_header, b.id as id_modelo_body, c.id as id_pergunta, c.descricao as pergunta,
                        d.id as id_resposta, d.descricao as resposta, e.id as id_atividade, e.descricao as atividade, d.nota, a.id_parametro_fk
                    FROM tb_modelo_header a, tb_modelo_body b, tb_perguntas c, tb_respostas d, tb_atividades e
                    WHERE a.id_subarea_fk = ".$request->id_subarea_fk."
                      and a.ativo = 'Sim'
                      and b.id_modelo_header_fk = a.id
                      and c.id = b.id_pergunta_fk
                      and d.id = b.id_resposta_fk
                      and e.id = b.id_atividade_fk
                    ORDER BY c.descricao, d.nota";


        $modelos = DB::select($sql);

        return response()->json($modelos);

    }


}
