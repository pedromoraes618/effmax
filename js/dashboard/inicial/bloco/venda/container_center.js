

    var lucro = [];

    // Calculando o lucro
    // for (var i = 0; i < valor_minhas_vendas.length; i++) {
    //     lucro.push(receita[i] - despesa[i]);
    // }

    var chart1 = document.getElementById('myChart-1').getContext('2d'); //lucro
    // var chart2 = document.getElementById('myChart-2').getContext('2d'); //faturamento nota fiscal
    // var chart3 = document.getElementById('myChart-3').getContext('2d'); //comparação receita entre os meses em diferentes anos
    // var chart4 = document.getElementById('myChart-4').getContext('2d'); //comparação despesa entre os meses em diferentes anos

    var myChart = new Chart(chart1, {
        type: 'bar',
        data: {
            labels: ["Jan", "fev", "mar", "abr", "mai", "jun", 'jul', 'ago', 'set', 'out', 'nov', 'dez'],

            datasets: [{
                label: 'Total R$ ',
                data: valor_minhas_vendas,
                type: 'line',
                borderColor: 'green',
                fill: false
            }, {
                label: 'Quantidade',
                data: qtd_minhas_vendas,
                backgroundColor: 'blue'
            },  ]
        },
        options: {
            locale: 'br-BR',
            
            elements: {
                line: {
                    tension: 0
                }
            },
            tooltips: {
               
                backgroundColor: 'rgba(255, 255, 255, 1)',
                bodyFontColor: 'rgba(0, 0, 0, 1)',
                titleFontColor: 'rgba(0, 0, 0, 1)',
                titleFontSize: 20,
                caretPadding: 10,
                xPadding: 5,
                yPadding: 15,

                caretSize: 10,
                titleFontStyle: 'bold',
            },
           
        }
    });

  