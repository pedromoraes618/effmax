<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Consultar Lançamentos Financeiros</label>
</div>
<hr>

<div class="row">
    <div class="col-md-auto  mb-2">
        <div class="input-group">
            <span class="input-group-text">Dt. Venc</span>
            <input type="date" class="form-control  " id="data_inicial" name="data_incial" title="Inicio" placeholder="Data Inicial" value="<?php echo $data_inicial_mes_bd ?>">
            <input type="date" class="form-control " id="data_final" name="data_final" title="Fim" placeholder="Data Final" value="<?php echo $data_final_mes_bd; ?>">
        </div>
    </div>


    <div class="col-md mb-2">
        <select name="" class="select2 chosen-select" id="status_lancamento">
            <option value="0">Status..</option>
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


    <div class="col-md-2 mb-2">
        <select name="" class="select2 chosen-select " id="classificao_financeiro">
            <option value="0">Classificação..</option>
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
    <div class="col-md-2 mb-2">
        <select class="select2 chosen-select " id="forma_pagamento_consulta">
            <option value="0">Forma Pagamento..</option>
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
    <div class="col-md mb-2">
        <select name="" class="select2 chosen-select" id="tipo_lancamento">
            <option value="0">Tipo..</option>
            <option value="RECEITA">Receita</option>
            <option value="DESPESA">Despesa</option>
        </select>
    </div>
    <div class="col-md-2 mb-2">
        <select name="" class="select2 chosen-select " id="conta_finan">
            <option value="0">Conta Financeira..</option>
            <?php
            $resultados = consulta_linhas_tb($conecta, 'tb_conta_financeira');
            if ($resultados) {
                foreach ($resultados as $linha) {
                    $conta = $linha['cl_conta'];
                    $banco = utf8_encode($linha['cl_banco']);
                    echo "<option  value='$conta'> $banco </option>";
                }
            }
            ?>
        </select>
    </div>

</div>
<div class="row">

    <div class="col-md  mb-2">
        <div class="input-group">
            <input type="text" class="form-control" id="pesquisa_conteudo" placeholder="Tente pesquisar pela descrição, nº do doc ou cliente/fornecedor" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
        </div>
    </Div>
    <div class="col-md-auto  d-grid gap-2 d-sm-block mb-2">
        <button type="button" id="adicionar_lancamento_receita" class="btn btn-dark">
            <i class="bi bi-plus-circle"></i> Receita
        </button>
        <button type="button" id="adicionar_lancamento_despesa" class="btn btn-dark">
            <i class="bi bi-plus-circle"></i> Despesa
        </button>
        <button type="button" id="adicionar_lancamento_multiplo" class="btn btn-dark">
            <i class="bi bi-plus-circle"></i> Múltiplos
        </button>
        <button type="button" id="adicionar_lancamento_transferencia_valores" class="btn btn-dark">
            <i class="bi bi-plus-circle"></i> Transferência
        </button>
    </div>
</div>


<div class="tabela">

</div>

<div class="modal_show">

</div>

<script src="js/financeiro/lancamento_financeiro/consultar_lancamento_financeiro.js"></script>