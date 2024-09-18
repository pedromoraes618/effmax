<?php
include "../../../../modal/servico/gestao_servico/gerenciar_gestao_servico.php";
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
                <th scope="col">Responsável</th>

                <th scope="col">Local</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $valor_total = 0;

            while ($linha = mysqli_fetch_assoc($consultar_gestao_servico)) {
                $situacao_span = "";
                $id = $linha['cl_id'];
                $data_abertura = $linha['cl_data_abertura'];
                $numero_ordem = $linha['cl_numero_nf'];
                $serie_nf = $linha['cl_serie_nf'];
                $parceiro_id = utf8_encode($linha['cl_parceiro_id']);
                $razao_social = utf8_encode($linha['cl_razao_social']);
                $nome_fantasia = utf8_encode($linha['cl_nome_fantasia']);
                $tipo_servico = utf8_encode($linha['tiposervico']);
                $descricao_obra = utf8_encode($linha['cl_descricao_obra']);
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
                $responsavel = ($linha['responsavel']);

            ?>
                <tr>
                    <th><?php echo formatDateB($data_abertura); ?></th>
                    <td><?php echo $serie_nf . $numero_ordem; ?></td>
                    <td><?php echo ($tipo_servico); ?></td>
                    <td class="max_width_descricao"><?php echo $razao_social;  ?><br>
                        <hr class="mb-0"><?php echo $nome_fantasia; ?>
                    </td>
                    <td><?php echo ($responsavel); ?></td>

                    <td class="td-btn">
                        <div class="btn-group">
                            <button type="buttom" id='<?php echo $id; ?>' class="btn btn-sm btn-info editar">Editar</button>
                        </div>
                    </td>
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
<script src="js/servico/gestao_servico/table/editar_gestao_servico.js">