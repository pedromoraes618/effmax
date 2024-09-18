<?php include "../../../modal/ecommerce/pre_venda/gerenciar_pre_venda.php"; ?>
<div class="modal fade" id="modal_pre_venda" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Detalhe da Pré venda</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="pre_venda">
                <input type="hidden" id="id" name="id" value="<?= $form_id; ?>">

                <div class="modal-body">
                    <div class="title mb-2">
                        <label class="form-label sub-title">Pré venda <?= $form_id; ?></label>
                        <label class="bg-primary status_momento_venda"></label>
                    </div>

                    <div class="row  mb-2">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                            <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                            <button type="button" id="button_form" class="btn btn-sm btn-dark gerar_venda"><i class="bi bi-check-all"></i> Gerar Venda</button>
                            <button type="button" class="btn btn-sm  btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md border p-2">
                            <div class="accordion " id="accordionPanelsStayOpenExample">
                                <div class="accordion-item mb-2 ">
                                    <h2 class="accordion-header ">
                                        <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                            Pedido
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show ">
                                        <div class="accordion-body">
                                            <div class="row mb-2">
                                                <div class="col-md-auto">
                                                    <label class="form-label" for="data_pedido">Data Pedido</label>
                                                    <input type="datetime-local" id="data_pedido" class="form-control" disabled>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md  mb-2">
                                                    <label class="form-label" for="status_pedido_tela">Status Pedido</label>
                                                    <select id="status_pedido_tela" disabled name="status_pedido_tela" class="form-select chosen-select">
                                                        <option value="0">Selecione..</option>
                                                        <option value="CONCLUIDO">Concluido</option>
                                                        <option value="ANDAMENTO">Andamento</option>
                                                        <option value="CANCELADO">Cancelado</option>
                                                    </select>
                                                </div>
                                                <div class="col-md  mb-2">
                                                    <label class="form-label" for="status_pagamento_tela">Status Pagamento</label>
                                                    <select name="status_pagamento_tela" disabled class="form-select chosen-select" id="status_pagamento_tela">
                                                        <option value="0">Status Pagamento..</option>
                                                        <option value="approved">Aprovado</option>
                                                        <option value="in_process">Em processamento</option>
                                                        <option value="pending">Pendente</option>
                                                        <option value="rejected">Pagamento rejeitado</option>
                                                        <option value="cancelled">Cancelado</option>
                                                        <option value="naorealizado">Não realizado</option>
                                                    </select>
                                                </div>
                                                <div class="col-md mb-2">
                                                    <label class="form-label" for="forma_pagamento_id">Forma Pagamento</label>
                                                    <select name="forma_pagamento_id" disabled class="form-select chosen-select" id="forma_pagamento_id">
                                                        <option value="0">Selecione..</option>
                                                        <?php
                                                        $resultados = consulta_linhas_tb_2_filtro($conecta, 'tb_forma_pagamento', 'cl_ativo', 'S', 'cl_ativo_delivery', 'S');
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
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="accordion-item mb-2 ">
                                    <h2 class="accordion-header ">
                                        <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
                                            Cliente
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show ">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-auto">
                                                    <label class="form-label" for="nome">Nome</label>
                                                    <p id="nome"></p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <label class="form-label" for="cpfcnpj">Cpf/Cnpj</label>
                                                    <p id="cpfcnpj"></p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <label class="form-label" for="email">Email</label>
                                                    <p id="email"></p>
                                                </div>
                                                <div class="col-md-auto">
                                                    <label class="form-label" for="telefone">Telefone</label>
                                                    <p id="telefone"></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="accordion-item mb-2 ">
                                    <h2 class="accordion-header ">
                                        <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="true" aria-controls="panelsStayOpen-collapseThree">
                                            Frete
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show ">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-7">
                                                    <label class="form-label" for="tipo_frete">Tipo</label>
                                                    <input type="text" disabled class="form-control" id="tipo_frete">
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="form-label" for="codigo_rastreio">Código de Rastreio</label>
                                                    <input type="text" class="form-control" id="codigo_rastreio" name="codigo_rastreio" placeholder="Informe o código de rastreio">
                                                    <div class="form-text" id="help_codigo_rastreio"></div>

                                                </div>
                                                <div class="col-md-auto">
                                                    <label class="form-label" for="data_entrega">Data Entrega</label>
                                                    <input type="date" class="form-control" id="data_entrega" name="data_entrega">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="accordion-item mb-2 ">
                                    <h2 class="accordion-header ">
                                        <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapsefour" aria-expanded="true" aria-controls="panelsStayOpen-collapsefour">
                                            Endereço
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapsefour" class="accordion-collapse collapse show ">

                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-3  mb-2">
                                                    <label class="form-label" for="cep">Cep</label>
                                                    <input type="text" class="form-control" id="cep" name="cep">
                                                </div>
                                                <div class="col-md-7  mb-2">
                                                    <label class="form-label" for="endereco">Endereço</label>
                                                    <input type="text" class="form-control" id="endereco" name="endereco">
                                                </div>
                                                <div class="col-md-2  mb-2">
                                                    <label class="form-label" for="numero">Nº</label>
                                                    <input type="text" class="form-control" id="numero" name="numero">
                                                </div>
                                                <div class="col-md-4  mb-2">
                                                    <label class="form-label" for="bairro">Bairro</label>
                                                    <input type="text" class="form-control" id="bairro" name="bairro">
                                                </div>
                                                <div class="col-md-8  mb-2">
                                                    <label class="form-label" for="complemento">Complemento</label>
                                                    <input type="text" class="form-control" id="complemento" name="complemento">
                                                </div>
                                                <div class="col-md-3  mb-2">
                                                    <label class="form-label" for="cidade">Cidade</label>
                                                    <input type="text" class="form-control" id="cidade" name="cidade">
                                                </div>
                                                <div class="col-md-2  mb-2">
                                                    <label class="form-label" for="estado">Estado</label>
                                                    <input type="text" class="form-control" id="estado" name="estado">
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="px-2 border-1 border  mb-3  rounded">
                                <?php

                                $linha = mysqli_fetch_assoc($consultaProdutos);
                                $descricao = utf8_encode($linha['cl_descricao']);
                                $referencia = utf8_encode($linha['cl_referencia']);
                                $quantidade = $linha['cl_quantidade'];
                                $valor_unit_prod = ($linha['cl_valor_produto']);
                                $valor_total_produto = $valor_unit_prod * $quantidade;
                                $valor_liquido = $linha['cl_valor_liquido'];
                                $valor_desconto = $linha['cl_desconto'];
                                $valor_frete = $linha['cl_valor_frete'];
                                // $total = $valor * $quantidade;
                                // $valor = real_format($valor);
                                // $total = real_format($total);

                                $codigo_nf = ($linha['cl_codigo']);
                                $img_produto = consulta_tabela_query($conecta, "select * from tb_imagem_produto where cl_codigo_nf ='$codigo_nf' order by cl_ordem asc limit 1", 'cl_descricao');
                                if ($img_produto == "") {
                                    $img_produto = $imagem_produto_default;
                                } else {
                                    $img_produto = "img/produto/$img_produto";
                                }

                                ?>

                                <div class="card card-pedido bg-body-tertiary  border-0 position-relative mb-1 ">
                                    <div class=" g-0 position-relative d-flex justify-content-start just align-items-center">
                                        <div class="position-relative">
                                            <!-- <img src="..." class="img-fluid rounded-start" alt="..."> -->
                                            <img class="img-thumbnail border-0" width="100" src='<?= $img_produto; ?>' alt="<?= $descricao; ?>">

                                        </div>

                                        <div class="card-body text-start">
                                            <div class="mb-2">
                                                <p class="card-title fw-bold"><?= $descricao . " x" . $quantidade; ?> </p>
                                                <p class="card-subtitle text-muted"><?= $referencia; ?></p>
                                            </div>
                                            <div class="card-text fw-bold"><?= real_format($valor_total_produto); ?></div>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                ?>
                                <div class="mt-4">
                                    <div class="d-flex justify-content-between">
                                        <div class="text-muted">Produto</div>
                                        <div class="text-muted valorSubTotalCheckout"><?= real_format($valor_total_produto); ?></div>
                                    </div>
                                    <?php if ($valor_frete > 0) { ?>
                                        <div class="d-flex justify-content-between">
                                            <div class="text-muted">Frete</div>
                                            <div class="text-muted"><?= real_format($valor_frete); ?></div>
                                        </div>
                                    <?php } ?>
                                    <?php if ($valor_desconto > 0) { ?>
                                        <div class="d-flex justify-content-between">
                                            <div class="text-muted">Desconto</div>
                                            <div class="text-muted">- <?= real_format($valor_desconto); ?></div>
                                        </div>
                                    <?php } ?>
                                    <hr>
                                    <div class="d-flex justify-content-between mb-4">
                                        <div class="fw-bold">Total</div>
                                        <div class="fw-bold valorTotalCheckout"><?= real_format($valor_liquido); ?></div>
                                    </div>
                                </div>

                            </div>
                            <div class="row mb-2">
                                <div class="col-md  mb-2">
                                    <label for="observacao" class="form-label">Observação</label>
                                    <textarea class="form-control" rows="3" name="observacao" id="observacao" placeholder="A observação não aparecerá para o cliente.." id="observacao" aria-label="With textarea"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="accordion-footer text-muted p-2 border-top">
                                    <i class="bi bi-exclamation-circle"></i> Atenção: qualquer alteração, afetará o pedido do cliente
                                </div>
                            </div>
                        </div>
                    </div>


                </div>



            </form>
        </div>
    </div>
</div>




<script src="js/ecommerce/pre_venda/pre_venda_tela.js"></script>