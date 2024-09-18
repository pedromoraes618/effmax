<?php
include "../../../modal/configuracao/usuario/gerenciar_usuario.php";
?>

<div class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-hidden="true" id="modal_resetar_senha" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Resetar Senha</h1>
                <button type="button" class="btn-close btn-fechar btn-fechar-resetar" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="resete_senha">

                <div class="modal-body">
                    <div class="title mb-2">
                        <label class="form-label sub-title">Resetar senha</label>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end btn-acao">
                        <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                        <button type="button" class="btn btn-sm btn-secondary btn-fechar" data-bs-dismiss="modal">Fechar</button>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md mb-2">
                            <label for="senha" class="form-label">Nova senha *</label>
                            <input type="text" class="form-control " id="senha" name="senha" autocomplete="off" placeholder="Apenas letras, números e símbolos" value="">
                        </div>
                        <div class="col-md  mb-2">
                            <label for="nome" class="form-label">Confirma senha *</label>
                            <input type="text" class="form-control " id="confirmar_senha" autocomplete="off" name="confirmar_senha" value="">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="js/configuracao/usuario/resetar_senha_tela.js"></script>