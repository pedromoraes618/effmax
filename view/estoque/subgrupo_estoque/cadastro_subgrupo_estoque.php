<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
include "../../../modal/estoque/subgrupo_estoque/gerenciar_subgrupo_estoque.php";

?>


<div class="title">
    <label class="form-label">Cadastrar subgrupo estoque</label>
    <div class="msg_title">
        <p>Cadastrar subgrupo de estoque </p>
    </div>
</div>
<hr>
<form id="cadastrar_subgrupo_estoque" class="p-2">
    <div class="row">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
            <button type="subbmit" class="btn btn-sm btn-success">Cadastrar</button>
            <?php if ($sistema_delivery == 'S') { ?>
                <button type="button" id="modal_delivery" class="btn btn-sm btn-dark">Delivery</button>
            <?php } ?>
        </div>
    </div>
    <div class="row mb-3">
        <input type="hidden" name="formulario_cadastrar_subgrupo_estoque">
        <?php include "../../input_include/usuario_logado.php" ?>
        <div class="col-sm  mb-2">
            <label for="descricao" class="form-label">Subgrupo *</label>
            <input type="text" class="form-control" id="descricao" name="descricao" placeholder="Informe a descrição do subgrupo" value="">
        </div>
        <div class="col-sm  mb-2">
            <label for="grupo_estoque" class="form-label">Grupo Pai *</label>
            <select name="grupo_estoque" class="form-select chosen-select" id="grupo_estoque">
                <option value="0">Selecione..</option>
                <?php while ($linha  = mysqli_fetch_assoc($consultar_grupo_estoque)) {
                    $id_grupo = $linha['cl_id'];
                    $descricao_b = utf8_encode($linha['cl_descricao']);

                    echo "<option value='$id_grupo'> $descricao_b </option>'";
                } ?>
            </select>
        </div>

    </div>
    <div class="row">
        <div class="col-sm">
            <span class="badge rounded-2 mb-3 d-area dv">Estoque</span>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-sm  mb-2">
            <label for="est_inicial" class="form-label">Estoque inicial</label>
            <input type="text" class="form-control inputNumber" id="est_inicial" name="est_inicial" placeholder="Ex.5" value="">
        </div>
        <div class="col-sm  mb-2">
            <label for="est_minimo" class="form-label">Estoque mínimo</label>
            <input type="text" class="form-control inputNumber" id="est_minimo" name="est_minimo" placeholder="Ex.20" value="">
        </div>
        <div class="col-sm  mb-2">
            <label for="est_maximo" class="form-label">Estoque máximo</label>
            <input type="text" class="form-control inputNumber" id="est_maximo" name="est_maximo" placeholder="Ex.200" value="">
        </div>
        <div class="col-sm  mb-2">
            <label for="local_estoque" class="form-label">Local de estoque</label>
            <input type="text" class="form-control" id="local_estoque" name="local_estoque" placeholder="Ex. prateleira Ab" value="">
        </div>

        <div class="col-sm  mb-2">
            <label for="unidade_md" class="form-label">Unidade *</label>
            <select name="unidade_md" class="form-select chosen-select" id="unidade_md">
                <option value="0">Selecione..</option>
                <?php while ($linha  = mysqli_fetch_assoc($consultar_und_medida)) {
                    $id_und = $linha['cl_id'];
                    $descricao_und = utf8_encode($linha['cl_descricao']);
                    $sigla_und = utf8_encode($linha['cl_sigla']);

                    echo "<option  value='$id_und'> $descricao_und - $sigla_und </option>";
                } ?>
            </select>
        </div>

    </div>

    <div class="row">
        <div class="col-sm">
            <span class="badge rounded mb-3 d-area dv">Fiscal</span>
        </div>
    </div>


    <div class="row mb-3">
        <div class="col-md-2   mb-2">
            <label for="ncm" class="form-label">Ncm</label>
            <input type="text" class="form-control inputNumber" id="ncm" name="ncm">

        </div>

        <div class="col-md-1   mb-2">
            <label for="cst_icms" class="form-label">Cst icms</label>
            <input class="form-control inputNumber" list="datalistOptionsIcms" id="cst_icms" name="cst_icms">

        </div>



        <div class="col-md-1   mb-2">
            <label for="cst_pis_s" class="form-label">Cst Pis S</label>
            <input type="text" list="datalistOptionsPisS" class="form-control inputNumber" id="cst_pis_s" name="cst_pis_s" value="">

        </div>

        <div class="col-md-1   mb-2">
            <label for="cst_pis_s" class="form-label">Cst Pis E</label>
            <input type="text" list="datalistOptionsPisE" class="form-control inputNumber" id="cst_pis_e" name="cst_pis_e" value="">

        </div>


        <div class="col-md-1   mb-2">
            <label for="cst_cofins_s" class="form-label">Cst Cofins S</label>
            <input type="text" list="datalistOptionsCofinsS" class="form-control inputNumber" id="cst_cofins_s" name="cst_cofins_s" value="">

        </div>

        <div class="col-md-1   mb-2">
            <label for="cst_cofins_e" class="form-label">Cst Cofins E</label>
            <input type="text" list="datalistOptionsCofinsE" class="form-control inputNumber" id="cst_cofins_e" name="cst_cofins_e" value="">

        </div>
        <div class="col-md-2  mb-2">
            <label for="cfop_interno" class="form-label">Cfop Interno *</label>
            <select name="cfop_interno" class="form-select chosen-select" id="cfop_interno">
                <option value="0">Selecione..</option>
                <?php while ($linha  = mysqli_fetch_assoc($consultar_cfop_interno)) {
                    $id_cfop = $linha['cl_id'];
                    $codigo_cfop_b = $linha['cl_codigo_cfop'];
                    $descricao_cfop_b = utf8_encode($linha['cl_desc_cfop']);
                    echo "<option  value='$codigo_cfop_b'> $codigo_cfop_b - $descricao_cfop_b </option>";
                } ?>
            </select>
        </div>
        <div class="col-md-2  mb-2">
            <label for="cfop_externo" class="form-label">Cfop Externo *</label>
            <select name="cfop_externo" class="form-select chosen-select" id="cfop_externo">
                <option value="0">Selecione..</option>
                <?php while ($linha  = mysqli_fetch_assoc($consultar_cfop_externo)) {
                    $id_cfop = $linha['cl_id'];
                    $codigo_cfop_externo_b = $linha['cl_codigo_cfop'];
                    $descricao_cfop_externo_b = utf8_encode($linha['cl_desc_cfop']);
                    echo "<option  value='$codigo_cfop_externo_b'> $codigo_cfop_externo_b  - $descricao_cfop_externo_b</option>";
                } ?>
            </select>
        </div>

    </div>
    <!-- <div class="row">
        <div class="col-sm">
            <span class="badge rounded mb-3 d-area dv">Delivery</span>
        </div>
    </div> -->
    <div class="row mb-3">
        <div class="col-sm  mb-2">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" name="delivery" id="delivery">
                <label class="form-check-label" for="delivery">
                    Delivery / E-commerce </label>
            </div>
        </div>
    </div>
    <input type="hidden" class="form-control" id="img_subgrupo_estoque" name="img_subgrupo_estoque" value="">

</form>
<div class="modal_externo"></div>
<script src="js/funcao.js"></script>
<script src="js/configuracao/users/user_logado.js"></script>
<script src="js/estoque/subgrupo_estoque/cadastrar_subgrupo_estoque.js"></script>