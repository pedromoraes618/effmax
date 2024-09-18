<?php
include "../../../modal/anexo/gerenciar_anexo.php";
?>
<div class="modal fade" id="modal_anexo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header mb-2">
                <h1 class="modal-title fs-5">Anexo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="title mb-2">
                    <label class="form-label sub-title">Consultar anexo</label>
                </div>
                <div class="row">
                    <input type="hidden" disabled id="tipo" value="<?= $tipo; ?>">
                    <input type="hidden" disabled id="codigo_nf" name="codigo_nf" value="<?= $codigo_nf;  ?>">
                    <input type="hidden" disabled id="form_id" name="form_id" value="<?= $form_id; ?>">
                    <div class="col-md  mb-2">
                        <div class="input-group">
                            <input type="text" class="form-control" id="pesquisa_conteudo_anexo" placeholder="Pesquise pela descrição ou usuário" aria-label="Recipient's username" aria-describedby="button-addon2">
                            <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa_anexo">Pesquisar</button>
                        </div>
                    </div>
                    <div class="col-md-auto  d-grid gap-2 d-sm-block mb-1">
                        <button type="button" id="adicionar_anexo" class="btn btn-dark">
                            <i class="bi bi-plus-circle"></i> Anexo
                        </button>
                    </div>
                </div>
                <div class="modal_externo_anexo"></div>
                <div class="tabela_anexo">
                </div>
            </div>
        </div>
    </div>
</div>


<script src="js/include/anexo/consultar_anexo.js"></script>