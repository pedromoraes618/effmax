<?php
include "../../../modal/delivery/pedido/gerenciar_pedido.php";
?>

<style>

</style>
<div class="d-flex">
    <div>
        <div class="title"><label class="form-label">Consultar Pedidos</label>
        </div>
    </div>
    <div class="ms-auto atualizar_pedidos" style="cursor: pointer;"><i class="bi bi-asterisk" style="font-size: 1.1em;"> Atualizar Pedidos</i> </div>
</div>

<hr>
<div class="row pedidos gy-5 ">
    <div class="col-md-3 col-sm-4 order-2 order-md-1  p-0">
        <div class="card  border-0">
            <?php if ($qtd_consulta_pd_solicitacao_cancelar > 0) { ?>
                <div class="card mb-2">
                    <div class="card-header bg-danger bg-gradient  text-white">
                        Solicitação para cancelamento (<?php echo $qtd_consulta_pd_solicitacao_cancelar ?>)
                    </div>
                    <div class="card-body">
                        <?php
                        while ($linha = mysqli_fetch_assoc($consulta_pd_solicitacao_cancelar)) {
                            $id_pedido = ($linha['nfid']);
                            $numero_pedido = ($linha['cl_numero_venda']);
                            $metodo_entrega = ($linha['cl_opcao_delivery']);
                            $data_pedido_delivery = ($linha['cl_data_pedido_delivery']);
                            $valor_liquido = ($linha['cl_valor_liquido']);
                        ?>

                            <nav class="status_pedido mb-2 aguardando_confirmacao blink"  onmouseout="removerClasseBlink(<?php echo $id_pedido;?>)"  id="id_pedido<?php echo $id_pedido; ?>" id_pedido="<?php echo $id_pedido; ?>">
                                <div class="active selecionado<?php echo $id_pedido; ?> mb-2">

                                </div>
                                <ul class="list-group active ">
                                    <li class="list-group-item bg-body-secondary"><strong>Nº Pedido:</strong> <?php echo $numero_pedido; ?> | <strong>Data:</strong> <?php echo  formatarTimeStamp($data_pedido_delivery); ?></li>
                                    <li class="list-group-item bg-body-secondary"><strong>Método de Entrega:</strong> <?php echo formatarTexto($metodo_entrega); ?></li>
                                  
                                    <li class="list-group-item bg-body-secondary">
                                        <div class="d-flex">
                                            <div> <strong>Valor:</strong> <?php echo real_format($valor_liquido); ?></div>
                                            <div class="ms-auto">
                                                <button type="button" pedido_id='<?php echo $id_pedido ?>' class="aceitar_cancelamento btn btn-sm btn-danger">Aceitar</button>
                                                <button type="button" pedido_id='<?php echo $id_pedido ?>' class="recusar_cancelamento btn btn-sm btn-secondary">Recusar</button>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </nav>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
            <div class="card  mb-2">
                <div class="card-header  bg-primary bg-gradient  text-white">
                    Aguardando confirmação (<?php echo $qtd_consulta_pedido_confirmacao ?>)
                </div>
                <div class="card-body p-1">
                    <?php
                    while ($linha = mysqli_fetch_assoc($consulta_pd_aguardando_confirmacao)) {
                        $id_pedido = ($linha['nfid']);
                        $numero_pedido = ($linha['cl_numero_venda']);
                        $metodo_entrega = ($linha['cl_opcao_delivery']);
                        $data_pedido_delivery = ($linha['cl_data_pedido_delivery']);
                        $valor_liquido = ($linha['cl_valor_liquido']);


                        // Verifica se o pedido já foi processado e não foi marcado no localStorage
                        echo "<script>";
                        echo "if (localStorage.getItem('pedido_$id_pedido') === null) {";
                        echo "    localStorage.setItem('pedido_$id_pedido', 'true');";
                        echo "}";
                        echo "</script>";


                    ?>

                        <nav class="status_pedido mb-2 aguardando_confirmacao blink"  onmouseout="removerClasseBlink(<?php echo $id_pedido;?>)" id="id_pedido<?php echo $id_pedido; ?>" id_pedido="<?php echo $id_pedido; ?>">
                            <div class="active selecionado<?php echo $id_pedido; ?> mb-2">

                            </div>
                            <ul class="list-group active">
                                <li class="list-group-item bg-body-secondary"><strong>Nº Pedido:</strong> <?php echo $numero_pedido; ?> | <strong>Data:</strong> <?php echo  formatarTimeStamp($data_pedido_delivery); ?></li>
                               <li class="list-group-item bg-body-secondary"><strong>Método de Entrega:</strong> <?php echo formatarTexto($metodo_entrega); ?></li>
                               <li class="list-group-item bg-body-secondary"><strong>Tempo de Entrega (min):</strong> <input type="number" id='entrega_<?php echo $id_pedido; ?>' class="form-control" value="<?php echo ($tempo_entrega); ?>"></li>
                               <li class="list-group-item bg-body-secondary">
                                    <div class="d-flex">
                                        <div> <strong>Valor:</strong> <?php echo real_format($valor_liquido); ?></div>
                                        <div class="ms-auto">
                                            <button type="button" pedido_id='<?php echo $id_pedido ?>' class="aceitar_pedido btn btn-sm btn-success">Aceitar</button>

                                            <button type="button" pedido_id='<?php echo $id_pedido ?>' class="recusar_pedido btn btn-sm btn-danger">Recusar</button>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </nav>
                    <?php } ?>

                </div>

            </div>
            <div class="card mb-2">
                <div class="card-header  bg-warning bg-gradient  text-white">
                    Pedidos em andamento (<?php echo $qtd_consulta_pedido_andamento ?>)
                </div>
                <div class="card-body">
                    <?php
                    while ($linha = mysqli_fetch_assoc($consulta_pd_andamento)) {
                        $id_pedido = ($linha['nfid']);
                        $numero_pedido = ($linha['cl_numero_venda']);
                        $metodo_entrega = ($linha['cl_opcao_delivery']);
                        $data_pedido_delivery = ($linha['cl_data_pedido_delivery']);
                        $valor_liquido = ($linha['cl_valor_liquido']);
                    ?>
                        <nav class="status_pedido mb-2" id="id_pedido<?php echo $id_pedido; ?>" id_pedido="<?php echo $id_pedido; ?>">
                            <div class="active selecionado<?php echo $id_pedido; ?> mb-2">

                            </div>
                            <ul class="list-group active">
                                <li class="list-group-item bg-body-secondary"><strong>Nº Pedido:</strong> <?php echo $numero_pedido; ?> | <strong>Data:</strong> <?php echo  formatarTimeStamp($data_pedido_delivery); ?></li>
                                <li class="list-group-item bg-body-secondary"><strong>Método de Entrega:</strong> <?php echo formatarTexto($metodo_entrega); ?></li>
                                <li class="list-group-item bg-body-secondary">
                                    <div class="d-flex">
                                        <div> <strong>Valor:</strong> <?php echo real_format($valor_liquido); ?></div>

                                    </div>
                                </li>
                            </ul>
                        </nav>
                    <?php } ?>
                </div>
            </div>
            <div class="card mb-2">
                <div class="card-header  bg-success bg-gradient  text-white">
                    Pronto para Entrega ou Retirada (<?php echo $qtd_consulta_pedido_aguardando_entrega ?>)
                </div>
                <div class="card-body">
                    <?php
                    while ($linha = mysqli_fetch_assoc($consulta_pd_aguardando_entrega)) {
                        $id_pedido = ($linha['nfid']);
                        $numero_pedido = ($linha['cl_numero_venda']);
                        $metodo_entrega = ($linha['cl_opcao_delivery']);
                        $data_pedido_delivery = ($linha['cl_data_pedido_delivery']);
                        $valor_liquido = ($linha['cl_valor_liquido']);
                    ?>
                        <nav class="status_pedido mb-2" id="id_pedido<?php echo $id_pedido; ?>" id_pedido="<?php echo $id_pedido; ?>">
                            <div class="active selecionado<?php echo $id_pedido; ?> mb-2">

                            </div>
                            <ul class="list-group active">
                                <li class="list-group-item bg-body-secondary"><strong>Nº Pedido:</strong> <?php echo $numero_pedido; ?> | <strong>Data:</strong> <?php echo formatarTimeStamp($data_pedido_delivery); ?></li>
                                <li class="list-group-item bg-body-secondary"><strong>Método de Entrega:</strong> <?php echo formatarTexto($metodo_entrega); ?></li>
                                <li class="list-group-item bg-body-secondary">
                                    <div class="d-flex">
                                        <div> <strong>Valor:</strong> <?php echo real_format($valor_liquido); ?></div>

                                    </div>
                                </li>
                            </ul>
                        </nav>
                    <?php } ?>
                </div>
            </div>

        </div>

    </div>
    <div class="col order-1 order-sm">
        <div class="card-externo">
            <div class="text-center"><img src="img/pedidos.svg" style="height:450px" class="card-img-top img-responsive"></div>

        </div>
    </div>

</div>


<div class="modal_show">

</div>

<script src="js/delivery/pedido/consultar_pedido.js"></script>