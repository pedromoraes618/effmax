var lucro = [];

// Calculando o lucro
for (var i = 0; i < receita.length; i++) {
    lucro.push(receita[i] - despesa[i]);
}

var chart1 = document.getElementById('myChart-1').getContext('2d'); //lucro
var chart2 = document.getElementById('myChart-2').getContext('2d'); //faturamento nota fiscal
var chart3 = document.getElementById('myChart-3').getContext('2d'); //comparação receita entre os meses em diferentes anos
var chart4 = document.getElementById('myChart-4').getContext('2d'); //comparação despesa entre os meses em diferentes anos

var myChart = new Chart(chart1, {
    type: 'bar',
    data: {
        labels: ["Jan", "fev", "mar", "abr", "mai", "jun", 'jul', 'ago', 'set', 'out', 'nov', 'dez'],

        datasets: [{
            label: 'Lucro R$',
            data: lucro,
            type: 'line',
            borderColor: 'green',
            fill: false
        }, {
            label: 'Receita R$',
            data: receita,
            backgroundColor: 'blue'
        }, {
            label: 'Despesa R$',
            data: despesa,
            backgroundColor: 'red'
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

var myChart = new Chart(chart2, {
    type: 'bar',
    data: {
        labels: ["Jan", "fev", "mar", "abr", "mai", "jun", 'jul', 'ago', 'set', 'out', 'nov', 'dez'],
        datasets: [
            {
                label: 'Valor Vendido R$',
                data: valor_anul_vendas,
                type: 'line',
                borderColor: 'green',
                fill: false
            },
            {
                label: 'Quantidade vendas',
                data: quantidade_anul_vendas,
                backgroundColor: 'blue',
                fill: false
            },

        ]
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


var myChart = new Chart(chart3, {
    type: 'line',
    data: {
        labels: ["Jan", "fev", "mar", "abr", "mai", "jun", 'jul', 'ago', 'set', 'out', 'nov', 'dez'],
        datasets: [{
            label: ano_atual + ' R$',
            data: receita_anual_atual,
            type: 'line',
            borderColor: 'blue',
            fill: false
        }, {
            label: ano_anterior + ' R$',
            data: receita_anual_anterior,
            type: 'line',
            borderColor: 'dark',
            fill: false
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

var despesa_ano_atual = [5000, 2000, 18000.52, 1000, 5000, 5000, 7000, 2000, 3000, 15000, 20000, 7000];
var despesa_ano_anterior = [1000, 3000, 10000.52, 11000, 3000, 8000, 9000, 2000, 2000, 12000, 10000, 5000];
var myChart = new Chart(chart4, {
    type: 'line',
    data: {
        labels: ["Jan", "fev", "mar", "abr", "mai", "jun", 'jul', 'ago', 'set', 'out', 'nov', 'dez'],
        datasets: [{
            label: ano_atual + ' R$',
            data: despesa_anual_atual,
            type: 'line',
            borderColor: 'blue',
            fill: false
        }, {
            label: ano_anterior + ' R$',
            data: despesa_anual_anterior,
            type: 'line',
            borderColor: 'dark',
            fill: false
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
