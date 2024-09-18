<?php
include "../../../modal/suporte/parametro/gerenciar_parametro.php"; // trazer as informações da categoria
?>

<div class="title">
    <label class="form-label">Editar parâmetro</label>
    <div class="msg_title">
        <p>Os parâmetros configuráveis permitem ajustar e personalizar o comportamento de um sistema sem alterar o
            código-fonte. </p>
    </div>
</div>
<hr>
<form id="editar_parametro">
    <div class="row mb-2">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
            <button type="submit" class="btn btn-sm btn-success">Alterar</button>
            <button type="button" id="voltar_cadastro" class="btn btn-sm btn-secondary">Voltar</button>
        </div>
    </div>

    <div class="row mb-2">
        <input type="hidden" name="formulario_editar_parametro">

        <?php include "../../input_include/usuario_logado.php" ?>

        <input type="hidden" value="<?php echo $id_parametro; ?>" name="id_parametro">

        <div class="col-md-1 mb-2">
            <label for="descricao" class="form-label">Código</label>
            <input type="text" class="form-control" disabled value="<?php echo $id_parametro; ?>">
        </div>
        <div class="col-md-5 mb-2">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" class="form-control" id="descricao" name="descricao" placeholder="" value="<?php echo $descricao_b; ?>">
        </div>
        <div class="col-md-4  mb-2">
            <label for="valor" class="form-label">Valor</label>
            <input type="text" class="form-control" id="valor" name="valor" placeholder="" value="<?php echo $valor_b; ?>">
        </div>
        <div class="col-md  mb-2">
            <label for="configuracao" class="form-label">Configuração</label>
            <select name="configuracao" id="configuracao" class="form-select chosen-select">
                <option value="0">Selecione...</option>
                <option <?php if ($configuracao_b == "seguranca") {
                            echo "selected";
                        } ?> value="seguranca">Segurança
                </option>
                <option <?php if ($configuracao_b == "performance") {
                            echo "selected";
                        } ?> value="performance">Performance
                </option>
                <option <?php if ($configuracao_b == "usuario") {
                            echo "selected";
                        } ?> value="usuario">Usuário
                </option>
                <option <?php if ($configuracao_b == "interface") {
                            echo "selected";
                        } ?> value="interface">Interface</option>
                <option <?php if ($configuracao_b == "checklist") {
                            echo "selected";
                        } ?>
                    <?php if ($configuracao_b == "loja") {
                        echo "selected";
                    } ?> value="loja">Loja</option>
            </select>
        </div>


    </div>


</form>

<script src="js/configuracao/users/user_logado.js"></script>

<script src="js/suporte/parametro/editar_parametro.js"></script>