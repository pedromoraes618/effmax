<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/estoque/produto/gerenciar_produto.php";

?> <div class="row">
    <?php
    if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
        while ($linha = mysqli_fetch_assoc($consultar_produtos)) {
            $produto_id = $linha['produtoid'];
            $codigo_produto_b = $linha['cl_codigo'];
            $descricao_b = utf8_encode($linha['descricao']);
            $referencia_b = utf8_encode($linha['cl_referencia']);
            $estoque_minimo_b = utf8_encode($linha['cl_estoque_minimo']);
            $estoque_maximo_b = utf8_encode($linha['cl_estoque_maximo']);
            $subgrupo_b = utf8_encode($linha['subgrupo']);
            $und_b = utf8_encode($linha['und']);
            $fabricante_b = utf8_encode($linha['cl_fabricante']);
            $estoque_b = $linha['cl_estoque'];
            $preco_venda_b = ($linha['cl_preco_venda']);
            $ativo = ($linha['ativo']);
            $img_produto = ($linha['cl_img_produto']);
            $img_default = (verficar_paramentro($conecta, "tb_parametros", "cl_id", "34")); //imagem default dos produtos
            $preco_promocao = ($linha['cl_preco_promocao']);
            $data_validade_promocao = ($linha['cl_data_valida_promocao']);


            if ($img_produto == "") {
                $img_produto = $img_default;
            } else {
                $img_produto = "img/produto/$img_produto";
            }

            if (($data_validade_promocao != "" ) and ($data_validade_promocao >= $data_lancamento) and $preco_promocao > 0) {
                $valores =   "<p class='col-7 m-0 card-price card-price-promo'  >" . real_format($preco_promocao) . " </p> <p class='col-7 m-0 text-decoration-line-through'>" . real_format($preco_venda_b) . "</p>";
                $promocao = true;
            } else {
                $valores =   "<p '>" . real_format($preco_venda_b) . " </p>";
                $promocao = false;
            }
    ?>


            <div class="col-6 col-md-2 card-group group-card-produtos p-2 border-0 mb-3">
                <div class="card card_produtos">
                    <img src="<?php echo $img_produto; ?>" class="card-img-top" alt="...">
                    <div class="card-body p-1">
                        <p class="fs-6 fw-medium"><?php echo $descricao_b; ?></p>
                        <div class="card-group-price d-inline-flex mb-0">
                            <?php echo $valores; ?>
                        </div>

                        <p class="card-text"></p>
                        <div class="d-grid gap-2">
                            <button type="button" estoque="<?php echo $estoque_b; ?>" unidade="<?php echo $und_b ?>" preco_venda="<?php echo $preco_venda_b; ?>" ativo=<?php echo $ativo; ?> id_produto=<?php echo $produto_id; ?> class="btn btn-primary   btn-sm selecionar_produto ">Selecionar</button>

                            <?php
                            if ($promocao) {
                            ?>
                                <button type="button" promocao='sim' estoque="<?php echo $estoque_b; ?>" unidade="<?php echo $und_b ?>" preco_venda="<?php echo $preco_promocao; ?>" ativo=<?php echo $ativo; ?> id_produto=<?php echo $produto_id; ?> class="btn btn-dark   btn-sm selecionar_produto ">Promoção</button>
                            <?php
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" id="<?php echo $produto_id; ?>" value="<?php echo $descricao_b; ?>">
            <input type="hidden" referencia_<?php echo $produto_id ?>="<?php echo $produto_id; ?>" value="<?php echo $referencia_b; ?>">

        <?php } ?>
</div>

<label>
    Registros <?php echo $qtd; ?>
</label>
<?php
    } else {
        include "../../../../view/alerta/alerta_pesquisa.php"; // mesnsagem para usuario pesquisar
    }
?>
<script src="js/include/produto/table/consultar_produto_delivery.js">