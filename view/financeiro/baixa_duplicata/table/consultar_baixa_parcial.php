<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/financeiro/baixa_duplicata/gerenciar_baixa_duplicata.php";
?>
<div class="title mb-2">
    <label class="form-label sub-title">Registros de Baixa Parcial</label>
</div>
<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">Dt Pagamento</th>
            <th scope="col">Doc</th>
            <th scope="col">Pagamento</th>
            <th scope="col">Status</th>
            <th scope="col">Vlr Baixado </th>
   
        </tr>
    </thead>
    <tbody>
        <?php

        $valor_total = 0;
        while ($linha = mysqli_fetch_assoc($consultar_baixa_parcial)) {
            $id_b = $linha['cl_id'];
            $data_vencimento = ($linha['cl_data_vencimento']);
            $data_pagamento = ($linha['cl_data_pagamento']);
            $nome_fantasia_b = utf8_encode($linha['cl_nome_fantasia']);
            $razao_social_b = utf8_encode($linha['cl_razao_social']);
            $descricao_b = utf8_encode($linha['descricao']);
            $doc_b = utf8_encode($linha['cl_documento']);
            $forma_pagamento_b = utf8_encode($linha['formapgt']);
            $status_b = utf8_encode($linha['status']);
            $status = utf8_encode($linha['cl_status_id']);
            $tipo_b = utf8_encode($linha['cl_tipo_lancamento']);
            $valor_liquido_b = ($linha['cl_valor_liquido']);
            $classificacao = utf8_encode($linha['classificacao']);
            $pagamento = utf8_encode($linha['pagamento']);

            if ($status == "2" or $status == "4") {
                $valor_total += $valor_liquido_b;
            }

        ?>
            <tr>
                <th scope="row"><?php echo formatDateB($data_pagamento) ?></th>
                <td><?php echo ($doc_b) ?></td>
                <td><?php echo ($pagamento) ?></td>
                <td><?php echo ($status_b) ?></td>
                <td><?php echo real_format($valor_liquido_b) ?></td>
            </tr>

        <?php } ?>

    </tbody>
    <tfoot>
        <th scope="col" colspan="4">Total</th>
        <th scope="col"><?php echo real_format($valor_total); ?></th>

    </tfoot>
</table>
<label>
    Registros <?php echo $qtd; ?>
</label>