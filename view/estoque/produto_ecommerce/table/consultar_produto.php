<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/estoque/produto/gerenciar_produto.php";

if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>

                <th scope="col">Código</th>
                <th scope='col'>Imagem</th>
                <th scope="col">Titulo</th>
                <th scope="col">Categoria</th>
                <th scope="col">Und</th>
                <th scope="col">Estoque</th>
                <th scope="col">Preço</th>
                <th scope="col">Publicação</th>
                <th scope="col">Status</th>
                <th scope="col"></th>

            </tr>
        </thead>
        <tbody>
            <?php

            while ($linha = mysqli_fetch_assoc($consultar_produtos)) {

                $produto_id = $linha['produtoid'];
                $codigo_nf = $linha['cl_codigo'];
                $descricao = utf8_encode($linha['descricao']);
                $referencia = utf8_encode($linha['cl_referencia']);
                $estoque_minimo = utf8_encode($linha['cl_estoque_minimo']);
                $estoque_maximo = utf8_encode($linha['cl_estoque_maximo']);
                $categoria = utf8_encode($linha['subgrupo']);
                $grupo = utf8_encode($linha['grupo']);
                $und = utf8_encode($linha['descunidade']);
                $fabricante = utf8_encode($linha['cl_fabricante']);
                $estoque = $linha['cl_estoque'];
                $preco_venda = ($linha['cl_preco_venda']);
                $ativo = ($linha['ativo']);
                $preco_promocao = ($linha['cl_preco_promocao']);
                $data_validade_promocao = ($linha['cl_data_valida_promocao']);
                $data_cadastro = ($linha['cl_data_cadastro']);

                $img_produto = consulta_tabela_query($conecta, "select * from tb_imagem_produto where cl_codigo_nf ='$codigo_nf' order by cl_ordem asc limit 1", 'cl_descricao');

                if ($img_produto == "") {
                    $img_produto = $imagem_produto_default;
                } else {
                    $img_produto = "img/produto/$img_produto";
                }
                $span_promocao = "";
                if (($data_validade_promocao >= $data_lancamento) and $preco_promocao > 0) { //promoção
                    $span_promocao = "<span class='badge text-bg-info'>Promoção Até " . formatDateB($data_validade_promocao) . "</span>";
                    $preco_venda = "<span class='text-decoration-line-through  text-muted '>" . real_format($preco_venda) .
                        "</span><br><span class='fw-semibold fs-6 '>" . real_format($preco_promocao) . "</span>";
                } else {
                    $preco_venda = real_format($preco_venda);
                }

                $qtd_duvida_produto = consulta_tabela_query($conecta, "select count(*) as total from tb_duvida_loja where cl_produto_id ='$produto_id' and cl_respondido = 0 ", 'total');

            ?>
                <tr>

                    <th scope="row"><?= $produto_id ?></th>
                    <td><img src='<?= $img_produto; ?>' class='card-thumbnail mx-2 p-0 mb-0 rounded-2' style='width: 50px;object-fit:scale-down; height: 50px;'></td>
                    <td style="max-width: 180px;"><?php echo $descricao; ?>
                        <hr class="mb-1"><?= "Ref: " . $referencia ?>
                    </td>
                    <td><?= $grupo . " - " . $categoria; ?></td>
                    <td><?= $und; ?></td>
                    <td><?= ($estoque); ?></td>
                    <td style="max-width: 100px;"><?php echo $preco_venda; ?><br><?= $span_promocao; ?></td>
                    <td><?= formatDateB($data_cadastro); ?></td>
                    <td><span class='badge text-bg-<?php echo ($ativo == "SIM") ? 'success' : 'danger' ?>'>
                            <?php echo ($ativo == "SIM") ? 'Ativo' : 'Inativo' ?></span>
                    </td>
                    <td>
                        <?php if ($estoque_maximo != 0 and $estoque < $estoque_minimo) {
                            echo "<i title='produto abaixo do estoque minimo' class='bi bi-emoji-expressionless-fill'></i>";
                        } ?>
                        <?php if ($estoque_maximo != 0 and $estoque > $estoque_maximo) {
                            echo "<i title='produto acima do estoque maximo' class='bi bi-emoji-dizzy-fill'></i>";
                        } ?>
                    </td>
                    <td>
                        <div class="btn-group">
                            <button type="button" data-id=<?php echo $produto_id; ?> class="btn btn-info   btn-sm editar_produto ">Editar</button>
                            <button type="button" data-id=<?php echo $produto_id; ?> class="btn btn-secondary   btn-sm detalhe_produto">Detalhes <?php if ($qtd_duvida_produto > 0) {
                                                                                                                                                        echo "<span class='position-absolute z-3 top-0 start-100 translate-middle badge rounded-pill bg-danger'>$qtd_duvida_produto+<span class='visually-hidden'>unread messages</span></span>";
                                                                                                                                                    }
                                                                                                                                                    $qtd_duvida_produto; ?></button>
                            <button type="button" data-id=<?php echo $produto_id; ?> class="btn btn-warning   btn-sm consultar_kardex ">Karkex</button>
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
<script src="js/estoque/produto_ecommerce/table/editar_produto.js">