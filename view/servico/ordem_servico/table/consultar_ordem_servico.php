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
                <th scope="col">Doc</th>
                <th scope="col">Tipo</th>
                <th scope="col">Cliente</th>
                <th scope="col">Equipamento</th>
                <th scope="col">Nº Série</th>
                <th scope="col">Vlr. Liquido</th>
                <?php
                echo $tipo_ordem == 1 ? '<th scope="col">Vlr. pendente</th>' : '<th scope="col">Vlr. fechado</th>';
                ?>
                <th></th>

            </tr>
        </thead>
        <tbody>
            <?php
            $valor_total = 0;

            while ($linha = mysqli_fetch_assoc($consultar_ordem_servico)) {
                $situacao_span = "";
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
                $valor_despesa = $linha['cl_valor_despesa'];
                $valor_desconto = $linha['cl_desconto'];
                $os_fechada = $linha['cl_ordem_fechada'];
                $statusos = utf8_encode($linha['statusos']);
                $codigo_nf = utf8_encode($linha['cl_codigo_nf']);
                $valor_fechado = ($linha['cl_valor_fechado']);
                $taxa_adiantamento = ($linha['cl_taxa_adiantamento']);
                $situacao = ($linha['cl_situacao']);

                $codigo_nf_material = ($linha['cl_codigo_nf_material']);
                $codigo_nf_servico = ($linha['cl_codigo_nf_servico']);
                $codigo_nf_recibo = ($linha['cl_codigo_nf_recibo']);

                $valor_a_receber = $valor_pecas + $valor_servico + $valor_despesa - $valor_desconto - $taxa_adiantamento;
                if ($situacao == 0) {
                    $situacao_span = "<span class='badge rounded-pill text-bg-primary'>Em andamento</span></td>";
                } elseif ($situacao == 1) {
                    $situacao_span = "<span class='badge rounded-pill text-bg-success'>Faturada</span></td>";
                } elseif ($situacao == 2) {
                    $situacao_span = "<span class='badge rounded-pill text-bg-danger'>Cancelada</span></td>";
                }

                $nf_doc = '';
                $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_nf_saida WHERE cl_codigo_nf ='$codigo_nf_material' OR cl_codigo_nf ='$codigo_nf_servico'  OR cl_codigo_nf ='$codigo_nf_recibo' ");
                if ($resultados) {
                    $documentos_nf = '';
                    foreach ($resultados as $linha) {
                        $numero_nf_gerada = ($linha['cl_numero_nf']);
                        $serie_nf_gerada = utf8_encode($linha['cl_serie_nf']);
                        $documentos_nf .= $serie_nf_gerada . $numero_nf_gerada . " - ";
                    }
                    $nf_doc = "<span title='Documento referente as $serie_nf_gerada' class='badge rounded-pill text-bg-primary'>$documentos_nf</span>";
                }

                $valor_total += $valor_liquido;
            ?>
                <tr>
                    <th><?php echo formatDateB($data_abertura); ?></th>
                    <td><?php echo $serie_nf . $numero_ordem; ?><hr>
                    <?= $nf_doc; ?>
                </td>
                    <td><?php echo ($tipo_servico); ?><br>
                        <hr class="mb-0"><?php echo $situacao_span; ?>
                    </td>
                    <td class="max_width_descricao"><?php echo $razao_social;  ?><br>
                        <hr class="mb-0"><?php echo $nome_fantasia; ?>
                    </td>
                    <td><?php echo ($equipamento); ?></td>
                    <td><?php echo ($numero_serie); ?></td>
                    <td><?php echo real_format($valor_liquido); ?></td>

                    <?php
                    echo $tipo_ordem == 1 ? "<td>" . real_format($valor_a_receber) . "</td>" : "<td>" . real_format($valor_fechado) . "</td>";
                    ?>

                    <td class="td-btn">
                        <div class="btn-group">
                            <button type="buttom" id='<?php echo $id; ?>' class="btn btn-sm btn-info editar">Editar</button>
                            <button type="buttom" id='<?php echo $id; ?>' parceiro_id='<?php echo $parceiro_id; ?>' codigo_nf='<?php echo $codigo_nf; ?>' class="btn btn-sm btn-warning contato">Contato</button>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <th scope="col" colspan="6">Total</th>
            <th scope="col"><?php echo real_format($valor_total); ?></th>
            <th></th>

        </tfoot>
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