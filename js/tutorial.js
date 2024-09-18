

$("#abrir_chat").click(function () {
    if ($('#card-receita').length > 0) {
        const driver = window.driver.js.driver;
        const driverObj = driver({
            showProgress: true,
            steps: [{
                element: '#card-receita',
                popover: {
                    title: 'Receita',
                    description: 'Registro de todas as transações financeiras do tipo receita por período. ',
                    side: "left",
                    align: 'start'
                },

            }, {
                element: '#card-despesa',
                popover: {
                    title: 'Despesas',
                    description: 'Registro de todas as transações financeiras do tipo despesa por período. ',
                    side: "left",
                    align: 'start'
                },

            },
            {
                element: '#card-caixa-diario',
                popover: {
                    title: 'Caixa Diário',
                    description: 'O Caixa Diário é composto pelas formas de pagamento associadas à conta financeira da Caixa, somado ao saldo inicial do período anterior e ao saldo atual. ',
                    side: "left",
                    align: 'start'
                },

            },
            {
                element: '#vendas',
                popover: {
                    title: 'Vendas',
                    description: 'Todas as vendas registradas no período. ',
                    side: "left",
                    align: 'start'
                },

            },
            {
                element: '#lucro-periodo',
                popover: {
                    title: 'Lucro por Périodo',
                    description: 'O cálculo do lucro por período é determinado pela soma da receita total, subtraindo as despesas correspondentes, resultando no lucro líquido obtido durante o período analisado.',
                    side: "left",
                    align: 'start'
                },

            }, {
                element: '#analise-vendas',
                popover: {
                    title: 'Análise de Vendas',
                    description: 'Na análise de vendas por período, examinamos o desempenho das transações comerciais ao longo de um intervalo específico de tempo. Isso envolve a avaliação detalhada das vendas realizadas durante o período, identificando padrões, tendências e fatores que possam influenciar os resultados.',
                    side: "left",
                    align: 'start'
                },

            },
            ],

            // onDestroyStarted is called when the user tries to exit the tour
            onDestroyStarted: () => {
                if (!driverObj.hasNextStep() || confirm("Deseja finalizar?")) {
                    driverObj.destroy();
                }
            },
        });
        driverObj.drive();
    }

    if ($('#card-ajuste-preco').length > 0) {
        const driver = window.driver.js.driver;
        const driverObj = driver({
            showProgress: true,
            steps: [{
                element: '#aba_consultar_ajuste',
                popover: {
                    title: 'Consultar Ajustes de preço',
                    description: 'Verifique todos os registros de ajustes efetuados no sistema.',
                    side: "left",
                    align: 'start'
                },

            }, {
                element: '#aba_ajuste_item',
                popover: {
                    title: 'Ajuste por Item',
                    description: 'Realize ajustes de preço individuais nos produtos.',
                    side: "left",
                    align: 'start'
                },

            },{
                element: '#aba_ajuste_lote',
                popover: {
                    title: 'Ajuste em lote',
                    description: 'Realize ajustes de preço em grupos de produtos.',
                    side: "left",
                    align: 'start'
                },

            },
            ],

            // onDestroyStarted is called when the user tries to exit the tour
            onDestroyStarted: () => {
                if (!driverObj.hasNextStep() || confirm("Deseja finalizar?")) {
                    driverObj.destroy();
                }
            },
        });
        driverObj.drive();
    }


})

