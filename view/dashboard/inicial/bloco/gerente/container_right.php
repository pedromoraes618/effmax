<?php
include "../../../../../modal/dashboard/inicial/gerenciar_dashboard.php";
?>

<div class="card m-1 shadow border-0 mb-2 ">
    <div class="card-header header-card-dashboard ">
        <h6><i class="bi bi-exclamation-octagon"></i> Caixa</h6>
    </div>
    <div class="card-body card-right p-1 card-status-cx" style="text-align:center">
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
        <?php if ($qtd_consultar_lembretes > 0 or $qtd_consultar_validade_prd > 0) { ?>
            <?php while ($linha = mysqli_fetch_assoc($consultar_lembretes)) {
                $id_tarefa_b = $linha['cl_id'];
                $data_lancamento_b = ($linha['cl_data_lancamento']);
                $descricao_b = utf8_encode($linha['cl_descricao']);
                $comentario_b = utf8_encode($linha['cl_comentario']);
                $status_b = $linha['status'];
                $prioridade_b = $linha['cl_prioridade'];
                $data_limite_b = ($linha['cl_data_limite']);
                $usuario_func = $linha['usuario_func'];
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

            while ($linha = mysqli_fetch_assoc($consultar_validade_prod)) {
                $data_validade = $linha['cl_data_validade'];
                $descricao = utf8_encode($linha['cl_descricao']);

            ?>
                <div class="card p-1 mb-2 border border-danger-subtle">
                    <div class="card-body p-1">
                        <h6 class="card-title"><?php echo "Vencimento " . formatDateB($data_validade) . ""; ?></h6>
                        <p class="card-text"><?php echo "Produto " . $descricao . " com data de validade próxima ao vencimento"; ?></p>
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
        <div class="card p-1 mb-2">
            <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-2">
                <button type="button" id="adicionar_venda" venda_delivery='<?php if ($consultar_sistema_delivery == "S") {
                                                                                echo "S";
                                                                            } else {
                                                                                echo "N";
                                                                            } ?>' class="btn btn-sm btn-dark adicionar_venda">Venda</button>
                <button type="button" id="adicionar_lancamento" tipo="DESPESA" class="btn btn-sm btn-dark adicionar_lancamento">Retirar Valor</button>

            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-2">
                <button type="button" id="adicionar_lancamento" tipo="RECEITA" class="btn btn-sm btn-dark adicionar_lancamento">Adicionar Valor</button>
            </div>
            <div class="d-grid gap-2 d-md-flex justify-content-md-start ">
                <button type="button" id="abertura_fechamento_cx" class="btn btn-sm btn-dark abertura_fechamento_cx">Abertura e Fechamento cx</button>
            </div>
        </div>
    </div>
</div>

<div class="card m-1 shadow border-0 ">
    <div class="card-header header-card-dashboard">
        <h6><i class="bi bi-exclamation-octagon"></i> Desempenho da Equipe</h6>
    </div>
    <div class="card-body card-right p-1">
        <div class="card p-1 mb-2">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <tH>Vnd</td>
                        <tH>Vendedor</tH>
                        <tH>Vlr Vendas</tH>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($qtd_desempenho_equipe > 0) { ?>
                        <?php while ($linha = mysqli_fetch_assoc($consultar_desemepenho_equipe)) {
                            $vendedor_b = utf8_encode($linha['vendedor']);
                            $valor_b = $linha['valor'];
                            $vendas_b = $linha['vendas'];
                        ?>
                            <tr>
                                <td><?php echo $vendas_b; ?></td>
                                <td><?php echo $vendedor_b; ?></td>
                                <td><?php echo $valor_b; ?></td>
                            </tr>
                    <?php }
                    } else {
                        echo '<div class="sem_registro"><img class="img-fluid img_sem_registro" src="img/sem_registro.svg"> </div>';
                    } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card m-1 shadow border-0 ">
    <div class="card-header header-card-dashboard">
        <h6><i class="bi bi-exclamation-octagon"></i> Produtos mais Vendidos</h6>
    </div>
    <div class="card-body card-right p-1">

        <div class="card p-1 mb-2">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <tH>Rank</td>
                        <tH>Produto</tH>
                        <tH>Qtd</tH>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($qtd_prod_mais_vendidos > 0) {
                        $rank = 0;
                        while ($linha = mysqli_fetch_assoc($consultar_prod_mais_vendidos)) {
                            $descricao = utf8_encode($linha['cl_descricao']);
                            $quantidade = number_format($linha['total_vendido'], 2);
                            $valor_total = $linha['valor_total'];
                            if ($quantidade > 0) {
                                $rank = $rank + 1;
                    ?>
                                <tr>
                                    <td><?php echo $rank; ?></td>
                                    <td><?php echo $descricao; ?></td>
                                    <td><?php echo $quantidade; ?></td>
                                </tr>
                    <?php }
                        }
                    } else {
                        echo '<div class="sem_registro"><img class="img-fluid img_sem_registro" src="img/sem_registro.svg"> </div>';
                    } ?>
                </tbody>
            </table>
        </div>

    </div>
</div>
<script src="js/dashboard/inicial/bloco/gerente/container_right.js"></script>