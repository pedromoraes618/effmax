<?php
include "../../../modal/estoque/grupo_estoque/gerenciar_grupo_estoque.php"; // trazer as informações da categoria
?>

<div class="title">
    <label class="form-label">Editar grupo estoque</label>
    <div class="msg_title">
        <p>Edite os grupos de estoque </p>
    </div>
</div>
<hr>
<form id="editar_grupo_estoque">

    <div class="row">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
            <button type="subbmit" class="btn btn-sm btn-success">Alterar</button>
            <button type="button" id="remover" class="btn btn-sm btn-danger">Remover</button>
            <button type="button" id="voltar_cadastro" class="btn btn-sm btn-secondary">Voltar Para Cadastro</button>
        </div>
    </div>
    <div class="row mb-2">
        <input type="hidden" name="formulario_editar_grupo_estoque">

        <?php include "../../input_include/usuario_logado.php" ?>

        <input type="hidden" value="<?php echo $id_grupo; ?>" id="id_grupo" name="id_grupo">
        <div class="col-sm  mb-2">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" class="form-control" id="descricao" name="descricao" placeholder="" value="<?php echo $descricao_b; ?>">
        </div>

    </div>

    <div class="row">
        <div class="col-sm">
            <div title="os produtos que estão incluidos nesse grupo pertencem ao modulo de vendas" class="form-check form-check-inline">
                <input class="form-check-input" <?php if ($grupo_venda_b == 1) {
                                                    echo "checked";
                                                } ?> type="checkbox" id="grupo_venda" name="grupo_venda">
                <label class="form-check-label" for="grupo_venda">Grupo para venda</label>
            </div>
            <div title="os produtos que estão incluidos nesse grupo pertencem ao modulo de serviços" class="form-check form-check-inline">
                <input class="form-check-input" <?php if ($grupo_servico_b == 1) {
                                                    echo "checked";
                                                } ?> type="checkbox" id="grupo_servico" name="grupo_servico">
                <label class="form-check-label" for="grupo_servico">Grupo para serviço</label>
            </div>

        </div>
    </div>



</form>

<script src="js/configuracao/users/user_logado.js"></script>

<script src="js/estoque/grupo_estoque/editar_grupo_estoque.js"></script>