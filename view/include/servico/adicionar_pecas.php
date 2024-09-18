<?php
include "../../../modal/servico/ordem_servico/gerenciar_ordem_servico.php";
?>
<div class="modal fade " id="modal_pecas_ordem_servico" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content border ">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Material</h1>
                <button type="button" class="btn-close " data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="title mb-3">
                    <label class="form-label sub-title sub-title-material"></label>
                </div>

                <form action="" id="pecas">
                    <div class="d-flex flex-wrap justify-content-end gap-2 mb-3">
                        <button type="button" class="btn btn-dark btn-sm requisitar_material">Requisitar Material</button>
                        <button type="button" class="btn btn-danger btn-sm cancelar_requisicao_material">Cancelar Requisição de Material</button>
                        <button type="button" class="btn btn-sm btn-secondary btn-fechar" data-bs-dismiss="modal">Fechar</button>
                    </div>


                    <div class="row mbb-2">
                        <input type="hidden" id="item_id_material" name="item_id">

                        <div class="col-md-2 mb-2">
                            <label for="tipo_material" class="form-label">Tipo material <span class="d-inline-block BD-" tabindex="0" data-bs-toggle="popover"
                                    data-bs-placement="top" data-bs-trigger="hover focus" data-bs-content="Especifique o tipo do material que será utilizado no serviço (Ativo Imobilizado: Bens duráveis usados em várias obras ao longo do tempo, contabilizados como ativos fixos. Observação: Não contabilza no valor final na ordem de serviço), (Uso e Consumo: Materiais e bens de curto prazo, consumidos durante a obra, contabilizados como despesas. Observação: contabiliza no valor final na ordem de serviço).">
                                    <i class="bi bi-info-circle"></i>
                                </span>
                            </label>
                            <select name="tipo_material" id="tipo_material" class="select2-modal-modal">
                                <option value="0">Selecione</option>
                                <option value="9" selected>Outros</option>
                                <option value="10">Ativo imobilizado</option>
                            </select>
                        </div>
                        <div class="col-md  mb-2">
                            <label for="descricao_produto" class="form-label">Material *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="descricao_produto" name="descricao_produto" placeholder="Ex. Ferramentas">
                                <input type="hidden" class="form-control" name="produto_id" id="produto_id" value="">
                                <!--<input type="hidden" class="form-control" name="referencia" id="referencia" value=""> -->
                                <!-- <input type="hidden" class="form-control" name="estoque" id="estoque" value=""> -->
                                <button class="btn btn-secondary" type="button" name="modal_produto" id="modal_produto">Buscar</button>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 mb-2">
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
                        <div class="col-6 col-md-2 mb-2">
                            <label for="unidade" class="form-label">Unidade *</label>
                            <input type="text" class="form-control" onblur="calcular_valor_total()" name="unidade" id="unidade" value="" placeholder="Ex. und">
                        </div>

                        <div class="col-6 col-md-2 mb-2">
                            <label for="quantidade" class="form-label">Quantidade *</label>
                            <input type="number" step="any" class="form-control" onblur="calcular_valor_total()" name="quantidade" id="quantidade" value="" placeholder="0.00">
                        </div>

                        <div class="col-6 col-md  mb-2">
                            <label for="preco_venda" class="form-label">Valor Unitário *</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" step="any" class="form-control " onblur="calcular_valor_total()" name="preco_venda" id="preco_venda" value="" placeholder="0.00">

                            </div>
                        </div>
                        <div class="col-6 col-md  mb-2">
                            <label for="valor_total_item" class="form-label">Valor Total </label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" disabled name="valor_total" id="valor_total" value="" placeholder="0.00">
                                <button type="submit" id="button_form_peca" class="btn btn-sm btn-success "><i class="bi bi-check-all"></i> Salvar</button>

                            </div>
                        </div>
                    </div>

                </form>
                <div class="accordion " id="accordionPanelsStayOpenExample">
                    <div class="accordion-item mb-2 ">
                        <h2 class="accordion-header ">
                            <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne-2" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne-2">
                                Itens para uso e consumo (outros)
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseOne-2" class="accordion-collapse collapse show ">
                            <div class="accordion-body">
                                <div class="tabela tabela_material tabela_modal  mb-2" style="max-height:500px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item mb-2 ">
                        <h2 class="accordion-header ">
                            <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo-2" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo-2">
                                Ativo imobilizado
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseTwo-2" class="accordion-collapse collapse show ">
                            <div class="accordion-body">
                                <div class="tabela tabela_material_ativo_imobilizado tabela_modal  mb-2" style="max-height:500px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal_externo_3">

</div>
<script src="js/include/servico/adicionar_pecas.js"></script>