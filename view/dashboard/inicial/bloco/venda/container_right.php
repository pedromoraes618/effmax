<?php
include "../../../../../modal/dashboard/inicial/gerenciar_dashboard.php";
?>

<div class="card m-1 shadow border-0 mb-2 ">
    <div class="card-header header-card-dashboard ">
        <h6><i class="bi bi-exclamation-octagon"></i> Caixa</h6>
    </div>
    <div class="card-body card-right p-1" style="text-align:center">
        <?php if ($resultado_consulta > 0) {
            if ($status == "aberto" or $status == "reaberto") {
                echo "<img style='max-width:180px;' class='img-fluid img_status' src='img/caixa_aberto.svg' ><p>Caixa aberto</p>";
            } else {
                echo "<img  style=max-width:180px;class='img-fluid img_status' src='img/caixa_fechado.svg' ><p>Caixa Fechado</p>";
            }
        } else {
            echo "<img   style=max-width:180px; class='img-fluid img_status' src='img/caixa_nao_aberto.svg' ><p>Caixa não aberto</p>";
        } ?>

    </div>
</div>
<div class="card m-1 shadow border-0 mb-2 ">
    <div class="card-header header-card-dashboard ">
        <h6><i class="bi bi-exclamation-octagon"></i> Lembretes</h6>
    </div>
    <div class="card-body card-right p-1">
        <?php if ($qtd_consultar_lembretes > 0) { ?>
            <?php while ($linhas = mysqli_fetch_assoc($consultar_lembretes)) {
                $id_tarefa_b = $linhas['cl_id'];
                $data_lancamento_b = ($linhas['cl_data_lancamento']);
                $descricao_b = utf8_encode($linhas['cl_descricao']);
                $comentario_b = utf8_encode($linhas['cl_comentario']);
                $status_b = $linhas['status'];
                $prioridade_b = $linhas['cl_prioridade'];
                $data_limite_b = ($linhas['cl_data_limite']);
                $usuario_func = $linhas['usuario_func'];
                if ($prioridade_b == "1") {
                    $bordar = 'border border-danger-subtle';
                } else {
                    $bordar = '';
                }
            ?>
                <div class="card p-1 mb-2 <?php echo $bordar; ?>">
                    <div class="card-body p-1">
                        <h6 class="card-title"><?php echo formatDateB($data_lancamento_b) . ""; ?></h6>
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
        <h6><i class="bi bi-exclamation-octagon"></i> Atalhos</h6>
    </div>
    <div class="card-body card-right p-1">
        <div class="card p-1 ">
            <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-2">
                <button type="button" id="adicionar_venda" venda_delivery='<?php if ($consultar_sistema_delivery == "S") {
                                                                                echo "S";
                                                                            } else {
                                                                                echo "N";
                                                                            } ?>' class="btn btn-sm btn-dark adicionar_venda">Venda</button>
                <button type="button" id="adicionar_lancamento" tipo="DESPESA" class="btn btn-sm btn-dark adicionar_retirada_caixa">Retirar Valor</button>
            </div>
       

            <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-2">
                <button type="button" id="abertura_fechamento_cx" class="btn btn-sm btn-dark abertura_fechamento_cx">Abertura e Fechamento cx</button>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-start ">
                <button type="button" id="adicionar_atendimento" class="btn btn-sm btn-dark adicionar_atendimento">Atendimento</button>
            </div>
        </div>
    </div>
</div>
<div class="card m-1 shadow border-0 ">
    <div class="card-header header-card-dashboard">
        <h6><i class="bi bi-exclamation-octagon"></i> Minhas vendas</h6>
    </div>
    <div class="card-body card-right p-1">
        <?php
        while ($linha = mysqli_fetch_assoc($consultar_minhas_vendas)) {
            $numero_nf = $linha['cl_numero_nf'];
            $serie_nf = $linha['cl_serie_nf'];
            $valor_liquido = $linha['cl_valor_liquido'];
            $data_movimento = $linha['cl_data_movimento'];
        ?>
            <div class="card p-1 mb-2">
                <div class="card-body p-1">
                    <h6 class="card-title"><?php echo $serie_nf . " " . $numero_nf . " / Data " . formatDateB($data_movimento); ?></h6>
                    <p class="card-text"><?php echo  real_format($valor_liquido); ?></p>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<div class="card m-1 shadow border-0 ">
    <div class="card-header header-card-dashboard">
        <h6><i class="bi bi-exclamation-octagon"></i> Vendas a receber</h6>
    </div>
    <div class="card-body card-right p-1">

        <?php
        while ($linha = mysqli_fetch_assoc($consultar_vendas_a_receber)) {
            $numero_nf = $linha['cl_numero_nf'];
            $serie_nf = $linha['cl_serie_nf'];
            $valor_liquido = $linha['cl_valor_liquido'];
            $data_movimento = $linha['cl_data_movimento'];
        ?>
            <div class="card p-1 mb-2">
                <div class="card-body p-1">
                    <h6 class="card-title"><?php echo $serie_nf . " " . $numero_nf . " / Data " . formatDateB($data_movimento); ?></h6>
                    <p class="card-text"><?php echo  real_format($valor_liquido); ?></p>
                </div>
            </div>
        <?php } ?>


    </div>
</div>

<script src="js/dashboard/inicial/bloco/gerente/container_right.js"></script>