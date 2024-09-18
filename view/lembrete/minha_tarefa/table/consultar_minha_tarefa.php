<?php 
include "../../../../modal/lembrete/minha_tarefa/gerenciar_minha_tarefa.php";
?>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Código</th>
            <th scope="col">Lançamento</th>
            <th scope="col">Descrição</th>
            <th scope="col">Comentário</th>
            <th scope="col">Solicitante</th>
            <th scope="col">Status</th>

            <th scope="col">Prioridade</th>
            <th scope="col">Data Limite</th>
    

        </tr>
    </thead>

    <tbody>
        <?php while($linha = mysqli_fetch_assoc($consultar_tarefas)){
                $id_tarefa_b= $linha['cl_id'];
                $data_lancamento_b = ($linha['cl_data_lancamento']);
                $descricao_b = utf8_encode($linha['cl_descricao']);
                $comentario_b = utf8_encode($linha['cl_comentario']);
             
                $status_b = $linha['status'];
                $prioridade_b= $linha['cl_prioridade'];
                $data_limite_b = ($linha['cl_data_limite']);
                $usuario_func = $linha['usuario_func'];
                $usuario_ordem = $linha['usuarioordem'];
                if($prioridade_b == "1"){
                    $prioridade_b ="sim";
                }else{
                    $prioridade_b = "Não";
                }

            ?>
        <tr>
            <th scope="row"><?php echo $id_tarefa_b ; ?></th>
            <th><?php echo formatDateB($data_lancamento_b);?></th>
            <td><?php echo $descricao_b; ?></td>
            <td><?php echo $comentario_b; ?></td>
            <td><?php echo $usuario_ordem; ?></td>
            <td><span
                    class="badge text-bg-<?php if($status_b == "Concluido"){echo 'success' ;}else{echo 'primary';} ?>"><?php echo $status_b; ?></span>
            </td>
            <td><span
                    class="badge text-bg-<?php if($prioridade_b=="sim"){ echo 'danger';}else{ echo 'secondary';} ?>"><?php echo $prioridade_b; ?></span>
            </td>
            <td><?php echo formatDateB($data_limite_b); ?></td>


           
        </tr>

        <?php } ?>
    </tbody>
</table>

<!-- <script src="js/lembrete/minha_tarefa/table/editar_tarefa.js"> -->