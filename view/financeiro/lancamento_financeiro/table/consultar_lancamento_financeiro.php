<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/financeiro/lancamento_financeiro/gerenciar_lancamento_financeiro.php";

?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Dt. Vencimento</th>
                <th scope="col">Dt. Pagamento</th>
                <th scope="col">Cliente / Fornecedor</th>
                <th scope="col">Descrição</th>
                <th scope="col">Status</th>
                <th scope="col">Tipo</th>
                <!-- <th scope="col">Vlr Bruto</th> -->
                <th scope="col">Vlr Liquido </th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php
            $valor_total_receita = 0;
            $valor_total_despesa = 0;
            while ($linha = mysqli_fetch_assoc($consultar_lancamento_financeiro)) {
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
                    <th><?php echo formatDateB($data_pagamento) ?></th>
                    <td class="max_width_descricao"><?php echo $razao_social_b;  ?><br>
                        <hr class="mb-0"><?php echo $nome_fantasia_b; ?>
                    </td>
                    <td>
                        <?php if ($descricao_b != '') { ?>
                            <span class="texto-reduzido"><?php echo reduzir_texto($descricao_b) ?>
                                <span class="texto-caixa"><?php echo $descricao_b; ?></span>
                            </span>
                            <br>
                        <?php } ?>
                        <span><?php echo $doc_b != '' ? 'Doc ' . $doc_b .', ': ''; ?> <?php echo ($forma_pagamento_b) ?></span>


                    </td>


                    <td><?php echo ($status_b) ?></td>

                    <td><span class="badge text-bg-<?php if ($tipo_b == "RECEITA") {
                                                        echo 'success';
                                                    } elseif ($tipo_b == "DESPESA") {
                                                        echo 'danger';
                                                    } else {
                                                        echo 'default';
                                                    } ?>"><?php echo $tipo_b; ?></span>
                    </td>
                    <!-- <td><?php echo real_format($valor_bruto_b) ?></td> -->
                    <td><?php echo real_format($valor_liquido_b) ?></td>

                    <td class="td-btn">
                        <div class="btn-group">
                            <div>
                                <button type="button" lancamento_financeiro_id="<?php echo $id_b; ?>" tipo="<?php echo $tipo_b; ?>" class="btn btn-info   btn-sm editar_lancamento_financeiro ">Editar</button>
                            </div>
                            <div class="input-group mb-3">
                                <button class="btn btn-sm btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Ações</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item clonar" style="cursor: pointer;" lancamento_financeiro_id="<?php echo $id_b; ?>">Clonar</a></li>
                                    <li><a class="dropdown-item remover" style="cursor: pointer;" lancamento_financeiro_id="<?php echo $id_b; ?>">Remover</a></li>
                                </ul>
                            </div>
                        </div>
                    </td>
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