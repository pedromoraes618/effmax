<?php

include "../../../conexao/conexao.php";
include "../../../modal/recebimento_nf/nf_saida/gerenciar_recebimento.php";
include "../../../funcao/funcao.php";
if (isset($_GET['recebimento_nf'])) {
    $tipo = $_GET['tipo'];
    $id_nf = $_GET['nf_id'];
}
?>
<div class="modal fade" id="modal_recebimento_nf" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Recebimento</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" id="fechar_modal_recebimento" aria-label="Close"></button>
            </div>
            <form action="" id="recebimento_nf">
                <?php include "../../input_include/usuario_logado.php" ?>
                <div class="modal-body">

                    <div class=" d-flex flex-wrap justify-content-end gap-2 mb-3">
                        <button type="submit" id="iniciar_venda" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Receber</button>
                        <button type="button" class="btn btn-sm btn-secondary" id="fechar_modal_recebimento" data-bs-dismiss="modal">Fechar</button>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3 mb-2">
                            <label for="dt_recebimento" class="form-label">Dt recebimento</label>
                            <input type="date" class="form-control" id="dt_recebimento" name="dt_recebimento" value="<?php echo $data_lancamento; ?>">
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="quantidade" class="form-label">Nº documento</label>
                            <input type="text" class="form-control" disabled id="numero_nf" value="">
                            <input type="hidden" class="form-control" name="nf_id" id="nf_id" value="<?php echo $id_nf; ?>">
                        </div>
                        <div class="col-md mb-2">
                            <label for="vlr_liquido" class="form-label">Forma pagamento</label>
                            <select name="forma_pagamento" class="form-select chosen-select" id="forma_pagamento">
                                <option value="0">Selecione</option>
                                <?php
                                $resultados = consulta_linhas_tb_query($conecta, "SELECT * 
                        from tb_forma_pagamento where cl_ativo ='S' and cl_tipo_pagamento_id !='3'");
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
                        <div class="col-md mb-2">
                            <label for="vlr_liquido" class="form-label">Valor Liquido</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" disabled name="valor_liquido" id="valor_liquido" value="">
                            </div>
                        </div>
                        <div class="col-md mb-2">
                            <label for="valor_credito" class="form-label">Valor crédito</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" disabled name="valor_credito" id="valor_credito" value="">
                            </div>
                        </div>
                        <div class="col-md mb-2">
                            <label for="valor_adiantamento" class="form-label">Valor adiantamento</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" disabled name="valor_adiantamento" id="valor_adiantamento" value="">
                            </div>
                        </div>
                        <div class="col-md mb-2">
                            <label for="valor_a_receber" class="form-label">Valor a receber</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control" disabled name="valor_a_receber" id="valor_a_receber" value="">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md  mb-2">
                            <label for="quantidade" class="form-label">Cliente</label>
                            <input type="text" disabled class="form-control" name="cliente" id="cliente" value="">
                        </div>
                        <!-- <div class="col-md-3  mb-2">
                            <label for="restante" class="form-label">Troco</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="text" disabled class="form-control" name="restante" id="restante" value="">
                            </div>
                        </div> -->
                    </div>
                    <hr>
                    <div class="row">
                        <?php
                        $resultados = consulta_linhas_tb_query($conecta, "SELECT * 
                        from tb_forma_pagamento where cl_ativo ='S' and cl_tipo_pagamento_id !='3'");
                        if ($resultados) {
                            foreach ($resultados as $linha) {
                                $id = $linha['cl_id'];
                                $descricao = utf8_encode($linha['cl_descricao']);

                        ?>
                                <div class="col-md-6 mb-2" title="">
                                    <div class="input-group mb-3">
                                        <span class="input-group-text "><?php echo $descricao; ?></span>
                                        <span class="input-group-text">R$</span>
                                        <input type="number" step="any" class="form-control rec" placeholder="0.00" onchange="valorPago()" name="<?php echo $id; ?>" id="vlr_liquido" value="">
                                    </div>
                                </div>

                        <?php }
                        } ?>
                    </div>
                    <div class="tabela_historico_lancamento"></div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal_externo_2"></div>
</div>

<script src="js/include/recebimento_nf/gerenciar_recibemento.js"></script>