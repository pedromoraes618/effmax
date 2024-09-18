<?php
include "../../../../modal/empresa/credito/gerenciar_credito.php";
?>

<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">Data</th>
            <th scope="col">Justificativa</th>
            <th scope="col">Valor</th>
            <th scope="col">Tipo</th>
            <th scope="col">Status</th>
        </tr>

    </thead>
    <tbody>
        <?php
        $total = 0;
        while ($linha = mysqli_fetch_assoc($consultar_historico)) {
            $data = $linha['cl_data'];
            $jutificativa = utf8_encode($linha['cl_justificativa']);
            $valor = $linha['cl_valor'];
            $status = $linha['cl_status'];
            $tipo = $linha['cl_tipo'];
        

            if($tipo=="ENTRADA"){
                $total += $valor;
            }else{
                $total -= $valor;
            }

        ?>
            <tr>
                <th scope="row"><?= formatDateB($data) ?></th>
                <td><?= $jutificativa ?></td>
                <td><?= real_format($valor) ?></td>
                <td><?php echo $tipo; ?></td>
                <td><?php echo $status; ?></td>
            </tr>

        <?php } ?>
        <tr>

            <td colspan="2">Total</td>
            <td><?= real_format($total); ?></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
<label>
    Registros <?php echo $qtd; ?>
</label>

<script src="js/include/parceiro/pesquisa_parceiro.js">