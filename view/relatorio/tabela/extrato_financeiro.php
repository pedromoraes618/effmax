<div class="info">
    <h2 class=""><?php echo $titulo_relatorio; ?></h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th scope="col">Data Pagamento </th>
                <th scope="col">Doc</th>
                <th scope="col">Cliente</th>
                <th scope="col">Forma Pgto</th>
                <th scope="col">Classificação</th>
                <th scope="col">Tipo</th>
                <th scope="col">Valor</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>Saldo Inicial</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>

                <td><?php echo  real_format($saldo_inical); ?></td>
            </tr>
            <?php
            $valor_receita = 0;
            while ($linha = mysqli_fetch_assoc($consulta_extrato_financeiro)) {
                $data_recebimento = $linha['cl_data_pagamento'];
                $documento = utf8_encode($linha['cl_documento']);
                $cliente = utf8_encode($linha['cl_razao_social']);
                $forma_pagamento = utf8_encode($linha['formapg']);
                $classificao_financeira = utf8_encode($linha['classificacaofin']);
                $tipo_lancamento = ($linha['cl_tipo_lancamento']);
                $valor_liquido = ($linha['cl_valor_liquido']);

                if ($tipo_lancamento == "DESPESA") {
                    $tipo_lancamento = "<p style='color:red;margin:0px'>Despesa</p>";
                    $valor_receita = $valor_receita - $valor_liquido;
                } else {
                    $tipo_lancamento =  "<p style='color:green;margin:0px'>Receita</p>";
                    $valor_receita = $valor_receita + $valor_liquido;
                }
            ?>
                <tr>
                    <td><?php echo formatDateB($data_recebimento); ?></td>
                    <td><?php echo $documento;  ?></td>
                    <td><?php echo $cliente; ?></td>
                    <td><?php echo $forma_pagamento; ?></td>
                    <td><?php echo $classificao_financeira; ?></td>
                    <td><?php echo $tipo_lancamento; ?></td>
                    <td><?php echo real_format($valor_liquido); ?></td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <th><?php echo real_format($valor_receita + $saldo_inical); ?></td>

            </tr>
        </tfoot>
    </table>
</div>