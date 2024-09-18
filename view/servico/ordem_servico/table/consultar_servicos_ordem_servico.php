<?php
include "../../../../modal/servico/ordem_servico/gerenciar_ordem_servico.php";
?>
<div class="title mb-2">
    <label class="form-label sub-title">Registros de Serviços</label>
</div>
<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">Código</th>
            <th scope="col">Descrição</th>
            <th scope="col">Vlr unit</th>
            <th scope="col">Qtd</th>
            <th scope="col">Vlr Total</th>
            <th scope="col">Responsável</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        $total = 0;
        while ($linha = mysqli_fetch_assoc($consulta_servicos)) {
            $id = $linha['cl_id'];
            $servico_id = $linha['cl_produto_id'];
            $descricao = utf8_encode($linha['cl_item_descricao']);
            $quantidade = $linha['cl_quantidade_orcada'];
            $valor_unitario = $linha['cl_valor_unitario'];
            $valor_total = $linha['cl_valor_total'];
            $responsavel = utf8_encode($linha['cl_usuario']);
            $razao_social_terceirizado = utf8_encode($linha['cl_razao_social']);
            $data_inicio_terceirizado = formatDateB($linha['cl_data_inicio']);
            $data_fim_terceirizado = formatDateB($linha['cl_data_fim']);
            $valor_fechado_terceirizado = real_format($linha['cl_valor_fechado']);
            $descricao_servico_terceirizado = utf8_encode($linha['cl_descricao_servico']);

            if (!empty($razao_social_terceirizado) or !empty($data_inicio_terceirizado) or !empty($data_fim_terceirizado) or ($valor_fechado_terceirizado > 0)) {
                $razao_social_terceirizado = "<span>Empresa terceirizada: $razao_social_terceirizado <br> Inicio: $data_inicio_terceirizado, Fim: $data_fim_terceirizado, Valor: $valor_fechado_terceirizado</span>";
            }
            $total += $valor_total;
        ?>
            <tr>
                <th scope="row"><?= $id ?></th>
                <td><?php echo $descricao; ?>
                    <hr class="mb-1"><?= $razao_social_terceirizado; ?>
                </td>
                <td><?= real_format($valor_unitario); ?></td>
                <td><?= $quantidade ?></td>
                <td><?= real_format($valor_total); ?></td>
                <td><?= $responsavel; ?></td>
                <td class="td-btn">
                    <div class="btn-group">
                        <button type="button" id="<?php echo $id; ?>" class="btn btn-sm btn-info editar_servico">Editar</button>
                        <button type="button" id='<?php echo $id; ?>' title='Remover servico' class="btn btn-sm btn-danger remover_servico"><i style="font-size: 1.4em;" class="bi bi-trash"></i></button>
                    </div>
                </td>
            </tr>
        <?php } ?>

    </tbody>
    <tfoot>
        <th scope="col" colspan="4">Total <?= $qtd; ?> </th>
        <th scope="col"><?php echo real_format($total); ?></th>
        <th></th>
    </tfoot>
</table>

<script src="js/servico/ordem_servico/table/edita_servicos_ordem_servico.js">