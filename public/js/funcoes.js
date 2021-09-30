


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

            html = "<table class='table' style='font-size: 12px;width: 104%;margin-left:-20px'>";

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
                        "     <tr class='info' style='background-color:#F0FFF0;font-weight: bold;'  colspan='3'>" +
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
                    "       <td class='descresposta' style='text-align: left;'>"+ item.resposta +  "</td>" +
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

function exibirGraficoPie(resultado,exibirNivel) {


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
                    position: 'top',
                    labels: {
                        fontSize:15,
                        color:'#293259',
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
            alert(descricao_nivel);
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

function exibirGraficoBarra(chart, label, dados, titulo) {


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
            pointStrokeColor: "#fff",
            pointHighlightFill: "#fff",
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
                    size: 40
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

}

function exibirGraficoPolar(chart, label, dados, titulo) {


    const labels = [
        label
    ];
    const data = {
        labels: label,
        datasets: [{
            label: 'ANÁLISE POR ÁREA',
            backgroundColor: [
                '#fe819d',
                '#ffce56',
                '#36a2eb',
                '#4bc0c0',
                '#c0c2d0',
                '#ebe0ff',
                '#CB20F6'
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

