<?php

include "../../../modal/servico/ordem_servico/gerenciar_ordem_servico.php";
?>
<div class="modal fade " id="modal_resumo_valores_ordem_servico" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content border">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Resumo de Valores</h1>
                <button type="button" class="btn-close btn-close-valores" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <ul class="list-group ">
                    <li class="list-group-item d-flex border border-success-subtle justify-content-between align-items-start">
                        <div class="col">
                            <div class="input-group mb-1">
                                <span class="input-group-text ">Material + </span>
                                <input type="text" aria-label="First name" disabled name="valor_pecas" id="valor_pecas" class="form-control" value="">
                                <button type="button" class="btn btn-secondary  <?php echo (($tipo_ordem == 2 or $tipo_ordem == "") ? 'adicionar_pecas' : 'consultar_pecas'); ?> " name="adicionar_pecas" id="adicionar_pecas">Material</button>
                            </div>

                            <div class="input-group mb-1">
                                <span class="input-group-text ">Serviço +</span>
                                <input type="text" aria-label="First name" disabled name="valor_servico" id="valor_servico" class="form-control" value="">
                                <?php if ($tipo_ordem == 2 or $tipo_ordem == "") { ?>
                                    <button type="button" class="btn btn-secondary adicionar_servico" name="adicionar_servico" id="adicionar_servico">Serviço</button>
                                <?php
                                } ?>
                            </div>
                            <div class="input-group mb-1">
                                <span class="input-group-text ">Despesa +</span>
                                <input type="text" aria-label="First name" disabled id="valor_despesa" class="form-control" value="">
                                <button type="button" class="btn btn-secondary adicionar_despesa" name="adicionar_despesa" id="adicionar_despesa">Despesa</button>
                            </div>

                            <div class="input-group mb-1">
                                <span class="input-group-text ">Desconto -</span>
                                <input type="number" step="any" name="desconto" id="desconto" class="form-control">
                                <button type="button" class="btn btn-secondary" name="adicionar_desconto" id="adicionar_desconto">Desconto</button>
                            </div>
                            <div class="input-group mb-1">
                                <span class="input-group-text ">Valor Ordem</span>
                                <input type="text" disabled name="valor_liquido" id="valor_liquido" class="form-control">
                            </div>
                            <div class="input-group mb-1">
                                <span class="input-group-text">Taxa/Adiantamento -</span>
                                <input type="text" disabled name="taxa" id="taxa" class="form-control">
                                <button type="button" class="btn btn-secondary adicionar_taxa">Taxa/Adiantamento</button>
                            </div>

                            <div class="input-group">
                                <span class="input-group-text">Valor a Receber</span>
                                <input type="text" disabled id="valor_a_receber" class="form-control" value="">
                                <?php
                                if ($situacao == "0") {
                                ?>
                                    <button type="button" class="btn btn-success faturar_os" name="faturar_os" id="faturar_os">Faturar</button>
                                <?php
                                } elseif ($situacao == "1") {
                                ?>
                                    <button type="button" class="btn btn-danger remover_faturamento_os" data-form_id=<?= $form_id; ?> name="remover_faturamento_os" id="remover_faturamento_os">Remover do faturamento</button>
                                <?php
                                } ?>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="modal_externo_2"></div>
</div>

<script src="js/include/servico/valores_ordem_servico.js"></script>