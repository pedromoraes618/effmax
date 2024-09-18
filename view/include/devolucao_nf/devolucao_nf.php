<?php
include "../../../modal/devolucao_nf/devolucao_mercadoria/gerenciar_devolucao.php";
?>
<div class="modal fade" id="modal_devolucao_nf" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5"><?php echo $titulo; ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="devolucao_nf">
                    <div class="row  mb-2">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end btn-acao">
                            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Concluir</button>
                        </div>
                    </div>
                    <div class="row">
                        <input type="hidden" id="tipo" name="tipo" value="<?php echo $tipo; ?>">
                        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
                        <div class="col-md-2 mb-2">
                            <label for="doc" class="form-label">Doc</label>
                            <input type="text" class="form-control" disabled name="doc" id="doc" value="">
                        </div>
                        <div class="col-md mb-2">
                            <label for="parceiro" class="form-label"><?php echo $parceiro; ?></label>
                            <input type="text" class="form-control" disabled name="parceiro" id="parceiro" value="">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="valor_doc" class="form-label">Valor Doc</label>
                            <input type="text" class="form-control" disabled name="valor_doc" id="valor_doc" value="">
                        </div>
                    </div>
                    <div class="row align-items-end">
                        <div class="col-md-2 mb-2">
                            <label for="serie" class="form-label">Série *</label>
                            <select class="form-control" name="serie" id="serie">
                                <option value="0">Selecione..</option>
                                <option value="DEV">DEV</option>
                                <option value="NFE">NFE</option>
                            </select>
                        </div>
                        <?php if ($tipo == 'saidadev') { ?>
                            <div class="col">
                                <div class="col-auto">
                                    <div class="form-check" title="Deseja gerar um crédito para o cliente">
                                        <input class="form-check-input" type="checkbox" checked name="credito" id="credito">
                                        <label class="form-check-label" for="credito">
                                            Gerar Crédito
                                        </label>
                                    </div>
                                </div>
                            </div>
                        <?php }
                        ?>
                    </div>
                    <div class="card border-0 p-2 mb-2 shadow">
                        <div class="tabela_externa tabela tabela_itens mb-2"></div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
<div class="alert"></div>

<script src="js/include/devolucao_nf/devolucao_nf.js"></script>