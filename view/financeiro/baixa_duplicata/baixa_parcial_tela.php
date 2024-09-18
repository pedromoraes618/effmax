<?php
include "../../../conexao/conexao.php";
include "../../../modal/financeiro/baixa_duplicata/gerenciar_baixa_duplicata.php";
include "../../../funcao/funcao.php";
?>
<div class="modal fade" id="modal_baixa_parcial" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Baixa Parcial</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="baixa_parcial">
                <input type="hidden" id="id_lancamento" name="id_lancamento" value="<?php if (isset($_GET['form_id'])) {
                                                                                        echo $_GET['form_id'];
                                                                                    } ?>">
                <input type="hidden" id="id_baixa" name="id_baixa" value="">
                <div class="modal-body">
                    <div class="title mb-2">
                        <label class="form-label sub-title">Baixa Parciais de Duplicatas</label>
                    </div>
                    <div class="row  mb-2">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                            <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Baixar</button>
                            <button type="button" class="btn btn-sm  btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                    <div class="row mb-2">

                        <div class="col-md  mb-2">
                            <label for="data_pagamento" class="form-label">Data Pagamento</label>
                            <input type="date" class="form-control" id="data_pagamento" name="data_pagamento" value="<?php echo $data_lancamento; ?>">
                        </div>
                        <div class="col-md-2  mb-2">
                            <label for="doc" class="form-label">Doc</label>
                            <input type="text" disabled class="form-control" id="doc">
                        </div>

                        <div class="col-md  mb-2">
                            <label for="conta_financeira" class="form-label">Conta Financeira</label>
                            <select name="conta_financeira" class="form-select chosen-select" id="conta_financeira">
                                <option value="0">Selecione</option>
                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_conta_financeira');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $conta = $linha['cl_conta'];
                                        $descricao = utf8_encode($linha['cl_banco']);

                                        echo "<option value='$conta'>$descricao</option>";
                                    }
                                }
                                ?>
                            </select>

                        </div>
                        <div class="col-md  mb-2">
                            <label for="forma_pagamento" class="form-label">Forma Pagamento</label>
                            <select name="forma_pagamento" class="form-select chosen-select" id="forma_pagamento">
                                <option value="0">Selecione</option>
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
                    </div>

                    <div class="row mb-2">
                        <div class="col-md  mb-2">
                            <label for="valor_bx_parcial" class="form-label">Valor Bx Parcial</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">R$</span>
                                <input type="number" step="any" onchange="calcula_v_liquido_bx_parcial()" class="form-control " id="valor_bx_parcial" name="valor_bx_parcial" value="<?php ?>">

                            </div>
                        </div>
                        <div class="col-md  mb-2">
                            <label for="valor_liquido" class="form-label">Valor a receber</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">R$</span>
                                <input type="text" disabled class="form-control" id="valor_liquido" value="">
                                <input type="hidden" disabled class="form-control" id="valor_liquido_hidden" value="">

                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md  mb-2">
                            <label for="observacao" class="form-label">Observação</label>
                            <textarea class="form-control" name="observacao" id="observacao" aria-label="With textarea"></textarea>

                        </div>
                    </div>
                    <div class="card p-2 border-0 border-top shadow tabela_bx_parcial tabela_modal  mb-2"></div>

                </div>
            </form>

        </div>

    </div>
</div>

<script src="js/financeiro/baixa_duplicata/baixa_duplicata_tela.js"></script>