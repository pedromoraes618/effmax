$(".remover_membro_equipe").click(function () {

    var membro_equipe_id = $(this).attr("id");
    var item = $(this).attr("item");
    // Usando template literals para selecionar o elemento com base no ID
    remover_membro_equipe(membro_equipe_id, item)//remove o membro no bd
});


function remover_membro_equipe(form_id, item) {
    $.ajax({
        type: "POST",
        data: "formulario_ordem_servico=true&acao=remover_membro_equipe&form_id=" + form_id,
        url: "modal/servico/ordem_servico/gerenciar_ordem_servico.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {

        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            $(".membro_equipe_" + item).remove();//remove a linha
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Verifique!',
                text: $dados.title,
                timer: 7500,

            })

        }
    }

    function falha() {
        console.log("erro");
    }

}

