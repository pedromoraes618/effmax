<?php

include "../../../modal/compra/compra_mercadoria/gerenciar_compra.php";

?>
<div class="modal fade" id="modal_compra" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Compra de Mercadoria</h1>
                <button type="button" class="btn-close" id="fechar_modal_compra" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="nota_fsical_entrada">

                    <input type="hidden" id="id" name="id" value="<?php echo $form_id; ?>">
                    <input type="hidden" id="codigo_nf" name="codigo_nf" value="<?php echo $codigo_nf; ?>">
                    <div class="col-md mb-4">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                            <button type="button" id="finalizar_nf" onclick="calcularTotal()" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Finalizar</button>

                            <button type="submit" id="alterar_nf" onclick="calcularTotal()" class="btn btn-sm btn-dark"><i class="bi bi-check2"></i> Alterar</button>
                            <?php if ($form_id != "") { ?>
                                <button type="button" id="cancelar_nf" onclick="calcularTotal()" class="btn btn-sm btn-danger"><i class="bi bi-x-circle"></i> Cancelar</button>
                                <button type="button" id="cancelar_provisionamento_nf" onclick="calcularTotal()" class="btn btn-sm btn-danger"><i class="bi bi-folder-x"></i> Cancelar Provisionamento</button>
                                <button type="button" id="remover_nf" onclick="calcularTotal()" class="btn btn-sm btn-danger"><i class="bi bi-trash3-fill"></i> Remover</button>
                            <?php } ?>
                            <!-- <button type="button" id="modal_observacao" class="btn btn-sm btn-dark">Informação adicionais</button> -->
                            <button type="button" class="btn btn-sm btn-secondary fechar_modal_compra" onclick="window.opener.location.reload(); window.close();" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-6  col-md-2 mb-2">
                            <label for="data_entrada" class="form-label">Data Entrada</label>
                            <input type="date" class="form-control" id="data_entrada" name="data_entrada" value="">
                        </div>

                        <div class="col-sm-6  col-md-2 mb-2">
                            <label for="data_emissao" class="form-label">Data Emissão</label>
                            <input type="date" class="form-control" id="data_emissao" name="data_emissao" value="">
                        </div>

                        <div class="col-sm-6  col-md-2 mb-2">

                            <label for="numero_nf" class="form-label">Nº NF</label>
                            <input type="number" class="form-control" id="numero_nf" name="numero_nf" value="">
                        </div>

                        <div class="col-sm-6  col-md-2 mb-2">
                            <label for="fpagamento" class="form-label">Forma Pagamento </label>
                            <select name="fpagamento" class="select2-modal chosen-select" id="fpagamento">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_forma_pagamento');
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
                        <div class=" col-md-4 mb-2">
                            <label for="cfop" class="form-label">Cfop </label>
                            <select name="cfop" class="select2-modal chosen-select " id="cfop">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_cfop');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $codigo_cfop = $linha['cl_codigo_cfop'];
                                        $descricao = utf8_encode($linha['cl_desc_cfop']);

                                        echo "<option value='$codigo_cfop'>$codigo_cfop $descricao</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-6  col-md-2 mb-2">
                            <label for="serie" class="form-label">Série</label>
                            <select name="serie" class="select2-modal chosen-select" id="serie">
                                <option value="0">Selecione..</option>

                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_serie');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = $linha['cl_id'];
                                        $descricao = utf8_encode($linha['cl_descricao']);

                                        echo "<option value='$descricao'>$descricao</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-6  col-md-3 mb-2">
                            <label for="frete" class="form-label">Frete</label>
                            <select name="frete" class="select2-modal chosen-select" id="frete">
                                <option value="SN">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_frete');
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
                        <div class=" col-md mb-2">
                            <label for="chave_acesso" class="form-label">Chave de acesso</label>
                            <input type="number" class="form-control" id="chave_acesso" name="chave_acesso" value="">
                        </div>
                        <div class=" col-md-3  mb-2">
                            <label for="protocolo" class="form-label">Protocolo</label>
                            <input type="number" class="form-control" id="protocolo" name="protocolo" value="">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="  col-md  mb-2">
                            <label for="parceiro_descricao" class="form-label">Fornecedor</label>
                            <div class="input-group">
                                <input type="text" class="form-control" readonly id="parceiro_descricao" name="parceiro_descricao" placeholder="Pesquise pelo Cliente/Fornecedor" aria-label="Recipient's username" aria-describedby="button-addon2">
                                <input type="hidden" class="form-control" name="parceiro_id" id="parceiro_id" value="">
                                <button class="btn btn-secondary" type="button" name="modal_parceiro" id="modal_parceiro">Pesquisar</button>
                            </div>
                        </div>
                        <div class=" col-md ">
                            <label for="transportadora_descricao" class="form-label">Transportadora</label>
                            <div class="input-group">
                                <input type="text" class="form-control" readonly id="transportadora_descricao" name="transportadora_descricao" placeholder="Pesquise pela Transportadora" aria-label="Recipient's username" aria-describedby="button-addon2">
                                <input type="hidden" class="form-control" name="transportadora_id" id="transportadora_id" value="">
                                <button class="btn btn-secondary" type="button" name="modal_transportadora" id="modal_transportadora">Pesquisar</button>
                            </div>
                        </div>

                    </div>

                    <div class="accordion " id="accordionPanelsStayOpenExample">
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne-3" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne-3">
                                    Valores
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseOne-3" class="accordion-collapse collapse show ">
                                <div class="accordion-body">

                                    <div class="row border-success">
                                        <div class="col-sm-6  col-md-2  mb-2">
                                            <label for="bcicms_nota" class="form-label">Bc Icms</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" disabled name="bcicms_nota" id="bcicms_nota" value="">
                                            </div>
                                        </div>
                                        <div class="col-sm-6  col-md-2  mb-2">
                                            <label for="icms_nota" class="form-label">Icms</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" disabled name="icms_nota" id="icms_nota" value="">
                                            </div>
                                        </div>
                                        <div class="col-sm-6  col-md-2  mb-2">
                                            <label for="bcicms_sub_nota" class="form-label">Bc Icms Sub</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" disabled name="bcicms_sub_nota" id="bcicms_sub_nota" value="">
                                            </div>
                                        </div>
                                        <div class="col-sm-6  col-md-2  mb-2">
                                            <label for="icms_sub_nota" class="form-label">Icms Sub</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" disabled name="icms_sub_nota" id="icms_sub_nota" value="">
                                            </div>
                                        </div>

                                        <div class="col-sm-6  col-md-2  mb-2">
                                            <label for="ipi_nota" class="form-label">IPI</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" disabled name="ipi_nota" id="ipi_nota" value="">
                                            </div>
                                        </div>
                                        <div class="col-sm-6  col-md-2  mb-2">
                                            <label for="vfrete" class="form-label">Frete</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" onchange="calcularTotal()" name="vfrete" id="vfrete" value="">
                                            </div>
                                        </div>
                                        <div class="col-sm-6  col-md-2  mb-2">
                                            <label for="vfrete_conhecimento" class="form-label">Frete Conhecimento</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" onchange="calcularTotal()" name="vfrete_conhecimento" id="vfrete_conhecimento" value="">
                                            </div>
                                        </div>
                                        <div class="col-sm-6  col-md-2  mb-2">
                                            <label for="vseguro" class="form-label">Seguro</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" onchange="calcularTotal()" name="vseguro" id="vseguro" value="">
                                            </div>
                                        </div>
                                        <div class="col-sm-6  col-md-2  mb-2">
                                            <label for="outras_despesas" class="form-label">Outras Despesas</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" onchange="calcularTotal()" name="outras_despesas" id="outras_despesas" value="">
                                            </div>
                                        </div>

                                        <div class="col-sm-6  col-md-2  mb-2">
                                            <label for="vlr_total_produtos" class="form-label">Valor Produtos</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" disabled name="vlr_total_produtos" id="vlr_total_produtos" value="">
                                            </div>
                                        </div>
                                        <div class="col-sm-6  col-md-2  mb-2">
                                            <label for="desconto_nota" class="form-label">Desconto</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" onchange="calcularTotal()" name="desconto_nota" id="desconto_nota" value="">
                                            </div>
                                        </div>

                                        <div class="col-sm-6  col-md-2  mb-2">
                                            <label for="vlr_total_nota" class="form-label">Valor Nota</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" disabled class="form-control" name="vlr_total_nota" id="vlr_total_nota" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6  col-md">
                                            <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                                                <!-- <button type="button" id="modal_observacao" class="btn btn-sm btn-dark">Informação adicionais</button> -->
                                                <button type="button" id="modal_produto_add_item" class="btn btn-sm btn-success"><i class="bi bi-plus-circle"></i> Incluir item</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
                                    Informações adicionais
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show ">
                                <div class="accordion-body">
                                    <div class="row mb-2">
                                        <div class="col-md  mb-2">
                                            <textarea rows="3" class="form-control" name="informacoes_adicionais" placeholder="Ex nº pedido 1568744" id="informacoes_adicionais"></textarea>
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
                            <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne-1" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne-1">
                                Item
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseOne-1" class="accordion-collapse collapse show ">
                            <div class="accordion-body">
                                <div class=" tabela_externa tabela_modal "></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal_externo"></div>
</div>


<script src="js/compra/compra_mercadoria/compra_tela.js"></script>