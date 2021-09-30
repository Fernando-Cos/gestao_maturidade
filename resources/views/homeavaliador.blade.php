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
    $usuario = App\Models\tb_usuario::findOrFail(Auth::user()->id);
    $unidade = \App\Models\tb_unidades::findOrFail($usuario->id_unidade_fk);
    $unidadeareas = \App\Models\tb_unidadesareas::where('id_unidade_fk',$unidade->id)->get();

    ?>


    @include('layouts.frontend.cabecalho_unidade')


    <form action="{{route("exibirPerguntas")}}" method="post">

            {{ csrf_field() }}

        <div class="form-group text-center col-sm-12 col-md-12 col-lg-12">


                <input type="hidden" name="id_unidades_fk" value="{{$unidade->id}}">

                <?php

                $gestor = "";

                if ($usuario->id_permissao_fk == \App\Utils\ConstUtil::PERM_GESTOR_UNIDADE_ID){ //gestor unidade, visualiza todas as áreas
                    $gestor = 'unidade';
                }elseif ($usuario->id_permissao_fk == \App\Utils\ConstUtil::PERM_GESTOR_AREA_ID){//gestor da area, visualiza apenas sua área
                    $area_gestor = $usuario->id_area_fk;
                    $gestor = "area";
                }elseif ($usuario->id_permissao_fk == \App\Utils\ConstUtil::PERM_GESTOR_SUBAREA_ID){//gestor de subárea
                    $subarea_gestor = $usuario->id_subarea_fk;
                    $gestor = 'subarea';
                }

                ;
                $area_anterior=0;

                foreach ($unidadeareas as $ua){

                    $estilo = "pointer-events: none;opacity: 0.4;";

                    if($area_anterior!=$ua->id_area_fk){
                        $area = \App\Models\tb_area::findOrFail($ua->id_area_fk);
                        echo '<div class="form-group col-sm-12 col-md-12 col-lg-12 text-left areanome" ><h7>'.strtoupper($area->nome).'</h7></div>';
                    }

                    $subarea = \App\Models\tb_subarea::findOrFail($ua->id_subarea_fk);

                    //habilita a sub área somente se tiver permissão e se o modelo de questões estiver completo para fazer o diagnostico
                    if ((
                            ($gestor=="unidade")  ||
                            ($gestor=="area" && $area_gestor == $ua->id_area_fk) ||
                            ($gestor=="subarea" && $subarea_gestor == $ua->id_subarea_fk)
                        )
                        && (\App\Models\tb_diagnostico_header::modeloCompleto($ua->id_area_fk,$ua->id_subarea_fk)==1)){
                        $estilo = "";//desabilita o ícone

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
            <button class="btn btn-info btn-lg text-center" id="btnFazerDiagnostico" type="submit"><i class="fa fa-question"></i> Diagnóstico</button>

            <a class="btn btn-warning btn-lg" href="{{route('showIndices',$unidade->id)}}"><i class="fas fa-chart-bar"></i>
                    Índices
            </a>

            <a class="btn btn-success btn-lg" href="{{route('showDiagnosticos',array($unidade->id,$gestor,$usuario))}}"><i class="fas fa-check"></i>
                Diagnósticos
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
