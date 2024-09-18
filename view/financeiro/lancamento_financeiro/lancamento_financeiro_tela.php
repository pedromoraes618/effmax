<?php

include "../../../conexao/conexao.php";
include "../../../modal/financeiro/lancamento_financeiro/gerenciar_lancamento_financeiro.php";
include "../../../funcao/funcao.php";
?>
<div class="modal fade" id="modal_lancamento_financeiro" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Lançamento Financeiro</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="" id="lancamento_financeiro">
                <input type="hidden" id="id" name="id" value="<?php if (isset($_GET['form_id'])) {
                                                                    echo $_GET['form_id'];
                                                                } ?>">
                <input type="hidden" id="tipo" name="tipo" value="<?php if (isset($_GET['tipo'])) {
                                                                        echo $_GET['tipo'];
                                                                    } ?>">

                <div class="modal-body">
                    <div class="title mb-2">
                        <label class="form-label sub-title"></label>
                    </div>
                    <div class="row  mb-2">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                            <button type="submit" id="button_form" onclick="calcula_v_liquido() " class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                            <button type="button" class="btn btn-sm  btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md  mb-2">
                            <label for="data_movimento" class="form-label">Data Movimento *</label>
                            <input type="date" class="form-control " disabled id="data_movimento" name="data_movimento" value="<?php if (!isset($_GET['form_id'])) {
                                                                                                                                    echo $data_final_mes_bd;
                                                                                                                                } ?>">
                        </div>

                        <div class="col-md  mb-2">
                            <label for="data_vencimento" class="form-label">Data Vencimento *</label>
                            <input type="date" class="form-control " id="data_vencimento" name="data_vencimento" value="">
                        </div>
                        <div class="col-md  mb-2">
                            <label for="data_pagamento" class="form-label">Data Pagamento</label>
                            <input type="date" class="form-control " id="data_pagamento" name="data_pagamento" value="">
                        </div>

                    </div>
                    <div class="row mb-2">
                        <div class="col-md  mb-2">
                            <label for="conta_financeira" class="form-label">Conta financeira *</label>
                            <select class="select2-modal chosen-select chosen-select" name="conta_financeira" id="conta_financeira">
                                <option value="0">Selecione..</option>
                                <?php while ($linha  = mysqli_fetch_assoc($consultar_conta_financeira)) {
                                    $id_b = $linha['cl_id'];
                                    $conta_b = $linha['cl_conta'];
                                    $banco_b = utf8_encode($linha['cl_banco']);
                                    echo "<option  value='$conta_b'> $banco_b </option>";
                                } ?>
                            </select>

                            </select>
                        </div>

                        <div class="col-md  mb-2">
                            <label for="forma_pagamento" class="form-label">Forma pagamento *</label>
                            <select class="select2-modal chosen-select chosen-select" name="forma_pagamento" id="forma_pagamento">
                                <option value="0">Selecione..</option>
                                <?php while ($linha  = mysqli_fetch_assoc($consultar_forma_pagamento)) {
                                    $id_b = $linha['cl_id'];
                                    $descricao_b = utf8_encode($linha['cl_descricao']);
                                    echo "<option  value='$id_b'> $descricao_b </option>";
                                } ?>
                            </select>
                            </select>
                        </div>
                        <div class="col-md  mb-2">
                            <label for="status" class="form-label">Status *</label>
                            <select class="select2-modal chosen-select chosen-select sua-classe-personalizada" name="status" id="status" style="height: 200px;">
                                <option value="0">Selecione..</option>
                                <?php while ($linha  = mysqli_fetch_assoc($consultar_status_recebimento)) {
                                    $id_b = $linha['cl_id'];
                                    $descricao_b = utf8_encode($linha['cl_descricao']);
                                    $tipo_b = utf8_encode($linha['cl_tipo']);
                                    $tipo_lancamento = $_GET['tipo'];

                                    if (($tipo_lancamento == $tipo_b) or $tipo_b == "CANCELADO") { //verificar se o tipo do lancamento é igual ao tipo do status se for popular o select
                                        echo "<option  value='$id_b'> $descricao_b </option>";
                                    }
                                } ?>

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
                        <div class="col-md  mb-2">
                            <label for="descricao" class="form-label">Descrição</label>
                            <input type="text" class="form-control" id="descricao" placeholder="Descreva o lançamento financeiro aqui (ex: pagamento de fornecedores etc" name="descricao" value="">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-5  mb-2">
                            <label for="classificacao" class="form-label">Classificação *</label>
                            <select style="width: 100%;" class="select2-modal chosen-select chosen-select" name="classificacao" id="classificacao">
                                <option value="0">Selecione..</option>
                                <?php while ($linha  = mysqli_fetch_assoc($consultar_classificacao_financeiro)) {
                                    $id_b = $linha['cl_id'];
                                    $descricao_b = utf8_encode($linha['cl_descricao']);
                                    echo "<option  value='$id_b'> $descricao_b </option>";
                                } ?>
                            </select>
                            </select>
                        </div>
                        <div class="col-md  mb-2">
                            <label for="documento" class="form-label">Documento</label>
                            <input type="text" class="form-control" id="documento" placeholder="ex. doc123" name="documento" value="">
                        </div>
                        <div class="col-md  mb-2">
                            <label for="ordem_servico" class="form-label">Ordem Serviço</label>
                            <input type="text" class="form-control" id="ordem_servico" placeholder="" name="ordem_servico" value="">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md  mb-2">
                            <label for="conta" class="form-label">Valor Bruto *</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">R$</span>
                                <input type="text" onchange="calcula_v_liquido()" class="form-control inputnumber" id="valor_bruto" name="valor_bruto" value="<?php ?>">

                            </div>
                        </div>


                        <div class="col-md  mb-2">
                            <label for="juros" class="form-label">Juros</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">R$</span>
                                <input type="text" onchange="calcula_v_liquido()" class="form-control inputnumber " id="juros" name="juros" value="<?php ?>">
                                <div id="validationServer03Feedback" class="invalid-feedback">
                                    Previsão de Juros
                                </div>
                            </div>
                        </div>

                        <div class="col-md  mb-2">
                            <label for="taxa" class="form-label">+ Taxa</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">R$</span>
                                <input type="text" onchange="calcula_v_liquido()" class="form-control inputnumber" id="taxa" name="taxa" value="<?php ?>">
                            </div>
                        </div>
                        <div class="col-md  mb-2">
                            <label for="taxa" class="form-label">- Taxa Cartão</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">R$</span>
                                <input type="text" onchange="calcula_v_liquido()" class="form-control inputnumber" id="taxa_cartao" name="taxa_cartao" value="">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md  mb-2">
                            <label for="baixa_parcial" class="form-label">Baixa Parcial</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">R$</span>
                                <input type="text" disabled class="form-control inputnumber" id="baixa_parcial" name="baixa_parcial" value="<?php ?>">

                            </div>
                        </div>
                        <div class="col-md  mb-2">
                            <label for="desconto" class="form-label">Desconto</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">R$</span>
                                <input type="text" onchange="calcula_v_liquido()" class="form-control inputnumber" id="desconto" name="desconto" value="<?php ?>">

                            </div>
                        </div>


                        <div class="col-md  mb-2">
                            <label for="valor_liquido" class="form-label">Valor liquido</label>
                            <div class="input-group mb-2">
                                <span class="input-group-text">R$</span>
                                <input type="text" class="form-control inputnumber" id="valor_liquido" disabled value="<?php ?>">
                                <input type="hidden" class="form-control inputnumber" id="valor_liquido_hidden" name="valor_liquido" value="<?php ?>">

                            </div>
                        </div>

                    </div>
                    <div class="row mb-2">
                        <div class="col-md  mb-2">
                            <label for="observacao" class="form-label">Observação</label>
                            <textarea class="form-control" name="observacao" id="observacao" aria-label="With textarea"></textarea>

                        </div>
                    </div>


                </div>



            </form>
        </div>
    </div>
</div>
<div class="modal_parceiro">

</div>

<?php include '../../../funcao/funcaojavascript.jar'; ?>
<script src="js/funcao.js"></script>
<!-- <script src="js/configuracao/users/user_logado.js"></script> -->
<script src="js/financeiro/lancamento_financeiro/lancamento_financeiro_tela.js"></script>
<!-- <script src="js/include/parceiro/pesquisa_parceiro.js"></script> -->