
$("#adicionar_fpg").click(function (e) {
    $.ajax({
        type: 'GET',
        data: "cadastrar_fpg=true",
        url: "view/configuracao/forma_pagamento/forma_pagamento_tela.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_show").html(result) + $("#modal_forma_pagamento").modal('show');

        },
    });
})


//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo");


//consultar tabela
$.ajax({
    type: 'GET',
    data: "consultar_fpg=inicial",
    url: "view/configuracao/forma_pagamento/table/consultar_forma_pagamento.php",
    success: function (result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
    },
});



$("#pesquisar_filtro_pesquisa").click(function () {
    localStorage.setItem("storage_pesquisa", conteudo_pesquisa.value);
    $.ajax({
        type: 'GET',
        data: "consultar_fpg=detalhado&conteudo_pesquisa=" + conteudo_pesquisa.value,
        url: "view/configuracao/forma_pagamento/table/consultar_forma_pagamento.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
        },
    });

})


