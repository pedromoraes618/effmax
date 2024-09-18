<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/estoque/kardex/gerenciar_kardex.php";
?>
<div class="card m-1 card-print card-dashboard position-relative shadow border-0 mb-2 ">
    <div class="card-header header-card-dashboard ">
        <h6><i class="bi bi-exclamation-octagon"></i> Kardex - <?= $descricao_produto;?></h6>
    </div>
    <div class="card-body table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Data</th>
                    <th scope="col">Doc</th>
                    <th scope="col">Parceiro</th>
                    <th scope="col">Usuário</th>
                    <th scope="col">Tipo</th>
                    <th scope="col">Entrada</th>
                    <th scope="col">Saida</th>
                    <th scope="col">Saldo</th>
                    <th scope="col">Status</th>
                    <th scope="col" title="Valor venda">Vlr Vnd</th>
                    <th scope="col" title="Valor compra">Vlr Cmp</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Estoque inicial</td>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope=""><?php echo $estoque_inicial ?></th>
                    <th scope=""></th>
                    <th scope=""></th>
                    <th scope=""></th>
                </tr>
                <?php
                $saldo = 0 + $estoque_inicial;
                $operador = "";

                $total_saida = 0;
                $total_entrada = 0;
                while ($linha = mysqli_fetch_assoc($consultar_historico_produto)) {
                    $true_ajuste_inicial = $linha['cl_ajuste_inicial'];
                    $quantidade = $linha['cl_quantidade'];
                    $tipo = $linha['tipo'];
                    $documento = $linha['cl_documento'];
                    $status = $linha['cl_status'];
                    $data_lancamento = $linha['cl_data_lancamento'];
                    $usuario = utf8_encode($linha['cl_usuario']);
                    $empresa = utf8_encode($linha['empresa']);
                    $parceiro_id = $linha['cl_parceiro_id'];
                    $valor_venda = $linha['valorv'];
                    $valor_compra = $linha['valorc'];
                    $parceiro = utf8_encode($linha['cl_razao_social']);
                    $ajuste = utf8_encode($linha['cl_ajuste']);
                    $ajuste = $ajuste == 1 ? "Ajuste " : '';

                    if ($tipo == "ENTRADA") {
                        $quantidade_entrada = $quantidade;
                        $quantidade_saida = 0; // informar zero

                        if ($status == "cancelado") { //verificar se o ajuste foi cancelado
                            $saldo = 0 + $saldo;
                        } else {
                            $saldo = $quantidade + $saldo;
                            $total_entrada += $quantidade_entrada;
                        }

                        $cor = 'primary';
                        $titulo = "$ajuste Entrada";
                    }
                    if ($tipo == "SAIDA") {
                        $quantidade_saida = $quantidade;
                        $quantidade_entrada = 0; // informar zero

                        //foi um ajuste de saida
                        if ($status == "cancelado") { //verificar se o ajuste foi cancelado
                            $saldo = 0 + $saldo;
                        } else {
                            $saldo = $saldo - $quantidade;
                            $total_saida += $quantidade_saida;
                        }

                        $cor = 'success';
                        $titulo = "$ajuste Saida";
                    }

                    if ($parceiro_id == "") {
                        $empresa =  $empresa;
                    } else {
                        $empresa = $parceiro;
                    }

                ?>

                    <tr>
                        <td><?php echo formatDateB($data_lancamento); ?></td>
                        <td><?php echo $documento; ?></td>
                        <td><?php echo ($empresa) ?></td>
                        <td><?php echo $usuario; ?></td>
                        <td><span class="badge text-bg-<?php echo $cor; ?>"><?php echo $titulo; ?></span>
                        </td>

                        <td><?php echo $quantidade_entrada ?></td>
                        <td><?php echo $quantidade_saida ?></td>
                        <td><?php echo $saldo; ?></td>
                        <td style="width: 20px;">
                            <?php if ($status == "cancelado") {
                                echo "<i title='Cancelado' class='bi bi-x text-danger fs-4'></i>";
                            } ?>
                        </td>
                        <td><?php echo real_format($valor_venda); ?></td>
                        <td><?php echo real_format($valor_compra); ?></td>

                    </tr>

                <?php

                } ?>
                <tr>
                    <td>Sub total</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?= $total_entrada; ?></td>
                    <td><?= $total_saida; ?></td>
                    <td><?php
                        $sub_total = $estoque - $saldo;
                        if ($sub_total == "0") {
                            echo $estoque; //quando for zerado, será informado o valor do estoque
                        } else {
                            echo $sub_total;
                        }

                        ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

                <tr>
                    <td>Estoque</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td><?php echo $estoque; ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>

            </tbody>
        </table>
    </div>
</div>