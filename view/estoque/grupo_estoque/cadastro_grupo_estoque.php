<?php include "../../../conexao/conexao.php"; ?>
<?php include "../../../modal/estoque/grupo_estoque/gerenciar_grupo_estoque.php";
?>


<div class="title">
    <label class="form-label">Cadastrar Grupo estoque</label>
    <div class="msg_title">
        <p>Cadastrar grupo de estoque </p>
    </div>
</div>
<hr>
<form id="cadastrar_grupo_estoque">
    <div class="row">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
            <button type="subbmit" class="btn btn-sm btn-success">Cadastrar</button>
        </div>

    </div>
    <div class="row">
        <input type="hidden" name="formulario_cadastrar_grupo_estoque">
        <?php include "../../input_include/usuario_logado.php" ?>
        <div class="col-sm  mb-2">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" class="form-control" id="descricao" name="descricao" placeholder="" value="">
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm">
            <div title="os produtos que estão incluidos nesse grupo pertencem ao modulo de vendas" class="form-check form-check-inline">
                <input class="form-check-input" checked type="checkbox" id="grupo_venda" name="grupo_venda">
                <label class="form-check-label" for="grupo_venda">Grupo para venda</label>
            </div>
            <div title="os produtos que estão incluidos nesse grupo pertencem ao modulo de serviços" class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="grupo_servico" name="grupo_servico">
                <label class="form-check-label" for="grupo_servico">Grupo para serviço</label>
            </div>

        </div>
    </div>


</form>

<script src="js/configuracao/users/user_logado.js"></script>

<script src="js/estoque/grupo_estoque/cadastrar_grupo_estoque.js"></script>