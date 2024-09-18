<?php
include "../../../conexao/conexao.php";
include "../../../modal/lembrete/tarefa/gerenciar_tarefa.php";
?>

<div class="title">
    <label class="form-label">Editar Tarefa</label>
    <div class="msg_title">
        <p>Edite tarefas dos usúarios </p>
    </div>
</div>
<hr>
<form id="editar_tarefa">
    <div class="row">
        <input type="hidden" name="formulario_editar_tarefa">

        <?php include "../../input_include/usuario_logado.php" ?>

        <input type="hidden" value="<?php echo $id_tarefa; ?>" id="id_tarefa" name="id_tarefa">
        <div class="col-sm-5  mb-2">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea class="form-control" name="descricao" id="descricao" aria-label="With textarea"><?php echo $descricao_b; ?></textarea>

        </div>

        <div class="col-sm-2  mb-2">
            <label for="data_limite" class="form-label">Data limite</label>
            <input type="date" class="form-control" id="data_limite" name="data_limite"  value="<?php echo $data_limite_b; ?>">

        </div>

        <div class="col-sm-2  mb-2">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-select chosen-select" id="status_lembrete">
                <option value="0">Selecione..</option>
                <?php while ($linha = mysqli_fetch_assoc($consultar_status_tarefas)) {
                    $id_status_tarefa = $linha['cl_id'];
                    $descricao = $linha['cl_descricao'];
                    if ($status_b == $id_status_tarefa) {
                ?>
                        <option selected value="<?php echo $id_status_tarefa; ?>"><?php echo $descricao  ?></option>
                    <?php
                    } else {
                    ?>
                        <option value="<?php echo $id_status_tarefa; ?>"><?php echo $descricao  ?></option>
                <?php
                    }
                } ?>
            </select>
        </div>
        <div class="col-sm  mb-2">
            <label for="usuario" class="form-label">Usúario</label>
            <select name="usuario" class="form-select chosen-select" id="usuario">
                <option value="0">Selecione..</option>
                <?php while ($linha = mysqli_fetch_assoc($consultar_usuarios)) {
                    $id_user = $linha['cl_id'];
                    $usuario = $linha['cl_usuario'];
                    if ($id_user == $usuario_func_b) {
                ?>
                        <option selected value="<?php echo $id_user; ?>"><?php echo $usuario  ?></option>
                    <?php
                    } else {
                    ?>
                        <option value="<?php echo $id_user; ?>"><?php echo $usuario  ?></option>
                <?php
                    }
                } ?>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-sm  mb-3">
            <label for="comentario" class="form-label">Comentário</label>
            <input type="text" class="form-control" id="comentario" name="comentario" placeholder="" value="<?php echo $comentario_b; ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-sm  mb-3">
            <label for="comentario" class="form-label">Comentario funcionário</label>
            <input type="text" disabled class="form-control" placeholder="" value="<?php echo $comentario_func_b; ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-sm  mb-2">
            <div class="form-check form-check-inline">
                <input class="form-check-input" <?php if ($prioridade_b == "1") {
                                                    echo 'checked';
                                                } ?> name="prioridade" type="checkbox" id="prioridade">
                <label class="form-check-label" for="prioridade">
                    Prioridade
                </label>
            </div>
        </div>

    </div>
    <div class="row">
        <div class=" d-grid gap-2 d-sm-block">
            <button type="submit" class="btn btn-sm btn-success">Alterar</button>
            <button type="button" id="remover" class="btn btn-sm btn-danger">Remover</button>
            <button type="button" id="voltar_cadastro" class="btn btn-sm btn-secondary">Voltar</button>
        </div>
    </div>




</form>
<?php include '../../../funcao/funcaojavascript.jar'; ?>
<script src="js/configuracao/users/user_logado.js"></script>
<script src="js/lembrete/tarefa/editar_tarefa.js"></script>