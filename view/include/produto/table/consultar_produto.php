<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/estoque/produto/gerenciar_produto.php";

?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Código</th>
                <th scope="col">Descrição</th>
                <th scope="col">Und</th>
                <th scope="col">Fabricante</th>
                <th scope="col">Estoque</th>
                <th scope="col">Preço venda</th>
                <th scope="col">Status</th>
                <th scope="col"></th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php

            while ($linha = mysqli_fetch_assoc($consultar_produtos)) {
                $span_referencia = '';
                $produto_id = $linha['produtoid'];
                $codigo_produto = $linha['cl_codigo'];
                $descricao = utf8_encode($linha['descricao']);
                $referencia = utf8_encode($linha['cl_referencia']);
                $estoque_minimo = utf8_encode($linha['cl_estoque_minimo']);
                $estoque_maximo = utf8_encode($linha['cl_estoque_maximo']);
                $subgrupo = utf8_encode($linha['subgrupo']);
                $und = utf8_encode($linha['und']);
                $fabricante = utf8_encode($linha['cl_fabricante']);
                $estoque = $linha['cl_estoque'];
                $preco_venda = ($linha['cl_preco_venda']);
                $preco_promocao = ($linha['cl_preco_promocao']);
                $ativo = ($linha['ativo']);
                $data_validade_promocao = ($linha['cl_data_valida_promocao']);
                if (($data_validade_promocao >= $data_lancamento) and $preco_promocao > 0) { //promoção
                    $preco_venda_span = "<span class='text-decoration-line-through  text-muted '>" . real_format($preco_venda) .
                        "</span><br><span class='fw-semibold fs-6 '>" . real_format($preco_promocao) . "</span>";
                } else {
                    $preco_venda_span = real_format($preco_venda);
                }

                $span_referencia = !empty($referencia) ? "<br><span>Ref: $referencia</span>" : '';
            ?>
                <tr>
                    <th scope="row"><?= $produto_id; ?></th>
                    <td class="max_width_descricao"><?= $descricao . $span_referencia; ?></td>
                    <td><?= $und; ?></td>
                    <td><?= $fabricante; ?></td>
                    <td><?= $estoque; ?></td>
                    <td><?= $preco_venda_span; ?></td>
                    <td>
                        <span class='badge text-bg-<?php echo ($ativo == "SIM") ? 'success' : 'danger' ?>'>
                            <?php echo ($ativo == "SIM") ? 'Ativo' : 'Inativo'; ?>
                        </span>
                    </td>

                    <?php
                    if ($ativo == "SIM") {
                        if ($estoque > 0 and ($data_validade_promocao >= $data_lancamento) and $preco_promocao > 0 and ($operacao_produto == "venda" or $operacao_produto == "")) { //promoção 
                    ?>
                            <td class="td-btn">
                                <button type="button" promocao='sim' estoque="<?php echo $estoque; ?>" unidade="<?php echo $und ?>" preco_venda="<?php echo $preco_promocao; ?>" ativo="<?php echo $ativo; ?>" id_produto="<?php echo $produto_id; ?>" class="btn btn-dark btn-sm selecionar_produto">Promoção</button>
                            </td>
                        <?php
                        } else {
                        ?>
                            <td class="td-btn">
                                <button type="button" estoque="<?php echo $estoque; ?>" unidade="<?php echo $und ?>" preco_venda="<?php echo $preco_venda; ?>" ativo="<?php echo $ativo; ?>" id_produto="<?php echo $produto_id; ?>" class="btn btn-info btn-sm selecionar_produto">Selecionar</button>
                            </td>
                        <?php }
                    }


                    if (($operacao_produto == "venda" or $operacao_produto == "") and $ativo == "SIM" and $estoque > 0) {
                        $resultados = consulta_linhas_tb_filtro($conecta, 'tb_tabela_preco', "cl_produto_id", $produto_id);
                        if ($resultados) {
                        ?>
                            <td>
                                <div class="input-group mb-3">
                                    <button class="btn btn-sm btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Tabela Preço</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <?php
                                        foreach ($resultados as $linha) {
                                            $id = $linha['cl_id'];
                                            $forma_pagamento_id = $linha['cl_forma_pagamento_id'];
                                            $descricao_fpg = utf8_encode(consulta_tabela($conecta, "tb_forma_pagamento", "cl_id", $forma_pagamento_id, "cl_descricao"));
                                            $valor = $linha['cl_valor'];
                                        ?>
                                            <li>
                                                <a estoque="<?php echo $estoque; ?>" unidade="<?php echo $und ?>" preco_venda="<?php echo $valor; ?>" ativo="<?php echo $ativo; ?>" id_produto="<?php echo $produto_id; ?>" class="dropdown-item selecionar_produto" style="cursor: pointer;">
                                                    <?php echo $descricao_fpg . " " . real_format($valor); ?>
                                                </a>
                                            </li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </div>
                            </td>
                    <?php
                        }
                    } ?>
                    <td>
                        <input type="hidden" id="<?php echo $produto_id; ?>" value="<?php echo $descricao; ?>">
                        <input type="hidden" referencia_<?php echo $produto_id ?>="<?php echo $produto_id; ?>" value="<?php echo $referencia; ?>">
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
<script src="js/include/produto/pesquisa_produto.js">