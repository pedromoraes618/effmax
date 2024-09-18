<?php
include "../../../modal/estoque/produto/gerenciar_produto.php";
?>

<div class="title">
    <label class="form-label">Consultar Produtos</label>
</div>
<hr>
<div class="row mb-2">
    <div class="col-md-2 mb-2">
        <select name="status_prod" class="form-select chosen-select" id="status_prod">
            <option value="0">Status..</option>
            <option selected value="SIM">Ativo</option>
            <option value="NAO">Inativo</option>
        </select>
    </div>
    <div class="col-6 col-md-2  mb-2">
        <select name="tipo_produto" class="select2 chosen-select" id="tipo_produto">
            <option value="0">Tipo..</option>
            <?php
            $resultados = consulta_linhas_tb($conecta, 'tb_tipo_produto');
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
    <div class="col-md-2 mb-2">
        <select name="subgrupo" class="select2 chosen-select" id="subgrupo">
            <option value="0">Categoria..</option>
            <?php
            $resultados = consulta_linhas_tb($conecta, 'tb_subgrupo_estoque');
            if ($resultados) {
                foreach ($resultados as $linha) {
                    $id = $linha['cl_id'];
                    $descricao_subgrupo = utf8_encode($linha['cl_descricao']);
                    $grupo_id = ($linha['cl_grupo_id']);
                    $descricao_grupo = consulta_tabela($conecta, "tb_grupo_estoque", "cl_id", $grupo_id, "cl_descricao");
                    echo "<option  value='$id'>$descricao_grupo - $descricao_subgrupo</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="col-md-2 mb-2">
        <select name="estoque_consulta" class="select2 chosen-select" id="estoque_consulta">
            <option value="0">Estoque..</option>
            <option value="S">Com estoque</option>
            <option value="N">Sem estoque</option>
        </select>
    </div>
    <div class="col-md-2 mb-2">
        <select name="status_promocao" class="select2 chosen-select" id="status_promocao">
            <option value="0">Status promoções..</option>
            <option value="ativo">Em promoção</option>
            <option value="expirado">Expirado</option>
        </select>
    </div>
    <div class="col-md-2 mb-2">
        <select class="select2 chosen-select" id="unidade_medida">
            <option value="0">Und.</option>
            <?php
            $resultados = consulta_linhas_tb($conecta, 'tb_unidade_medida');
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
    <div class="col-md  mb-2">
        <div class="input-group">
            <input type="text" class="form-control" id="pesquisa_conteudo" placeholder="Tente pesquisar pelo titulo, referência, código ou código de barras" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
        </div>
        <div class="alerta">

        </div>
    </div>
    <div class="col-md-auto  d-grid gap-2 d-sm-block mb-1">
        <button class="btn btn-dark" type="button" id="adicionar_produto"><i class="bi bi-plus-circle"></i> Produto</button>
    </div>
</div>

<div class="tabela">

</div>

<div class="modal_show">

</div>
<script src="js/estoque/produto_ecommerce/consultar_produto.js"></script>