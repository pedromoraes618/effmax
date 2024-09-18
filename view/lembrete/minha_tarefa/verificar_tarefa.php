<?php  include "../../../conexao/conexao.php"; ?>
<?php include "../../../modal/lembrete/minha_tarefa/gerenciar_minha_tarefa.php";  
?>

<div class="title">
    <label class="form-label">Minhas tarefas</label>
    <div class="msg_title">
        <p>Adicione tarefas aos usuários </p>
    </div>
</div>
<hr>
<form>
    <div class="row mx-1">
        <?php 
            while($linha  = mysqli_fetch_assoc($consultar_minhas_tarefas)){
                $data_lancamento_b = formatDateB($linha['cl_data_lancamento']);
                $descricao_b = utf8_encode($linha['cl_descricao']);
                $data_limite_b = formatDateB($linha['cl_data_limite']);
                $status_b = ($linha['status']);
                $status_id_b = ($linha['cl_status']);
                $usuario_ordem_b = $linha['usuarioord'];
                $comentario_b = utf8_encode($linha['cl_comentario']);
                $comentario_func_b = utf8_encode($linha['cl_comentario_func']);
                $prioridade_b = ($linha['cl_prioridade']);
                $id_tarefa = ($linha['idtarefa']);
                $usuario_func = $linha['userfunc'];
        ?>

        <div  class="col-sm-6 mb-3 mb-sm-0">
            <div class="card mb-2 bg-Light card-tarefa card<?php echo $id_tarefa ?>">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-auto">
                            <span class="badge text-bg-primary"><?php echo $status_b; ?></span>
                        </div>
                    <?php if($prioridade_b == "1"){?>

                    <div class="col-auto">
                    <span class="badge text-bg-danger">Prioridade</span>
                    </div>

                    <?php } ?>
                        <div class="col-auto">
                            Dt lançamento: <?php echo $data_lancamento_b ?>
                        </div>


                        <?php if($data_limite_b != ""){ ?>
                        <div class="col-auto">
                            Dt limite: <?php echo $data_limite_b ?>
                        </div>
                        <?php }?>

                       
                    </div>

                    <div class="row mb-3">
                        <p class="card-text"><?php echo $descricao_b; ?></p>
                    </div>
                    <div class="row mb-3">
                        <p class="card-text">Obs: <?php echo $comentario_b; ?></p>
                    </div>
                    <div class="row mb-1">
                        <div class="col  mb-2">

                            <input type="text" class="form-control" id="comentario<?php echo $id_tarefa ?>"
                                name="comentario" placeholder="Adicione o seu comentario" value="<?php echo $comentario_func_b; ?>">

                        </div>
                    </div>
                    <div class="row  mb-2">
                        <div class="col-sm-auto col-md-4  mb-2">
                            <select name="status" id="status<?php echo $id_tarefa; ?>" class="form-select chosen-select">
                                <option value="0">Status..</option>
                                <?php
                                verificar_status($conecta,$status_id_b)
                                ?>

                            </select>
                        </div>
                        <div class="col-auto">
                            Solicitante: <?php echo $usuario_ordem_b; ?>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-4  d-grid gap-2 d-sm-block mb-1  ">
                            <button class="btn btn-info col-auto atualizar_tarefa"
                                user_logado=<?php echo $usuario_func; ?> status_tarefa=<?php echo $status_id_b; ?>
                                id_tarefa=<?php echo $id_tarefa; ?> type="button">Atualizar</button>
                            
                        </div>

                    </div>
                </div>
            </div>

        </div>
        <?php }?>

    </div>





</form>
<?php include '../../../funcao/funcaojavascript.jar'; ?>
<script src="js/lembrete/minha_tarefa/verificar_minha_tarefa.js"></script>
<script src="js/configuracao/users/user_logado.js"></script>
