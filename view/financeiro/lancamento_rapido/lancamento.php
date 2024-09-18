<?php include "../../../modal/financeiro/lancamento_financeiro/gerenciar_lancamento_financeiro.php"; ?>
<div class="modal fade" id="modal_lancamento_rapido" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Lançamento Rápido</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="" id="lancamento_rapido">
                <input type="hidden" id="id" name="id" value="<?php echo $form_id; ?>">
                <input type="hidden" id="tipo" name="tipo" value="<?php echo $tipo; ?>">

                <div class="modal-body">
                    <div class="title mb-2">
                        <label class="form-label sub-title"><?php echo $titulo; ?></label>
                    </div>
                    <div class="row  mb-2">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                            <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                            <button type="button" class="btn btn-sm  btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md  mb-2">
                            <label for="data_pagamento" class="form-label">Data</label>
                            <input type="date" class="form-control" id="data_pagamento" name="data_pagamento" value="<?php echo $data_lancamento; ?>">
                        </div>
                        <div class="col-md-4  mb-2">
                            <label for="conta_financeira" class="form-label">Conta financeira</label>
                            <select class="select2-modal" name="conta_financeira" id="conta_financeira">
                                <option value="0">Selecione..</option>
                                <?php $resultados = consulta_linhas_tb($conecta, 'tb_conta_financeira');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id_b = $linha['cl_id'];
                                        $conta_b = $linha['cl_conta'];
                                        $banco_b = utf8_encode($linha['cl_banco']);
                                        echo "<option  value='$conta_b'> $banco_b </option>";
                                    }
                                } ?>
                            </select>

                        </div>

                        <div class="col-md-4  mb-2">
                            <label for="forma_pagamento" class="form-label">Forma pagamento</label>
                            <select class="select2-modal" name="forma_pagamento" id="forma_pagamento">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb_filtro($conecta, 'tb_forma_pagamento', 'cl_ativo', 'S');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id_b = $linha['cl_id'];
                                        $descricao_b = utf8_encode($linha['cl_descricao']);
                                        echo "<option  value='$id_b'> $descricao_b </option>";
                                    }
                                }
                                ?>
                            </select>
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
                        <div class="col-md-3  mb-2">
                            <label for="valor" class="form-label">Valor</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" step="any" class="form-control" id="valor" name="valor" value="" placeholder="Ex. 10.00">

                            </div>
                        </div>
                        <div class="col-md-9  mb-2">
                            <label for="classificacao" class="form-label">Classificação</label>
                            <select class="select2-modal" name="classificacao" id="classificacao">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_classificacao_financeiro');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id_b = $linha['cl_id'];
                                        $descricao_b = utf8_encode($linha['cl_descricao']);
                                        echo "<option  value='$id_b'> $descricao_b </option>";
                                    }
                                } ?>
                            </select>
                        </div>
                    </div>



                    <div class="row mb-2">
                        <div class="col-md  mb-2">
                            <label for="observacao" class="form-label">Descrição</label>
                            <textarea class="form-control" name="observacao" id="observacao" placeholder="ex. transferência entre contas" aria-label="With textarea"></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal_externo"></div>
</div>



<script src="js/financeiro/lancamento_rapido/lancamento.js"></script>