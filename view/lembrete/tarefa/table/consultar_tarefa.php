<?php 
include "../../../../modal/lembrete/tarefa/gerenciar_tarefa.php";
?>

<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">Código</th>
            <th scope="col">Lançamento</th>
            <th scope="col">Descrição</th>
            <th scope="col">Comentário</th>
            <th scope="col">Usuário</th>
            <th scope="col">Status</th>

            <th scope="col">Prioridade</th>
            <th scope="col">Data Limite</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
    </thead>
    <?php if(isset($consultar_tarefas)){//verificar se foi feito a requisição corretamente?>
    <tbody>
        <?php while($row = mysqli_fetch_assoc($consultar_tarefas)){
                $id_tarefa_b= $row['cl_id'];
                $data_lancamento_b = ($row['cl_data_lancamento']);
                $descricao_b = utf8_encode($row['cl_descricao']);
                $comentario_b = utf8_encode($row['cl_comentario']);
             
                $status_b = $row['status'];
                $prioridade_b= $row['cl_prioridade'];
                $data_limite_b = ($row['cl_data_limite']);
                $usuario_func = $row['usuario_func'];
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
            <td><?php echo $usuario_func; ?></td>
            <td><span
                    class="badge text-bg-<?php if($status_b == "Concluido"){echo 'success' ;}else{echo 'primary';} ?>"><?php echo $status_b; ?></span>
            </td>
            <td><span
                    class="badge text-bg-<?php if($prioridade_b=="sim"){ echo 'danger';}else{ echo 'secondary';} ?>"><?php echo $prioridade_b; ?></span>
            </td>
            <td><?php echo formatDateB($data_limite_b); ?></td>


            <td class="td-btn"><button type="button" id_tarefa=<?php echo $id_tarefa_b; ?>
                    class="btn btn-sm btn-info editar_tarefa">Editar</button>
            </td>
            <td class="td-btn"><button title="remover tarefa" type="button" id_tarefa=<?php echo $id_tarefa_b; ?>
                    class="btn btn-sm btn-danger remover_tarefa"><i class="bi bi-trash"></i></button>
            </td>
        </tr>

        <?php } }?>
    </tbody>
</table>

<script src="js/lembrete/tarefa/table/editar_tarefa.js">