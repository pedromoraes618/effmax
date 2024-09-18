var id = document.getElementById('id')
var pesquisa_conteudo_historico = document.getElementById('pesquisa_conteudo_historico')

$("#pesquisar_filtro_pesquisa_historico").click(function () {
    $.ajax({
        type: 'GET',
        data: "consultar_historico_credito_parceiro=true&parceiro_id=" + id.value + "&conteudo=" + pesquisa_conteudo_historico.value,
        url: "view/include/parceiro/table/consultar_historico_credito.php",
        success: function (result) {
            return $("#modal_historico_credito_parceiro .tabela_externa").html(result);
        },
    });
});
$("#pesquisar_filtro_pesquisa_historico").trigger('click'); // clicar automaticamente para realizar a consulta de localidade
