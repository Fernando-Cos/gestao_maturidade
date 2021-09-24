


$(document).ready(function() {

    $('.js-autocomplete_').select2({

        // minimumInputLength: 2, // only start searching when the user has input 2 or more characters
        //minimumResultsForSearch: 30, // at least 10 results must be displayed
        width: 'resolve', // need to override the changed default
        allowClear: true,
        /*placeholder: "Selecione",
        theme: "classic"*/

    });

});

function somarDataComDias(data_inicial,qtd_dias) {

    var parts = data_inicial.split("/");
    var data = new Date(parts[2], parts[1] - 1, parts[0]);
    data.setDate(data.getDate() + parseInt(qtd_dias));
    var novaData = data.toLocaleDateString("pt-BR");

    return novaData;

}

function Idade() {

    var hoje = new Date();
    var datanascimento = $('#data_nascimento').val();

    var arrayData =  datanascimento.split("/");

    var retorno = "#ERR#";

    if (arrayData.length == 3) {
        // Decompoem a data em array
        var ano = parseInt( arrayData[2] );
        var mes = parseInt( arrayData[1] );
        var dia = parseInt( arrayData[0] );

        // Valida a data informada
        if ( arrayData[0] > 31 || arrayData[1] > 12 ) {
            return retorno;
        }

        ano = ( ano.length == 2 ) ? ano += 1900 : ano;

        // Subtrai os anos das duas datas
        var idade = ( hoje.getYear()+1900 ) - ano;

        // Subtrai os meses das duas datas
        var meses = ( hoje.getMonth() + 1 ) - mes;

        // Se meses for menor que 0 entao nao cumpriu anos. Se for maior sim ja cumpriu
        idade = ( meses < 0 ) ? idade - 1 : idade;

        meses = ( meses < 0 ) ? meses + 12 : meses;

        retorno = ( idade + " a " + meses + " m" );
    }

    $('#idade').val(retorno);
}

function consultarCep (cep) {

  //Verifica se campo cep possui valor informado.
  if (cep != "") {

    //Expressão regular para validar o CEP.
    var validacep = /^[0-9]{8}$/;

    //Valida o formato do CEP.
    if(validacep.test(cep)) {

      //Preenche os campos com "..." enquanto consulta webservice.
      $("#endereco").val("...");
      $("#numero").val("...");
      $("#complemento").val("...");
      $("#bairro").val("...");
      $("#cidade").val("...");
      $("#uf").val("...");


      //Consulta o webservice viacep.com.br/
      $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

        if (!("erro" in dados)) {
          //Atualiza os campos com os valores da consulta.
          $("#endereco").val(dados.logradouro);
          $("#numero").val('');
          $("#complemento").val('');
          $("#bairro").val(dados.bairro);
          $("#cidade").val(dados.localidade);
          $("#uf").val(dados.uf);
        } //end if.
        else {
          //CEP pesquisado não foi encontrado.
          limpa_campos_endereco();
          alert("CEP não encontrado.");
        }
      });
    } //end if.
    else {
      //cep é inválido.
      limpa_campos_endereco();
      alert("Formato de CEP inválido.");
    }
  } //end if.
  else {
    //cep sem valor, limpa formulário.
    limpa_campos_endereco();
  }

}

//faz a comparação entre emails digitados pelo usuário
function confirmarEmail (email1,email2) {

    if(email1 !== '' && email2 !== '')
    {
      if(email1 !== email2)
      {
        return 0;//emails diferentes
      }else{
        return 1;//emails iguais
      }
    }else{
        return 2;//preencher email
    }
}

