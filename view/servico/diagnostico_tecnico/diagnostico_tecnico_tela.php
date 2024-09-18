<?php
include "../../../modal/servico/ordem_servico/gerenciar_ordem_servico.php";
?>


<div class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-hidden="true" id="modal_diagnostico_tecnico" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Diagnóstico Técnico</h1>
                <button type="button" class="btn-close btn-fechar" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="title mb-2">
                    <label class="form-label sub-title">Diagnóstico de Serviço e Material</label>
                </div>
                <form id="servico_os">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end btn-acao">
                        <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                        <button type="button" class="btn btn-sm btn-secondary btn-fechar" data-bs-dismiss="modal">Fechar</button>
                    </div>
                    <input type="hidden" id="id" name="id" value="<?= $form_id; ?>">
                    <div class="row mb-2">
                        <div class="col-md-2  mb-2">
                            <label for="numero_ordem" class="form-label">Nº Ordem</label>
                            <input type="text" disabled id="numero_ordem" class="form-control numero_ordem" value="">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md  mb-2">
                            <label for="equipamento" class="form-label">Equipamento *</label>
                            <input type="text" class="form-control" name="equipamento" id="equipamento" placeholder="Matelo..">
                        </div>
                        <div class="col-md-3  mb-2">
                            <label for="numero_serie" class="form-label">Nº Série</label>
                            <input type="text" class="form-control" name="numero_serie" id="numero_serie" placeholder="1568751..">
                        </div>
                        <div class="col-md-2  mb-2">
                            <label for="numero_caixa" class="form-label">Nº Caixa</label>
                            <input type="text" class="form-control" name="numero_caixa" id="numero_caixa" placeholder="4897..">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md mb-2">
                            <label for="tipo_servico" class="form-label">Tipo Serviço *</label>
                            <select id="tipo_servico" class="select2-modal chosen-select" name="tipo_servico">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_tipo_servico_os');
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
                            <label for="status" class="form-label">Status *</label>
                            <select id="status" class="select2-modal chosen-select" name="status">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_status_os');
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
                        <div class="col-md  mb-2">
                            <label for="defeito_constatado" class="form-label">Defeito Constatado *</label>
                            <textarea name="defeito_constatado" class="form-control" id="defeito_constatado" cols="30" rows="3" placeholder="Ex. Troca de pastilha"></textarea>
                        </div>
                        <div class="col-md  mb-2">
                            <label for="defeito_informado" class="form-label">Defeito Informado</label>
                            <textarea class="form-control" name="defeito_informado" id="defeito_informado" cols="30" rows="3" disabled></textarea>
                        </div>
                    </div>
                </form>
                <div class="accordion " id="accordionPanelsStayOpenExample">
                    <div class="accordion-item mb-2 ">
                        <h2 class="accordion-header ">
                            <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                Serviço
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show ">
                            <div class="accordion-body">
                                <form action="" id="item_servico">
                                    <div class="row mbb-2">
                                        <input type="hidden" id="item_id_servico" name="item_id">
                                        <div class="col-md  mb-2">
                                            <label for="descricao_servico" class="form-label">Serviço *</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" id="descricao_servico" name="descricao_servico" placeholder="Ex. Mão de obra...">
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
                                            <select id="responsavel" class="select2-modal chosen-select" name="responsavel">
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
                                        <div class="col-md-2 mb-2">
                                            <label for="quantidade" class="form-label">Quantidade *</label>
                                            <input type="number" step="any" class="form-control" onblur="calcular_valor_total_servico_item()" name="quantidade" id="quantidade_servico_item" value="" placeholder="0.00">
                                        </div>

                                        <div class="col-md  mb-2">
                                            <label for="valor_unitario" class="form-label">Valor Unitário *</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control " onblur="calcular_valor_total_servico_item()" name="valor_unitario" id="valor_unitario_servico_item" value="" placeholder="0.00">

                                            </div>
                                        </div>
                                        <div class="col-md  mb-2">
                                            <label for="valor_total_item" class="form-label">Valor Total</label>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text">R$</span>
                                                <input type="text" class="form-control" disabled name="valor_total" id="valor_total_servico_item" value="" placeholder="0.00">
                                                <button type="submit" id="button_form_peca" class="btn btn-sm btn-success "><i class="bi bi-check-all"></i> Salvar</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class=" tabela_servicos tabela_modal  mb-2" style="max-height:500px;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion-item mb-2 ">
                        <h2 class="accordion-header ">
                            <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
                                Material
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show ">
                            <div class="accordion-body">
                                <form id="peca_os">
                                    <div class="row mb-2">
                                        <input type="hidden" id="item_id_material" name="item_id">
                                        <div class="col-md  mb-2">
                                            <label for="descricao_produto" class="form-label">Material *</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" disabled id="descricao_produto" name="descricao_produto" placeholder="">
                                                <input type="hidden" class="form-control" name="produto_id" id="produto_id" value="">
                                                <!--<input type="hidden" class="form-control" name="referencia" id="referencia" value=""> -->
                                                <!-- <input type="hidden" class="form-control" name="estoque" id="estoque" value=""> -->
                                                <button class="btn btn-secondary" type="button" name="modal_produto" id="modal_produto">Buscar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-2 mb-2">
                                            <label for="unidade" class="form-label">Und *</label>
                                            <input type="text" class="form-control" disabled name="unidade" id="unidade" value="">
                                        </div>

                                        <div class="col-md-2 mb-2">
                                            <label for="quantidade" class="form-label">Quantidade *</label>
                                            <input type="number" step="any" class="form-control" onblur="calcular_valor_total()" name="quantidade" id="quantidade" value="">
                                        </div>

                                        <div class="col-md  mb-2">
                                            <label for="preco_venda" class="form-label">Valor Unitário *</label>
                                            <div class="input-group mb-3">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" disabled onblur="calcular_valor_total()" name="preco_venda" id="preco_venda" value="">

                                            </div>
                                        </div>
                                        <div class="col-md  mb-2">
                                            <label for="valor_total_item" class="form-label">Valor Total </label>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text">R$</span>
                                                <input type="text" class="form-control" disabled name="valor_total" id="valor_total" value="">
                                                <button type="submit" id="button_form_peca" class="btn btn-sm btn-success "><i class="bi bi-check-all"></i> Salvar</button>

                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <div class="tabela_material tabela_modal  mb-2" style="max-height:500px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal_externo"></div>
</div>
<script src="js/servico/diagnostico_tecnico/diagnostico_tela.js"></script>