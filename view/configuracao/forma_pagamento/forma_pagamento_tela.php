<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
include "../../../modal/configuracao/forma_pagamento/gerenciar_forma_pagamento.php";
?>
<div class="modal fade" id="modal_forma_pagamento" data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Forma de pagamento</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="forma_pagamento">
                <input type="hidden" id="id" name="id" value="<?php if (isset($_GET['form_id'])) {
                                                                    echo $_GET['form_id'];
                                                                } ?>">
                <div class="modal-body">

                    <div class="title mb-2">
                        <label class="form-label sub-title"></label>
                    </div>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end btn-acao">
                        <button type="submit" id="button_form" class="btn btn-sm btn-success">Salvar</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Voltar</button>
                    </div>
                    <div class="col  mb-2">
                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control " id="descricao" name="descricao" value="">
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md mb-2">
                            <label for="conta_financeira" class="form-label">Conta financeira</label>
                            <select class="form-control select2-modal" name="conta_financeira" id="conta_financeira">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_conta_financeira');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id_b = $linha['cl_id'];
                                        $conta_b = $linha['cl_conta'];
                                        $banco_b = utf8_encode($linha['cl_banco']);
                                        echo "<option  value='$conta_b'> $banco_b </option>";
                                    }
                                }
                                ?>
                            </select>
                            <?php

                            ?>
                        </div>
                        <div class="col-sm-6 col-md mb-2">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-control select2-modal" name="status" id="status">
                                <option value="0">Selecione..</option>
                                <?php

                                $resultados = consulta_linhas_tb($conecta, 'tb_status_recebimento');
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
                    <div class="row">
                        <div class="col-sm-6 col-md mb-2">
                            <label for="classificacao" class="form-label">Classificação</label>
                            <select class="form-control select2-modal" name="classificacao" id="classificacao">
                                <option value="0">Selecione..</option>
                                <?php

                                $resultados = consulta_linhas_tb($conecta, 'tb_classificacao_financeiro');
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

                        <div class="col-sm-6 col-md mb-2">
                            <label for="tipo_pagamento" class="form-label">Tipo Pagamento</label>
                            <select class="form-control select2-modal" name="tipo_pagamento" id="tipo_pagamento">
                                <option value="0">Selecione..</option>
                                <?php

                                $resultados = consulta_linhas_tb($conecta, 'tb_tipo_pagamento');
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
                        <div class="col-sm-6 col-md mb-2">
                            <label for="tipo_pagamento_nf" class="form-label">Pagamento Nota Fiscal</label>
                            <select class="form-control select2-modal" name="tipo_pagamento_nf" id="tipo_pagamento_nf">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_forma_pagamento_nf');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = $linha['cl_id'];
                                        $descricao = utf8_encode($linha['cl_descricao']);

                                        echo "<option  value='$id'>$descricao</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 col-md mb-2">
                            <label for="numero_parcela" class="form-label">Número parcela</label>
                            <input type="number" class="form-control " id="numero_parcela" name="numero_parcela" placeholder="Ex. 5" value="">
                        </div>

                        <input type="hidden" class="form-control" id="prazo_fatura" name="prazo_fatura" placeholder="Ex. 8" value="">

                        <div class="col-sm-6 col-md mb-2">
                            <label for="intervalo_parcela" class="form-label">Intervalo parcela</label>
                            <input type="number" class="form-control" id="intervalo_parcela" placeholder="Ex. 30" name="intervalo_parcela" value="">
                        </div>
                        <div class="col-sm-6 col-md mb-2">
                            <label for="desconto_maximo" class="form-label">Desconto Max(%)</label>
                            <input type="number" step="any" class="form-control" id="desconto_maximo" name="desconto_maximo" placeholder="Ex. 5" value="">
                        </div>
                    </div>
                    <div class="row d-flex align-items-end">

                        <div class="col-sm-6 col-md-4 mb-2">
                            <label for="taxa" class="form-label">Taxa</label>
                            <input type="number" step="any" class="form-control" placeholder="Ex. 0.5" id="taxa" name="taxa" value="">
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_taxa" value="f" id="taxa_fixo">
                                <label class="form-check-label" for="taxa_fixo">
                                    Taxa R$
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="tipo_taxa" value="%" id="taxa_porcentagem" checked>
                                <label class="form-check-label" for="taxa_porcentagem">
                                    Taxa %
                                </label>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" name="avista" id="avista">
                                <label class="form-check-label" for="avista">
                                    Avista
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" name="default" id="default">
                                <label class="form-check-label" for="default">
                                    Default
                                </label>
                            </div>


                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" name="ativo" id="ativo">
                                <label class="form-check-label" for="ativo">
                                    Ativo
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" name="delivery" id="delivery">
                                
                                <label class="form-check-label" for="delivery">
                                    Delivery / Loja
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="js/funcao.js"></script>
<script src="js/configuracao/forma_pagamento/forma_pagamento_tela.js"></script>