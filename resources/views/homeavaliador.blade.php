@extends('layouts.frontend.pagina')

@section('title', \App\Utils\ConstUtil::NOME_SISTEMA)

@section('content')


    <style>


        input[type="radio"] {
            visibility: hidden;
        }

        .areanome{
            margin-bottom:30px;
            color: gray;
            font-weight: bold;
            clear: left;
            margin-top: 50px;
        }

        .subareanome{
            color: #293259;
            font-weight: bold;
        }

        #btnFazerDiagnostico{
            border-radius: 4px;
            background-color: #293259;
            border-color: #293259;
        }

        .divImagem {
            border-radius: 5px;
            border-style: solid;
            border-color: #bee5eb;
            padding: 5px;
            background-color: #bee5eb;

        }

   </style>

    <?php

    use Illuminate\Support\Facades\Auth;
    $usuario = App\Models\User::find(Auth::user()->id);
    $unidade = \App\Models\tb_unidades::findOrFail($usuario->id_unidade_fk);
    $unidadeareas = \App\Models\tb_unidadesareas::where('id_unidade_fk',$unidade->id)->get();

    ?>


    @include('layouts.frontend.cabecalho_unidade')


    <form action="{{route("exibirPerguntas")}}" method="post">

            {{ csrf_field() }}

        <div class="form-group text-center col-sm-12 col-md-12 col-lg-12">


                <input type="hidden" name="id_unidades_fk" value="{{$unidade->id}}">

                <?php

                $area_anterior=0;


                foreach ($unidadeareas as $ua){

                    $estilo = "";

                    if($area_anterior!=$ua->id_area_fk){
                        $area = \App\Models\tb_area::findOrFail($ua->id_area_fk);
                        echo '<div class="form-group col-sm-12 col-md-12 col-lg-12 text-left areanome" ><h7>'.strtoupper($area->nome).'</h7></div>';
                    }

                    $subarea = \App\Models\tb_subarea::findOrFail($ua->id_subarea_fk);

                    //habilita a sub área somente se tiver permissão e se o modelo de questões estiver completo para fazer o diagnostico
                   // echo \App\Models\tb_diagnostico_header::modeloCompleto($ua->id_area_fk,$ua->id_subarea_fk).'<br>';

                    if (\App\Models\tb_diagnostico_header::modeloCompleto($ua->id_area_fk,$ua->id_subarea_fk)==0){
                        $estilo = "pointer-events: none;opacity: 0.4;";//desabilita o ícone
                    } ?>

                    <div class="form-group col-sm-2 col-md-2 col-lg-2 text-center imagem" id="divImagem{{$subarea->id}}" style="{{$estilo.';float:left;'}}" >
                        <input type="radio" name="subareas" id="{{'id_subareas_fk_'.$subarea->id}}" class="subareas" value="{{$subarea->id}}" >
                        <div>
                            <label for={{'id_subareas_fk_'.$subarea->id}}>
                                <img src="{{ asset('storage/imagens/'.$subarea->imagem)}}" width="60" height="60" >
                            </label>
                        </div>
                        <div>
                            <label class="align-bottom subareanome" ><h6>{{strtoupper($subarea->nome)}}</h6></label>
                        </div>
                    </div>

                    <?php
                    $area_anterior = $ua->id_area_fk;
                }

                ?>

        </div>


        <div class="form-group col-sm-12 col-md-12 col-lg-12 text-center" style="padding:10px;margin-top:150px;clear: left;">
            <button class="btn btn-info btn-lg text-center" id="btnFazerDiagnostico" type="submit">Fazer Diagnóstio</button>
            <a class="btn btn-success btn-lg"href="">
                Visão Geral dos Índices
            </a>
        </div>

    </form>


    <script type="text/javascript">

        $(document).ready(function () {

            var vMostrar = "";

            $('.subareas').on('click',function () {

                $('.imagem').each(function(index) {
                    $(this).removeClass('divImagem');
                });
                vMostrar = $('input:radio:checked').val();
                $('#divImagem'+vMostrar).addClass('divImagem');

            })

            $("#btnFazerDiagnostico").click(function (e) {

               if (vMostrar == ""){
                    alert('Escolha uma Área para Fazer o Diagnóstico');
                    return false;
                }
            });

        });

    </script>

@endsection
