<?php

include "../../../../modal/delivery/pedido/gerenciar_pedido.php";


?>

<div class="row">
    <div class="col-md">
        <div class="card mb-2">
            <div class="card-header text-bg-dark"><?php echo $statuspedido; ?></div>
            <div class="card-body ">
                <nav class="">
                    <ul class="list-group">
                        <li class="list-group-item"><strong>Nº Pedido:</strong> <?php echo $numero_pedido; ?></li>
                        <li class="list-group-item"><strong>Método de entrega:</strong> <?php echo formatarTexto($metodo_entrega); ?></li>

                        <li class="list-group-item"><span><strong>Cliente:</strong> <?php echo $usuario_delivery; ?> <strong>Telefone:</strong></span> <?php echo $telefone; ?> </li>
                        <li class="list-group-item"><strong>Localização:</strong> <?php echo $endereco_cliente; ?> <a target="_blank" href="https://google.com.br/maps/dir/<?php echo $endereco_maps_empresa; ?>/<?php echo $endereco_maps; ?>/data=!3m1!4b1!4m14!4m13!1m5!1m1!1s0x7f690173e2c5a37:0xb9149c97b526a3a1!2m2!1d-44.2365011!2d-2.5556441!1m5!1m1!1s0x7f691ca7134b4bd:0xeeb88527dc06016!2m2!3e9?entry=ttu">Ver no mapa</a></li>
                        <li class="list-group-item"><strong>Entrega estimada:</strong> <i class='bi bi-clock'></i> <?php echo calcularHoraEntrega($data_pedido_delivery, $tempo_entrega_estimado); ?></li>

                        <li class="list-group-item"><strong>Forma de pagamento:</strong> <?php echo $formapgt; ?> </li>
                        <!-- <li class="list-group-item"><strong>Status:</strong> <?php echo $statuspedido; ?>  </li> -->
                        <li class="list-group-item"><strong>Valor entrega:</strong> <?php if ($metodo_entrega == "ENTREGA") {
                                                                                        echo $valor_entrega_delivery;
                                                                                    } else {
                                                                                        echo "Grátis";
                                                                                    } ?> </li>
                        <li class="list-group-item"><strong>Valor Total:</strong> <?php echo real_format($valor_liquido); ?> </li>
                    </ul>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md  mb-2">
                        <label for="status" class="form-label">Status Pedido</label>
                        <select name="status" class="form-select chosen-select" id="status">
                            <option value="0">Selecione..</option>
                            <?php while ($linha  = mysqli_fetch_assoc($consultar_status_pedido)) {
                                $id = $linha['cl_id'];
                                $descricao = utf8_encode($linha['cl_descricao']);
                                if ($status_id == $id) {
                                    $check = "selected";
                                } else {
                                    $check = '';
                                }
                                if ($id != "6" and $id != "7" and $id != "8" and $id != "9") {
                                    if ($metodo_entrega == "ENTREGA" and $id != '4') { //id para status retirada
                                        echo "<option $check value='$id'> $descricao </option>'";
                                    } elseif ($metodo_entrega == 'RETIRADA' and $id != "3") { //id para status entrega
                                        echo "<option $check value='$id'> $descricao </option>'";
                                    }
                                }
                            } ?>
                        </select>

                    </div>

                </div>
                <?php
                if ($solicitar_cancelamento_delivery == "SIM") {
                ?>
                    <div class="row">
                        <div class="col-md">
                            <label for="status" class="form-label">Motivo Cancelamento</label>
                            <textarea name="" disabled class="form-control" id="" cols="20" rows="3"><?php echo $motivo_cancelamento_delivery; ?></textarea>
                        </div>
                    </div>
                <?PHP } ?>
            </div>
            <div class="card-footer">
                <div class="d-grid gap-2 d-md-flex  mb-2">
                    <button type="button" id="alterar_status" pedido_id='<?php echo $id_pedido; //id_pedido= $cl_id da nf
                                                                            ?>' class="btn btn-sm btn-success">Alterar Pedido</button>
                    <?php if ($status_recebimento == '1') {
                    ?>
                        <button type="button" pedido_id="<?php echo $pedido_id; ?>" tipo_pagamento='cartao' class="receber_nf btn btn-sm btn-warning ">Recebimento</button>
                    <?php
                    } ?>
                    <!-- <button type="button" id="open_whats" telefone='<?php echo $dd_estado . $telefone ?>' class="btn btn-sm btn-success"><i class="bi bi-whatsapp"></i> Mensagem para o cliente</butt> -->
                    <input type="hidden" id="mensagem_for_cliente" value="<?php echo $mesagem_alerta_pedido_pronto; ?>">

                    <a href="https://api.whatsapp.com/send?phone=55<?php echo "$dd_estado" . $telefone; ?>&text=<?php echo $mesagem_alerta_pedido_pronto; ?> &#128512; Número do pedido <?php echo $numero_pedido; ?> " target="_blank" class="btn btn-sm btn-success"><i class="bi bi-whatsapp"></i> Mensagem para o cliente</a>

                </div>
                <div class="d-grid gap-2 d-md-flex  mb-2">
                    <button type="button" pedido_id="<?php echo $pedido_id; ?>" class="btn btn-sm btn-info comanda">Comanda</button>
                </div>


                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" pedido_id='<?php echo $id_pedido; ?>' class="btn btn-sm btn-danger cancelar_pedido">Cancelar Pedido</button>
                </div>

            </div>
        </div>
    </div>
    <div class="col-md">
        <div class="card mb-2">
            <div class="card-header text-bg-dark">
                Itens

            </div>

            <div class="card-body ">
                <div class="">
                    <ul class="list-group">
                        <?php
                        /*tela de pedidos do usuario */
                        $select = "SELECT * from tb_nf_saida_item where cl_usuario_id_delivery ='$usuario_id' and 
                                cl_tipo_item_delivery ='PRODUTO' and cl_codigo_nf ='$codigo_nf' ";
                        $consulta_produto = mysqli_query($conecta, $select);
                        $qtd_consulta_produto = mysqli_num_rows($consulta_produto);

                        $valor_total = 0;
                        $valor_item_total = 0;
                        while ($linha = mysqli_fetch_assoc($consulta_produto)) {
                            $id_delivery = ($linha['cl_id_delivery']);
                            $titulo = utf8_encode($linha['cl_descricao_item']);
                            $valor_unitario = ($linha['cl_valor_unitario']);
                            $quantidade_produto = ($linha['cl_quantidade']);
                            $observacao_produto = utf8_encode($linha['cl_observacao_delivery']);
                            $valor_produto = $valor_unitario * $quantidade_produto;
                        ?>

                            <li class="list-group-item p-2">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="titulo_item">
                                        <div class="fw-medium"><?php echo $titulo; ?></div>
                                    </div>
                                    <div class="d-flex valores_item">
                                        <p class="mb-0 mx-2">Qtd: <?php echo $quantidade_produto; ?></p>
                                        <p class="mb-0"><?php echo real_format($valor_produto); ?></p>
                                    </div>
                                </div>
                                <!-- Lista de produtos adicionais -->

                                <ul class="list-group mt-3 m-1 mb-2">
                                    <?php

                                    $select = "SELECT cl_descricao_item,cl_valor_unitario,sub.cl_descricao as grupo,cl_quantidade,cl_valor_total,cl_tipo_adicional_delivery,
                                    cl_tipo_item_delivery from tb_nf_saida_item as nfi inner join tb_produtos as prd on 
                                    prd.cl_id = nfi.cl_item_id inner join tb_subgrupo_estoque as sub on sub.cl_id  = prd.cl_grupo_id   where cl_usuario_id_delivery ='$usuario_id' and 
                                (cl_tipo_item_delivery ='ADICIONAL' or cl_tipo_item_delivery ='COMPLEMENTO') and cl_codigo_nf ='$codigo_nf' 
                                and cl_id_pai_delivery ='$id_delivery' order by prd.cl_grupo_id   desc ";
                                    $consulta_itens = mysqli_query($conecta, $select);
                                    //   $qtd_consulta_produto = mysqli_num_rows($consulta_produto);
                                    while ($linha = mysqli_fetch_assoc($consulta_itens)) {
                                        $titulo = utf8_encode($linha['cl_descricao_item']);
                                        $quantidade = ($linha['cl_quantidade']);
                                        $valor_unitario = ($linha['cl_valor_unitario']);

                                        $tipo_adicional_delivery = ($linha['cl_tipo_adicional_delivery']);
                                        $tipo_item_delivery = ($linha['cl_tipo_item_delivery']);
                                        $grupo = utf8_encode($linha['grupo']);

                                        $valor = $quantidade * $valor_unitario; //valor total do produto

                                        $tipo_item_delivery_span = '<span class="badge rounded-pill text-bg-primary">' . formatarTexto($tipo_item_delivery) . '</span>';
                                        if ($tipo_adicional_delivery == "GRATIS") {
                                            $tipo_adicional_delivery_span = '<span class="badge rounded-pill text-bg-success">Grátis</span>';
                                            // $valor = 0;
                                        } else {
                                            $tipo_adicional_delivery_span = '';
                                            // $valor = $valor;
                                        }
                                        $grupos = '<span class="badge rounded-pill text-bg-info">' . ($grupo) . '</span>';

                                    ?>
                                        <li class="list-group-item p-2 d-flex justify-content-between align-items-start">
                                            <div class="titulo_item">
                                                <div style="font-size: 0.8em;" class="fw-medium"><?php echo $titulo   ?></div>
                                                <div style="font-size: 0.8em;"><?php echo $tipo_item_delivery_span . " " . $grupos . " " . $tipo_adicional_delivery_span;  ?></div>
                                            </div>
                                            <div class="d-flex valores_item">
                                                <p style="font-size: 0.8em;" class="mb-0 mx-2">Qtd: <?php echo $quantidade;
                                                                                                    ?></p>
                                                <p style="font-size: 0.8em;" class="mb-0"><?php echo real_format($valor); ?></p>
                                            </div>
                                        </li>

                                    <?php
                                        $valor_item_total  = $valor  + $valor_item_total; //valores do adiconais e complemementos
                                        //   $valor_total = $valor_produto + $valor_item_total ; //valor total apenas de um produto e seus acompanhamentos
                                    } ?>
                                </ul>

                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div class="titulo_item">
                                        <div class="fw-medium">Sub Total</div>
                                    </div>
                                    <div class="d-flex valores_item">
                                        <p class="mb-0"><?php echo real_format($valor_item_total + $valor_produto); ?></p>
                                    </div>
                                </div>
                                <?php if ($observacao_produto != "") { ?>
                                    <div class="d-flex justify-content-between align-items-start shadow p-1">
                                        <div class="titulo_item">
                                            <div class="fw-medium">Observação: <?php echo $observacao_produto; ?></div>
                                        </div>

                                    </div>
                                <?php } ?>
                            </li>

                        <?php $valor_item_total = 0;
                        } ?>

                    </ul>
                </div>
            </div>
        </div>

    </div>
</div>
<script src="js/delivery/pedido/card/pedido_detalhado.js"></script>