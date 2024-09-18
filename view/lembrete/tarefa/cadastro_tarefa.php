<?php  include "../../../conexao/conexao.php"; ?>
<?php include "../../../modal/lembrete/tarefa/gerenciar_tarefa.php";  
?>


<div class="title">
    <label class="form-label">Adicionar Tarefa</label>
    <div class="msg_title">
        <p>Adicione tarefas aos usuários </p>
    </div>
</div>
<hr>
<form id="cadastrar_tarefa">
    <div class="row">
        <input type="hidden" name="formulario_cadastrar_tarefa">

        <?php include "../../input_include/usuario_logado.php"?>

        <div class="col-sm-5  mb-2">
            <label for="descricao" class="form-label">Descrição</label>
            <textarea class="form-control" name="descricao" id="descricao" aria-label="With textarea"></textarea>

        </div>

        <div class="col-sm-2  mb-2">
            <label for="data_limite" class="form-label">Data limite</label>
            <input type="date" class="form-control" id="data_limite"
                name="data_limite" value="">

        </div>

        <div class="col-sm-2  mb-2">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-select chosen-select" id="status_lembrete">
                <option value="0">Selecione..</option>
                <?php while($linha = mysqli_fetch_assoc($consultar_status_tarefas)){ 
                $id_status_tarefa = $linha['cl_id'];
                $descricao = $linha['cl_descricao'];
                ?>
                <option value="<?php echo $id_status_tarefa; ?>"><?php echo $descricao  ?></option>
                <?php
            } ?>
            </select>
        </div>
        <div class="col-sm  mb-2">
            <label for="usuario" class="form-label">Usuário</label>
            <select name="usuario" class="form-select chosen-select" id="usuario">
                <option value="0">Selecione..</option>
                <?php while($linha = mysqli_fetch_assoc($consultar_usuarios)){ 
                $id_user = $linha['cl_id'];
                $usuario = $linha['cl_usuario'];
                ?>
                <option value="<?php echo $id_user; ?>"><?php echo $usuario  ?></option>

                <?php
            } ?>
            </select>
        </div>
    </div>

    <div class="row">
        <div class="col-sm  mb-3">
            <label for="comentario" class="form-label">Comentario</label>
            <input type="text" class="form-control" id="comentario" name="comentario" placeholder="" value="">
        </div>
    </div>
    <div class="row">
        <div class="col-sm  mb-2">
        
            <div class="form-check form-check-inline">
                <input class="form-check-input" name="prioridade" type="checkbox" id="prioridade">
                <label class="form-check-label" for="prioridade">Prioridade</label>
            </div>
        </div>

    </div>
    <Div class="row">
        <div class="group-btn d-grid gap-2 d-sm-block">
        <button type="submit" class="btn btn-sm btn-success">Adicionar</button>
        </div>
    </Div>


</form>
<?php include '../../../funcao/funcaojavascript.jar'; ?>
<script src="js/configuracao/users/user_logado.js"></script>
<script src="js/lembrete/tarefa/cadastro_tarefa.js"></script>