<?php
include "../../../modal/servico/ordem_servico/gerenciar_ordem_servico.php";
?>
<div class="modal fade " id="modal_despesa_ordem_servico" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg ">
        <div class="modal-content border ">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Despesa</h1>
                <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="incluir_despesa">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end btn-acao">
                        <button type="button" class="btn btn-sm btn-secondary btn-fechar" data-bs-dismiss="modal">Fechar</button>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 mb-2">
                            <label for="dt_pagamento" class="form-label">Dt pagamento</label>
                            <input type="date" class="form-control" id="dt_pagamento" name="dt_pagamento" value="<?php echo $data_lancamento; ?>">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="classificao_id" class="form-label">Classificação *</label>
                            <select id="classificao_id" class="select2-modal-modal chosen-select" name="classificao_id">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_classificacao_financeiro');
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
                        <div class="col-md-3 mb-2">
                            <label for="forma_pagamento_id" class="form-label">Forma Pagamento *</label>
                            <select id="forma_pagamento_id" class="select2-modal-modal  chosen-select" name="forma_pagamento_id">
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
                        <div class="col-md-3 mb-2">
                            <label for="tipo" class="form-label">Tipo *</label>
                            <select id="tipo" class="select2-modal-modal  chosen-select" name="tipo">
                                <option value="0">Selecione..</option>
                                <option value="1">Contabiliza na OS</option>
                                <option value="2">Não Contabiliza na Os</option>
                            </select>
                        </div>

                    </div>

                    <div class="row mb-2">
                        <div class="col-md-4 mb-2">
                            <label for="servico_id" class="form-label">Serviço <span class="d-inline-block BD-" tabindex="0" data-bs-toggle="popover"
                                    data-bs-placement="top" data-bs-trigger="hover focus" data-bs-content="Especifique o serviço ao qual este material será destinado.">
                                    <i class="bi bi-info-circle"></i>
                                </span></label>
                            <select name="servico_id" id="servico_id" class="select2-modal-modal">
                                <option value="0">Selecione</option>
                                <?php
                                $codigo_nf = consulta_tabela($conecta, 'tb_os', 'cl_id', $_GET['form_id'], 'cl_codigo_nf');
                                $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_infraestrutura_os where cl_codigo_nf = '$codigo_nf' and cl_tipo ='SERVICO'");
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = $linha['cl_id'];
                                        $descricao = utf8_encode($linha['cl_item_descricao']);
                                        echo "<option value='$id'> $id - $descricao</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-8 mb-2">
                            <label for="parceiro_id" class="form-label">Fornecedor *</label>
                            <select id="parceiro_id" class="select2-modal-modal  chosen-select" name="parceiro_id">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_parceiros where cl_situacao_ativo ='SIM'");
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = $linha['cl_id'];
                                        $descricao = utf8_encode($linha['cl_razao_social']);
                                        $cpfCnpj = esconderParteCPF($linha['cl_cnpj_cpf']);

                                        echo "<option value='$id'>$descricao - $cpfCnpj</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">

                        <div class="col-md-3  mb-2">
                            <label for="valor" class="form-label">Valor</label>
                            <div class="input-group ">
                                <span class="input-group-text">R$</span>
                                <input type="number" step="any" class="form-control" id="valor" name="valor" value="" placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md  mb-2">
                            <label for="descricao" class="form-label">Descrição</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Ex. Pagamento da gasolina" value="">
                                <button type="submit" id="button_form" class="btn btn-sm btn-success "><i class="bi bi-check-all"></i> Incluir</button>
                            </div>
                        </div>

                    </div>
                </form>
                <div class="tabela_taxa tabela_modal " style="max-height: 500px;"> </div>
            </div>
        </div>
    </div>
    <div class="modal_externo_3"></div>
</div>

<script src="js/include/servico/adicionar_despesa.js"></script>