<?php
include "../../../../modal/ecommerce/configuracao/gerenciar_configuracao.php";
?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Criação</th>
                <th scope="col">Cupom</th>
                <th scope="col">Descrição</th>
                <th scope="col">Desconto</th>
                <th scope="col">válidade</th>
                <th scope="col">Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $valor_total = 0;
            while ($linha = mysqli_fetch_assoc($consultar_cupom)) {
                $id = $linha['cl_id'];
                $data = utf8_encode($linha['cl_data']);
                $descricao = utf8_encode($linha['cl_descricao']);
                $cupom = utf8_encode($linha['cl_codigo']);
                $valor = ($linha['cl_valor']);
                $data_validade_final = ($linha['cl_data_validade_final']);

                $status = ($linha['cl_status']);
                $operador = ($linha['cl_operador']);
                $valor_view = $operador == "moeda" ? real_format($valor) : formatarPorcentagem($valor);


            ?>
                <tr>
                    <th><?= formatDateB($data); ?></th>
                    <th><?= ($cupom); ?></th>
                    <td><?= $descricao; ?></td>
                    <td><?= ($valor_view); ?></td>
                    <td <?= ($data_validade_final < $data_lancamento) ? 'style="color: red;"' : ''; ?>><?= formatDateB($data_validade_final); ?></td>
                    <td><span class='badge text-bg-<?= ($status == "1") ? 'success' : 'danger' ?>'>
                            <?= ($status == "1") ? 'Ativo' : 'Inativo' ?></span>
                    </td>
                    <td><button type="buttom" id='<?= $id; ?>' class="btn btn-sm btn-info editar">Editar</button></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <label>
        Registros <?php echo $qtd; ?>
    </label>
<?php
} else {
    include "../../../../view/alerta/alerta_pesquisa.php"; // mesnsagem para usuario pesquisar
}
?>
<script src="js/ecommerce/configuracao/table/consultar_cupom.js"></script>