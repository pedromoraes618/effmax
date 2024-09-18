

$(".atualizar_tarefa").click(function (e) {
    var id_tarefa = $(this).attr("id_tarefa")
    var user_logado = $(this).attr("user_logado")


    var status = "status" + id_tarefa
    var status_lembrete = document.getElementById(status)

    var comentario = "comentario" + id_tarefa
    var comentario_tarefa = document.getElementById(comentario)

    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja atualizar esse lembrete?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {

            $.ajax({
                type: "POST",
                data: "atualizar_minha_tarefa=true&status=" + status_lembrete.value + "&comentario=" + comentario_tarefa.value + "&id_tarefa=" + id_tarefa + "&user_logado=" + user_logado,
                url: "modal/lembrete/minha_tarefa/gerenciar_minha_tarefa.php",
                async: false
            }).then(sucesso, falha);

            function sucesso(data) {
                $sucesso = $.parseJSON(data)["sucesso"];
                $mensagem = $.parseJSON(data)["mensagem"];
                if ($sucesso) {
                    Swal.fire({
                        position: 'center',
                        icon: 'success',
                        title: 'Tarefa alterada com sucesso',
                        showConfirmButton: false,
                        timer: 1500
                    })

                    //remover o card
                    if (status_lembrete.value == "3") {
                        $(".card" + id_tarefa).css("opacity", '1')
                        $(".card" + id_tarefa).css("opacity", '0.5')
                        $(".card" + id_tarefa).css("display", 'none')
                    }

                    //consultar tabela
                    $.ajax({
                        type: 'GET',
                        data: "consultar_tarefa=inicial&usuario_logado=" + user_logado,
                        url: "view/lembrete/minha_tarefa/table/consultar_minha_tarefa.php",
                        success: function (result) {
                            return $(".bloco-pesquisa-2 .tabela").html(result);
                        },
                    });

                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Verifique!',
                        text: $mensagem,
                        timer: 7500,

                    })
                }
            }

            function falha() {
                console.log("erro adicionar acesso ao usuario")
            }

        }
    })
})


