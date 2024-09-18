var chart2 = document.getElementById('myChart-2').getContext('2d'); //lucro

var myChart = new Chart(chart2, {
    type: 'bar',
    data: {
        labels: descricao_produtos_vendidos,
        datasets: [{
            type: 'bar',
            label: 'Quantidade',
            data: qtd_produtos_vendidos,
            fill: true,
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 205, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(153, 102, 255, 0.2)',
                'rgba(201, 203, 207, 0.2)'
            ],
        },]
    },
    options: {
        scales: {
            y: {
                grid: {
                  offset: false
                }
            }
        },
        indexAxis: 'y',
        locale: 'br-BR',
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
        plugins: {
            legend: {
                display: false,
            }
        },
        
    }
});