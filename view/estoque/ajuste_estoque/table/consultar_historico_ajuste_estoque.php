<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/estoque/ajuste_estoque/gerenciar_ajuste_estoque.php";
include "../../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Historico de ajuste</label>
    <div class="msg_title">
        <p>Consulte todos os ajuste de estoque desse produto </p>
    </div>
</div>
<hr>

<table class="table">
    <thead>
        <tr>
            <th scope="col">Código</th>
            <th scope="col">Data ajuste</th>
            <th scope="col">Doc</th>
            <th scope="col">Usúario</th>
            <th scope="col">Tipo</th>
            <th scope="col">Quantidade</th>
            <th scope="col"></th>
            <!-- <th scope="col"></th> -->


        </tr>
    </thead>
    <tbody>
        <?php
        $qtd_registro = 0;
        while ($linha = mysqli_fetch_assoc($consultar_historico_produto)) {
            $qtd_registro += $qtd_registro;
            $quantidade_b = $linha['cl_quantidade'];
            $id_b = $linha['cl_id'];
            $data_lancamento_b = $linha['cl_data_lancamento'];
            $tipo_b = $linha['cl_tipo'];
            $doc_b = $linha['cl_documento'];
            $status_b = $linha['cl_status'];
            $usuario_b = $linha['usuario'];

            $tipo_b = strtolower($tipo_b);
        ?>
            <tr>
                <th scope="row"><?php echo $id_b; ?></th>
                <td><?php echo formatDateB($data_lancamento_b); ?></td>
                <td><?php echo $doc_b; ?></td>
                <td><?php echo $usuario_b; ?></td>
                <td><span class="badge text-bg-<?php if ($tipo_b == "saida") {
                                                    echo 'success';
                                                } else {
                                                    echo 'primary';
                                                } ?>"><?php echo $tipo_b; ?></span>
                </td>
                <td><?php echo $quantidade_b; ?></td>
                <td>
                    <?php if ($status_b == "cancelado") {
                        echo  "<span
                    class='badge text-bg-danger'> $status_b </span>";
                    } ?></td>


                <!-- <td class="td-btn"><button type="button" class="btn btn-info realizar_ajuste ">Ajuste</button>
            </td> -->

            </tr>

        <?php } ?>
    </tbody>
</table>
<label>
    Registros <?= $qtd_registro; ?>
</label>


<!-- <script src="js/estoque/ajuste_estoque/cadastro_ajuste_estoque.js"> -->