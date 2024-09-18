<?php

include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
?>
<div class="modal fade" id="modal_lancamento_multiplo" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Lançamento múltiplos</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" id="fechar_modal_lanc_multiplo" aria-label="Close"></button>
            </div>
            <form action="" id="lancamento_multiplo">
                <div class="modal-body">
                    <div class="row  mb-2">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                            <button type="submit" id="iniciar_venda" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Provisionar</button>
                            <button type="button" class="btn btn-sm btn-secondary" id="fechar_modal_lanc_multiplo" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                    <div class="row mb-2 d-flex align-items-end">

                        <div class="col-md-3 mb-2">
                            <label for="status" class="form-label">Status *</label>
                            <select class="select2-modal chosen-select" name="status" id="status">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_status_recebimento');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = ($linha['cl_id']);
                                        $descricao = utf8_encode($linha['cl_descricao']);
                                        $tipo = ucfirst(strtolower($linha['cl_tipo']));
                                        echo "<option value='$id'>$tipo - $descricao</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="classificacao" class="form-label">Classificação *</label>
                            <select class="select2-modal chosen-select" name="classificacao" id="classificacao">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_classificacao_financeiro');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = ($linha['cl_id']);
                                        $descricao = utf8_encode($linha['cl_descricao']);
                                        echo "<option value='$id'>$descricao</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-3 mb-2">
                            <label for="forma_pagamento" class="form-label">Forma pagamento *</label>
                            <select class="select2-modal chosen-select" name="forma_pagamento" id="forma_pagamento">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb_filtro($conecta, 'tb_forma_pagamento', 'cl_ativo', 'S');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = ($linha['cl_id']);
                                        $descricao = utf8_encode($linha['cl_descricao']);
                                        echo "<option value='$id'>$descricao</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="conta_financeira" class="form-label">Conta financeira</label>
                            <select class="select2-modal chosen-select" name="conta_financeira" id="conta_financeira">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_conta_financeira');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = ($linha['cl_id']);
                                        $descricao = utf8_encode($linha['cl_banco']);
                                        $conta = ($linha['cl_conta']);
                                        echo "<option value='$conta'>$descricao</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2 d-flex align-items-end">
                        <div class="col-md-2  mb-2">
                            <label for="valor_liquido" class="form-label">Valor total</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" step="any" class="form-control" id="valor_liquido" name="valor_liquido" value="">
                            </div>
                        </div>
                        <div class="col-md-2  mb-2">
                            <label for="nparcelas" class="form-label">Nº parcelas</label>
                            <input type="number" class="form-control" id="nparcelas" name="nparcelas" placeholder="ex. 6" value="">
                        </div>
                        <div class="col-md-2  mb-2">
                            <label for="primeira_parcela" class="form-label">1º parcela</label>
                            <input type="date" class="form-control" name="primeira_parcela" id="primeira_parcela" value="">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="intervalo" class="form-label">Intarvalo das Parcelas</label>
                            <input type="number" class="form-control" id="intervalo" name="intervalo" placeholder="ex. 30" value="">
                        </div>
                        <div class="col-md-2 mb-2">
                            <div class="d-grid gap-2 d-sm-flex ">
                                <button type="button" id="previa_parcelas" title="visualize informações relacionadas ao pagamento parcelado antes de efetuar o pagamento completo" class="btn btn-sm btn-dark"><i class="bi bi-eye-fill"></i> Prévia Parcelas</button>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md  mb-2">
                            <label for="parceiro_descricao" class="form-label">Cliente / Fornecedor</label>
                            <div class="input-group">
                                <input type="text" class="form-control" disabled id="parceiro_descricao" placeholder="Pesquise pelo Cliente/Fornecedor" aria-label="Recipient's username" aria-describedby="button-addon2">
                                <input type="hidden" class="form-control" name="parceiro_id" id="parceiro_id" value="">
                                <button class="btn btn-outline-secondary" type="button" name="modal_parceiro" id="modal_parceiro">Pesquisar</button>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md  mb-2">
                            <label for="descricao" class="form-label">Descrição *</label>
                            <input type="text" class="form-control" name="descricao" id="descricao" placeholder="ex. pagamento recorrente da internet">
                        </div>
                    </div>

                    <div class="row ">
                        <div class="title mb-2">
                            <label class="form-label sub-title">Parcelas</label>
                        </div>
                        <?php for ($i = 1; $i < 12; $i++) {
                            $name_data = $i . "dtvencimento";
                            $name_doc = $i . "doc";
                            $name_valor = $i . "valor";
                        ?>
                            <div class="row mb-2">
                                <div class="col-md-2 mb-2">
                                    <input type="date" class="form-control dtvencimento" placeholder="Data Vencimento" id="<?php echo $name_data; ?>" name="<?php echo $name_data; ?>" value="">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <input type="text" class="form-control doc" placeholder="Documento" id="<?php echo $name_doc; ?>" name="<?php echo $name_doc; ?>" value="">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <input type="number" step="any" class="form-control valor" placeholder="Valor" id="<?php echo $name_valor; ?>" name="<?php echo $name_valor; ?>" value="">
                                </div>
                                <?php
                                $i += 1;
                                $name_data = $i . "dtvencimento";
                                $name_doc = $i . "doc";
                                $name_valor = $i . "valor";
                                ?>
                                <div class="col-md-2 mb-2">
                                    <input type="date" class="form-control dtvencimento" placeholder="Data Vencimento" id="<?php echo $name_data; ?>" name="<?php echo $name_data; ?>" value="">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <input type="text" class="form-control doc" placeholder="Documento" id="<?php echo $name_doc; ?>" name="<?php echo $name_doc; ?>" value="">
                                </div>
                                <div class="col-md-2 mb-2">
                                    <input type="number" step="any" class="form-control valor" placeholder="Valor" id="<?php echo $name_valor; ?>" name="<?php echo $name_valor; ?>" value="">
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
<div class="modal_externo"></div>
<!-- 
<script src="js/funcao.js"></script>
 -->
<script src="js/financeiro/lancamento_financeiro/lancamento_multiplo.js"></script>