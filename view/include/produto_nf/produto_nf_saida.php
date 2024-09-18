<?php
include "../../../modal/venda/venda_mercadoria/gerenciar_venda.php";
?>

<div class="modal fade" id="modal_produto" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Item</h1>
                <button type="button" class="btn-close" id="fechar_modal_item" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="form_produto">
                    <input type="hidden" id="item_id" name="item_id" value="<?php echo $item_id; ?>">
                    <input type="hidden" id="codigo_nf" name="codigo_nf" value="<?php echo $codigo_nf; ?>">

                    <div class="col-md mb-2">
                        <div class="d-grid gap-2  d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-sm btn-success" id="salvar_prod">Alterar</button>
                            <button type="button" class="btn btn-sm btn-secondary" id="fechar_modal_prod" data-bs-dismiss="modal" aria-label="Close">Fechar</button>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-1 mb-2">
                            <label for="descricao_produto" class="form-label">Código</label>
                            <input type="text" disabled class="form-control" id="codigo" name="codigo" value="">
                        </div>

                        <div class="col-md mb-2">
                            <label for="descricao_produto" class="form-label">Produto *</label>
                            <input type="text" disabled class="form-control" id="descricao_produto" name="descricao_produto" value="">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-2 mb-2">
                            <label for="unidade" class="form-label">Und *</label>
                            <input type="text" class="form-control" disabled id="unidade" name="unidade" value="">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="quantidade" class="form-label">Qtd *</label>
                            <input type="text" class="form-control" disabled onchange="calculaValorTotalProduto()" id="quantidade" name="quantidade" value="">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="preco_compra_atual" class="form-label">Vlr Unitário *</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" disabled onchange="calculaValorTotalProduto()" id="preco_venda_unitario" name="preco_venda_unitario" value="">
                            </div>
                        </div>
                        <div class="col-md mb-2">
                            <label for="valor_total_compra" class="form-label">Vlr Total </label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" disabled id="preco_venda_total" name="preco_venda_total" value="">
                            </div>
                        </div>
                    </div>

                    <div class="accordion " id="accordionPanelsStayOpenExample">
                    
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo-3" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo-3">
                                    Fiscal
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseTwo-3" class="accordion-collapse collapse show ">
                                <div class="accordion-body">
                                    <div class="row mb-2">
                                        <div class="col-md-5 mb-2">
                                            <label for="cfop_prod" class="form-label">Cfop* </label>
                                            <select name="cfop_prod" class="select2-modal-modal chosen-select" id="cfop_prod">
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


                                        <div class="col-md-2 mb-2">
                                            <label for="referencia_prod" class="form-label">Referência</label>
                                            <input type="text" list="datalistOptions" class="form-control" id="referencia_prod" name="referencia_prod" value="">

                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label for="ncm_prod" class="form-label">Ncm </label>
                                            <input type="number" step="any" list="datalistOptions Ncm_prod" class="form-control" id="ncm_prod" name="ncm_prod" value="">

                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label for="cest_prod" class="form-label">Cest</label>
                                            <input type="number" step="any" list="datalistOptions Cest_prod" class="form-control" id="cest_prod" name="cest_prod" value="">

                                        </div>
                                        <div class="col-md-1 mb-2">
                                            <label for="cst_icms_prod" class="form-label">Cst </label>
                                            <input type="text" list="datalistOptions Cst_prod" class="form-control" id="cst_icms_prod" name="cst_icms_prod" value="">

                                        </div>

                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-2 mb-2">
                                            <label for="base_icms_prod" class="form-label">Base Icms</label>
                                            <input type="number" step="any" class="form-control" id="base_icms_prod" name="base_icms_prod" value="">

                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label for="aliq_icms_prod" class="form-label">% Icms *</label>
                                            <div class="input-group ">
                                                <span class="input-group-text">%</span>
                                                <input type="number" step="any" class="form-control" id="aliq_icms_prod" name="aliq_icms_prod" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label for="vlr_icms_prod" class="form-label">Icms</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" id="vlr_icms_prod" name="vlr_icms_prod" value="">
                                            </div>
                                        </div>

                                        <div class="col-md-2 mb-2">
                                            <label for="base_icms_sub_prod" class="form-label">Base Icms Sub</label>

                                            <input type="number" step="any" class="form-control" id="base_icms_sub_prod" name="base_icms_sub_prod" value="">

                                        </div>

                                        <div class="col-md-2 mb-2">
                                            <label for="vlr_icms_sub_prod" class="form-label">Icms Sub</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" id="vlr_icms_sub_prod" name="vlr_icms_sub_prod" value="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-2 mb-2">
                                            <label for="base_pis_prod" class="form-label">Base Pis</label>

                                            <input type="number" step="any" class="form-control" id="base_pis_prod" name="base_pis_prod" value="">

                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label for="pis_prod" class="form-label">Pis</label>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" id="pis_prod" name="pis_prod" value="">
                                            </div>
                                        </div>

                                        <div class="col-md-2 mb-2">
                                            <label for="cst_pis_prod" class="form-label">Cst Pis *</label>
                                            <input type="text" class="form-control" list="datalistOptionsCst_pis_prod" id="cst_pis_prod" name="cst_pis_prod" value="">

                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label for="aliq_ipi_prod" class="form-label">% IPI</label>
                                            <div class="input-group">
                                                <span class="input-group-text">%</span>
                                                <input type="number" step="any" class="form-control" id="aliq_ipi_prod" name="aliq_ipi_prod" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label for="ipi_prod" class="form-label">IPI</label>
                                            <div class="input-group">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" id="ipi_prod" name="ipi_prod" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label for="ipi_devolvido_prod" class="form-label">IPI Devolvido</label>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class="form-control" id="ipi_devolvido_prod" name="ipi_devolvido_prod" value="">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-2 mb-2">
                                            <label for="base_cofins_prod" class="form-label">Base Cofins</label>
                                            <input type="number" step="any" class="form-control" id="base_cofins_prod" name="base_cofins_prod" value="">
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label for="cofins_prod" class="form-label">Cofins</label>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" " class=" form-control" id="cofins_prod" name="cofins_prod" value="">
                                            </div>
                                        </div>

                                        <div class="col-md-2 mb-2">
                                            <label for="cst_cofins_prod" class="form-label">Cst cofins *</label>
                                            <input type="text" class="form-control" id="cst_cofins_prod" name="cst_cofins_prod" value="">

                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label for="base_iss_prod" class="form-label">Base ISS</label>
                                            <input type="number" step="any" class="form-control" id="base_iss_prod" name="base_iss_prod" value="">
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label for="iss_prod" class="form-label">ISS</label>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text">R$</span>
                                                <input type="number" step="any" class=" form-control" id="iss_prod" name="iss_prod" value="">
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md-2 mb-2">
                                            <label for="numero_pedido_prod" class="form-label">Número Pedido</label>
                                            <input type="text" class="form-control" id="numero_pedido_prod" name="numero_pedido_prod" value="">
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label for="item_pedido_prod" class="form-label">Item Pedido</label>
                                            <input type="text" class="form-control" id="item_pedido_prod" name="item_pedido_prod" value="">
                                        </div>

                                        <div class="col-md mb-2">
                                            <label for="gtin" class="form-label">Gtin</label>
                                            <input type="number" step="any" class="form-control" id="gtin" name="gtin" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal_externo_externo"></div>
</div>
<script src="js/include/produto_nf/produto_nf_saida.js"></script>