function exibirPerguntas(id_subarea){


    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $.ajax({
        url: '/diagnostico/resultModeloArea',
        type: 'POST',
        dataType: "json",
        data: {
            id_subarea_fk:id_subarea,
        },
        success: function(data) {

            html = "<table class='table table-striped' style='font-size: 12px;width: 104%;margin-left:-20px'>";

            var pergunta_anterior = "";
            var valorheader = "";
            var valorParametro = "";
            var qtd_perguntas = 0;
            var indice = 0;

            $.each(data, function (key, item) {


                valorheader = item.id_modelo_header;
                valorParametro = item.id_parametro_fk;


                if (pergunta_anterior != item.id_pergunta) {/*evita repetir a pergunta*/

                    qtd_perguntas = qtd_perguntas + 1;

                    html = html +
                        "     <tr class='info' style='font-weight: bold;'  colspan='3'>" +
                        "       <td></td>" +
                        "       <td style='display: none'><input type='checkbox' class='perguntas' name='pergunta_id'  value='" +item.id_pergunta + "' checked></td>" +
                        "       <td class='text-center descpergunta'>" + item.pergunta + "</td>" +
                        "       <td></td>" +
                        "     </tr>";
                }


                html = html +
                    "     <tr class='warning' style='font-weight: bold;line-height:10px;' colspan='3'>" +
                    "       <td width='1'><input  type='radio' class='respostas' name='resposta_" + qtd_perguntas + "' id='resposta_da_pergunta_" + indice + "'  value=" +item.id_resposta + ">" +
                    "       <label class='btn_opcoes' for='resposta_da_pergunta_" + indice + "'></label></td>" +
                    "       <td style='text-align: left;'>"+ item.resposta +  "</td>" +
                    "       <td><input type='hidden' id='id_atividade' name = 'id_atividade[]' value='" + item.id_atividade +"'></td>" +
                    "     </tr>";


                pergunta_anterior = item.id_pergunta;
                indice = indice+1;

            });


            html = html + '<tr>' +
                '<td style="background-color:white"><input type="hidden" id="id_modelo_header_fk" name="id_modelo_header_fk"  value="' + valorheader + '">' +
                '<td style="background-color:white"><input type="hidden" id="id_parametro_fk" name="id_parametro_fk"  value="' + valorParametro + '">' +
                '<input type="hidden" id="qtd_perguntas" name="qtd_perguntas" value="' + qtd_perguntas + '"></td>' +
                '</tr>' +
                '</table>';


           $('#divPerguntas').html("");
           $('#divPerguntas').append(html);

        }
    });
}

function exibirGrafico(resultado,exibirNivel) {

    var descricao_nivel = '';
    var valor_nivel = 0;
    var valor_melhorar = 0;

    $.each(resultado, function (value, item) {

        descricao_nivel = item.descricao;
        valor_nivel = item.nivel_maturidade;
        valor_melhorar = item.valor_melhorar

        //conteudo do canvas do grafico <canvas id="myChart" width="400" height="400"></canvas>
        var ctx = document.getElementById("myChart").getContext("2d");
        ctx.height=300;

        const data = {
            labels: [
                'Nível a Melhorar',
                'Nível de Maturidade Atual'
            ],
            datasets: [{
                label: 'My First Dataset',
                data: [valor_melhorar,valor_nivel],
                backgroundColor: [
                    '#dc3545',
                    '#28a745',
                ]
            }]
        };

        const config = {
            type: 'doughnut',
            data,
            options: {
                responsive: true,
                maintainAspectRatio: false, //considerar o tamanho do grafico da div
                legend: {
                    position: 'right',
                    labels: {
                        fontSize:15,
                    }
                },
                plugins: {
                    datalabels: {
                        formatter: (value, ctx) => {
                            return value+"%";
                        },
                        color: '#fff',
                        font: {
                            weight: 'bold',
                            size: 18,
                        }
                    }
                }
            },

        };

        var myChart = new Chart(ctx,
            config
        )

        //FIM EXIBIR O GRÁFICO


        //EXIBIR DESCRIÇÃO DO NÍVEL
        if (exibirNivel) {
            var html = '<span class="text-justify"><h5>' + descricao_nivel + '</h5></span>';
            $('#divResultadoDescNivel').append(html);
            $('#divResultadoDescNivel').show();
        }
        //FIM

        return false;
    });

}

