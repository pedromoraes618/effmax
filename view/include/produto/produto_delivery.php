<?php
include "../../../conexao/conexao.php";
include "../../../modal/estoque/produto/gerenciar_produto.php";

?>


<div class="modal fade" id="modal_produto_delivery" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Delivery Item</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="produto_delivery">
                    <?php include "../../input_include/usuario_logado.php" ?>
                    <div class="row  mb-2">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end btn-acao">
                            <button type='submit' id="salvar_prod_delivery" class="btn btn-sm btn-success">Salvar</button>
                            <button type="button" class="btn btn btn-sm  btn-secondary" data-bs-dismiss="modal">Fechar</button>
                        </div>
                    </div>
                    <input type="hidden" id="id" name="id" value="<?php echo $id_produto; ?>">
                    <!-- <input type="hidden" class="form-control" id="img_produto" name="img_produto" value="<?php echo $img_produto; ?>"> -->

                    <div class="row mb-2">
                        <div class="col-md-auto">
                            <!-- <div class="col-auto mb-3 ">
                                <div class="bg-secondary img-upload bg-img-produto rounded-circle">
                                    <button type="button" class="btn btn-secondary border-0" id="open_upload_img_prod"><i class="bi bi-camera"></i></button>
                                </div>
                            </div> -->
                            <div class="col mb-2">
                                <label for="min_produto_delivery">Tempo para o preparo</label>
                                <input type="number" name="min_produto_delivery" id="min_produto_delivery" placeholder="Ex. 5" value="<?php echo $tempo_preparo; ?>" class="form-control">
                            </div>
                            <div class="col">
                                <div class="card">
                                    <div class="card-header">
                                        <h6>Quantidade por subgrupo<br> (Gratuitos)</h6>
                                    </div>
                                    <div class="card-body">
                                        <?php while ($linha = mysqli_fetch_assoc($consulta_categoria)) {
                                            $id_categoria = $linha['cl_id'];
                                            $descricao = utf8_encode($linha['cl_descricao']);

                                            $select = "SELECT * FROM tb_subgrupo_limite_delivery where cl_subgrupo_id = '$id_categoria' and cl_produto_id ='$id_produto'";
                                            $consulta_limite_subgrupo = mysqli_query($conecta, $select);
                                            $linha = mysqli_fetch_assoc($consulta_limite_subgrupo);
                                            $quantidade_limite_subgrupo = $linha['cl_quantidade'];

                                        ?>
                                            <div class="col mb-1">
                                                <label for="qtd_subgrupo_<?php echo $id_categoria; ?>"><?php echo $descricao; ?></label>
                                                <select class="form-select chosen-select" name="qtd_subgrupo_<?php echo $id_categoria; ?>" id="">
                                                    <!-- <option value="S">Selecione</option> -->

                                                    <option <?php if ($quantidade_limite_subgrupo == "0") {
                                                                echo 'selected';
                                                            } ?> value="0">0</option>
                                                    <option <?php if ($quantidade_limite_subgrupo == "1") {
                                                                echo 'selected';
                                                            } ?> value="1">1</option>
                                                    <option <?php if ($quantidade_limite_subgrupo == "2") {
                                                                echo 'selected';
                                                            } ?> value="2">2</option>
                                                    <option <?php if ($quantidade_limite_subgrupo == "3") {
                                                                echo 'selected';
                                                            } ?> value="3">3</option>
                                                    <option <?php if ($quantidade_limite_subgrupo == 4) {
                                                                echo 'selected';
                                                            } ?> value="4">4</option>
                                                    <option <?php if ($quantidade_limite_subgrupo == 5) {
                                                                echo 'selected';
                                                            } ?> value="5">5</option>
                                                    <option <?php if ($quantidade_limite_subgrupo == 6) {
                                                                echo 'selected';
                                                            } ?> value="6">6</option>
                                                    <option <?php if ($quantidade_limite_subgrupo == 7) {
                                                                echo 'selected';
                                                            } ?> value="7">7</option>
                                                    <option <?php if ($quantidade_limite_subgrupo == 8) {
                                                                echo 'selected';
                                                            } ?> value="8">8</option>
                                                </select>
                                            </div>

                                        <?php
                                        } ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md">
                            <!-- <div class="row mb-2">
                                <div class="col-md  mb-2">
                                    <label for="descricao_delivery" class="form-label">Descrição</label> -->
                            <input type="hidden" class="form-control" name="descricao_delivery" id="descricao_delivery" placeholder="Máximo 50 caracteres" value="<?php echo $descricao_delivery ?>">
                            <!-- </div>
                            </div> -->
                            <div class="row">
                                <div class="col-md mb-2">
                                    <label for="descricao_ext_delivery" class="form-label">Descrição </label>
                                    <textarea name="descricao_ext_delivery" id="descricao_ext_delivery" class="form-control" cols="30" rows="5" placeholder="Máximo 500 caracteres"><?php echo $descricao_extendida_delivery; ?></textarea>
                                </div>
                            </div>

                            <div class="row mb-2">
                                <div class="col">
                                    <div class="card p-0">
                                        <div class="card-header h-50 d-flex justify-content-center">
                                            <div class="mx-3">
                                                <span class="badge text-bg-dark">Adicionais Gratuitos</span>
                                            </div>

                                        </div>
                                        <div class="row p-2 d-flex align-items-end">

                                            <div class="col-auto">
                                                <label class="form-check-label" for="max_add_obg">
                                                    Máximo Permitido
                                                </label>
                                                <select id="max_add_obg" name="max_add_obg" class="form-control  ">
                                                    <option value="0">0</option>
                                                    <?php for ($i = 1; $i <= $qtd_consultar_produto_adicional_delivery_obg; $i++) {
                                                    ?>
                                                        <option <?php if ($qtd_max_obg == "$i") {
                                                                    echo 'selected';
                                                                } ?> value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                                    <?php } ?>

                                                </select>
                                            </div>
                                            <div class="col">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" name="status_obg" value="" id="flexCheckChecked" <?php if ($status_obg == 'SIM') {
                                                                                                                                                            echo 'checked';
                                                                                                                                                        } ?>>
                                                    <label class="form-check-label" for="flexCheckChecked">
                                                        Obrigatório
                                                    </label>
                                                </div>
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

                                                $select = "SELECT * FROM tb_produto_adicional_delivery WHERE cl_produto_adicional_id = '$id_prod_add' AND cl_status_ativo ='SIM' AND cl_obrigatorio='SIM' AND cl_produto_id ='$id_produto' "; // Aqui concatenamos o valor de $("#id").val() na string PHP.

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
                                                    <input class="form-check-input" type="checkbox" <?php echo $check; ?> id="addobg<?php echo $id_prod_add; ?>" name="addobg<?php echo $id_prod_add; ?>" value="">
                                                    <label class="form-check-label fw-medium" for="addobg<?php echo $id_prod_add; ?>"><?php echo "$descricao"; ?></label>
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

                                                $select = "SELECT * FROM tb_produto_adicional_delivery WHERE cl_produto_adicional_id = '$id_prod_add' AND
                                             cl_status_ativo ='SIM' AND cl_obrigatorio='NAO' AND cl_produto_id ='$id_produto'";

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
                                                    <input class="form-check-input" type="checkbox" <?php echo $check; ?> id="add<?php echo $id_prod_add; ?>" name="add<?php echo $id_prod_add; ?>" value="">
                                                    <label class="form-check-label" for="add<?php echo $id_prod_add; ?>"><?php echo  $descricao . " + " . real_format($preco_venda); ?></label>
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
                                                <span class="badge text-bg-dark">Complementos Pagos</span>
                                            </div>
                                        </div>
                                        <div class="card-body p-1">
                                            <?php while ($linha = mysqli_fetch_assoc($consultar_produto_complemento_delivery)) {

                                                $id_prod_add = $linha['cl_id'];
                                                $descricao = utf8_encode($linha['cl_descricao']);
                                                $preco_promocao = ($linha['cl_preco_promocao']);
                                                $data_validade_promocao = ($linha['cl_data_valida_promocao']);

                                                if (($data_validade_promocao != "") and ($data_validade_promocao >= $data_lancamento) and $preco_promocao > 0) { //Está em promoção
                                                    $preco_venda = $preco_promocao;
                                                }
                                                $select = "SELECT * FROM tb_produto_adicional_delivery WHERE cl_produto_adicional_id = '$id_prod_add' AND
                                             cl_status_ativo ='SIM' AND cl_obrigatorio='NAO' AND cl_complemento='SIM' and cl_produto_id ='$id_produto'";

                                                $consultar_status_ativo_add = mysqli_query($conecta, $select);
                                                $qtd_consultar_status_ativo_add = mysqli_num_rows($consultar_status_ativo_add);
                                                if ($qtd_consultar_status_ativo_add > 0) {
                                                    $check = "checked";
                                                } else {
                                                    $check = "";
                                                }
                                            ?>
                                                <div class="form-check form-check-inline" style="font-size: 0.8em">
                                                    <input class="form-check-input" type="checkbox" <?php echo $check; ?> id="addcpm<?php echo $id_prod_add; ?>" name="addcpm<?php echo $id_prod_add; ?>" value="">
                                                    <label class="form-check-label" for="addcpm<?php echo $id_prod_add; ?>"><?php echo $descricao . " " . real_format($preco_venda); ?></label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="modal_externo_modal"></div>

    </div>
</div>
</div>

<script src="js/funcao.js"></script>
<script src="js/configuracao/users/user_logado.js"></script>
<script src="js/include/produto/produto_delivery.js"></script>