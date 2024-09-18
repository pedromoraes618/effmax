<?php
include "../../../conexao/conexao.php";
include "../../../modal/financeiro/classificacao_financeira/gerenciar_classificacao.php";
?>
<div class="modal fade" id="modal_classificacao_financeira" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Classificação Financeira</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="classificacao_financira">
                <input type="hidden" id="id" name="id" value="<?= $form_id; ?>">
                <div class="modal-body">
                    <div class="title mb-2">
                        <label class="form-label sub-title"></label>
                    </div>

                    <div class="row  mb-2">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end btn-acao">
                            <button type="submit" id="button_form" class="btn btn-sm btn-success">Salvar</button>
                            <?php if ($form_id != "") {
                                echo "<button type='button' id='remover_registro' class='btn btn-sm btn-danger'>Remover</button>";
                            } ?>
                            <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                    <div class="col  mb-2">
                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control " id="descricao" name="descricao" value="">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="js/financeiro/classificacao_financeira/classificacao_tela.js"></script>