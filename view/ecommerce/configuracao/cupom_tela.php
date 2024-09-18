<?php
include "../../../modal/ecommerce/configuracao/gerenciar_configuracao.php";
?>

<div class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-hidden="true" id="modal_cupoom" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Cupom</h1>
                <button type="button" class="btn-close btn-fechar" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="cupom">

                <input type="hidden" id="id" name="id" value="<?= $form_id; ?>">
                <div class="modal-body">
                    <div class="title mb-2">
                        <label class="form-label sub-title"></label>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end btn-acao">
                        <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                        <button type="button" class="btn btn-sm btn-secondary btn-fechar" data-bs-dismiss="modal">Fechar</button>
                    </div>
                    <div class="row  mb-2">
                        <div class="col-md-4  mb-2">
                            <label for="cupom" class="form-label">Cupom <span class="d-inline-block BD-" tabindex="0" data-bs-toggle="popover" data-bs-placement="top" data-bs-trigger="hover focus" data-bs-content="Esse é o código que os clientes precisarão para usar esse cupom.">
                                    <i class="bi bi-info-circle"></i>
                                </span></label>
                            <input type="text" name="cupom" id="cupom" class="form-control" placeholder="ex. A1B2C3">
                        </div>
                        <div class="col-md-auto  mb-2">
                            <label for="status" class="form-label">Status * </label>
                            <select name="status" class="form-select chosen-select" id="status">
                                <option value="sn">Selecione..</option>
                                <option value="1" selected>Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </div>
                    </div>
                    <div class="row  mb-2">
                        <div class="col-md  mb-2">
                            <label for="descricao" class="form-label">Descrição *</label>
                            <input type="text" name="descricao" id="descricao" placeholder="ex. Sua primeira compra, ganhe 10% de desconto em sua primeria compra" class="form-control" value="">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <label for="valor" class="form-label">Desconto *</label>
                        <div class="input-group">
                            <div class="col-md-auto">
                                <select class="form-select chosen-select" name="operador" id="operador">
                                    <option value="percent">%</option>
                                    <option value="moeda">R$</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="number" step="any" class="form-control" id="valor" name="valor" value="">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3 d-flex align-items-end">
                        <div class="col-md-6">
                            <label for="data_validade" class="form-label">Válido entre <span class="d-inline-block BD-" tabindex="0" data-bs-toggle="popover" data-bs-placement="top" data-bs-trigger="hover focus" data-bs-content="Defina desde quando o cupom é válido e quando expira.">
                                    <i class="bi bi-info-circle"></i>
                                </span></label>
                            <div class="input-group">
                                <input type="date" class="form-control " id="validade_data_incial" name="validade_data_incial" title="Data Inicial" placeholder="Data Inicial" value="<?php echo $data_inicial_ano_bd ?>">
                                <input type="date" class="form-control " id="validade_data_final" name="validade_data_final" title="Data Final" disabled placeholder="Data Final" value="">
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" checked id="sem_expiracao_validade" name="sem_expiracao_validade">
                                <label class="form-check-label" for="sem_expiracao_validade">
                                    Sem expiração
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="accordion " id="accordionPanelsStayOpenExample">
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                    Personalize o seu cupom
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show ">
                                <div class="accordion-body">
                                    <div class="row  mb-2">

                                        <div class="col-md-4  mb-2">
                                            <label for="valor_minimo" class="form-label">Subtotal mínimo
                                                <span class="d-inline-block BD-" tabindex="0" data-bs-toggle="popover" data-bs-placement="top" data-bs-trigger="hover focus" data-bs-content="Defina um valor mínimo para a utilização deste cupom.">
                                                    <i class="bi bi-info-circle"></i>
                                                </span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" aria-describedby="valorMinimoHelp" class="form-control" id="valor_minimo" name="valor_minimo" value="ex. 350">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label for="data_validade" class="form-label">Limite de uso</label>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" name="limite_total_cupom" id="limite_total_cupom">
                                            <label class="form-check-label" for="limite_total_cupom">
                                                Limite o número total de usos para esse cupom
                                            </label>
                                            <div class="col-md-3 mb-2">
                                                <input type="number" step="any" class="form-control" style="display: none;" aria-describedby="limiteHelp" id="limite_utilizado" name="limite_utilizado" value="ex. 3">
                                            </div>
                                        </div>


                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" name="limite_cliente_cupom" id="limite_cliente_cupom" checked>
                                            <label class="form-check-label" for="limite_cliente_cupom">
                                                Limite de 1 uso por cliente
                                            </label>

                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="" name="condicao_cadastrado" id="condicao_cadastrado">
                                            <label class="form-check-label" for="condicao_cadastrado">
                                                Cliente cadastrado <span class="d-inline-block BD-" tabindex="0" data-bs-toggle="popover" data-bs-placement="top" data-bs-trigger="hover focus" data-bs-content="Condição para uso do cupom apenas se o cliente estiver cadastrado">
                                                    <i class="bi bi-info-circle"></i>
                                                </span>
                                            </label>

                                        </div>
                                        <!-- <div class="col-md-5  mb-2">
                                            <label for="limite_utilizado" class="form-label">Limite o número total de usos para esse cupom</label>
                                            <div id="limiteHelp" class="form-text"> Defina um limite de utilização deste cupom.</div>
                                        </div> -->
                                    </div>
                                    <!-- <div class="col-md  mb-2">
                                        <label for="primeira_compra" class="form-label">Cupom de primeira compra</label>
                                        <select name="primeira_compra" class="form-select chosen-select" id="primeira_compra">
                                            <option value="sn">Selecione..</option>
                                            <option value="1">Sim</option>
                                            <option value="0">Não</option>
                                        </select>
                                    </div> -->

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="js/ecommerce/configuracao/cupom_tela.js"></script>