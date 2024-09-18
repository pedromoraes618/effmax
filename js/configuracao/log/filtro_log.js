$("#pesquisa_conteudo").click(function(e) {
    $('.tabela').css("display", 'none')
    $('.tabela').fadeIn(500)
    let data_inicial = document.getElementById("data_incial").value;
    let data_final = document.getElementById("data_final").value;
    let usuario = document.getElementById("usuario").value;
    let conteudo = document.getElementById("conteudo").value;
    $.ajax({
        type: 'GET',
        data: "consultar_log=detelhado&data_inicial="+data_inicial+"&data_final="+data_final+"&usuario="+usuario+"&conteudo="+conteudo,
        url: "view/configuracao/log/table/consultar_log.php",
        success: function(result) {
            return $(".bloco-pesquisa-2 .tabela").html(result);
        },
    });
})