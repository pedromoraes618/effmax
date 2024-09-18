<?php
include "../../../../modal/servico/ordem_servico/gerenciar_ordem_servico.php";
?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>

                <th scope="col">Dt. Abertura</th>
                <th scope="col">Ordem</th>
                <th scope="col">Tipo</th>
                <th scope="col">Cliente</th>
                <th scope="col">Equipamento</th>
                <th scope="col">Nº Série</th>
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
                $tecnico = utf8_encode($linha['tecnico']);
                $situacao = ($linha['cl_situacao']);
                $situacao_span = "";
                if ($situacao == 0) {
                    $situacao_span = "<span class='badge rounded-pill text-bg-primary'>Em andamento</span></td>";
                } elseif ($situacao == 1) {
                    $situacao_span = "<span class='badge rounded-pill text-bg-success'>Faturada</span></td>";
                } elseif ($situacao == 2) {
                    $situacao_span = "<span class='badge rounded-pill text-bg-danger'>Cancelada</span></td>";
                }
            ?>
                <tr>
                    <th><?php echo formatDateB($data_abertura); ?></th>
                    <td><?php echo $serie_nf . $numero_ordem; ?></td>
                    <td><?php echo ($tipo_servico); ?><br>
                        <hr class="mb-0"><?php echo $statusos; ?>
                    </td>

                    <td class="max_width_descricao"><?php echo $razao_social;  ?><br>
                        <hr class="mb-0"><?php echo $nome_fantasia; ?>
                    </td>

                    <td><?php echo ($equipamento); ?></td>
                    <td><?php echo ($numero_serie); ?></td>

                    <td><?php echo $situacao_span; ?></td>
                    <td class="td-btn">
                        <div class="btn-group">
                            <button type="buttom" id='<?php echo $id; ?>' class="btn btn-sm btn-info diagnostico">Diagnóstico</button>
                        </div>
                    </td>
                    <td></td>
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
<script src="js/servico/diagnostico_tecnico/table/editar_diagnostico_tecnico.js">