<?php
include "../../../conexao/conexao.php";
include "../../../modal/venda/nf_saida/gerenciar_nf_saida.php";
include "../../../funcao/funcao.php";
?>
<div class="modal fade" id="modal_nf_fiscal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Documento Fiscal</h1>
                <button type="button" class="btn-close btn-close-modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form class="form_principal" id="nota_fsical_saida">

                    <div class="col-md mb-4">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                            <button type="submit" id="salvar_nf" onclick="calcularTotal()" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                            <button type="button" id="recalcular_nf" onclick="calcularTotal()" class="btn btn-sm btn-dark"><i class="bi bi-arrow-clockwise"></i> Recalcular Nota</button>
                            <button type="button" class="btn btn-sm btn-close-modal btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm">
                            <span class="badge rounded-2 mb-3 d-area dv">Informações Básicas</span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-sm-6  col-md-1 mb-2">
                            <input type="hidden" id="id" name="id" value="<?php echo $id_nf ?>">
                            <input type="hidden" id="codigo_nf" name="codigo_nf" value="<?php echo $codigo_nf ?>">
                            <label for="numero_nf" class="form-label">Nº NF</label>
                            <input type="text" class="form-control" disabled id="numero_nf" name="numero_nf" value="">
                        </div>
                        <div class="col-sm-6  col-md-2 mb-2">
                            <label for="data_emissao" class="form-label">Data emissão</label>
                            <input type="text" class="form-control" disabled id="data_emissao" name="data_emissao" value="">
                        </div>

                        <div class="col-sm-6  col-md-2 mb-2">
                            <label for="finalidade" class="form-label">Finalidade </label>
                            <select name="finalidade" class="select2-modal chosen-select chosen-select" id="finalidade">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_finalidade');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = $linha['cl_id'];
                                        $descricao = utf8_encode($linha['cl_descricao']);

                                        echo "<option  value='$id'>$id - $descricao</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="tipo_nota" class="form-label">Tipo nota </label>
                            <select name="tipo_nota" class="select2-modal chosen-select chosen-select" id="tipo_nota">
                                <option value="SN">Selecione..</option>
                                <option value="1">1 - Saida</option>
                                <option value="0">0 - Entrada</option>

                            </select>
                        </div>

                        <div class="col-md-5 mb-2">
                            <label for="frete" class="form-label">Frete</label>
                            <select name="frete" class="select2-modal chosen-select chosen-select chosen-select" id="frete">
                                <option value="SN">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_frete');
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
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 mb-2">
                            <label for="fpagamento" class="form-label">Forma pagamento </label>
                            <select style="width: 100%;" name="fpagamento" class="select2-modal chosen-select chosen-select" id="fpagamento">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb_filtro($conecta, 'tb_forma_pagamento', 'cl_ativo', 'S');
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


                        <input type="hidden" name="formulario_nota_fiscal">
                        <div class="col-sm-6  col-md-2 mb-2">
                            <label for="numero_pedido" class="form-label">Nº pedido</label>
                            <input type="text" class="form-control" id="numero_pedido" name="numero_pedido" value="" placeholder="Ex. 15">
                        </div>

                        <div class="col-md mb-2">
                            <label for="chave_acesso" class="form-label">Chave de acesso</label>
                            <input type="text" class="form-control" disabled id="chave_acesso" name="chave_acesso" value="">
                        </div>
                        <div class="col-md-3  mb-2">
                            <label for="protocolo" class="form-label">Protocolo</label>
                            <input type="text" class="form-control" disabled id="protocolo" name="protocolo" value="">
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md mb-2">
                            <label for="cfop" class="form-label">Cfop</label>
                            <select style="width: 100%; " name="cfop" class="select2-modal chosen-select chosen-select" id="cfop">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_cfop');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $codigo_cfop = $linha['cl_codigo_cfop'];
                                        $descricao = utf8_encode($linha['cl_desc_cfop']);

                                        echo "<option  value='$codigo_cfop'>$codigo_cfop - $descricao</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md  mb-2">
                            <label for="parceiro_id" class="form-label">Cliente</label>
                            <div class="input-group">
                                <input type="text" class="form-control" readonly id="parceiro_descricao" name="parceiro_descricao" placeholder="Pesquise pelo Cliente/Fornecedor" aria-label="Recipient's username" aria-describedby="button-addon2">
                                <input type="hidden" class="form-control" name="parceiro_id" id="parceiro_id" value="">
                                <button class="btn btn-secondary" type="button" name="modal_parceiro" id="modal_parceiro">Pesquisar</button>
                            </div>
                        </div>
                    </div>

                    <div class="accordion " id="accordionPanelsStayOpenExample">
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                    Duplicatas
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse ">
                                <div class="accordion-body">
                                    <div class="row mb-2">
                                        <div class="col-md">
                                            <div class="form-check">
                                                <input class="form-check-input" name="visualzar_duplicatas" type="checkbox" value="" id="visualzar_duplicatas">
                                                <label class="form-check-label" for="visualzar_duplicatas">
                                                    Visualizar Duplicatas na Nota ?
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row duplicatas mb-2">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
                                    Transportadora
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse  ">
                                <div class="accordion-body">
                                    <div class="row mb-2">
                                        <div class="col-md  mb-2">
                                            <label for="transportadora_id" class="form-label">Empresa</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" readonly id="transportadora_descricao" name="transportadora_descricao" placeholder="Pesquise pela Transportadora" aria-label="Recipient's username" aria-describedby="button-addon2">
                                                <input type="hidden" class="form-control" name="transportadora_id" id="transportadora_id" value="">
                                                <button class="btn btn-secondary" type="button" name="modal_transportadora" id="modal_transportadora">Pesquisar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6  col-md-2 ">
                                            <label for="placa" class="form-label">Placa</label>
                                            <input type="text" class="form-control" id="placa" name="placa" value="" placeholder="Ex. hx4568">
                                        </div>
                                        <div class="col-sm-6  col-md mb-2">
                                            <label for="uf_veiculo" class="form-label">UF Veículo</label>
                                            <input type="text" class="form-control" id="uf_veiculo" name="uf_veiculo" value="" placeholder="Ex. MA">
                                        </div>
                                        <div class="col-sm-6 col-md mb-2">
                                            <label for="quantidade_trp" class="form-label">Quantidade</label>
                                            <input type="number" step="any" class="form-control" id="quantidade_trp" name="quantidade_trp" value="" placeholder="0.00">
                                        </div>
                                        <div class="col-sm-6 col-md  mb-2">
                                            <label for="especie" class="form-label">Espécie</label>
                                            <input type="text" class="form-control" id="especie" name="especie" value="" placeholder="Ex. Volume">
                                        </div>
                                        <div class="col-sm-6 col-md mb-2">
                                            <label for="peso_bruto" class="form-label">Peso bruto</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">kg</span>
                                                <input type="number" step="any" class="form-control" id="peso_bruto" name="peso_bruto" value="" placeholder="Ex. 0.400">
                                            </div>
                                        </div>
                                        <div class="col-sm-6 col-md  mb-2">
                                            <label for="peso_liquido" class="form-label">Peso Liquido</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">kg</span>
                                                <input type="number" step="any" class="form-control" id="peso_liquido" name="peso_liquido" value="" placeholder="Ex. 0.40">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="true" aria-controls="panelsStayOpen-collapseThree">
                                    Devolução
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse  ">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-sm-6 col-md  mb-2">
                                                <label for="chave_acess_ref" class="form-label">Chave acesso ref</label>
                                                <input type="text" class="form-control" id="chave_acesso_ref" name="chave_acesso_ref" placeholder="Informe o número da chave de acesso da nota de venda ou compra">
                                            </div>
                                            <div class="col-sm-6 col-sm-6  col-md-2  mb-2">
                                                <label for="chave_acess_ref" class="form-label">Número nfe ref</label>
                                                <input type="text" class="form-control" id="numero_nf_ref" name="numero_nf_ref" placeholder="Ex. 16">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="true" aria-controls="panelsStayOpen-collapseFour">
                                    Informações adicionais
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse  ">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="row">
                                            <div class="col-md  mb-2">
                                                <label for="observacao" class="form-label">Observação</label>
                                                <textarea type="text" class="form-control" id="observacao" name="observacao" placeholder="Ex. Nota de saida"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseSix" aria-expanded="true" aria-controls="panelsStayOpen-collapseSix">
                                    Serviço
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseSix" class="accordion-collapse collapse  ">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-8 mb-3">
                                            <label for="parceiro_terceirizado_id" class="form-label">Empresa terceirizada *</label>
                                            <select name="parceiro_terceirizado_id" class="select2-modal" id="parceiro_terceirizado_id">
                                                <option value="0">Selecione..</option>
                                                <?php
                                                $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_parceiros where cl_situacao_ativo = 'SIM'");
                                                if ($resultados) {
                                                    foreach ($resultados as $linha) {
                                                        $id = $linha['cl_id'];
                                                        $razao_social = utf8_encode($linha['cl_razao_social']);
                                                        $cpf_cnpj = ($linha['cl_cnpj_cpf']);
                                                        echo "<option value='$id'> $razao_social - $cpf_cnpj</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md">
                                            <span class="badge rounded-pill text-bg-dark d-area dv">Impostos federais</span>
                                        </div>
                                    </div>
                                    <div class="row border-success">
                                        <div class="col-md-2  mb-2">
                                            <label for="valor_pis_servico" class="form-label">Valor pis</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="text" class="form-control" name="valor_pis_servico" id="valor_pis_servico" value="" placeholder="0.00">
                                            </div>
                                        </div>


                                        <div class="col-md-2  mb-2">
                                            <label for="valor_cofins_servico" class="form-label">Valor cofins</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="text" class="form-control" name="valor_cofins_servico" id="valor_cofins_servico" value="" placeholder="0.00">
                                            </div>
                                        </div>

                                        <div class="col-md-2  mb-2">
                                            <label for="valor_inss" class="form-label">Valor inss</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="text" class="form-control" name="valor_inss" id="valor_inss" value="" placeholder="0.00">
                                            </div>
                                        </div>
                                        <div class="col-md-2  mb-2">
                                            <label for="valor_ir" class="form-label">Valor ir</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="text" class="form-control" name="valor_ir" id="valor_ir" value="" placeholder="0.00">
                                            </div>
                                        </div>
                                        <div class="col-md-2  mb-2">
                                            <label for="valor_csll" class="form-label">Valor csll</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="text" class="form-control" name="valor_csll" id="valor_csll" value="" placeholder="0.00">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md">
                                            <span class="badge rounded-pill text-bg-dark d-area dv">Impostos municipais</span>
                                        </div>
                                    </div>
                                    <div class="row border-success">
                                        <div class="col-md-2  mb-2">
                                            <label for="valor_base_calculo" class="form-label">Base cálculo</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="text" class="form-control" disabled name="valor_base_calculo" id="valor_base_calculo" value="" placeholder="0.00">
                                            </div>
                                        </div>
                                        <div class="col-md-2  mb-2">
                                            <label for="valor_aliquota" class="form-label">Valor aliq</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">%</span>
                                                <input type="text" class="form-control" name="valor_aliquota" id="valor_aliquota" value="" placeholder="0.00">
                                            </div>
                                        </div>
                                        <div class="col-md-2  mb-2">
                                            <label for="valor_iss" class="form-label">Valor Iss</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="text" class="form-control" disabled name="valor_iss" id="valor_iss" value="" placeholder="0.00">
                                            </div>
                                        </div>
                                        <div class="col-md-2  mb-2">
                                            <label for="valor_desconto_condicionado" class="form-label">Desc. condicionado</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="text" class="form-control" name="valor_desconto_condicionado" id="valor_desconto_condicionado" value="" placeholder="0.00">
                                            </div>
                                        </div>
                                        <div class="col-md-2  mb-2">
                                            <label for="valor_desconto_incondicionado" class="form-label">Desc. incondicionado</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="text" class="form-control" name="valor_desconto_incondicionado" id="valor_desconto_incondicionado" value="" placeholder="0.00">
                                            </div>
                                        </div>
                                        <div class="col-md-2  mb-2">
                                            <label for="valor_deducoes" class="form-label">Valor deducoes</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="text" class="form-control" name="valor_deducoes" id="valor_deducoes" value="" placeholder="0.00">
                                            </div>
                                        </div>
                                        <div class="col-md-2  mb-2">
                                            <label for="valor_outras_retencoes" class="form-label">Outras retencoes</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="text" class="form-control" name="valor_outras_retencoes" id="valor_outras_retencoes" value="" placeholder="0.00">
                                            </div>
                                        </div>

                                        <div class="col-md-5 mb-2">
                                            <label for="atividade_id" class="form-label">Atividade serviço *</label>
                                            <select name="atividade_id" class="form-select" id="atividade_id">
                                                <option value="0">Selecione..</option>
                                                <?php
                                                $resultados = consulta_linhas_tb($conecta, 'tb_atividade_servico');
                                                if ($resultados) {
                                                    foreach ($resultados as $linha) {
                                                        $id = $linha['cl_id'];
                                                        $descricao = utf8_encode($linha['cl_nome_atividade']);
                                                        $servico_id = utf8_encode($linha['cl_servico_id']);

                                                        echo "<option value='$id'>$servico_id - $descricao</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="col-md-5 mb-2">
                                            <label for="natureza_operacao_servico" class="form-label">Natureza Operação *</label>
                                            <select name="natureza_operacao_servico" class="form-select" id="natureza_operacao_servico">
                                                <option value="0">Selecione..</option>
                                                <?php
                                                $resultados = consulta_linhas_tb($conecta, 'tb_natureza_operacao_servico');
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
                                    <div class="row">
                                        <div class="col-md-auto  mb-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="retem_iss" type="checkbox" role="switch" id="retem_iss">
                                                <label class="form-check-label" for="retem_iss">Retem Iss</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="true" aria-controls="panelsStayOpen-collapseFive">
                                    Valores
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse show ">
                                <div class="accordion-body ">
                                    <div class="row">
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
                                            <label for="bciss_nota" class="form-label">Bc ISS</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" disabled name="bciss_nota" id="bciss_nota" value="">
                                            </div>
                                        </div>
                                        <div class="col-sm-6  col-md-2  mb-2">
                                            <label for="iss_nota" class="form-label">Iss</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" disabled name="iss_nota" id="iss_nota" value="">
                                            </div>
                                        </div>
                                        <div class="col-sm-6  col-md-2  mb-2">
                                            <label for="bcpis_nota" class="form-label">Bc Pis</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" disabled name="bcpis_nota" id="bcpis_nota" value="">
                                            </div>
                                        </div>
                                        <div class="col-sm-6  col-md-2  mb-2">
                                            <label for="pis_nota" class="form-label">Pis</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" disabled name="pis_nota" id="pis_nota" value="">
                                            </div>
                                        </div>
                                        <div class="col-sm-6  col-md-2  mb-2">
                                            <label for="bccofins_nota" class="form-label">Bc Cofins</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" disabled name="bccofins_nota" id="bccofins_nota" value="">
                                            </div>
                                        </div>
                                        <div class="col-sm-6  col-md-2  mb-2">
                                            <label for="cofins_nota" class="form-label">Cofins</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" disabled name="cofins_nota" id="cofins_nota" value="">
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
                                            <label for="vlr_total_produtos" class="form-label">Valor Itens</label>
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
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
                <div class="accordion " id="accordionPanelsStayOpenExample">
                    <div class="accordion-item mb-2 ">
                        <h2 class="accordion-header ">
                            <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne-2" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne-2">
                                Itens
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseOne-2" class="accordion-collapse collapse show ">
                            <div class="accordion-body">
                                <div class="tabela_externa tabela_modal"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal_externo"></div>
</div>

<script src="js/venda/nf_saida/nf_tela.js"></script>