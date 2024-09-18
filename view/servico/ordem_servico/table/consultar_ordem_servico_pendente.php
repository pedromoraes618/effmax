<?php
include "../../../../modal/servico/ordem_servico/gerenciar_ordem_servico.php";
?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th></th>
                <th scope="col">Dt. Abertura</th>
                <th scope="col">Nº</th>
                <th scope="col">Tipo</th>
                <th scope="col">Equipamento</th>
                <th scope="col">Vlr. Liquido</th>
                <th scope="col">Vlr. Pendente</th>
                <th></th>

            </tr>
        </thead>
        <tbody>
            <?php
            $valor_total = 0;
            while ($linha = mysqli_fetch_assoc($consultar_ordem_servico)) {
                $id = $linha['cl_id'];
                $data_abertura = $linha['cl_data_abertura'];
                $numero_ordem = $linha['cl_numero_nf'];
                $serie_nf = $linha['cl_serie_nf'];
                $parceiro_id = utf8_encode($linha['cl_parceiro_id']);
                $razao_social = utf8_encode($linha['cl_razao_social']);
                $nome_fantasia = utf8_encode($linha['cl_nome_fantasia']);
                $tipo_servico = utf8_encode($linha['tiposervico']);
                $equipamento = utf8_encode($linha['cl_equipamento']);
                $numero_serie = $linha['cl_numero_serie'];
                $valor_liquido = $linha['cl_valor_liquido'];
                $valor_pecas = $linha['cl_valor_pecas'];
                $valor_servico = $linha['cl_valor_servico'];
                $valor_desconto = $linha['cl_desconto'];
                $os_fechada = $linha['cl_ordem_fechada'];
                $statusos = utf8_encode($linha['statusos']);
                $codigo_nf = utf8_encode($linha['cl_codigo_nf']);
                $valor_fechado = ($linha['cl_valor_fechado']);
                $valor_ordem = $valor_pecas + $valor_servico - $valor_desconto;

                if ($os_fechada == 1) { //os fechada
                    $os_fechada = "<span class='badge rounded-pill text-bg-success'>Fechada</span></td>";
                } else {
                    $os_fechada = "<span class='badge rounded-pill text-bg-info'>Em andamento</span></td>";
                }
                $valor_total += $valor_ordem;
            ?>
                <tr>
                    <th><input style="width: 20px; height: 20px;" class="form-check-input" value="<?= $id; ?>" type="checkbox" id="os_pendente"></th>
                    <th><?php echo formatarTimeStamp($data_abertura); ?></th>
                    <td><?php echo $serie_nf . $numero_ordem; ?></td>
                    <td><?php echo ($tipo_servico); ?><br>
                        <hr class="mb-0"><?php echo $statusos; ?>
                    </td>
                    <td><?php echo ($equipamento) . "<br>" . "Serie:" . $numero_serie; ?></td>

                    <td><?php echo real_format($valor_ordem); ?></td>
                    <td><?php echo real_format($valor_liquido); ?></td>
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
<script src="js/servico/ordem_servico/table/editar_ordem_servico.js">