<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Consultar Pedidos</label>
</div>
<hr>
<!-- <div class="row">
    <ul class="nav nav-links mb-3 mb-lg-2 mx-n3">
        <li class="nav-item"><a class="nav-link active" aria-current="page" href="#"><span>Todos </span><span class="text-body-tertiary fw-semibold">(68817)</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><span>Pendente </span><span class="text-body-tertiary fw-semibold">(6)</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><span>Não Concluido </span><span class="text-body-tertiary fw-semibold">(17)</span></a></li>
        <li class="nav-item"><a class="nav-link" href="#"><span>Concluido</span><span class="text-body-tertiary fw-semibold">(6,810)</span></a></li>
    </ul>
</div> -->
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
            <option value="0">Status Pedido..</option>
            <option value="CONCLUIDO">Conluido</option>
            <option value="ANDAMENTO">Em Andamento</option>
            <option value="CANCELADO">Cancelado</option>
        </select>
    </div>
    <div class="col-md-2 mb-2">
        <select name="status_pagamento" class="form-select chosen-select" id="status_pagamento">
            <option value="0">Status Pagamento..</option>
            <option value="approved">Aprovado</option>
            <option value="in_process">Em processamento</option>
            <option value="pending">Pendente</option>
            <option value="rejected">Pagamento rejeitado</option>
            <option value="cancelled">Cancelado</option>
        </select>
    </div>
    <div class="col-md-2 mb-2">
        <select name="forma_pgt" class="form-select chosen-select" id="forma_pgt">
            <option value="0">Forma Pagamento..</option>
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
</div>
<div class="row">
    <div class="col-md  mb-2">
        <div class="input-group">
            <input type="search" class="form-control" id="pesquisa_conteudo" placeholder="Tente pesquisar pelo cliente, e-mail ou nº do pedido" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
        </div>
    </div>
</div>

<div class="tabela"></div>
<div class="modal_show"></div>


<script src="js/ecommerce/pedido/consultar_pedido.js"></script>