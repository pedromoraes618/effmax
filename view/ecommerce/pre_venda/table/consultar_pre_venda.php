<?php
include "../../../../modal/ecommerce/pre_venda/gerenciar_pre_venda.php";
?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>

    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col"><i class="bi bi-cart"></i></th>
                <th scope="col">Pedido</th>
                <th scope="col">NF</th>
                <th scope="col">Produto</th>
                <th scope="col">Cliente</th>
                <th scope="col">Envio</th>
                <th scope="col">Status pagamento</th>
                <th scope="col">Entrega</th>
                <th scope="col">Vlr. Liquido</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $valor_total = 0;
            $qtd_total = 0;
            while ($linha = mysqli_fetch_assoc($consultar_pre_venda)) {
                $carrinho = $linha['carrinho'];
                $codigo_nf = $linha['cl_codigo_nf'];
                $nome = utf8_encode($linha['cl_nome_completo']);
                $data_pedido = formatarTimeStamp($linha['cl_data']);
                $pedido = $linha['cl_pagamento_id'];
                $email = $linha['cl_email'];
                $cpfcnpj = $linha['cl_cpf'];
                $telefone = $linha['cl_telefone'];
                $endereco = utf8_encode($linha['cl_endereco']);
                $bairro = utf8_encode($linha['cl_bairro']);
                $numero = utf8_encode($linha['cl_numero']);
                $complemento = utf8_encode($linha['cl_complemento']);
                $cep = utf8_encode($linha['cl_cep']);
                $cidade = utf8_encode($linha['cl_cidade']);
                $estado = utf8_encode($linha['cl_estado']);
                $transportadora = utf8_encode($linha['cl_transportadora']);
                $formapagamento = utf8_encode($linha['formapagamento']);
                $status_pagamento = utf8_encode($linha['cl_status_pagamento']);
                $status_compra = utf8_encode($linha['cl_status_compra']);
                $codigo_rastreio = $linha['cl_codigo_rastreio'];
                $descricao_produto = utf8_encode($linha['cl_descricao_produto']);

                // Cálculos e formatações
                $valor_frete = $linha['cl_valor_frete'];
                $valor_produto = $linha['cl_valor_produto'];
                $valor_desconto = $linha['cl_desconto'];
                $valor_liquido = $linha['cl_valor_liquido'];
                $valor_total += $valor_liquido;
                $check_email = $linha['cl_email_verificado'];

                $fbp = $linha['cl_fbp_pixel'];
                $fbc = $linha['cl_fbc_pixel'];
                $span_anuncio = !empty($fbc) ? "<i class='bi bi-megaphone' title='Cliente atráves de anúncio'></i>" : ''; //anuncio

                $numero_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_numero_nf");
                $pdf_nf = null;

                $data_entrega = ($linha['cl_data_entrega']);
                $status_entrega = '';
                if ($status_pagamento == "approved" and formatDateB($data_entrega) != "") {
                    $status_entrega = "<span class='badge text-bg-success'>Realizada</span>";
                } elseif ($status_pagamento == "approved" and formatDateB($data_entrega) == "") {
                    $status_entrega = "<span class='badge text-bg-secondary'>Pendente</span>";
                }


                if ($numero_nf != "") {
                    $serie_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_serie_nf");
                    $pdf_nf = consulta_tabela($conecta, "tb_nf_saida", "cl_codigo_nf", $codigo_nf, "cl_pdf_nf");
                }
                $numero_nf = $numero_nf != "" ? $serie_nf . $numero_nf : '';

                if ($check_email == 1) {
                    $check_email = "<i title='E-mail enviado com sucesso' style='color: green;font-weight: 600;font-size:1.5em' class='bi bi-check2-all'></i>'";
                } elseif ($check_email == 0 and $status_pagamento == "approved") {
                    $check_email = "<i title='Pendente para o envio, E-mail de compra' style='color:#8a8a8a;font-weight: 600;font-size:1.7em' class='bi bi-check'></i>'";
                } else {
                    $check_email = "";
                }
                $transportadora = "<span class='badge text-bg-primary'>$transportadora</span>";
                if ($status_pagamento == "approved") {
                    $status_pagamento = "<span class='badge text-bg-success'>$status_compra - Aprovado</span>";
                } elseif ($status_pagamento == "pending") {
                    $status_pagamento = "<span class='badge text-bg-primary'>$status_compra - Pagamento Pendente</span>";
                } elseif ($status_pagamento == "in_process") {
                    $status_pagamento = "<span class='badge text-bg-primary'>$status_compra - Pagamento em processamento</span>";
                } elseif ($status_pagamento == "rejected") {
                    $status_pagamento = "<span class='badge text-bg-warning'>$status_compra - Pagamento rejeitado</span>";
                } elseif ($status_pagamento == "cancelled") {
                    $status_pagamento = "<span class='badge text-bg-danger'>$status_compra - Pagamento não concluido</span>";
                } else {
                    $status_pagamento = "<span class='badge text-bg-secondary'>$status_compra - Não concluido</span>";
                }
            ?>
                <tr>
                    <th><?= ($carrinho) . "<br>" . $span_anuncio; ?></th>
                    <th><?= "Nº: ". $pedido . "<hr class='mb-1'>Data: " . $data_pedido; ?></th>
                    <td title='Número do documento fiscal (venda ou Nf-e)'><?php
                                                                            if ($pdf_nf != "") {
                                                                                echo $numero_nf .
                                                                                    " <a  href='$server$pdf_nf' target='_blank'><i class='bi bi-stickies'></i></a>";
                                                                            } else {
                                                                                echo $numero_nf;
                                                                            } ?> </td>
                    <td style="max-width: 210px;"><?= $descricao_produto; ?></td>

                    <td style="max-width: 250px;"><?php echo $nome . " <hr class='mb-1'> Uf - " . $estado . " - " . $cidade . "<br>" . $email . " " . $check_email; ?></td>
                    <td><?php echo $transportadora; ?></td>
                    <td><?php echo $status_pagamento . "<hr class='mb-1'> Pagamento: " . $formapagamento; ?></td>
                    <td><?php echo ($status_entrega . "<br> " . formatDateB($data_entrega)); ?></td>

                    <td><?php echo real_format($valor_liquido); ?></td>
                    <?php if ($status_compra == "CONCLUIDO" or $status_compra == "CANCELADO") {
                    ?>
                        <td><button type="button" id="<?= $carrinho; ?>" class="btn btn-sm btn-info editar">Detalhe</button></td>
                    <?php } else {
                        echo "<td></td>";
                    } ?>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <th colspan="8">Total</th>
            <th><?= real_format($valor_total); ?></th>
        </tfoot>
    </table>
    <label>
        Registros <?php echo $qtd; ?>
    </label>
<?php
} else {
    include "../../../../view/alerta/alerta_pesquisa.php"; // mesnsagem para usuario pesquisar
}
?>
<script src="js/ecommerce/pre_venda/table/editar_pre_venda.js">