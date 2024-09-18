<?php
include "../../../conexao/conexao.php";
include "../../../modal/financeiro/pagamentos_recebimentos/gerenciar_pagamentos_recebimentos.php";
include "../../../funcao/funcao.php";
?>

<div class="title">
    <label class="form-label">Consultar baixa de duplicatas</label>
</div>
<hr>
<div class="row">
    <div class="col-md-auto  mb-2">
        <div class="input-group">
            <span class="input-group-text">Dt. Vencimento</span>
            <input type="date" class="form-control  " id="data_inicial" name="data_incial" title="Data vencimento" placeholder="Data Inicial" value="<?php echo $data_inicial_mes_bd ?>">
            <input type="date" class="form-control " id="data_final" name="data_final" title="Data vencimento" placeholder="Data Final" value="<?php echo $data_final_mes_bd; ?>">
        </div>
    </div>

    <div class="col-md mb-2">
        <select name="" class="select2 chosen-select" id="classificao_financeiro">
            <option value="0">Classificação..</option>
            <?php
            $resultados = consulta_linhas_tb($conecta, 'tb_classificacao_financeiro');
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
        <select name="" class="select2 chosen-select" id="pagamento">
            <option value="0">Forma Pagamento..</option>
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
    <div class="col-md mb-2">
        <select name="" class="select2 chosen-select" id="status_pagamento">
            <option value="1">Duplicatas a Receber</option>
            <option value="3">Duplicatas a Pagar</option>
        </select>
    </div>
</div>
<div class="row">
    <div class="col-md  mb-2">
        <div class="input-group">
            <input type="text" class="form-control" id="pesquisa_conteudo" placeholder="Tente pesquisar pela descrição, nº do doc ou cliente/fornecedor" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
        </div>
    </div>
</div>
<div class="row"></div>
<div class="tabela"></div>
<div class="modal_show"></div>

<script src="js/financeiro/baixa_duplicata/consultar_baixa_duplicata.js"></script>