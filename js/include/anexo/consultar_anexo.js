
//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa_anexo = document.getElementById("pesquisa_conteudo_anexo");
var tipo = $("#tipo").val()
var form_id = $("#form_id").val()
var codigo_nf = $("#codigo_nf").val()


$("#adicionar_anexo").click(function () {
    $.ajax({
        type: 'GET',
        data: "anexo=true",
        url: "view/include/anexo/upload.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .modal_externo_anexo").html(result) + $("#modal_upload_anexo").modal('show');

        },
    });
})

$("#pesquisar_filtro_pesquisa_anexo").click(function () {//realizar a pesquisa

    $.ajax({
        type: 'GET',
        data: "consultar_anexo_tabela=detalhado&conteudo_pesquisa_anexo=" + conteudo_pesquisa_anexo.value + "&tipo=" + tipo + "&form_id=" + form_id + "&codigo_nf=" + codigo_nf,
        url: "view/include/anexo/table/consultar_anexo.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela_anexo").html(result);
        },
    });
})

$(document).ready(function () {
    $('#pesquisar_filtro_pesquisa_anexo').trigger('click'); // clicar automaticamente para realizar a consulta
})
