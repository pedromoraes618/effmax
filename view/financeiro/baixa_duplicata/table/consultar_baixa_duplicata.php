<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/financeiro/baixa_duplicata/gerenciar_baixa_duplicata.php";

?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Dt. Vencimento</th>
                <th scope="col">Cliente / Fornecedor</th>
                <th scope="col">Descrição</th>
                <th scope="col">Classificação</th>
                <th scope="col">Atraso</th>
                <th scope="col">Status</th>
                <th scope="col">Vlr Aberto </th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>

            <?php
            $valor_total_receita = 0;
            $valor_total_despesa = 0;
            while ($linha = mysqli_fetch_assoc($consultar_baixa_duplicata)) {
                $id_b = $linha['cl_id'];
                $data_vencimento = ($linha['cl_data_vencimento']);
                $data_pagamento = ($linha['cl_data_pagamento']);
                $nome_fantasia_b = utf8_encode($linha['cl_nome_fantasia']);
                $razao_social_b = utf8_encode($linha['cl_razao_social']);
                $descricao_b = utf8_encode($linha['descricao']);
                $doc_b = utf8_encode($linha['cl_documento']);
                $forma_pagamento_b = utf8_encode($linha['formapgt']);
                $status_b = utf8_encode($linha['status']);
                $tipo_b = utf8_encode($linha['cl_tipo_lancamento']);
                $valor_liquido_b = ($linha['cl_valor_liquido']);
                $valor_bruto_b = ($linha['cl_valor_bruto']);
                $classificacao = utf8_encode($linha['classificacao']);
                $pagamento = utf8_encode($linha['pagamento']);
                $atraso = ($linha['atraso']);
                if ($atraso > 0) {
                    $atraso = $atraso . " Dia(s)";
                } else {
                    $atraso = null;
                }

                if ($status_b != "Cancelado") {
                    if ($tipo_b == "RECEITA") {
                        $valor_total_receita = $valor_liquido_b + $valor_total_receita;
                    } elseif ($tipo_b == "DESPESA") {
                        $valor_total_despesa = $valor_liquido_b + $valor_total_despesa;
                    }
                }

            ?>
                <tr>
                    <th scope="row"><?php echo formatDateB($data_vencimento) ?></th>
                    <td class="max_width_descricao"><?php echo $razao_social_b;  ?><br>
                        <hr class="mb-0"><?php echo $nome_fantasia_b; ?>
                    </td>
                    <td>
                        <span class="texto-reduzido"><?php echo reduzir_texto($descricao_b) ?>
                            <span class="texto-caixa"><?php echo $descricao_b; ?></span>
                        </span>
                        <br>
                        <span>Doc <?php echo ($doc_b) ?>, <?php echo ($forma_pagamento_b) ?></span>
                    </td>
                    <td><?php echo $classificacao; ?></td>
                    <td><?php echo $atraso; ?></td>
                    <td><?php echo ($status_b) ?></td>
                    <td><?php echo real_format($valor_liquido_b) ?></td>

                    <td class="td-btn"><button type="button" lancamento_financeiro_id="<?php echo $id_b; ?>" tipo="<?php echo $tipo_b; ?>" class="btn btn-info
                       btn-sm editar_lancamento_financeiro ">Baixar</button></td>
                    <td class="td-btn"><button type="button" lancamento_financeiro_id="<?php echo $id_b; ?>" tipo="<?php echo $tipo_b; ?>" class="btn btn-dark  btn-sm bx_parcial ">Bx Parcial</button></td>

                </tr>

            <?php } ?>

        </tbody>
        <tfoot>
            <th scope="col" colspan="6">Total</th>
            <th scope="col"><?php echo real_format($valor_total_receita - $valor_total_despesa); ?></th>
            <th scope="col"></th>
            <th scope="col"></th>
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
<script src="js/financeiro/lancamento_financeiro/table/editar_lancamento_financeiro.js">