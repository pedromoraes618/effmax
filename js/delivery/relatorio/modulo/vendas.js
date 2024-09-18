var chart1 = document.getElementById('myChart-1').getContext('2d'); //lucro

var myChart = new Chart(chart1, {
    type: 'bar',
    data: {
        labels: [
            'Total de Vendas',
            'Consumo no Local',
            'Retirada',
            'Entrega',
        ],
        datasets: [{
            type: 'bar',
            label: 'Quantidade',
            data: valor_relatorio_vendas_dash,
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: [
                'rgba(255, 99, 132, 0.2)',
                'rgba(255, 159, 64, 0.2)',
                'rgba(255, 205, 86, 0.2)',
                'rgba(75, 192, 192, 0.2)',
            
              ],
        },]
    },
    options: {
        
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