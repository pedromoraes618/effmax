<?php
include "../../../modal/estoque/produto/gerenciar_produto.php";
?>
<div class="modal fade" id="modal_pergunta" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Dúvida</h1>
                <button type="button" class="btn-close fechar_tela_duvida" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="duvida">
                <div class="modal-body">
                    <input type="hidden" id="pergunta_id" value="<?= $form_id; ?>">
                    <div class="mb-3 p-3 border rounded bg-light">
                        <p class="mb-2"><strong>Cliente:</strong> <?= $cliente; ?></p>
                        <p class="mb-2"><strong>Produto:</strong> <?= $produto; ?></p>
                        <p class="mb-2"><strong>Pergunta:</strong> <?= $pergunta; ?></p>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md">
                            <label for="mensagem" class="form-label"><strong>Resposta</strong></label>
                            <textarea name="mensagem" class="form-control" rows="4" id="mensagem" placeholder="Escreva a sua resposta"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Enviar resposta</button>
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var pergunta_id = document.getElementById("pergunta_id")
    $("#duvida").submit(function(e) { //adicionar o produto na venda
        e.preventDefault()
        var formulario = $(this);
        Swal.fire({
            title: 'Tem certeza?',
            text: "Deseja enviar essa resposta?",
            icon: 'warning',
            showCancelButton: true,
            cancelButtonText: 'Não',
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim'
        }).then((result) => {
            if (result.isConfirmed) {
                var retorno = create(formulario, pergunta_id.value)
            }
        })

    })

    function create(dados, pergunta_id) {
        $.ajax({
            type: "POST",
            data: "formulario_produto_ecommerce=true&acao=responder_duvida&pergunta_id=" + pergunta_id + "&" + dados.serialize(),
            url: "modal/estoque/produto/gerenciar_produto.php",
            async: false
        }).then(sucesso, falha);


        function sucesso(data) {
            $dados = $.parseJSON(data)["dados"];
            if ($dados.sucesso == true) {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: $dados.title,
                    showConfirmButton: false,
                    timer: 3500
                })
                $(".pesquisar_filtro_pesquisa_pergunta").trigger('click')
                $(".fechar_tela_duvida").trigger('click')
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
</script>