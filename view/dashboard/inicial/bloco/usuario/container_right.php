<?php
include "../../../../../modal/dashboard/inicial/gerenciar_dashboard.php";
?>

<div class="card m-1 shadow border-0 mb-2 ">
    <div class="card-header header-card-dashboard ">
        <h6><i class="bi bi-exclamation-octagon"></i> Lembretes</h6>
    </div>
    <div class="card-body card-right p-1">
        <?php if ($qtd_consultar_lembretes > 0) { ?>
            <?php while ($linhas = mysqli_fetch_assoc($consultar_lembretes)) { 
                  $id_tarefa_b= $linhas['cl_id'];
                  $data_lancamento_b = ($linhas['cl_data_lancamento']);
                  $descricao_b = utf8_encode($linhas['cl_descricao']);
                  $comentario_b = utf8_encode($linhas['cl_comentario']);
                  $status_b = $linhas['status'];
                  $prioridade_b= $linhas['cl_prioridade'];
                  $data_limite_b = ($linhas['cl_data_limite']);
                  $usuario_func = $linhas['usuario_func'];
                  if($prioridade_b == "1"){
                    $bordar = 'border border-danger-subtle';
                  }else{
                    $bordar ='';
                  }
                ?>
                <div class="card p-1 mb-2 <?php echo $bordar; ?>">
                    <div class="card-body p-1">
                        <h6 class="card-title"><?php echo formatDateB($data_lancamento_b) ."" ;?></h6>
                        <p class="card-text"><?php echo $descricao_b; ?></p>
                    </div>
                </div>
        <?php }
        } else {
            echo '<div class="sem_registro"><img class="img-fluid img_sem_registro" src="img/sem_registro.svg"> </div>';
        } ?>

    </div>
</div>

<div class="card m-1 shadow border-0 ">
    <div class="card-header header-card-dashboard">
        <h6><i class="bi bi-exclamation-octagon"></i> Minhas vendas</h6>
    </div>
    <div class="card-body card-right p-1">

        <div class="card p-1 mb-2">
            <div class="card-body p-1">
                <h6 class="card-title">R$ 350,50</h6>
                <p class="card-text">Cliente avulso</p>
            </div>
        </div>
       
    </div>
</div>

<div class="card m-1 shadow border-0 ">
    <div class="card-header header-card-dashboard">
        <h6><i class="bi bi-exclamation-octagon"></i> Vendas a receber</h6>
    </div>
    <div class="card-body card-right p-1">

        <div class="card p-1 mb-2">
            <div class="card-body p-1">
                <h6 class="card-title">25/04/2023 VND35587</h6>
                <hp class="card-text">R$ 350,50 - Cartão</hp>
                <p class="card-text">Cliente avulso</p>
            </div>
        </div>
       
        
        <div class="card p-1 mb-2">
            <div class="card-body p-1">
                <h6 class="card-title">25/04/2023 VND35587</h6>
                <hp class="card-text">R$ 350,50 - Cartão</hp>
                <p class="card-text">Cliente avulso</p>
            </div>
        </div>
    </div>
</div>