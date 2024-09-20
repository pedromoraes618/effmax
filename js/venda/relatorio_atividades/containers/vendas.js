var myChart_1 = document.getElementById('myChart-1').getContext('2d');
var chart = new Chart(myChart_1, {
    type: 'bar',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Período Atual',
            data: vendas_presente,
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            currency: 'BRL'
        }, {
            label: 'Período Anterior',
            data: vendas_passado,
            backgroundColor: 'rgba(201, 203, 207, 0.7)',
            currency: 'BRL'
        }]
    },
    options: {
        scales: {
            y: {
                ticks: {
                    callback: function (value) {
                        return 'R$ ' + value.toLocaleString('pt-BR');
                    }
                }
            }
        }
    }
});


// Renderizar o gráfico
var myChart_2 = document.getElementById('myChart-2').getContext('2d');
var chart = new Chart(myChart_2, {
    type: 'pie',
    data: {
        labels: label_forma_pagamento_venda,  // Corrigido: vírgula estava faltando
        datasets: [{
            data: valor_vendas_forma_pagamento,
            // Dados fictícios
            borderWidth: 1
        }]
    },
    options: {
        plugins: {
            tooltip: {
                callbacks: {
                    label: function (context) {
                        // Exibe valores no tooltip com formatação de moeda
                        let value = context.raw;
                        return 'R$ ' + value.toLocaleString('pt-BR');
                    }
                }
            }
        }
    }
});