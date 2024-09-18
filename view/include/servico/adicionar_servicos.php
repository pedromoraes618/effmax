<?php

include "../../../modal/servico/ordem_servico/gerenciar_ordem_servico.php";
?>
<div class="modal fade " id="modal_servicos_ordem_servico" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border ">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Serviços</h1>
                <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="servico">
                    <div class="title mb-2">
                        <label class="form-label sub-title sub-title-material"></label>
                    </div>
                    <div class="d-flex flex-wrap justify-content-end gap-2 mb-3">
                        <button type="submit" id="button_form_peca" class="btn btn-sm btn-success "><i class="bi bi-check-all"></i> Salvar</button>
                        <button type="button" class="btn btn-sm btn-secondary btn-fechar" data-bs-dismiss="modal">Fechar</button>
                    </div>
                    <div class="row mbb-2">
                        <input type="hidden" id="item_id_servico" name="item_id">
                        <div class="col-md  mb-2">
                            <label for="descricao_servico" class="form-label">Serviço *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="descricao_servico" name="descricao_servico" placeholder="Ex. Manutenção de materiais">
                                <input type="hidden" class="form-control" name="servico_id" id="servico_id" value="">
                                <!--<input type="hidden" class="form-control" name="referencia" id="referencia" value=""> -->
                                <!-- <input type="hidden" class="form-control" name="estoque" id="estoque" value=""> -->
                                <button class="btn btn-secondary" type="button" name="modal_servico" id="modal_servico">Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-2  mb-2">
                            <label for="responsavel" class="form-label">Responsável/Técnico *</label>
                            <select id="responsavel" class="form-select chosen-select" name="responsavel">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_users where cl_ativo ='1' and cl_tecnico = 'SIM'");
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = $linha['cl_id'];
                                        $responsavel = utf8_encode($linha['cl_usuario']);
                                        $selected = $id == $usuario_id ? 'selected' : '';

                                        echo "<option $selected value='$id'>$responsavel</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-6 col-md-2 mb-2">
                            <label for="quantidade" class="form-label">Quantidade *</label>
                            <input type="number" step="any" class="form-control" onblur="calcular_valor_total()" name="quantidade" id="quantidade_servico_item" placeholder="0.00" value="">
                        </div>

                        <div class="col-6 col-md-3  mb-2">
                            <label for="valor_unitario" class="form-label">Valor Unitário *</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" step="any" class="form-control " onblur="calcular_valor_total()" name="valor_unitario" id="valor_unitario_servico_item" placeholder="0.00" value="">

                            </div>
                        </div>
                        <div class="col-md-3  mb-2">
                            <label for="valor_total_item" class="form-label">Valor Total</label>
                            <div class="input-group ">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" disabled name="valor_total" id="valor_total_servico_item" value="">
                            </div>
                        </div>
                    </div>
                    <div class="accordion " id="accordionPanelsStayOpenExample">
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne-2" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne-2">
                                    Dados adicionais
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseOne-2" class="accordion-collapse collapse show ">
                                <div class="accordion-body">
                                    <div class="row mb-2">
                                        <div class="col-md-5 mb-2">
                                            <label for="parceiro_terceirizado" class="form-label">Empresa terceirizada </label>
                                            <select name="parceiro_terceirizado" class="select2-modal-modal" id="parceiro_terceirizado">
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
                                        <div class="col-6 col-md mb-2">
                                            <label for="data_inicio_terceirizado" class="form-label">Data Inicio</label>
                                            <input type="date" class="form-control" name="data_inicio_terceirizado" id="data_inicio_terceirizado" placeholder="">
                                        </div>
                                        <div class="col-6 col-md mb-2">
                                            <label for="data_fim_terceirizado" class="form-label">Data Fim</label>
                                            <input type="date" class="form-control" name="data_fim_terceirizado" id="data_fim_terceirizado" placeholder="">
                                        </div>
                                        <div class="col-md mb-2">
                                            <label for="valor_fechado_terceirizado" class="form-label">Valor fechado</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" name="valor_fechado_terceirizado" id="valor_fechado_terceirizado" value="" placeholder="0.00">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md">
                                            <label for="descricao_terceirizado_terceirizado" class="form-label">Descrição serviço</label>
                                            <textarea name="descricao_terceirizado_terceirizado" id="descricao_terceirizado_terceirizado" class="form-control" rows="3" placeholder="Ex. Executar manutenção nas maquinas"></textarea>
                                        </div>
                                    </div>

                                    <div class="accordion " id="accordionPanelsStayOpenExample">
                                        <div class="accordion-item mb-2 ">
                                            <h2 class="accordion-header">
                                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne-3" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne-3">
                                                    Equipe
                                                </button>
                                            </h2>
                                            <div id="panelsStayOpen-collapseOne-3" class="accordion-collapse collapse show ">
                                                <div class="accordion-body">
                                                    <div id="equipe-fields"></div> <!-- Aqui serão adicionados os campos -->
                                                    <div class="d-flex flex-wrap justify-content-end gap-2 mb-3">
                                                        <button type="button" id="add-equipe-btn" class="btn btn-primary btn-sm ">Adicionar</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="card p-2 border-0 border-top shadow tabela tabela_servicos tabela_modal  mb-2" style="max-height:500px;"></div>
            </div>
        </div>
    </div>
    <div class="modal_externo_3"></div>
</div>

<script src="js/include/servico/adicionar_servicos.js"></script>