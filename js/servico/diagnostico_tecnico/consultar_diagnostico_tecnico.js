
//valores do campo de pesquisa //pesquisa via filtro
var conteudo_pesquisa = document.getElementById("pesquisa_conteudo");
var status_ordem = document.getElementById("status_ordem");
var tecnico_os = document.getElementById("tecnico_os");
var data_inicial = document.getElementById("data_inicial");
var data_final = document.getElementById("data_final");


//consultar tabela
$.ajax({
    type: 'GET',
    data: {
        consultar_diagnostico_tecnico: "inicial",
        data_inicial: data_inicial.value,
        data_final: data_final.value,
    },
    url: "view/servico/diagnostico_tecnico/table/consultar_diagnostico_tecnico.php",
    success: function (result) {
        return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
    },
});


$("#pesquisar_filtro_pesquisa").click(function () {//realizar a pesquisa

    $.ajax({
        type: 'GET',
        data: {
            consultar_diagnostico_tecnico: "detalhado",
            data_inicial: data_inicial.value,
            data_final: data_final.value,
            conteudo_pesquisa: conteudo_pesquisa.value,
            status_ordem: status_ordem.value,
            tecnico_os: tecnico_os.value
        },
        url: "view/servico/diagnostico_tecnico/table/consultar_diagnostico_tecnico.php",
        success: function (result) {
            return $(".bloco-pesquisa-menu .bloco-pesquisa-1 .tabela").html(result);
        },
    });
})


$(document).ready(function() {
    $('.select2').select2({
        dropdownAutoWidth: true,
        width: '100%',
        dropdownParent: $('body') // Ou o contêiner específico onde está o select
    });
});