function exibirPontosFortesFracos(perguntas,radioResposta,id_modelo_header) {

    $.ajax({
        url: "/diagnostico/consultarPontosFortesFracos",
        type: 'POST',
        dataType: "json",
        data: {
            array_respostas: radioResposta,
            array_perguntas: perguntas,
            id_modelo_header_fk:id_modelo_header,
        },
        success: function (pontos) {

            var pontosfracos = '';
            var pontosfortes = '';

            $.each(pontos, function (value, item) {

                if (item.nota_resposta < item.ponto_maximo){
                    pontosfracos = pontosfracos + '* ' + item.atividade + '<br>';
                }else{
                    pontosfortes = pontosfortes + '* ' + item.atividade + '<br>';
                }

            });

            if (pontosfortes!='') {
                var html = '<span class="text-justify"><span style="font-weight:bold;text-align: center"> PONTOS FORTES</span><br><br><p>' + pontosfortes + '</p></span>';
                $('#divPontosFortes').append(html);
                $('#divPontosFortes').show();
            }else{
                var html = '<span class="text-justify"><span style="font-weight:bold;text-align: center"> PONTOS FORTES</span><br><br><p>-</p></span>';
                $('#divPontosFortes').append(html);
                $('#divPontosFortes').show();
            }

            if (pontosfracos!='') {
                var html2 = '<span class="text-justify"><span style="font-weight:bold;text-align: center"> PONTOS A MELHORAR</span><br><br><p>' + pontosfracos + '</p></span>';
                $('#divPontosFracos').append(html2);
                $('#divPontosFracos').show();
            }else{
                var html2 = '<span class="text-justify"><span style="font-weight:bold;text-align: center"> PONTOS A MELHORAR</span><br><br><p>-</p></span>';
                $('#divPontosFracos').append(html2);
                $('#divPontosFracos').show();
            }

        }
    });

}

function graficoBarra(chart, label, dados, titulo) {


    const labels = [
        label
    ];
    const data = {
        labels: label,
        datasets: [{
            label: 'Níveis de Maturidade (%)',
            backgroundColor: [
                '#d1ecf1',
                '#5bc0de',
                '#36a2eb',
            ],
            borderColor: "#0F689E",
            size: 20,
            borderWidth: 1,
            fillColor: convertHexToRGBA("EBC50C",20),
            strokeColor: convertHexToRGBA("EBC50C",20),
            pointColor: convertHexToRGBA("EBC50C",20),
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
            pointHighlightStroke: convertHexToRGBA("EBC50C",20),
            pointStyle: 'rectRot',
            data: dados,
        }]
    };

    const config = {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            title: {
                display: true,
                fontColor: '#0F689E',
                text: titulo,
                font: {
                    size: 30
                },

            },
            layout: {
                padding: {
                    left: 50,
                    right: 10,
                    top: 20,
                    bottom: 0
                }
            },
            scales: {
                yAxes: [{
                    display:1,
                    ticks: {//valor do eixo y
                        display: false,
                        beginAtZero: true
                    },
                    gridLines: {
                        zeroLineColor: "transparent",
                        drawTicks: false,
                        display: false,
                        drawBorder: false
                    }
                }],
            },
            plugins: {
                legend: {
                    labels: {
                        // This more specific font property overrides the global property
                        usePointStyle: true,
                        font: {
                            size: 16
                        }
                    }
                }
            }
        }
    };

    //conteudo do canvas do grafico <canvas id="myChart"></canvas>
    var ctx = document.getElementById(chart);
    ctx.height=80;

    Chart.defaults.global.defaultFontColor = 'black';
    Chart.defaults.global.defaultFontSize = 15;

    var myChart = new Chart(ctx,
        config
    )


    function convertHexToRGBA(n, t) {
        return n = n.replace("#", ""), r = parseInt(n.substring(0, 2), 16), g = parseInt(n.substring(2, 4), 16), b = parseInt(n.substring(4, 6), 16), result = "rgba(" + r + "," + g + "," + b + "," + t / 100 + ")"
    }

}

function graficoPolar(chart, label, dados, titulo) {


    const labels = [
        label
    ];
    const data = {
        labels: label,
        datasets: [{
            label: 'ANÁLISE POR ÁREA',
            backgroundColor: [
                'rgba(75, 192, 192, 0.2)',
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 205, 86, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(201, 203, 207, 0.2)'
            ],
            data: dados,
        }]
    };

    const config = {
        type: 'polarArea',
        data: data,
        options: {
            responsive: true,
            title: {
                display: true,
                fontColor: '#0F689E',
                text: titulo,
                font: {
                    size: 30
                },

            },
        }
    };

    Chart.defaults.global.defaultFontColor = 'brown';
    Chart.defaults.global.defaultFontSize = 15;

    //conteudo do canvas do grafico <canvas id="myChart"></canvas>
    var ctx = document.getElementById(chart);
    ctx.height=100;

    Chart.defaults.global.defaultFontColor = 'brown';
    Chart.defaults.global.defaultFontSize = 15;

    var myChart = new Chart(ctx,
        config
    )

}

