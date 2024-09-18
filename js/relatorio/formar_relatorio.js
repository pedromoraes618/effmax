document.getElementById('openReport').addEventListener('click', function () {
    // Obtendo o conteúdo HTML da tabela e seus estilos
    var tableContainer = document.querySelector('.card-print');
    var tableContent = tableContainer.outerHTML;

    var cabecalho = "";

    // Função para obter o cabeçalho do relatório
    function detRelatorio() {
        return $.ajax({
            type: "POST",
            data: "relatorio=true&acao=cabecalho",
            url: "modal/relatorio/gerenciar_relatorio.php",
            async: false
        }).then(sucesso, falha);

        function sucesso(data) {
            var $dados = $.parseJSON(data)["dados"];
            if ($dados.sucesso == true) {
                cabecalho = $dados.valores['cabecalho'];
            }
        }

        function falha() {
            console.log("Erro ao obter o cabeçalho");
        }
    }

    // Chamar a função para obter o cabeçalho
    detRelatorio();

    // Criando o conteúdo da nova página
    var newPageContent = `
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Relatório</title>
        <link href="css/relatorioV=03.css" rel="stylesheet">
    </head>
    <body>
        ${cabecalho}
        ${tableContent}
        <div id="footer">
            <p>@Todos os direitos reservados a Effmax</p>
        </div>
        <script>
            window.onload = function() {
                window.print();
            };
        </script>
    </body>
    </html>
`;

    // Abrindo a nova página em uma nova aba
    var newPage = window.open();
    newPage.document.open();
    newPage.document.write(newPageContent);
    newPage.document.close();
});