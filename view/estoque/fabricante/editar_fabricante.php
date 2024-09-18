<?php
include "../../../modal/estoque/fabricante/gerenciar_fabricante.php"; // trazer as informações da categoria
?>

<div class="title">
    <label class="form-label">Editar fabricante</label>
    <div class="msg_title">
        <p>Edite os fabricante cadastrado no sistema </p>
    </div>
</div>
<hr>
<form id="editar_fabricante">
    <div class="row mb-2">
        <input type="hidden" name="formulario_editar_fabricante">

        <?php include "../../input_include/usuario_logado.php" ?>

        <input type="hidden" value="<?php echo $id_fabricante; ?>" id="id_fabricante" name="id_fabricante">
        <div class="col-sm  mb-2">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" class="form-control" id="descricao" name="descricao" placeholder="" value="<?php echo $descricao_b; ?>">
        </div>



    </div>
    <div class="row">
        <div class=" d-grid gap-2 d-sm-block">
            <button type="subbmit" class="btn btn-sm btn-success">Alterar</button>
            <button type="button" id="remover" class="btn btn-sm btn-danger">Remover</button>
            <button type="button" id="voltar_cadastro" class="btn  btn-sm  btn-dark">Voltar</button>
        </div>
    </div>


</form>

<script src="js/configuracao/users/user_logado.js"></script>

<script src="js/estoque/fabricante/editar_fabricante.js"></script>