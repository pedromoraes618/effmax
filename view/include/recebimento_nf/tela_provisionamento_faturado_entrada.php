<?php

include "../../../conexao/conexao.php";
include "../../../modal/recebimento_nf/nf_entrada/gerenciar_provisionamento.php";
include "../../../funcao/funcao.php";
if (isset($_GET['provisionamento_nf_entrada'])) {
    $tipo = $_GET['tipo'];
    $id_nf = $_GET['nf_id'];
}
?>
<div class="modal fade" id="modal_recebimento_nf_faturado" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Provisionamento de Compra</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" id="fechar_modal_provisionamento" aria-label="Close"></button>
            </div>
            <form action="" id="recebimento_nf_faturado">
                <div class="modal-body">
                    <div class="row  mb-2">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                            <button type="submit" id="iniciar_venda" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Provisionar</button>
                            <button type="button" class="btn btn-sm btn-secondary" id="fechar_modal_provisionamento" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <input type="hidden" name="nf_id" id="nf_id" value="<?php echo $id_nf ?>">
                        <div class="col-md-2 mb-2">
                            <label for="numero_nf" class="form-label">Nº NF</label>
                            <input type="text" disabled class="form-control" id="numero_nf" value="">
                        </div>
                        <div class="col-md mb-2">
                            <label for="fornecedor" class="form-label">Fornecedor</label>
                            <input type="text" disabled class="form-control" id="fornecedor" name="fornecedor" value="">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="valor_liquido" class="form-label">Valor Total NF</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">R$</span>
                                <input type="text" disabled class="form-control" id="valor_liquido" value="">
                            </div>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="forma_pagamento" class="form-label">Forma pagamento *</label>
                            <select class="form-select chosen-select" name="forma_pagamento" id="forma_pagamento">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb_filtro($conecta, 'tb_forma_pagamento', 'cl_ativo', 'S');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = utf8_encode($linha['cl_id']);
                                        $descricao = utf8_encode($linha['cl_descricao']);
                                        echo "<option value='$id'>$descricao</option>";
                                    }
                                }
                                ?>

                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="conta_financeira" class="form-label">Conta financeira *</label>
                            <select class="form-select chosen-select" name="conta_financeira" id="conta_financeira">
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
                    <hr>
                    <div class="row  mb">
                        <div class="col-md-2 mb-2">
                            <label for="valor_entrada" class="form-label">Valor entrada</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">R$</span>
                                <input type="number" step="any" class="form-control" id="valor_entrada" name="valor_entrada" value="">
                            </div>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="forma_pagamento_entrada" class="form-label">Forma pagamento </label>
                            <select class="form-select chosen-select" name="forma_pagamento_entrada" id="forma_pagamento_entrada">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb_query($conecta, "SELECT * 
                        from tb_forma_pagamento where cl_ativo ='S' and cl_tipo_pagamento_id !='3'");
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = utf8_encode($linha['cl_id']);
                                        $descricao = utf8_encode($linha['cl_descricao']);
                                        echo "<option value='$id'>$descricao</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="conta_financeira_entrada" class="form-label">Conta Financeira</label>
                            <select class="form-select chosen-select" name="conta_financeira_entrada" id="conta_financeira_entrada">
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
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2 d-flex align-items-end">
                        <div class="col-md-2  mb-2">
                            <label for="nparcelas" class="form-label">Nº Parcelas</label>
                            <input type="number" step="any" class="form-control" id="nparcelas" name="nparcelas" placeholder="Ex. 6" value="">
                        </div>
                        <div class="col-md-2  mb-2">
                            <label for="primeira_parcela" class="form-label">1 º Parcela</label>
                            <input type="date" class="form-control" name="primeira_parcela" id="primeira_parcela" value="">
                        </div>
                        <div class="col-md-2 mb-2">
                            <label for="intervalo" class="form-label">Intarvalo das Parcelas</label>
                            <input type="number" class="form-control" id="intervalo" name="intervalo" value="">
                        </div>
                        <div class="col-md-2 mb-2">
                            <div class="d-grid gap-2 d-sm-flex ">
                                <button type="button" id="previa_parcelas" title="visualize informações relacionadas ao pagamento parcelado antes de efetuar o pagamento completo" class="btn btn-sm btn-dark"><i class="bi bi-eye-fill"></i> Prévia Parcelas</button>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
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

<div class="alert"></div>

<!-- <script src="js/funcao.js"></script> -->
<script src="js/include/recebimento_nf/gerenciar_provisionamento_faturado_entrada.js"></script>