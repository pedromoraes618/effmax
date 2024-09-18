<?php
include "../../../modal/servico/lista_servico/gerenciar_lista_servico.php";
?>


<div class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-hidden="true" id="modal_servico" data-bs-keyboard="false">
    <div class="modal-dialog modal-sl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Serviço</h1>
                <button type="button" class="btn-close btn-fechar" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="servico">
                <input type="hidden" id="id" name="id" value="<?= $form_id; ?>">
                <div class="modal-body">
                    <div class="title mb-2">
                        <label class="form-label sub-title"></label>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end btn-acao">
                        <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                        <button type="button" class="btn btn-sm btn-secondary btn-fechar" data-bs-dismiss="modal">Fechar</button>
                    </div>
                    <?php if ($form_id != "") {
                        echo  "<div class='row mb-2'>
                <div class='col-md-3'>
                <label for='codigo' class='form-label'>Código</label>
                <input type='text' disabled class='form-control' id='codigo' name='codigo' value='$form_id'></div>
                </div>";
                    } ?>
                    <div class="row  mb-2">
                        <div class="col-md  mb-2">
                            <label for="descricao" class="form-label">Descrição</label>
                            <input type="text" name="descricao" id="descricao" class="form-control" value="">
                        </div>
                    </div>
                    <div class="row  mb-2">
                        <div class="col-md  mb-2">
                            <label for="valor" class="form-label">Valor</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">R$</span>
                                <input type="number" step="any" class="form-control" id="valor" name="valor" value="">
                            </div>
                        </div>
                        <div class="col-md  mb-2">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" class="form-select chosen-select" id="status">
                                <option value="sn">Status Serviço..</option>
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>



<script src="js/servico/lista_servico/servico_tela.js"></script>