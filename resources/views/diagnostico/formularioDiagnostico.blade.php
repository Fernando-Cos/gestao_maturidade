@extends('layouts.frontend.pagina')

@section('title', \App\Utils\ConstUtil::NOME_SISTEMA)

@section('content')


   <?php

       $usuario = \App\Models\tb_usuario::findOrFail(Auth()->user()->id);
       $unidade = \App\Models\tb_unidades::findOrFail($usuario->id_unidade_fk);
       $subareas = \App\Models\tb_subarea::findOrFail($subarea);
       $area = \App\Models\tb_area::findOrFail($subareas->id_area_fk);

   ?>

    <style>

    /* não exibe as opções dos inputs radios para as respostas*/
    input[type="radio"] {
       visibility: hidden;
    }

    label {
        display: block;
        border: 4px solid #16731C;
    }

    input[type="radio"]:checked+label {
        border: solid #16731C 1px;
        background: #16731C;
        background-image: -webkit-linear-gradient(top, #16731C, #3D94F6);
        background-image: -moz-linear-gradient(top, #16731C, #3D94F6);
        background-image: -ms-linear-gradient(top, #16731C, #3D94F6);
        background-image: -o-linear-gradient(top, #16731C, #3D94F6);
        background-image: -webkit-gradient(to bottom, #16731C, #3D94F6);
        -webkit-border-radius: 20px;
        -moz-border-radius: 20px;
        border-radius: 20px;
        text-decoration: none;
    }

    #divArea{
        border-radius:5px;
        margin-left:0px;
        color: #16731C;
        font-weight: bold;
        background-color: #F2F2F2;
    }

    </style>

         <!-- FORM DO HEADER -->

       <div class="form-group col-sm-12 col-md-12 col-lg-12 text-center" STYLE="color: #16731C">
           <h4>DIAGNÓSTICO</h4>
       </div>


       <div class="form-group col-sm-12 col-md-12 col-lg-12 text-center" id="divArea">
           @include('layouts.frontend.cabecalho_unidade')
           <h5>{{strtoupper($area->nome)}}</h5>
           <img src="{{ asset('storage/imagens/'.$subareas->imagem)}}" width="30" height="30" >
           <h5>{{strtoupper($subareas->nome)}}</h5>
        </div>

        <div class="form-group col-sm-12 col-md-12 col-lg-12 text-center">

            <form class="form-group col-sm-12 col-md-12 col-lg-12"  id="formDiagnostico">

                {{ csrf_field() }}

                <input type="hidden" name="id_usuario_fk" id="id_usuario_fk" value="{{Auth::user()->id}}">
                <input type="hidden" name="id_subareas_fk" id="id_subareas_fk" value="{{$subareas->id}}">
                <input type="hidden" name="id_unidade_fk" id="id_unidade_fk" value="{{$unidade->id}}">
                <input type="hidden" name="id_parametro_fk" id="id_parametro_fk" value="">

                <!--onde são exibidas as perguntas e respostas!-->
                <div class="form-group" id="divPerguntas"></div>

                <div class="form-group col-sm-12 col-md-12 col-lg-12 form-footer text-center" id="divBotoes">
                       <div>
                          <button class="btn btn-danger btn-lg" id="btnEnviarRespostas">Enviar Respostas</button>
                            <a class="btn btn-default btn-lg"
                               href="{{route('homeavaliador')}}">Voltar
                            </a>
                       </div>
                 </div>

            </form>
        </div>

        <div class="form-group col-sm-12 col-md-12 col-lg-12" >

            <div class="form-group col-sm-12 col-md-12 col-lg-12" style="height: 400px">
                <canvas id="myChart"></canvas>
            </div>

            <div class="form-group col-sm-12 col-md-12 col-lg-12"><br></div>

            <div class="form-group col-sm-12 col-md-12 col-lg-12 text-center" id="divResultadoDescNivel"></div>

            <div class="form-group col-sm-12 col-md-12 col-lg-12"><br></div>

            <div class="form-group pontos" id="divPontosFortes"></div>
            <div class="form-group col-sm-12 col-md-12 col-lg-12"><br></div>
            <div class="form-group pontos" id="divPontosFracos"></div>

        </div>

        <div class="form-group form-footer text-center" id="divVisaoGeral">
            <div>
                <a class="btn btn-info btn-lg"
                   href="{{route('atualizarIndices',array('Sim',$area->id))}}"><!--parametros: 1-informa se precisa atualizar os indices(Sim/Não);2-area que fez o diagnostico-->
                    Próximo
                </a>
            </div>
        </div>


    <script type="text/javascript">

        $(document).ready(function () {

            $(document).on("keydown", disableF5);

            $('#divBotoes').hide();
            $('#divVisaoGeral').hide();
            $('#divResultadoDescNivel').hide();
            $('#divPontosFortes').hide();
            $('#divPontosFracos').hide();
            $('#divBotoes').show();

            exibirPerguntas($("#id_subareas_fk").val());//exibe as perguntas e suas respostas

        });

        function disableF5(e) {
            if ((e.which || e.keyCode) == 116 || (e.which || e.keyCode) == 82)
                e.preventDefault();
        };


        $("#btnEnviarRespostas").click(function (e) {

            var qtd_perguntas = $('#qtd_perguntas').val();

            var radioResposta = [];
            /* procura por todos os radios que foram selecionados e guarda as respostas */
            $(".respostas:checked").each(function() {
                radioResposta.push($(this).val());
            });

            var checkPerguntas = [];
            /* procura por todos os checks que foram selecionados e guarda as perguntas */
            $(".perguntas:checked").each(function() {
                checkPerguntas.push($(this).val());
            });

            //checa se a qtd de resposta é igual a de perguntas
            if (radioResposta.length < qtd_perguntas){
                alert('Responda todas as Perguntas!!');
                return false;
            }else{
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ url('diagnostico/salvarRespostas') }}",
                    type: 'POST',
                    dataType: "json",
                    data: {
                        array_respostas:radioResposta,
                        array_perguntas:checkPerguntas,
                        id_modelo_header_fk:$("#id_modelo_header_fk").val(),
                        id_unidade_fk:$("#id_unidade_fk").val(),
                        id_usuario_fk:$("#id_usuario_fk").val(),
                    },
                    success: function(resultado) {

                        $("#id_parametro_fk").val(resultado.id_parametro_fk);
                        $('#divPerguntas').append('');
                        $('#divPerguntas').hide();
                        $('#divBotoes').hide();

                        //EXIBE O GRÁFICO E A DESCRIÇÃO DO NÍVEL
                        exibirGrafico(resultado,true);
                    }
                });


                //EXIBIR OS PONTOS FORTES E FRACOS

                exibirPontosFortesFracos(radioResposta,$("#id_modelo_header_fk").val());

                $('#divVisaoGeral').show();

            }


        });

    </script>


@endsection
