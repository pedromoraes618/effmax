<?php
include "../../../conexao/conexao.php";
include "../../../modal/estoque/subgrupo_estoque/gerenciar_subgrupo_estoque.php";
?>


<div class="title">
    <label class="form-label">Editar subgrupo estoque</label>
    <div class="msg_title">
        <p>Editar subgrupo de estoque </p>
    </div>
</div>
<hr>
<form id="editar_subgrupo_estoque" class="p-1">
    <div class="row">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
            <button type="subbmit" class="btn  btn-sm btn-success">Alterar</button>
            <?php if ($sistema_delivery == 'S') { ?>
                <button type="button" id="modal_delivery" class="btn btn-sm btn-dark">Delivery</button>
            <?php } ?>
            <button type="button" id="remover" class="btn  btn-sm btn-danger">Remover</button>
            <button type="button" id="voltar_cadastro" class="btn btn-sm btn-secondary">Voltar </button>
        </div>
    </div>

    <div class="row mb-3">
        <input type="hidden" name="formulario_editar_subgrupo_estoque">
        <?php include "../../input_include/usuario_logado.php" ?>
        <input type="hidden" value="<?php echo $id_subgrupo; ?>" id="id_subgrupo" name="id_subgrupo">
        <div class="col-sm  mb-2">
            <label for="descricao" class="form-label">Subgrupo</label>
            <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Informe a descrição do subgrupo" value="<?php echo $descricao_b; ?>">
        </div>
        <div class="col-sm  mb-2">
            <label for="grupo_estoque" class="form-label">Grupo Pai</label>
            <select name="grupo_estoque" class="form-select chosen-select" id="grupo_estoque">
                <option value="0">Selecione..</option>
                <?php while ($linha  = mysqli_fetch_assoc($consultar_grupo_estoque)) {
                    $id_grupo = $linha['cl_id'];
                    $descricao_b = $linha['cl_descricao'];
                    if ($grupo_pai_b == $id_grupo) {
                        echo "<option selected value='$id_grupo'> $descricao_b </option>";
                    } else {
                        echo "<option value='$id_grupo'> $descricao_b </option>'";
                    }
                } ?>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-sm">
            <span class="badge rounded-2 mb-3 d-area dv">Informações de Estoque</span>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm  mb-2">
            <label for="est_inicial" class="form-label">Estoque inicial</label>
            <input type="text" class="form-control inputNumber" id="est_inicial" name="est_inicial" placeholder="Ex.5" value="<?php echo $estoque_inicial_b ?>">
        </div>
        <div class="col-sm  mb-2">
            <label for="est_minimo" class="form-label">Estoque mínimo</label>
            <input type="text" class="form-control inputNumber" id="est_minimo" name="est_minimo" placeholder="Ex.20" value="<?php echo $estoque_minimo_b ?>">
        </div>
        <div class="col-sm  mb-2">
            <label for="est_maximo" class="form-label">Estoque máximo</label>
            <input type="text" class="form-control inputNumber" id="est_maximo" name="est_maximo" placeholder="Ex.200" value="<?php echo $estoque_maximo_b ?>">
        </div>
        <div class="col-sm  mb-2">
            <label for="local_estoque" class="form-label">Local de estoque</label>
            <input type="text" class="form-control" id="local_estoque" name="local_estoque" placeholder="Ex. prateleira Ab" value="<?php echo $estoque_local_b; ?>">
        </div>

        <div class="col-sm  mb-2">
            <label for="unidade_md" class="form-label">Unidade</label>
            <select name="unidade_md" class="form-select chosen-select" id="unidade_md">
                <option value="0">Selecione..</option>
                <?php while ($linha  = mysqli_fetch_assoc($consultar_und_medida)) {
                    $id_und = $linha['cl_id'];
                    $descricao_und = utf8_encode($linha['cl_descricao']);
                    $sigla_und = utf8_encode($linha['cl_sigla']);
                    if ($und_b == $id_und) {
                        echo "<option selected value='$id_und'> $descricao_und - $sigla_und </option>";
                    } else {
                        echo "<option  value='$id_und'> $descricao_und - $sigla_und </option>";
                    }
                } ?>
            </select>
        </div>

    </div>

    <div class="row">
        <div class="col-sm">
            <span class="badge rounded mb-3 d-area dv">Informações Fiscais</span>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-md-2   mb-2">
            <label for="ncm" class="form-label">Ncm</label>
            <input type="text" class="form-control inputNumber" id="ncm" name="ncm" value="<?php echo $ncm; ?>">

        </div>

        <div class="col-md-1   mb-2">
            <label for="cst_icms" class="form-label">Cst icms</label>
            <input class="form-control inputNumber" list="datalistOptionsIcms" id="cst_icms" name="cst_icms" value="<?php echo $cst_icms; ?>">

        </div>



        <div class="col-md-1   mb-2">
            <label for="cst_pis_s" class="form-label">Cst Pis S</label>
            <input type="text" list="datalistOptionsPisS" class="form-control inputNumber" id="cst_pis_s" name="cst_pis_s" value="<?php echo $cst_pis_s; ?>">

        </div>

        <div class="col-md-1   mb-2">
            <label for="cst_pis_s" class="form-label">Cst Pis E</label>
            <input type="text" list="datalistOptionsPisE" class="form-control inputNumber" id="cst_pis_e" name="cst_pis_e" value="<?php echo $cst_pis_e; ?>">

        </div>


        <div class="col-md-1   mb-2">
            <label for="cst_cofins_s" class="form-label">Cst Cofins S</label>
            <input type="text" list="datalistOptionsCofinsS" class="form-control inputNumber" id="cst_cofins_s" name="cst_cofins_s" value="<?php echo $cst_cofins_s; ?>">

        </div>

        <div class="col-md-1   mb-2">
            <label for="cst_cofins_e" class="form-label">Cst Cofins E</label>
            <input type="text" list="datalistOptionsCofinsE" class="form-control inputNumber" id="cst_cofins_e" name="cst_cofins_e" value="<?php echo $cst_cofins_e; ?>">

        </div>
        
        <div class="col-md-2  mb-2">
            <label for="cfop_interno" class="form-label">Cfop Interno</label>
            <select name="cfop_interno" class="form-select chosen-select" id="cfop_interno">
                <option value="0">Selecione..</option>
                <?php while ($linha  = mysqli_fetch_assoc($consultar_cfop_interno)) {
                    $id_cfop = $linha['cl_id'];
                    $codigo_cfop_b = $linha['cl_codigo_cfop'];
                    $descricao_cfop_b = utf8_encode($linha['cl_desc_cfop']);
                    if ($cfop_interno_b == $codigo_cfop_b) {
                        echo "<option selected value='$codigo_cfop_b'> $codigo_cfop_b - $descricao_cfop_b</option>";
                    } else {
                        echo "<option  value='$codigo_cfop_b'> $codigo_cfop_b  - $descricao_cfop_b</option>";
                    }
                } ?>


            </select>
        </div>
        <div class="col-md-2  mb-2">
            <label for="cfop_externo" class="form-label">Cfop Externo</label>
            <select name="cfop_externo" class="form-select chosen-select" id="cfop_externo">
                <option value="0">Selecione..</option>
                <?php while ($linha  = mysqli_fetch_assoc($consultar_cfop_externo)) {
                    $id_cfop = $linha['cl_id'];
                    $codigo_cfop_externo_b = $linha['cl_codigo_cfop'];
                    $descricao_cfop_externo_b = utf8_encode($linha['cl_desc_cfop']);
                    if ($cfop_externo_b == $codigo_cfop_externo_b) {
                        echo "<option selected value='$codigo_cfop_externo_b'> $codigo_cfop_externo_b - $descricao_cfop_externo_b</option>";
                    } else {
                        echo "<option  value='$codigo_cfop_externo_b'> $codigo_cfop_externo_b  - $descricao_cfop_externo_b</option>";
                    }
                } ?>

            </select>
        </div>
   
    </div>
    <div class="row mb-3">
        <div class="col-sm  mb-2">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="delivery" name="delivery" <?php if ($delivery == "SIM") {
                                                                                                            echo 'checked';
                                                                                                        } ?>>
                <label class="form-check-label" for="delivery">
                    Delivery / E-commerce
                </label>
            </div>
        </div>
    </div>
    <input type="hidden" class="form-control" id="img_subgrupo_estoque" name="img_subgrupo_estoque" value="<?php echo $img_subgrupo_estoque_b; ?>">



</form>
<div class="modal_externo"></div>
<script src="js/funcao.js"></script>
<script src="js/configuracao/users/user_logado.js"></script>
<script src="js/estoque/subgrupo_estoque/editar_subgrupo_estoque.js"></script>