<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Consultar Pré venda</label>
</div>
<hr>

<div class="row mb-2">
    <div class="col-auto  mb-2">
        <div class="input-group">
            <span class="input-group-text">Data</span>
            <input type="date" class="form-control " id="data_inicial" name="data_incial" title="Data Inicial" placeholder="Data Inicial" value="<?php echo $data_inicial_ano_bd ?>">
            <input type="date" class="form-control " id="data_final" name="data_final" title="Data Final" placeholder="Data Final" value="<?php echo $data_final_ano_bd; ?>">
        </div>
    </div>
    <div class="col-md-2 mb-2">
        <select name="status_pedido" class="form-select chosen-select" id="status_pedido">
            <option value="0">Status pedido..</option>
            <option value="CONCLUIDO">Conluido</option>
            <option value="ANDAMENTO">Em Andamento</option>
            <option value="CANCELADO">Cancelado</option>
        </select>

    </div>
    <div class="col-md-2 mb-2">
        <select name="status_pagamento" class="form-select chosen-select" id="status_pagamento">
            <option value="0">Status pagamento..</option>
            <option value="approved">Aprovado</option>
            <option value="in_process">Em processamento</option>
            <option value="pending">Pendente</option>
            <option value="rejected">Pagamento rejeitado</option>
            <option value="cancelled">Cancelado</option>
        </select>
    </div>
    <div class="col-md mb-2">
        <select name="forma_pgt" class="form-select chosen-select" id="forma_pgt">
            <option value="0">Forma pagamento..</option>
            <?php
            $resultados = consulta_linhas_tb_2_filtro($conecta, 'tb_forma_pagamento', 'cl_ativo', 'S', 'cl_ativo_delivery', 'S');
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
    <div class="col-md-2 mb-2">
        <select name="produto_id" class="form-select chosen-select" id="produto_id">
            <option value="0">Produto..</option>
            <?php
            $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_pre_venda group by cl_produto_id");
            if ($resultados) {
                foreach ($resultados as $linha) {
                    $id = $linha['cl_produto_id'];
                    if (!empty($id)) {
                        $descricao = utf8_encode($linha['cl_descricao_produto']);
                        echo "<option value='$id'>$descricao</option>";
                    }
                }
            }
            ?>
        </select>
    </div>
</div>

<div class="row mb-2">
    <div class="col-md  mb-2">
        <div class="input-group">
            <input type="search" class="form-control" id="pesquisa_conteudo" placeholder="Tente pesquisar pelo cliente, e-mail, nº da pré venda ou nº do carrinho" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
        </div>
    </div>
</div>

<div class="tabela"></div>
<div class="modal_show"></div>


<script src="js/ecommerce/pre_venda/consultar_pre_venda.js"></script>