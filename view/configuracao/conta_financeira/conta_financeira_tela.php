<?php
include "../../../conexao/conexao.php";
include "../../../modal/configuracao/conta_financeira/gerenciar_conta_financeira.php";
?>
<div class="modal fade" id="modal_conta_financeira" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Conta Financeira</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="" id="conta_financeira">
                <?php include "../../input_include/usuario_logado.php" ?>
                <input type="hidden" id="id" name="id" value="<?php if (isset($_GET['form_id'])) {
                                                                    echo $_GET['form_id'];
                                                                } ?>">


                <div class="modal-body">
                    <div class="title mb-2">
                        <label class="form-label sub-title"></label>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end btn-acao">
                        <button type="submit" id="button_form" class="btn btn-success">Salvar</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                    <div class="col  mb-2">
                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control " id="descricao" name="descricao" value="">
                    </div>
                    <div class="row">
                        <div class="col-md  mb-2">
                            <label for="conta" class="form-label">Conta</label>
                            <input type="text" class="form-control " id="conta" name="conta" value="">
                        </div>

                        <div class="col-md  mb-2">
                            <label for="digito_conta" class="form-label">Digito Conta</label>
                            <input type="text" class="form-control inputNumber" id="digito_conta" name="digito_conta" value="">
                        </div>
                        <div class="col-md  mb-2">
                            <label for="agencia" class="form-label">Agênia</label>
                            <input type="text" class="form-control inputNumber" id="agencia" name="agencia" value="">
                        </div>
                        <div class="col-md  mb-2">
                            <label for="numero_banco" class="form-label">Número banco</label>
                            <input type="text" class="form-control inputNumber" id="numero_banco" name="numero_banco" value="">
                        </div>
                    </div>
                </div>


            </form>
        </div>
    </div>
</div>

<script src="js/funcao.js"></script>
<script src="js/configuracao/users/user_logado.js"></script>
<script src="js/configuracao/conta_financeira/conta_financeira_tela.js"></script>