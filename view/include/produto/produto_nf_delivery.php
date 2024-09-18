<?php
include "../../../conexao/conexao.php";
include "../../../modal/estoque/produto/gerenciar_produto.php";


if (isset($_GET['item_nf'])) {
    $id_item_nf = $_GET['id_item_nf'];
    $serie = $_GET['serie'];
    
} else {
    $id_item_nf = "";
    $serie = "";
}
?>

<div class="modal fade" id="modal_item_nf" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


            <div class="modal-body">
                <div class="row  mb-2">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end btn-acao">
                        <button type="button" class="btn btn-sm btn-success" id="alterar_item"></button>
                        <button type="button" class="btn  btn-sm btn-secondary fechar_modal_alterar_item" id="fechar_modal_alterar_item" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-md-1">
                        <input type="text" disabled class="form-control" name="id_produto_item" id="id_produto_item" value="">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md mb-2">

                        <input type="hidden" class="form-control" name="id_item_nf" id="id_item_nf" value="<?php echo $id_item_nf; ?>">
                        <input type="hidden" class="form-control" name="serie_nf" id="serie_nf" value="<?php echo $serie; ?>">

                        <label for="descricao" class="form-label">Descrição</label>
                        <input type="text" class="form-control" name="descricao_item" readonly id="descricao_item" value="">
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-1  mb-2">
                        <label for="unidade_item" class="form-label">Und</label>
                        <input type="text" class="form-control" disabled name="unidade_item" id="unidade_item" value="">
                    </div>

                    <div class="col-md-2  mb-2">
                        <label for="quantidade_item" class="form-label">Quantidade</label>
                        <input type="text" class="form-control inputNumber" onblur="calcular_valor_total_item();calcularTotal();" name="quantidade_item" id="quantidade_item" value="">
                    </div>
                    <div class="col-md  mb-2">
                        <label for="preco_venda_item" class="form-label">Preço Venda</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">R$</span>
                            <input type="text" class="form-control inputNumber" onblur="calcular_desconto_item();calcular_valor_total_item();calcularTotal();" name="preco_venda_item" id="preco_venda_item" value="">
                            <input type="hidden" class="form-control inputNumber" name="preco_venda_item_atual" id="preco_venda_item_atual" value="">
                        </div>
                    </div>



                    <input type="hidden" class="form-control inputNumber" disabled onblur="calcular_valor_total_item()" name="desconto_item" id="desconto_item" value="">

                    <div class="col-md  mb-2">
                        <label for="valor_total_item" class="form-label">Preço total</label>
                        <div class="input-group mb-3">
                            <span class="input-group-text">R$</span>
                            <input type="text" class="form-control" disabled name="valor_total_item" id="valor_total_item" value="">
                        </div>
                    </div>
                </div>
                <?php if ($serie == "vnd_delivery") {
                ?>
                    <div class="row mb-2">
                        <div class="col">
                            <div class="card p-0">
                                <div class="card-header h-50 d-flex justify-content-center">
                                    <div class="mx-3">
                                        <span class="badge text-bg-dark">Adicionais Gratuitos</span>
                                    </div>
                                </div>

                                <div class="card-body p-1">
                                    <?php
                                    $currentSubgroup = null;
                                    $firstItem = true; // Variável para controlar o primeiro item no grupo
                                    while ($linha = mysqli_fetch_assoc($consultar_produto_adicional_delivery_obg)) {

                                        $id_prod_add = $linha['cl_id'];
                                        $descricao = utf8_encode($linha['cl_descricao']);
                                        $subgrupo = utf8_encode($linha['subgrupo']);

                                        $select = "SELECT * FROM tb_nf_saida_item where cl_id_pai_delivery ='$id_item_nf' and cl_item_id ='$id_prod_add' and cl_tipo_item_delivery ='ADICIONAL' and cl_tipo_adicional_delivery='GRATIS' "; // Aqui concatenamos o valor de $("#id").val() na string PHP.

                                        $consultar_status_ativo_add = mysqli_query($conecta, $select);
                                        if (!$consultar_status_ativo_add) {
                                            die(mysqli_error($conecta));
                                        }
                                        $qtd_consultar_status_ativo_add = mysqli_num_rows($consultar_status_ativo_add);
                                        if ($qtd_consultar_status_ativo_add > 0) {
                                            $check = "checked";
                                        } else {
                                            $check = "";
                                        }
                                        // Verificar se o subgrupo atual é diferente do subgrupo anterior
                                        if ($subgrupo != $currentSubgroup) {
                                            if (!$firstItem) {
                                                // Fecha o fieldset anterior, se não for o primeiro item do grupo
                                                echo '</fieldset>';
                                            }
                                            $currentSubgroup = $subgrupo; // Atualizar o subgrupo atual
                                            echo "<fieldset class='border rounded-4 border-1 m-1 pt-1 p-2' style='display: inline-block;'>";
                                            echo "<p class='m-0 fw-semibold'>$subgrupo</p><hr class='mb-1'>";
                                            $firstItem = false; // Define o primeiro item do grupo como false
                                        }
                                    ?>

                                        <div class="form-check form-check-inline" style="font-size: 0.8em">
                                            <input class="form-check-input" type="checkbox" <?php echo $check; ?> id="addobg<?php echo $id_prod_add; ?>" name="addobg<?php echo $id_prod_add; ?>" value="">
                                            <label class="form-check-label" for="addobg<?php echo $id_prod_add; ?>"><?php echo $descricao; ?></label>
                                        </div>
                                    <?php }
                                    // Fecha o último fieldset após o loop
                                    if (!$firstItem) {
                                        echo '</fieldset>';
                                    }
                                    ?>
                                </div>

                            </div>
                        </div>


                    </div>
                    <div class="row mb-2">
                        <div class="col">
                            <div class="card p-0">
                                <div class="card-header h-50 d-flex justify-content-center">
                                    <div class="mx-3">
                                        <span class="badge text-bg-dark">Adicionais Pagos</span>
                                    </div>
                                </div>
                                <div class="card-body p-1">
                                    <?php
                                    $currentSubgroup = null;
                                    $firstItem = true; // Variável para controlar o primeiro item no grupo
                                    while ($linha = mysqli_fetch_assoc($consultar_produto_adicional_delivery)) {

                                        $id_prod_add = $linha['cl_id'];
                                        $descricao = utf8_encode($linha['cl_descricao']);
                                        $subgrupo = utf8_encode($linha['subgrupo']);
                                        $preco_venda = utf8_encode($linha['cl_preco_venda']);
                                        $preco_promocao = ($linha['cl_preco_promocao']);
                                        $data_validade_promocao = ($linha['cl_data_valida_promocao']);

                                        if (($data_validade_promocao != "") and ($data_validade_promocao >= $data_lancamento) and $preco_promocao > 0) { //Está em promoção
                                            $preco_venda = $preco_promocao;
                                        }

                                        $select = "SELECT * FROM tb_nf_saida_item where cl_id_pai_delivery ='$id_item_nf' and cl_item_id ='$id_prod_add'
                                         and cl_tipo_item_delivery ='ADICIONAL' and cl_tipo_adicional_delivery='PAGO' "; // Aqui concatenamos o valor de $("#id").val() na string PHP.


                                        $consultar_status_ativo_add = mysqli_query($conecta, $select);
                                        $qtd_consultar_status_ativo_add = mysqli_num_rows($consultar_status_ativo_add);
                                        if ($qtd_consultar_status_ativo_add > 0) {
                                            $check = "checked";
                                        } else {
                                            $check = "";
                                        }

                                        // Verificar se o subgrupo atual é diferente do subgrupo anterior
                                        if ($subgrupo != $currentSubgroup) {
                                            if (!$firstItem) {
                                                // Fecha o fieldset anterior, se não for o primeiro item do grupo
                                                echo '</fieldset>';
                                            }
                                            $currentSubgroup = $subgrupo; // Atualizar o subgrupo atual
                                            echo "<fieldset class='border rounded-4 border-1 m-1 pt-1 p-2' style='display: inline-block;'>";
                                            echo "<p class='m-0 fw-semibold'>$subgrupo</p><hr class='mb-1'>";
                                            $firstItem = false; // Define o primeiro item do grupo como false
                                        }
                                    ?>
                                        <div class="form-check form-check-inline" style="font-size: 0.8em">
                                            <input class="form-check-input" type="checkbox" <?php echo $check; ?> preco_add='<?php echo $preco_venda; ?>' id="add<?php echo $id_prod_add; ?>" name="add<?php echo $id_prod_add; ?>" onchange="calcularTotal()" value="">
                                            <label class="form-check-label" for="add<?php echo $id_prod_add; ?>"><?php echo $descricao . " + " . real_format($preco_venda); ?></label>
                                        </div>
                                    <?php }
                                    // Fecha o último fieldset após o loop
                                    if (!$firstItem) {
                                        echo '</fieldset>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card p-0">
                                <div class="card-header h-50 d-flex justify-content-center">
                                    <div class="mx-3">
                                        <span class="badge text-bg-dark">Complementos</span>
                                    </div>
                                </div>
                                <div class="card-body p-1">
                                    <?php
                                    $currentSubgroup = null;
                                    $firstItem = true; // Variável para controlar o primeiro item no grupo
                                    while ($linha = mysqli_fetch_assoc($consultar_produto_complemento_delivery)) {

                                        $id_prod_add = $linha['cl_id'];
                                        $descricao = utf8_encode($linha['cl_descricao']);
                                        $preco_venda = utf8_encode($linha['cl_preco_venda']);
                                        $preco_promocao = ($linha['cl_preco_promocao']);
                                        $data_validade_promocao = ($linha['cl_data_valida_promocao']);

                                        if (($data_validade_promocao != "") and ($data_validade_promocao >= $data_lancamento) and $preco_promocao > 0) { //Está em promoção
                                            $preco_venda = $preco_promocao;
                                        }

                                        $select = "SELECT * FROM tb_nf_saida_item where cl_id_pai_delivery ='$id_item_nf' and cl_item_id ='$id_prod_add'
                                        and cl_tipo_item_delivery ='COMPLEMENTO' and cl_tipo_adicional_delivery='PAGO' "; // Aqui concatenamos o valor de $("#id").val() na string PHP.


                                        $consultar_status_ativo_add = mysqli_query($conecta, $select);
                                        $qtd_consultar_status_ativo_add = mysqli_num_rows($consultar_status_ativo_add);
                                        if ($qtd_consultar_status_ativo_add > 0) {
                                            $check = "checked";
                                        } else {
                                            $check = "";
                                        }

                                        // Verificar se o subgrupo atual é diferente do subgrupo anterior
                                        if ($subgrupo != $currentSubgroup) {
                                            if (!$firstItem) {
                                                // Fecha o fieldset anterior, se não for o primeiro item do grupo
                                                echo '</fieldset>';
                                            }
                                            $currentSubgroup = $subgrupo; // Atualizar o subgrupo atual
                                            echo "<fieldset class='border rounded-4 border-1 m-1 pt-1 p-2' style='display: inline-block;'>";
                                            echo "<p class='m-0 fw-semibold'>$subgrupo</p><hr class='mb-1'>";
                                            $firstItem = false; // Define o primeiro item do grupo como false
                                        }
                                    ?>
                                        <div class="form-check form-check-inline" style="font-size: 0.8em">
                                            <input class="form-check-input" type="checkbox" <?php echo $check; ?> preco_comp='<?php echo $preco_venda; ?>' id="addcpm<?php echo $id_prod_add; ?>" name="addcpm<?php echo $id_prod_add; ?>" value="">
                                            <label class="form-check-label" for="addcpm<?php echo $id_prod_add; ?>"><?php echo $descricao . " " . real_format($preco_venda); ?></label>
                                        </div>
                                    <?php }
                                    // Fecha o último fieldset após o loop
                                    if (!$firstItem) {
                                        echo '</fieldset>';
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php } ?>

            </div>


        </div>
    </div>
</div>
<div class="alert"></div>

<script src="js/funcao_2.js"></script>
<script src="js/include/produto/produto_nf.js"></script>