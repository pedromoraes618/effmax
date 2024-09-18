<?php include "../../../modal/compra/pedido_compra/gerenciar_pedido.php"; ?>
<div class="modal fade" id="modal_pedido_compra" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Pedido de compra</h1>
                <button type="button" class="btn-close fechar_tela_pedido" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="pedido_compra">
                    <input type="hidden" name="codigo_nf" id="codigo_nf" value="<?= $codigo_nf; ?>">
                    <input type="hidden" id="id" name="id" value="<?= $id_nf ?>">

                    <div class="title mb-3">
                        <label class="form-label sub-title"></label>
                        <label class="bg-primary status_momento_pedido"></label>
                    </div>


                    <div class="row mb-3">
                        <div class="d-flex flex-wrap justify-content-end gap-2">
                            <button type="button" id="iniciar_pedido" class="btn btn-sm btn-primary"><i class="bi bi-arrow-clockwise"></i> Iniciar Pedido</button>
                            <button type="button" class="btn gerar_pdf btn-sm btn-dark"><i class="bi bi-filetype-pdf"></i> Pdf</button>
                            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                            <button type="button" id="cancelar_pedido" class="btn btn-sm btn-danger"><i class="bi bi-check-all"></i> Cancelar</button>

                            <button type="button" class="btn btn-sm btn-secondary btn-fechar" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-6 col-md-2  mb-2">
                            <label for="data_movimento" class="form-label">Data movimento</label>
                            <input type="date" class="form-control" disabled id="data_movimento" name="data_movimento" value="<?= $data_lancamento; ?>">
                        </div>
                        <div class="col-6 col-md-2  mb-2">
                            <label for="documento" class="form-label">Doc</label>
                            <input type="text" class="form-control" disabled id="documento" name="documento" value="">
                        </div>
                        <div class="col-6 col-md-2  mb-2">
                            <label for="data_aprovacao" class="form-label">Data aprovação</label>
                            <input type="date" class="form-control " id="data_aprovacao" name="data_aprovacao" value="">
                        </div>
                        <div class="col-6  col-md-2 mb-2">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select chosen-select" name="status" id="status">
                                <option value="0">Selecione</option>
                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_status_pedido_compra');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = $linha['cl_id'];
                                        $descricao = utf8_encode($linha['cl_descricao']);
                                        echo "<option  value='$id'>$descricao</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-6 col-md-2 mb-2">
                            <label for="usuario_id" class="form-label">Usuário</label>
                            <select class="select2-modal chosen-select" name="usuario_id" id="usuario_id">
                                <option value="0">Selecione</option>
                                <?php
                                $resultados = consulta_linhas_tb_query($conecta, "SELECT * from tb_users where cl_ativo ='1'");
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = $linha['cl_id'];
                                        $descricao = utf8_encode($linha['cl_usuario']);
                                        $selected = $id == $usuario_id ? 'selected' : '';
                                        echo "<option $selected value='$id'>$descricao</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4 mb-2">
                            <label for="frete" class="form-label">Frete</label>
                            <select class="select2-modal chosen-select" name="frete" id="frete">
                                <option value="">Selecione</option>
                                <?php
                                $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_frete");
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = $linha['cl_id'];
                                        $descricao = utf8_encode($linha['cl_descricao']);
                                        echo "<option  value='$id'>$descricao</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="forma_pagamento_id" class="form-label">Forma pagamento</label>
                            <select id="forma_pagamento_id" class="select2-modal  chosen-select select2" name="forma_pagamento_id">
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
                        <div class="col-6  col-md-2 mb-2">
                            <label for="operacao" class="form-label">Operação</label>
                            <select class="form-select chosen-select" name="operacao" id="operacao">
                                <option value="0">Selecione</option>
                                <option value="compra">Compra</option>
                                <option value="venda">Venda</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-2  mb-2">
                            <label for="solicitacao" class="form-label">Nº solicitação</label>
                            <input type="text" class="form-control " id="solicitacao" name="solicitacao" value="" placeholder="Ex OS157">
                        </div>

                    </div>
                    <div class="row mb-2">
                        <div class="col-md  mb-2">
                            <label for="parceiro_descricao" class="form-label">Cliente/Fornecedor</label>
                            <div class="input-group">
                                <input type="text" class="form-control" readonly id="parceiro_descricao" name="parceiro_descricao" placeholder="Pesquise pelo Cliente/Fornecedor" aria-label="Recipient's username" aria-describedby="button-addon2">
                                <input type="hidden" class="form-control" name="parceiro_id" id="parceiro_id" value="">
                                <button class="btn btn-secondary" type="button" name="modal_parceiro" id="modal_parceiro">Pesquisar</button>
                            </div>
                        </div>
                        <div class="col-md  mb-2">
                            <label for="transportadora_descricao" class="form-label">Transportadora</label>
                            <div class="input-group">
                                <input type="text" class="form-control" readonly id="transportadora_descricao" name="transportadora_descricao" placeholder="Pesquise pela transportadora" aria-label="Recipient's username" aria-describedby="button-addon2">
                                <input type="hidden" class="form-control" name="transportadora_id" id="transportadora_id" value="">
                                <button class="btn btn-secondary" type="button" name="modal_transportadora" id="modal_transportadora">Pesquisar</button>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md  mb-2">
                            <label for="observacao" class="form-label">Observação</label>
                            <textarea class="form-control" rows="2" name="observacao" id="observacao" placeholder="Ex. Prazo de orçamento 30 dias"></textarea>
                        </div>
                    </div>

                    <div class="accordion " id="accordionPanelsStayOpenExample">
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                    Valores
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show ">
                                <div class="accordion-body">
                                    <div class="row mb-2">
                                        <div class="col-md-3  mb-2">
                                            <label for="valor_produtos" class="form-label">Valor Produtos</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" class="form-control" disabled id="valor_produtos" placeholder="0.00" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-3  mb-2">
                                            <label for="desconto" class="form-label">Desconto</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" onblur="calcular_valor_total()" id="valor_desconto" name="valor_desconto" placeholder="0.00" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-3  mb-2">
                                            <label for="valor_liquido_pedido" class="form-label">Valor Liquido</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" class="form-control" disabled id="valor_liquido_pedido" placeholder="0.00" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="accordion " id="accordionPanelsStayOpenExample">
                    <div class="accordion-item mb-2 ">
                        <h2 class="accordion-header ">
                            <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
                                Item
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show ">
                            <div class="accordion-body">
                                <form action="" id="produto_pedido">
                                    <input type="hidden" id="item_id" name="item_id">
                                    <input type="hidden" class="form-control" name="produto_id" id="produto_id" value="">

                                    <div class="row mb-2">
                                        <div class="col-md  mb-2">
                                            <label for="descricao_produto" class="form-label">Produto</label>
                                            <div class="input-group">
                                                <input type="text" name="descricao_produto" class="form-control" <?php if (verficar_paramentro($conecta, "tb_parametros", "cl_id", "14") == "N") {
                                                                                                                        echo 'readonly';
                                                                                                                    } ?> id="descricao_produto" placeholder="Descrição do Item">
                                                <button class="btn btn-outline-secondary" type="button" name="modal_produto" id="modal_produto">Pesquisar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6 col-md  mb-2">
                                            <label for="prazo_entrega" class="form-label">Prazo entrega</label>
                                            <div class="input-group ">
                                                <input type="number" class="form-control " name="prazo_entrega" id="prazo_entrega" placeholder="EX. 7" value="">
                                                <span class="input-group-text">Dia(s)</span>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md mb-2">
                                            <label for="unidade" class="form-label">Unidade</label>
                                            <input type="text" class="form-control" name="unidade" id="unidade" value="" <?php if (verficar_paramentro($conecta, "tb_parametros", "cl_id", "14") == "N") {
                                                                                                                                echo 'readonly';
                                                                                                                            } ?> placeholder="Ex. und">
                                        </div>

                                        <div class="col-6 col-md  mb-2 ">
                                            <label for="quantidade" class="form-label">Quantidade</label>
                                            <input type="text" class="form-control " onblur="calcular_valor_total_item()" placeholder="0.00" name="quantidade" id="quantidade" value="">
                                        </div>

                                        <div class="col-6 col-md   mb-2">
                                            <label for="preco_venda" class="form-label">Preço unitário</label>
                                            <div class="input-group ">
                                                <span class="input-group-text">R$</span>
                                                <input type="text" class="form-control" onblur="calcular_valor_total_item()" placeholder="0.00" name="preco_venda" id="preco_venda" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-3  mb-2">
                                            <label for="valor_total_item" class="form-label">Preço total</label>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text">R$</span>
                                                <input type="text" class="form-control" disabled name="valor_total" id="valor_total" value="">
                                                <button type="submit" id="adicionar_produto" onclick="calcular_valor_total_item()" class="btn btn-success">Salvar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="tabela_produtos tabela border"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal_externo"></div>
</div>

<script src="js/compra/pedido_compra/pedido_tela.js"></script>