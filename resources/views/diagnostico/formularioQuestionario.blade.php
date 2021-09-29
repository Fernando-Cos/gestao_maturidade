@extends('layouts.frontend.pagina')

@section('title', \App\Utils\ConstUtil::NOME_SISTEMA)

@section('content')


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
        background-image: -webkit-linear-gradient(top, #16731C, #16731C);
        background-image: -moz-linear-gradient(top, #16731C, #16731C);
        background-image: -ms-linear-gradient(top, #16731C, #16731C);
        background-image: -o-linear-gradient(top, #16731C, #16731C);
        background-image: -webkit-gradient(to bottom, #16731C, #16731C);
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
        background-color: #f8f9fa;
    }

    </style>



    <?php

    $usuario = \App\Models\tb_usuario::findOrFail(Auth()->user()->id);
    $unidade = \App\Models\tb_unidades::findOrFail($usuario->id_unidade_fk);
    $subareas = \App\Models\tb_subarea::findOrFail($subarea);
    $area = \App\Models\tb_area::findOrFail($subareas->id_area_fk);
    $parametros = \App\Models\tb_parametros::parametrosAtuais();

    ?>

       <div class="form-group col-sm-12 col-md-12 col-lg-12 text-center font-weight-bold" style="color: #16731C">
           <h4>DIAGNÓSTICO</h4>
       </div>


       <div class="form-group col-sm-12 col-md-12 col-lg-12 text-center" id="divArea">
           @include('layouts.frontend.cabecalho_unidade')
           <h5>{{strtoupper($area->nome)}}</h5>
           <img src="{{ asset('storage/imagens/'.$subareas->imagem)}}" width="40" height="40" >
           <h5>{{strtoupper($subareas->nome)}}</h5>
        </div>

        <div class="form-group col-sm-12 col-md-12 col-lg-12 text-center">

            <form class="form-group col-sm-12 col-md-12 col-lg-12"   id="formQuestionario">

                {{ csrf_field() }}

                <input type="hidden" name="id_usuario_fk" id="id_usuario_fk" value="{{Auth::user()->id}}">
                <input type="hidden" name="id_subareas_fk" id="id_subareas_fk" value="{{$subareas->id}}">
                <input type="hidden" name="id_unidade_fk" id="id_unidade_fk" value="{{$unidade->id}}">
                <input type="hidden" name="id_area_fk" id="id_area_fk" value="{{$subareas->id_area_fk}}">

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


                    <!-- EXIBE O GRÁFICO DO NÍVEL -->
                    <div class="form-group col-sm-12 col-md-12 col-lg-12" style="height: 350px">
                        <canvas id="myChart"></canvas>
                    </div>

                    <div class="form-group col-sm-12 col-md-12 col-lg-12"><br></div>

                    <!-- EXIBE A DESCRIÇÃO DO NÍVEL -->
                    <div class="form-group col-sm-12 col-md-12 col-lg-12" id="divResultadoDescNivel"></div>

                    <div class="form-group col-sm-12 col-md-12 col-lg-12"><br></div>

                    <!-- EXIBE OS PONTOS FORTES -->
                    <div class="form-group pontos" id="divPontosFortes"></div>

                    <div class="form-group col-sm-12 col-md-12 col-lg-12"><br></div>

                    <!-- EXIBE OS PONTOS FRACOS -->
                    <div class="form-group pontos" id="divPontosFracos"></div>

        </div>

        <div class="form-group form-footer text-center" id="divHome">
           <div>
               <a class="btn btn-info btn-lg" style="background-color:#293259;border-color:#293259;"
                  href="{{route('homeavaliador')}}">
                   Home
               </a>
           </div>
       </div>

    <script type="text/javascript">

        $(document).ready(function () {

            $(document).on("keydown", disableF5);

            $('#divBotoes').hide();
            $('#divHome').hide();
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
                $(this).prop("disabled",true);
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
                        id_area_fk:$("#id_area_fk").val(),
                    },
                    success: function(data) {

                        $.each(data, function (key, item) {

                            $("#id_parametro_fk").val(item.id_parametro_fk);
                            $('#divPerguntas').append('');
                            $('#divPerguntas').hide();
                            $('#divBotoes').hide();

                            //EXIBE O GRÁFICO E A DESCRIÇÃO DO NÍVEL
                            if (item.id_parametro_fk != 0) {
                                exibirGraficoPie(data, true);
                                exibirPontosFortesFracos(checkPerguntas, radioResposta, $("#id_modelo_header_fk").val());
                                $('#divHome').show();
                            } else {
                                alert(item.descricao);
                                location.reload(true);
                            }

                        });

                    }
                });

           }

        });

    </script>


@endsection
