<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/suporte/parametro/gerenciar_parametro.php";

?>

<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">Código</th>
            <th scope="col">Descrição</th>
            <th scope="col">Valor</th>
            <th scope="col">Configuação</th>
            <th scope="col"></th>

        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($consultar_parametros)) {
            $id_parametro_b = $row['cl_id'];
            $descricao_b = utf8_encode($row['cl_descricao']);
            $valor_b = utf8_encode($row['cl_valor']);
            $configuracao_b = $row['cl_configuracao'];
            // Limita o valor a 300 caracteres
            $valor_b = substr($valor_b, 0, 50);
        ?>
            <tr>
                <th style="width: 100px;" scope="row"><?php echo $id_parametro_b ?></th>
                <td style="width: 500px;"><?php echo $descricao_b; ?></td>
                <td style="max-width: 500px"><?php echo $valor_b; ?></td>
                <td><span class="badge text-bg-danger"><?php echo $configuracao_b; ?></span></td>

                <td class="td-btn"><button type="button" id_parametro=<?php echo $id_parametro_b; ?> class="btn btn-sm btn-info editar_parametro">Editar</button>
                </td>
            </tr>

        <?php } ?>
    </tbody>
</table>

<script src="js/suporte/parametro/table/editar_parametro.js">