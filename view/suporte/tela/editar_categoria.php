<?php
include "../../../modal/suporte/tela/gerenciar_tela.php"; // trazer as informações da categoria
?>

<form id="editar_categoria">

    <div class="row">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Alterar</button>
            <button type="button" id="voltar_cadastro" class="btn btn-sm btn-secondary">Voltar</button>
        </div>
    </div>

    <div class="row">
        <input type="hidden" name="formulario_editar_categoria">

        <?php include "../../input_include/usuario_logado.php" ?>

        <input type="hidden" value="<?php echo $id_categoria_b; ?>" name="id_categoria">
        <div class="col-sm  mb-1">
            <label for="categoria" class="form-label">Categoria</label>
            <input type="text" class="form-control" id="categoria" name="categoria" placeholder="" value="<?php echo $categoria_b; ?>">
        </div>
        <div class="col-sm  mb-1">
            <label for="icone" class="form-label">Icone</label>
            <input type="text" class="form-control" id="icone" name="icone" placeholder="Ex. <i class='<i class='bi bi-person'></i></i>" value='<?php echo $icone_b ?>'>
        </div>
        <div class="col-sm mb-1">
            <label for="ordem" class="form-label">Ordem</label>
            <input type="text" class="form-control" id="ordem" name="ordem" placeholder="Ex. 5" value="<?php echo $ordem_b; ?>">
        </div>


    </div>


</form>

<script src="js/configuracao/users/user_logado.js"></script>
<!-- gerenciamento de telas-->
<script src="js/suporte/tela/editar_categoria.js"></script>