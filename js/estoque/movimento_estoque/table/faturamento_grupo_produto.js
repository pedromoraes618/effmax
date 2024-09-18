

var chart = document.getElementById('detalhado_por_grupo_prd').getContext('2d'); //lucro
var myChart = new Chart(chart, {
    type: 'bar',
    data: {
        labels: label_faturamento_dash_grupo_prd,

        datasets: [{
            label: 'Média',
            data: media_faturamento_dash_grupo_prd,
            type: 'line',
            borderColor: 'green',
            fill: false
        }, {
            label: 'Total R$',
            data: valor_faturamento_dash_grupo_prd,
            borderColor: 'green',
            backgroundColor: 'blue'

        },]
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
