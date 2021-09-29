@extends('layouts.frontend.pagina')

@section('title', \App\Utils\ConstUtil::NOME_SISTEMA)

@section('content')


<?php

$unidade = \App\Models\tb_unidades::findOrFail($id_unidade);
$nomesSubareas = '';
$count_subareas=0;
$area = \App\Models\tb_area::findOrFail($id_area);
$subareas = \App\Models\tb_unidadesareas::where('id_unidade_fk',$id_unidade)->where('id_area_fk',$id_area)->get(); //areas da unidade
?>

<div class="form-group col-sm-12 col-md-12 col-lg-12 text-center" id="divArea">
    @include('layouts.frontend.cabecalho_unidade')
    <h5 style="color: #1d643b;font-weight: bold">{{strtoupper($area->nome)}}</h5>
</div>

<?php

/* recupera o último índice de cada sub área para fazer a comparação entre elas (gráfico Polar)*/
foreach($subareas as $a){

    $subareas = \App\Models\tb_subarea::findOrFail($a->id_subarea_fk);

?>

    <div class="form-group text-center" style="font-weight: bold;">


        <?php

        $dados = array();
        $label = array();

        //exibirá os três últimos indíces de cada sub área

        $sql = "SELECT c.nivel_maturidade, DATE_FORMAT(c.created_at,'%d/%m/%Y') as data
                                                   FROM tb_diagnostico_header c
                                                   INNER JOIN (
                                                                SELECT  a.id
                                                                FROM tb_diagnostico_header a, tb_modelo_header b
                                                                WHERE a.id_unidade_fk = ".$unidade->id."
                                                                  AND b.id = a.id_modelo_header_fk
                                                                  AND b.id_subarea_fk = ".$subareas->id."
                                                                ORDER BY a.id DESC LIMIT 3) as id2
                                                 ON c.id = id2.id
                                                 ORDER BY c.id";

        $resultado = DB::select($sql);

        foreach($resultado as $i){
            array_push($dados,$i->nivel_maturidade);
            array_push($label,$i->data);
        }

        if (count($resultado) > 0){//exibe somente os gráficos com diagnostico
            $count_subareas = $count_subareas + 1; //conta quantas sub áreas já fizeram o diagnóstico para saber se ainda falta algum que não fez
        ?>
            <div class="form-group text-center corFundoGrafico" style="padding: 10px">
                <img src="{{ asset('storage/imagens/'.$subareas->imagem)}}" width="50" height="50" >
                <h5>{{strtoupper($subareas->nome)}}</h5>
                <canvas id="{{'myChart'.$subareas->id}}"></canvas>
            </div>

            <script language="javascript" type="text/javascript">
                exibirGraficoBarra(<?php print json_encode('myChart'.$subareas->id);?>,<?php print json_encode($label); ?>,<?php print json_encode($dados); ?>,<?php print json_encode(''); ?>);
            </script>
        <?php

        }else{//armazena as sub áreas que ainda não fizeram o diagnóstico
            $nomesSubareas = $nomesSubareas .' => ' . $subareas->nome .'<br>';
        }
}

    if ($nomesSubareas!=''){//quando faltar sub áreas para o diagnostico, exibe quais estão faltando
        echo '<br>';
        echo '<div class="form-group col-sm-12 col-md-12 col-lg-12 text-center" id="divInformacao" >';
        echo '<h5> A(s) Sub Área(s) a seguir, ainda não realizou(aram) o diagnóstico:</h5>';
        echo "<span><h5>".$nomesSubareas."</span></h5>";
        echo '</div>';
     }


?>

<div class="form-group col-sm-12 col-md-12 col-lg-12 text-center" style="padding:10px;">
    <a class="btn btn-info btn-lg" style="background-color:#293259;border-color:#293259;"
       href="{{url('homeavaliador')}}">
        Home
    </a>
    <a class="btn btn-default btn-lg" href="{{route('showIndices',$unidade->id)}}">
        Voltar
    </a>
</div>



@endsection
