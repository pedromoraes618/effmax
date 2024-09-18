<?php

// include "../../../modal/configuracao/forma_pagamento/gerenciar_forma_pagamento.php";
?>
<div class="modal fade" id="modal_cfop" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Cfop</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="cfop">
                <input type="hidden" id="id" name="id" value="<?php if (isset($_GET['form_id'])) {
                                                                    echo $_GET['form_id'];
                                                                } ?>">
                <div class="modal-body">
                    <div class="title mb-2">
                        <label class="form-label sub-title"></label>
                    </div>
                    <div class="row  mb-2">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                            <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                            <button type="button" class="btn btn-sm  btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3  mb-2">
                            <label for="cfop_saida" class="form-label">Cfop Saida</label>
                            <input type="number" class="form-control " id="cfop_saida" name="cfop_saida" placeholder="Ex. 5102">
                        </div>
                        <div class="col-md  mb-2">
                            <label for="descricao" class="form-label">Descrição</label>
                            <input type="text" class="form-control " id="descricao" name="descricao" placeholder="Ex. Venda dentro do estado">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3  mb-2">
                            <label for="cfop_entrada" class="form-label">Cfop Entrada</label>
                            <input type="number" class="form-control " id="cfop_entrada" name="cfop_entrada" placeholder="Ex. 2102">
                        </div>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="js/configuracao/cfop/cfop_tela.js"></script>