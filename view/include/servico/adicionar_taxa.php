<?php
include "../../../modal/servico/ordem_servico/gerenciar_ordem_servico.php";
?>
<div class="modal fade " id="modal_taxa_ordem_servico" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content border ">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Taxa / Adiantamento</h1>
                <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="incluir_taxa">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end btn-acao mb-3">
                        <button type="submit" id="button_form" class="btn btn-sm btn-success "><i class="bi bi-check-all"></i> Incluir</button>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm  btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-file-earmark"></i> Recibo
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item recibo_taxa" href="#">Taxa</a></li>
                                <li><a class="dropdown-item recibo_adiantamento" href="#">Adiantamento</a></li>
                            </ul>
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary btn-fechar" data-bs-dismiss="modal">Fechar</button>
                    </div>
                    <div class="row">
                        <div class="col-md mb-2">
                            <label for="dt_recebimento" class="form-label">Dt recebimento</label>
                            <input type="date" class="form-control" id="dt_recebimento" name="dt_recebimento" value="<?php echo $data_lancamento; ?>">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="forma_pagamento" class="form-label">Forma Pagamento *</label>
                            <select id="forma_pagamento" class="select2-modal-modal  chosen-select" name="forma_pagamento">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb_filtro($conecta, 'tb_forma_pagamento', 'cl_ativo', 'S');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = $linha['cl_id'];
                                        $descricao = utf8_encode($linha['cl_descricao']);

                                        echo "<option value='$id'>$descricao</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md mb-2">
                            <label for="tipo" class="form-label">Tipo *</label>
                            <select id="tipo" class="form-select chosen-select" name="tipo">
                                <option value="0">Selecione..</option>
                                <option value="Taxa">Taxa</option>
                                <option value="Adiantamento">Adiantamento</option>
                            </select>
                        </div>

                        <div class="col-md  mb-2">
                            <label for="valor" class="form-label">Valor</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">R$</span>
                                <input type="number" step="any" class="form-control" id="valor" name="valor" value="">
                            </div>
                        </div>
                    </div>
                </form>
                <div class="tabela_taxa tabela_modal"></div>
            </div>
        </div>
    </div>
</div>
<!-- <div class="modal_externo_2">

</div> -->
<script src="js/include/servico/adicionar_taxa.js"></script>