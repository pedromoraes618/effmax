<?php
include "../../../conexao/conexao.php";
include "../../../modal/estoque/produto/gerenciar_produto.php";
?>

<div class="modal fade" id="modal_produto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl   ">
        <div class="modal-content ">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Produto</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="title mb-2">
                    <label class="form-label sub-title"></label>
                </div>
                <div class="row mb-2">
                    <div class="imagem-produto">
                    </div>
                </div>
                <form id="produto">
                    <input type="hidden" id="id" name="id" value="<?php echo $form_id; ?>">
                    <input type="hidden" readonly id="codigo_nf" name="codigo_nf" value="">

                    <div class="d-flex flex-wrap justify-content-end gap-2 mb-2">
                        <button type="submit" id="button_form" class="btn btn-sm btn-success"></button>
                        <?php if ($form_id != "") {
                            echo  "<button type='button' id='clonar' class='btn btn-sm btn-info'>Clonar</button>";
                        } ?>
                        <button type="button" class="btn btn-sm btn-warning modal_anexo"><i class="bi bi-file-earmark"></i> Anexo</button>
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>

                    <div class="accordion " id="accordionPanelsStayOpenExample">
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                    Informações básicas
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show ">
                                <div class="accordion-body">
                                    <div class="accordion-body p-0">
                                        <div class="row d-flex align-items-end mb-2">
                                            <?php if ($form_id != "") {
                                                echo  "<div class='col-md-2'>
                <label for='codigo' class='form-label'>Código</label>
                <input type='text' disabled class='form-control' id='codigo' name='codigo' value='$form_id'></div>";
                                            } ?>
                                            <div class=" col-md-7  mb-2">
                                                <label for="codigo_barras" class="form-label">Código de barras</label>
                                                <input type="text" class="form-control" id="codigo_barras" name="codigo_barras" value="">
                                            </div>
                                            <div class="col-md">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" value="" role="switch" name="lancamento" id="lancamento">
                                                    <label class="form-check-label" for="lancamento">
                                                        Produto Lançamento
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-12 col-md-6 mb-2">
                                                <label for="descricao" class="form-label">Descrição</label>
                                                <input type="text" class="form-control" id="descricao" name="descricao" value="" placeholder="Ex. Produto x">
                                            </div>
                                            <div class="col-6 col-md-3 mb-2">
                                                <label for="referencia" class="form-label">Referência</label>
                                                <input type="text" class="form-control" id="referencia" name="referencia" value="" placeholder="Ex. A1587654">
                                            </div>
                                            <div class="col-6 col-md-3 mb-2">
                                                <label for="equivalencia" class="form-label">Equivalência</label>
                                                <input type="text" class="form-control" id="equivalencia" name="equivalencia" value="" placeholder="Ex. 11558954">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-12 col-md-4  mb-2">
                                                <label for="grupo_estoque" class="form-label">Grupo</label>
                                                <select name="grupo_estoque"
                                                    title="ao selecionar o grupo, os campos serão 
                                                preenchidos automaticamente, para desativar essa
                                                 funcionalidade verifique com o suporte" class="select2-modal chosen-select" id="grupo_estoque">
                                                    <option value="0">Selecione..</option>
                                                    <?php
                                                    $resultados = consulta_linhas_tb($conecta, 'tb_subgrupo_estoque');
                                                    if ($resultados) {
                                                        foreach ($resultados as $linha) {
                                                            $id = $linha['cl_id'];
                                                            $descricao_subgrupo = utf8_encode($linha['cl_descricao']);
                                                            $grupo_id = ($linha['cl_grupo_id']);
                                                            $descricao_grupo = consulta_tabela($conecta, "tb_grupo_estoque", "cl_id", $grupo_id, "cl_descricao");
                                                            echo "<option  value='$id'>$descricao_grupo - $descricao_subgrupo</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-6 col-md mb-2">
                                                <label for="fabricante" class="form-label">Fabricante</label>
                                                <input type="text" class="form-control" id="fabricante" name="fabricante" value="" placeholder="Ex. Makita">
                                            </div>

                                            <div class="col-6 col-md-3 mb-2">
                                                <label for="tipo" class="form-label">Tipo</label>
                                                <select name="tipo" class="form-select" id="tipo">
                                                    <option value="0">Selecione..</option>
                                                    <?php
                                                    $resultados = consulta_linhas_tb($conecta, 'tb_tipo_produto');
                                                    if ($resultados) {
                                                        foreach ($resultados as $linha) {
                                                            $id = $linha['cl_id'];
                                                            $descricao = utf8_encode($linha['cl_descricao']);

                                                            echo "<option  value='$id'>$descricao</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="col-6 col-md-2 mb-2">
                                                <label for="status" class="form-label">Status</label>
                                                <select name="status" class="form-select" id="status">
                                                    <option value="0">Selecione..</option>
                                                    <option selected value="SIM">Ativo</option>
                                                    <option value="NAO">Inativo</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
                                    Estoque
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show ">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-6 col-md-2   mb-2">
                                            <label for="estoque" class="form-label">Estoque</label>
                                            <input type="text" <?php if (!empty($form_id) and $altera_estoque == "N") {
                                                                    echo "disabled";
                                                                } ?> class="form-control inputNumber" id="estoque" name="estoque" value="" placeholder="Ex. 0.00">
                                        </div>

                                        <div class="col-6 col-md-2  mb-2">
                                            <label for="est_minimo" class="form-label">Estoque mínimo</label>
                                            <input type="text" class="form-control inputNumber" id="est_minimo" name="est_minimo" placeholder="Ex. 12" value="">
                                        </div>

                                        <div class=" col-6 col-md-2 mb-2">
                                            <label for="est_maximo" class="form-label">Estoque máximo</label>
                                            <input type="text" class="form-control inputNumber" id="est_maximo" name="est_maximo" placeholder="Ex. 1" value="">
                                        </div>
                                        <div class="col-6 col-md-2  mb-2">
                                            <label for="local_produto" class="form-label">Local de estoque</label>
                                            <input type="text" class="form-control" id="local_produto" name="local_produto" placeholder="ex. prateleira ab" value="">
                                        </div>
                                        <div class=" col-6 col-md-2   mb-2">
                                            <label for="tamanho" class="form-label">Tamanho</label>
                                            <input type="text" class="form-control" id="tamanho" name="tamanho" value="" placeholder="Ex 16cm">
                                        </div>
                                        <div class="col-6 col-md-2  mb-2">
                                            <label for="unidade_md" class="form-label">Unidade</label>
                                            <select name="unidade_md" class="form-select" id="unidade_md">
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
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="true" aria-controls="panelsStayOpen-collapseThree">
                                    Valores
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show ">
                                <div class="accordion-body">
                                    <div class="row mb-2">
                                        <div class="col-12 col-md-3   mb-2">
                                            <label for="prc_venda" class="form-label">Preço</label>
                                            <div class="input-group ">
                                                <span class="input-group-text" id="basic-addon1">R$</span>
                                                <input type="number " step="any" class="form-control " placeholder="0.00" <?php if (!empty($form_id) and $altera_preco == "N") {
                                                                                                                                echo "disabled";
                                                                                                                            } ?> onchange="preco_venda_sugerido()" id="prc_venda" name="prc_venda" value="">
                                                <button class="btn btn-info bnt-sm tabela_preco" type="button">Tabela</button>
                                            </div>
                                        </div>
                                        <div class=" col-6 col-md-2  mb-2">
                                            <label for="prc_custo" class="form-label">Custo</label>
                                            <div class="input-group ">
                                                <span class="input-group-text" id="basic-addon1">R$</span>
                                                <input type="number" step="any" class="form-control " placeholder="0.00" <?php if (!empty($form_id) and $altera_preco == "N") {
                                                                                                                                echo "disabled";
                                                                                                                            } ?> onchange="preco_venda_sugerido()" id="prc_custo" name="prc_custo" value="">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-2  mb-2">
                                            <label for="margem_lucro" class="form-label">Lucro</label>
                                            <div class="input-group ">
                                                <span class="input-group-text" id="basic-addon1">%</span>
                                                <input type="number" step="any" class="form-control " placeholder="0.00" onchange="preco_venda_sugerido()" id="margem_lucro" name="margem_lucro" value="">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-2  mb-2">
                                            <label for="prc_venda_sug" class="form-label">Preço Sugerido</label>
                                            <div class="input-group ">
                                                <span class="input-group-text" id="basic-addon1">R$</span>
                                                <input type="text" disabled class="form-control " placeholder="0.00" id="prc_venda_sug" name="prc_venda_sug" value="">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6 col-md-2   mb-2">
                                            <label for="prc_promocao" class="form-label">Preço Promocional</label>
                                            <div class="input-group ">
                                                <span class="input-group-text" id="basic-addon1">R$</span>
                                                <input type="number " step="any" class="form-control " placeholder="0.00" <?php if (!empty($form_id) and $altera_preco == "N") {
                                                                                                                                echo "disabled";
                                                                                                                            } ?> id="prc_promocao" name="prc_promocao" value="">
                                            </div>
                                        </div>
                                        <div class="col-6 col-md-2   mb-2">
                                            <label for="data_valida_promocao" class="form-label">Promoção válida</label>
                                            <input type="date" class="form-control " <?php if (!empty($form_id) and $altera_preco == "N") {
                                                                                            echo "disabled";
                                                                                        } ?> id="data_valida_promocao" name="data_valida_promocao" value="">
                                        </div>
                                        <div class="col-6 col-md-2   mb-2">
                                            <label for="data_validade" class="form-label">Válidade</label>
                                            <input type="date" class="form-control " id="data_validade" name="data_validade" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if (empty($valida_produto_variante)) { ?>
                            <div class="accordion-item mb-2 ">
                                <h2 class="accordion-header ">
                                    <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseEight" aria-expanded="true" aria-controls="panelsStayOpen-collapseEight">
                                        Opções de produto
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseEight" class="accordion-collapse collapse show ">
                                    <div class="accordion-body">
                                        <div class="box-tabela-variantes"></div>
                                        <div class="box-opcao-produtos mb-3" style="width: 50%;">
                                            <div class="box-item">
                                                <h6>Seu produto tem diferentes opções como tamanho, cor ou material? Adicione-as aqui.</h6>
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2 d-md-block">
                                            <button type="button" class="btn btn-sm btn-primary opcao_produto"><i class="bi bi-plus-circle"></i> Adicionar opção</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="true" aria-controls="panelsStayOpen-collapseFour">
                                    Informações fiscais
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse show ">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-6 col-md-3  mb-2">
                                            <label for="cest" class="form-label">Cest</label>
                                            <div class="input-group  mb-2">
                                                <input type="text" class="form-control inputNumber" id="cest" name="cest" placeholder="Ex. 488798456">

                                                <button class="btn btn-outline-secondary" id="modal_cest" type="button"><i class="bi bi-search"></i></button>
                                            </div>
                                        </div>
                                        <div class=" col-6 col-md-3   mb-2">
                                            <label for="ncm" class="form-label">Ncm</label>
                                            <div class="input-group c mb-2">
                                                <input type="text" class="form-control inputNumber" id="ncm" name="ncm" placeholder="Ex. 15489765">

                                                <button class="btn btn-outline-secondary" title="pesquise pelo ncm" id="modal_ncm" type="button"><i class="bi bi-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-6 col-md  mb-2">
                                            <label for="cst_icms" class="form-label">Cst Icms</label>
                                            <input class="form-control inputNumber" list="datalistOptionsIcms" id="cst_icms" name="cst_icms" placeholder="Ex. 102">
                                            <datalist id="datalistOptionsIcms">
                                                <?php while ($linha  = mysqli_fetch_assoc($consultar_icms)) {

                                                    $icms_b = ($linha['cl_icms']);
                                                    $descricao_b = utf8_encode($linha['cl_descricao']);

                                                    echo "<option  value='$icms_b'> $descricao_b</option>";
                                                } ?>
                                            </datalist>
                                        </div>



                                        <div class="col-6 col-md mb-2">
                                            <label for="cst_pis_s" class="form-label">Cst Pis S</label>
                                            <input type="text" list="datalistOptionsPisS" class="form-control inputNumber" id="cst_pis_s" name="cst_pis_s" placeholder="Ex. 01">
                                            <datalist id="datalistOptionsPisS">
                                                <?php while ($linha  = mysqli_fetch_assoc($consultar_pis_s)) {

                                                    $icms_b = ($linha['cl_pis']);
                                                    $descricao_b = utf8_encode($linha['cl_descricao']);

                                                    echo "<option  value='$icms_b'> $descricao_b</option>";
                                                } ?>
                                            </datalist>
                                        </div>

                                        <div class="col-6 col-md  mb-2">
                                            <label for="cst_pis_s" class="form-label">Cst Pis E</label>
                                            <input type="text" list="datalistOptionsPisE" class="form-control inputNumber" id="cst_pis_e" name="cst_pis_e" placeholder="Ex. 01" value="">
                                            <datalist id="datalistOptionsPisE">
                                                <?php while ($linha  = mysqli_fetch_assoc($consultar_pis_e)) {

                                                    $icms_b = ($linha['cl_pis']);
                                                    $descricao_b = utf8_encode($linha['cl_descricao']);

                                                    echo "<option  value='$icms_b'> $descricao_b</option>";
                                                } ?>
                                            </datalist>
                                        </div>


                                        <div class="col-6 col-md  mb-2">
                                            <label for="cst_cofins_s" class="form-label">Cst Cofins S</label>
                                            <input type="text" list="datalistOptionsCofinsS" class="form-control inputNumber" id="cst_cofins_s" name="cst_cofins_s" placeholder="Ex. 01" value="">
                                            <datalist id="datalistOptionsCofinsS">
                                                <?php while ($linha  = mysqli_fetch_assoc($consultar_cofins_s)) {

                                                    $icms_b = ($linha['cl_cofins']);
                                                    $descricao_b = utf8_encode($linha['cl_descricao']);

                                                    echo "<option  value='$icms_b'> $descricao_b</option>";
                                                } ?>
                                            </datalist>
                                        </div>

                                        <div class="col-6 col-md   mb-2">
                                            <label for="cst_cofins_e" class="form-label">Cst Cofins E</label>
                                            <input type="text" list="datalistOptionsCofinsE" class="form-control inputNumber" id="cst_cofins_e" name="cst_cofins_e" placeholder="Ex. 01" value="">
                                            <datalist id="datalistOptionsCofinsE">
                                                <?php while ($linha  = mysqli_fetch_assoc($consultar_cofins_e)) {
                                                    $icms_b = ($linha['cl_cofins']);
                                                    $descricao_b = utf8_encode($linha['cl_descricao']);

                                                    echo "<option  value='$icms_b'> $descricao_b</option>";
                                                } ?>
                                            </datalist>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="accordion-item mb-2 ">
                                    <h2 class="accordion-header ">
                                        <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseSix" aria-expanded="true" aria-controls="panelsStayOpen-collapseSix">
                                            Observação
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapseSix" class="accordion-collapse collapse show ">
                                        <div class="accordion-body">
                                            <div class="row ">
                                                <div class="col-md   mb-2">
                                                    <label for="observacao" class="form-label">Observação</label>
                                                    <textarea class="form-control" name="observacao" id="observacao" aria-label="With textarea"></textarea>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="accordion-item mb-2 ">
                                    <h2 class="accordion-header ">
                                        <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseSeven" aria-expanded="true" aria-controls="panelsStayOpen-collapseSeven">
                                            Marcadores
                                        </button>
                                    </h2>
                                    <div id="panelsStayOpen-collapseSeven" class="accordion-collapse collapse show ">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-9">
                                                    <div class="input-group">
                                                        <input type="search" class="form-control" list="datalistMarcadores" id="descricao_marcador" aria-describedby="marcadorHelp" placeholder="Discos 200s, Mais vendidos.. ">
                                                        <datalist id="datalistMarcadores">
                                                            <?php
                                                            $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_marcadores group by cl_descricao");
                                                            if ($resultados) {
                                                                foreach ($resultados as $linha) {
                                                                    $descricao = utf8_encode($linha['cl_descricao']);
                                                                    echo "<option value='$descricao'>";
                                                                }
                                                            }
                                                            ?>
                                                        </datalist>
                                                        <button class="btn btn-dark" type="button" title="Adicionar" id="adicionar_marcador"><i class="bi bi-plus-circle"></i> </button>
                                                    </div>
                                                    <div id="marcadorHelp" class="form-text">Informe os marcadores</div>
                                                </div>
                                                <div class="marcadores"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="row">
                
                            <div id="editor">
                                <input type="text">
                            </div>
                        </div> -->
                    <div class="">
                        <input type="hidden" class="form-control inputNumber" id="cfop_interno" name="cfop_interno" value="">
                        <input type="hidden" class="form-control inputNumber" id="cfop_externo" name="cfop_externo" value="">

                        <input type="hidden" class="form-control" id="descricao_delivery" name="descricao_delivery" value="">
                        <input type="hidden" class="form-control" id="img_produto" name="img_produto" value="">
                        <input type="hidden" class="form-control" id="descricao_ext_delivery" name="descricao_ext_delivery" value="">
                    </div>



                </form>
            </div>
        </div>
        <div class="modal_externo"></div>
    </div>
</div>
<?php include '../../../funcao/funcaojavascript.jar'; ?>

<script src="js/funcao.js"></script>
<script src="js/estoque/produto/produto_tela.js"></script>