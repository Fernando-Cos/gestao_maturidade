@extends('layouts.frontend.pagina')

@section('title', \App\Utils\ConstUtil::NOME_SISTEMA)

@section('content')


<?php

$dados_unidade = array();
$label_unidade = array();

$unidade = \App\Models\tb_unidades::findOrFail($id_unidade);

$titulo_unidade = mb_strtoupper($unidade->nome, 'UTF-8');

/*
 *  recupera os 3 últimos índices da unidade
 * *
 */

$sql = " SELECT c.valor_nivel_unidade, DATE_FORMAT(c.created_at,'%d/%m/%Y') as data
                                           FROM tb_nivel_unidade c
                                           INNER JOIN (
                                                        SELECT id
                                                            FROM tb_nivel_unidade
                                                            WHERE id_unidade_fk = ".$id_unidade."
                                                              ORDER BY created_at DESC LIMIT 3) as id2
                                         ON c.id = id2.id
                                         ORDER BY c.created_at";

$niveis_unidade = DB::select($sql);

foreach($niveis_unidade as $i){
    array_push($dados_unidade,$i->valor_nivel_unidade);
    array_push($label_unidade,$i->data);
}

?>


<div class="form-group col-sm-12 col-md-12 col-lg-12" style="width: 100%;background-color: #F2F2F2">
    <h5 class="text-center font-weight-bold" style="padding-top: 10px;color: #539b27;">ÍNDICE DA UNIDADE</h5>
    <canvas id="myChart"></canvas>
</div>

<script language="javascript" type="text/javascript">
    exibirGraficoBarra(<?php print json_encode('myChart');?>,<?php print json_encode($label_unidade); ?>,<?php print json_encode($dados_unidade); ?>,<?php print json_encode($titulo_unidade); ?>);
</script>



<?php
/* recupera o último índice de cada área para fazer a comparação entre elas (gráfico Polar)*/

$areas = \App\Models\tb_unidadesareas::where('id_unidade_fk',$id_unidade)->distinct()->select('id_area_fk')->get(); //areas da unidade

$nomesareas = '';
$ultimo_indice = array();
$label = array();

foreach ( $areas as  $a){//recupera os últimos índices de cada área

    $sql = " SELECT b.nome as area, a.valor_nivel_area
                FROM tb_nivel_area a, tb_area b
                WHERE a.id_unidade_fk = " .$id_unidade. "
                AND a.id_area_fk = ". $a->id_area_fk ."
                AND b.id = a.id_area_fk
                ORDER BY a.id DESC LIMIT 1";

    $nivel_area = DB::select($sql);

    foreach($nivel_area as $i){
        array_push($ultimo_indice,$i->valor_nivel_area);
        array_push($label,$i->area);
    }

    if (count($nivel_area) == 0){//exibe às áreas sem diagnostico
        $area = \App\Models\tb_area::findOrFail($a->id_area_fk);
        $nomesareas = $nomesareas .' => ' . $area->nome .'<br>';
    }

}

?>

<div class="form-group col-sm-12 col-md-12 col-lg-12" style="width: 100%;background-color: #F2F2F2">
    <canvas id={{"myChartArea"}}></canvas><br>
</div>

<script language="javascript" type="text/javascript">
    exibirGraficoPolar(<?php print json_encode("myChartArea");?>,<?php print json_encode($label); ?>,<?php print json_encode($ultimo_indice); ?>,<?php print json_encode('ÍNDICE ATUAL POR ÁREA (%)'); ?>);
</script>



<!-- recupera os 3 últimos índices das áreas -->
<?php

    foreach ( $areas as  $a){

        $dados_area = array();
        $label_area = array();

        $sql = " SELECT d.id_area_fk, d.valor_nivel_area, DATE_FORMAT(d.created_at,'%d/%m/%Y') as data
                  FROM tb_nivel_area d
                  INNER JOIN (SELECT c.id
                          FROM tb_nivel_area c
                         WHERE c.id_unidade_fk = " .$id_unidade. "
                           AND c.id_area_fk = ". $a->id_area_fk ."
                      ORDER BY c.created_at DESC LIMIT 3) as id2
                   ON d.id = id2.id
                  ORDER BY d.created_at";

        $niveis_area = DB::select($sql);

        foreach($niveis_area as $i){
           array_push($dados_area,$i->valor_nivel_area);
           array_push($label_area,$i->data);
        }

        $areanome = \App\Models\tb_area::findOrFail($a->id_area_fk);
        $titulo_area = $areanome->nome;
        ?>


            <div class="form-group col-sm-12 col-md-12 col-lg-12 text-right" style="width: 100%;background-color: #F2F2F2">
                <a href="{{url('/diagnostico/showIndicesSubAreas/'.$id_unidade.'/'.$a->id_area_fk)}}" ><i class="fas fa-chart-bar" style="color: #3abc0b" title="Detalhes" ></i></a>
                <canvas id={{"myChart".$a->id_area_fk}}></canvas>
            </div>

            <script language="javascript" type="text/javascript">
                exibirGraficoBarra(<?php print json_encode("myChart".$a->id_area_fk);?>,<?php print json_encode($label_area); ?>,<?php print json_encode($dados_area); ?>,<?php print json_encode($titulo_area); ?>);
            </script>


<?php }

    if ($nomesareas!=''){//quando faltar áreas para o diagnostico, exibe quais estão faltando
        echo '<br>';
        echo '<div class="form-group col-sm-12 col-md-12 col-lg-12 text-center" id="divInformacao" >';
            echo '<h5> Para que o Ínice da Unidade seja mais <span class="font-weight-bold">Exato</span>, a(s) Área(s) a seguir, ainda não realizou(aram) o diagnóstico:</h5>';
            echo "<span><h5>".$nomesareas."</span></h5>";
            echo '</div>';
    }

?>

<div class="form-group col-sm-12 col-md-12 col-lg-12 text-center" style="padding:10px;">
    <a class="btn btn-info btn-lg" style="background-color:#293259;border-color:#293259;"
       href="{{url('homeavaliador')}}">
        HOME
    </a>
</div>



@endsection
