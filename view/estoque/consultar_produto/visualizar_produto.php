<?php  
include "../../../conexao/conexao.php";
include "../../../modal/estoque/produto/gerenciar_produto.php";  
?>


<div class="title">
    <label class="form-label">Visualizar Produto</label>
    <div class="msg_title">
        <p>Visualize as informações do produto, Lembrando não é possivel qualquer edição de informação</p>
    </div>
</div>
<hr>
<form id="editar_produto">
    <div class="row mb-2">
        <div class="col-sm-6 col-md-2   mb-2">
            <label for="referencia" class="form-label">Codigo</label>
            <input type="hidden" name="id_produto" value="<?php echo $id_produto ?>">
            <input type="text" readonly class="form-control" id="codigo_produto" name="codigo_produto" value="<?php echo $codigo_produto_b ?>">
        </div>
    </div>
    <div class="row mb-2">
        <input type="hidden" name="formulario_editar_produto">
        <?php include "../../input_include/usuario_logado.php"?>

        <div class="col-sm col-md-5  mb-2">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" class="form-control " id="descricao" name="descricao"
                value="<?php echo $descricao_b; ?>">
        </div>
        <div class="col-sm-6 col-md-2   mb-2">
            <label for="referencia" class="form-label">Referência</label>
            <input type="text" class="form-control" id="referencia" name="referencia"
                value="<?php echo $referencia_b; ?>">
        </div>
        <div class="col-sm-6 col-md-2 mb-2">
            <label for="equivalencia" class="form-label">Equivalencia</label>
            <input type="text" class="form-control" id="equivalencia" name="equivalencia"
                value="<?php echo $equivalencia_b; ?>">
        </div>
        <div class="col-sm-6 col-md-3  mb-2">
            <label for="codigo_barras" class="form-label">Código de barras</label>
            <input type="text" class="form-control" id="codigo_barras" name="codigo_barras"
                value="<?php echo $codigo_barras_b; ?>">
        </div>
    </div>
    <div class="row mb-2">
        <div class="col-md-4  mb-2">
            <label for="grupo_estoque" class="form-label">Grupo</label>
            <select name="grupo_estoque"
                title="ao selecionar o grupo, os campos serão preenchidos automaticamente, para desativar essa funcionalidade verifique com o suporte"
                class="form-select chosen-select" id="grupo_estoque">
                <option value="0">Selecione..</option>
                <?php while($linha  = mysqli_fetch_assoc($consultar_subgrupo_estoque)){ 
                $id_grupo = $linha['cl_id'];
                $descricao_b = utf8_encode($linha['cl_descricao']);
                $grupo = utf8_encode($linha['grupo']);
                if($grupo_id_b == $id_grupo){
                    echo "<option selected value='$id_grupo'> $grupo - $descricao_b </option>'";
                }else{
                    echo "<option value='$id_grupo'> $grupo - $descricao_b </option>'";
                }
            
             }?>
            </select>
        </div>
        <div class="col-md-3 mb-2">
            <label for="fabricante" class="form-label">Fabricante</label>
            <select name="fabricante" class="form-select chosen-select" id="fabricante">
                <option value="0">Selecione..</option>
                <?php while($linha  = mysqli_fetch_assoc($consultar_fabricantes)){ 
                $id_fabricante = $linha['cl_id'];
                $descricao= utf8_encode($linha['cl_descricao']);
                if($fabricante_b == $id_fabricante){
                echo "<option selected value='$id_fabricante' >$descricao</option>";
             }else{
                echo "<option  value='$id_fabricante' >$descricao</option>";
             }}?>
            </select>
        </div>

        <div class="col-md-3 mb-2">
            <label for="tipo" class="form-label">Tipo</label>
            <select name="tipo" class="form-select chosen-select" id="tipo">
                <option value="0">Selecione..</option>
                <?php while($linha  = mysqli_fetch_assoc($consultar_tipo_produto)){ 
                $id_tipo = $linha['cl_id'];
                $descricao= utf8_encode($linha['cl_descricao']);
                if($tipo_b == $id_tipo){
                echo "<option  selected value='$id_tipo'>$descricao</option>";
                }else{
                    echo "<option   value='$id_tipo'>$descricao</option>";
                }
             }?>
            </select>
        </div>
        <div class="col-md-2 mb-2">
            <label for="status" class="form-label">Status</label>
            <select name="status" class="form-select chosen-select" id="status">
                <option value="0">Selecione..</option>

                
        
                <option <?php  if($status_ativo_b =="SIM"){ echo "selected" ;} ?>  value="SIM">Ativo</option>
                <option  <?php  if($status_ativo_b =="NAO"){ echo "selected";} ?> value="NAO">Inativo</option>

            </select>
        </div>

    </div>

    <div class="row">
        <div class="col-sm">
            <span class="badge rounded-2 mb-3 d-area dv">Informações de estoque</span>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-sm-6 col-md-2   mb-2">
            <label for="estoque" class="form-label">Estoque</label>
            <input type="text" title="Não é possivel editar essa informação" disabled class="form-control inputNumber" id="estoque" name="estoque"
                value="<?php echo $estoque_b; ?>">
        </div>

        <div class="col-sm-6 col-md-2  mb-2">
            <label for="est_minimo" class="form-label">Estoque Mínimo</label>
            <input type="text" class="form-control inputNumber" id="est_minimo" name="est_minimo"
                value="<?php echo $est_minimo_b; ?>">
        </div>

        <div class="col-sm-6 col-md-2  mb-2">
            <label for="est_maximo" class="form-label">Estoque Máximo</label>
            <input type="text" class="form-control inputNumber" id="est_maximo" name="est_maximo"
                value="<?php echo $est_max_b; ?>">
        </div>
        <div class="col-sm-6 col-md-2   mb-2">
            <label for="local_produto" class="form-label">Local de Produto</label>
            <input type="text" class="form-control" id="local_produto" name="local_produto"
                value="<?php echo $local_b; ?>">
        </div>
        <div class="col-sm-6 col-md-2   mb-2">
            <label for="tamanho" class="form-label">Tamanho</label>
            <input type="text" class="form-control" id="tamanho" name="tamanho" value="<?php echo $tamanho_b; ?>">
        </div>
        <div class="col-sm-6 col-md-2  mb-2">
            <label for="unidade_md" class="form-label">Unidade de medida</label>
            <select name="unidade_md" class="form-select chosen-select" id="unidade_md">
                <option value="0">Selecione..</option>
                <?php while($linha  = mysqli_fetch_assoc($consultar_und_medida)){ 
                $id_und = $linha['cl_id'];
                $descricao_und= utf8_encode($linha['cl_descricao']);
                $sigla_und = utf8_encode($linha['cl_sigla']);
                
                if($und_b == $id_und){
                    echo "<option selected value='$id_und'> $descricao_und - $sigla_und </option>";
                }else{
                    echo "<option  value='$id_und'> $descricao_und - $sigla_und </option>";
                }
              
             }?>
            </select>
        </div>

    </div>

    <div class="row">
        <div class="col-sm">
            <span class="badge rounded-2 mb-3 d-area dv">Informações de Valores</span>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-sm-6 col-md-2   mb-2">
            <label for="prc_venda" class="form-label">Preço de venda</label>
            <input type="text" title="Não é possivel editar essa informação" disabled class="form-control inputNumber" onchange="maregm_lucro()" id="prc_venda"
                name="prc_venda" value="<?php echo $preco_venda_b; ?>">
        </div>
        <div class="col-sm-6 col-md-2   mb-2">
            <label for="prc_custo" class="form-label">Preço de Custo</label>
            <input type="text" title="Não é possivel editar essa informação" disabled class="form-control inputNumber" onchange="maregm_lucro()" id="prc_custo"
                name="prc_custo" value="<?php echo $preco_custo_b ?>">
        </div>


        <div class="col-sm-6 col-md-2  mb-2">
            <label for="margem_lucro" class="form-label">Magem de lucro %</label>
            <input type="text" class="form-control inputNumber"
                title="informe a margem do produto para definir o preço de venda" id="margem_lucro"
                onchange="preco_venda()" name="margem_lucro" value="<?php echo $margem_b; ?>">
        </div>

        <div class="col-sm-6 col-md-2   mb-2">
            <label for="prc_promocao" class="form-label">Preço Promoção</label>
            <input type="text" class="form-control inputNumber" id="prc_promocao" name="prc_promocao"
                value="<?php echo $preco_promocao_b; ?>">
        </div>
        <div class="col-sm-6 col-md-2   mb-2">
            <label for="desconto_maximo" class="form-label">Desconto Máximo</label>
            <input type="text" class="form-control inputNumber" id="desconto_maximo" name="desconto_maximo"
                value="<?php echo $desconto_maximo_b ?>">
        </div>
        <div class="col-sm-6 col-md-2  mb-2">
            <label for="ultimo_preco_compra" class="form-label">Ult preço compra</label>
            <input type="text" class="form-control inputNumber" disabled id="ultimo_preco_compra"
                name="ultimo_preco_compra" value="<?php echo $ult_preco_compra_b; ?>">
        </div>

    </div>

    <div class="row">
        <div class="col-sm">
            <span class="badge rounded-2 mb-3 d-area dv">Informações Fiscais</span>
        </div>
    </div>

    <div class="row mb-3">
        <div class="col-sm-6 col-md-2   mb-2">
            <label for="cest" class="form-label">Cest</label>
            <div class="input-group c mb-3">
                <input type="text" class="form-control inputNumber" id="cest" name="cest" value="<?php echo $cest_b ?>"
                    aria-label="Recipient's username" aria-describedby="button-addon2">

                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal_cunsultar_cest"
                    data-bs-whatever="@mdo" title="pesquise pelo cest" type="button"><i
                        class="bi bi-search"></i></button>
            </div>
        </div>
        <div class="col-sm-6 col-md-2   mb-2">
            <label for="ncm" class="form-label">Ncm</label>
            <div class="input-group c mb-3">
                <input type="text" class="form-control inputNumber" id="ncm" name="ncm" value="<?php echo $ncm_b ?>"
                    aria-label="Recipient's username" aria-describedby="button-addon2">

                <button class="btn btn-outline-secondary" title="pesquise pelo ncm" data-bs-toggle="modal"
                    data-bs-target="#modal_cunsultar_ncm" data-bs-whatever="@mdo" type="button"><i
                        class="bi bi-search"></i></button>
            </div>
        </div>
        <div class="col-sm-6 col-md-2   mb-2">
            <label for="cst_icms" class="form-label">Cst icms</label>
            <input class="form-control inputNumber" list="datalistOptionsIcms" id="cst_icms" name="cst_icms"
                value="<?php echo $cst_icms_b; ?>">
            <datalist id="datalistOptionsIcms">
                <?php while($linha  = mysqli_fetch_assoc($consultar_icms)){ 
                $icms_b= ($linha['cl_icms']);
                $descricao_b = utf8_encode($linha['cl_descricao']);
                echo "<option  value='$icms_b'> $descricao_b</option>";
             }?>
            </datalist>
        </div>



        <div class="col-sm-6 col-md-1   mb-2">
            <label for="cst_pis_s" class="form-label">Cst Pis S</label>
            <input type="text" list="datalistOptionsPisS" class="form-control inputNumber" id="cst_pis_s"
                name="cst_pis_s" value="<?php echo $pis_s_b ?>">
            <datalist id="datalistOptionsPisS">
                <?php while($linha  = mysqli_fetch_assoc($consultar_pis_s)){ 

                $icms_b= ($linha['cl_pis']);
                $descricao_b = utf8_encode($linha['cl_descricao']);

                echo "<option  value='$icms_b'> $descricao_b</option>";
             }?>
            </datalist>
        </div>

        <div class="col-sm-6 col-md-1   mb-2">
            <label for="cst_pis_s" class="form-label">Cst Pis E</label>
            <input type="text" list="datalistOptionsPisE" class="form-control inputNumber" id="cst_pis_e"
                name="cst_pis_e" value="<?php echo $pis_e_b ?>">
            <datalist id="datalistOptionsPisE">
                <?php while($linha  = mysqli_fetch_assoc($consultar_pis_e)){ 

                $icms_b= ($linha['cl_pis']);
                $descricao_b = utf8_encode($linha['cl_descricao']);

                echo "<option  value='$icms_b'> $descricao_b</option>";
             }?>
            </datalist>
        </div>


        <div class="col-sm-6 col-md-2   mb-2">
            <label for="cst_cofins_s" class="form-label">Cst Cofins S</label>
            <input type="text" list="datalistOptionsCofinsS" class="form-control inputNumber" id="cst_cofins_s"
                name="cst_cofins_s" value="<?php echo $cofins_s_b ?>">
            <datalist id="datalistOptionsCofinsS">
                <?php while($linha  = mysqli_fetch_assoc($consultar_cofins_s)){ 

                $icms_b= ($linha['cl_cofins']);
                $descricao_b = utf8_encode($linha['cl_descricao']);

                echo "<option  value='$icms_b'> $descricao_b</option>";
             }?>
            </datalist>
        </div>

        <div class="col-sm-6 col-md-2   mb-2">
            <label for="cst_cofins_e" class="form-label">Cst Cofins E</label>
            <input type="text" list="datalistOptionsCofinsE" class="form-control inputNumber" id="cst_cofins_e"
                name="cst_cofins_e" value="<?php echo $cofins_e_b ?>">
            <datalist id="datalistOptionsCofinsE">
                <?php while($linha  = mysqli_fetch_assoc($consultar_cofins_e)){ 
                $icms_b= ($linha['cl_cofins']);
                $descricao_b = utf8_encode($linha['cl_descricao']);

                echo "<option  value='$icms_b'> $descricao_b</option>";
             }?>
            </datalist>
        </div>

        <div class="row mb-2">
            <div class="col-sm-6 col-md-8   mb-2">
                <label for="prc_venda" class="form-label">Observação</label>
                <textarea class="form-control" name="observacao" id="observacao"
                    aria-label="With textarea"><?php echo $observacao_b; ?></textarea>

            </div>
        </div>

        <div class="">
            <input type="hidden" class="form-control inputNumber" id="cfop_interno" name="cfop_interno" value="">
            <input type="hidden" class="form-control inputNumber" id="cfop_externo" name="cfop_externo" value="">
        </div>


    </div>

    <?php
    //incluir modal do cest
     include "../../../view/estoque/produto/include/cest.php"; 
     include "../../../view/estoque/produto/include/ncm.php"; 
      ?>

    <div class="row">
    
        <div class="group-btn d-grid gap-2 d-sm-block">
            <button type="button" id="voltar_consulta" class="btn btn-outline-dark col-md-3">Voltar </button>
        </div>
    </div>



</form>
<script src="js/funcao.js"></script>
<script src="js/estoque/produto/funcao/consultar.js"></script>
<script src="js/configuracao/users/user_logado.js"></script>
<script src="js/estoque/consultar_produto/visualizar_produto.js"></script>