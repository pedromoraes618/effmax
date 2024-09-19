<?php
include "../../../modal/estoque/produto/gerenciar_produto.php";
?>

<div class="modal fade" id="modal_produto" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl   ">
        <div class="modal-content ">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Produto</h1>
                <button type="button" class="btn-close btn-closer" data-bs-dismiss="modal" aria-label="Close"></button>
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
                    <input type="hidden" id="codigo_nf" name="codigo_nf" value="">
                    <div class="row  mb-2">
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                            <button type="submit" id="button_form" class="btn btn-sm btn-success"></button>
                            <?php if ($form_id != "") {
                                echo  "<button type='button' id='clonar' class='btn btn-sm btn-info'>Clonar</button>";
                            } ?>
                            <button type="button" class="btn btn-sm btn-warning modal_anexo"><i class="bi bi-file-earmark"></i> Anexo</button>

                            <button type="button" class="btn btn-sm btn-secondary btn-closer" data-bs-dismiss="modal">Fechar</button>
                        </div>
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
                                        <div class="row">
                                            <?php if ($form_id != "") {
                                                echo  " <div class='col-md-2'>
                                                <label for='codigo' class='form-label'>Código</label>
                                                <input type='text' disabled class='form-control' id='codigo' name='codigo' value='$form_id'></div>";
                                            } ?>
                                            <div class="col-md mb-2">
                                                <label for="codigo_barras" class="form-label">Código de barras</label>
                                                <input type="text" class="form-control" id="codigo_barras" name="codigo_barras" value="">
                                            </div>

                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-7  mb-2">
                                                <label for="descricao" class="form-label">Titulo *</label>
                                                <input type="text" class="form-control" id="descricao" name="descricao" value="" aria-describedby="tituloHelp" placeholder="Insira o nome do produto">
                                                <div id="tituloHelp" class="form-text">Informe ex. artista, camisa, objeto..</div>
                                            </div>
                                            <div class="col-md-5   mb-2">
                                                <label for="referencia" class="form-label">Referência *</label>
                                                <input type="text" class="form-control" id="referencia" name="referencia" value="">
                                                <div id="tituloHelp" class="form-text">Informe ex. banda, sku..</div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-2 mb-2">
                                                <label for="tipoProduto" class="form-label">Tipo <span class="d-inline-block BD-" tabindex="0" data-bs-toggle="popover" data-bs-placement="top" data-bs-trigger="hover focus" data-bs-content="Informe o tipo do produto para definir a visilibidade do produto nos pontos de vendas.">
                                                        <i class="bi bi-info-circle"></i>
                                                    </span></label>
                                                <select name="tipoProduto" class="form-select chosen-select" id="tipoProduto">
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
                                            <div class="col-md-3  mb-2">
                                                <label for="grupo_estoque" class="form-label">Categoria *</label>
                                                <select name="grupo_estoque" title="ao selecionar a categoria, os 
                                                campos serão preenchidos automaticamente, para desativar essa funcionalidade verifique com o suporte"
                                                    class="select2-modal chosen-select" id="grupo_estoque">
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
                                            <div class="col-md-4   mb-2">
                                                <label for="fabricante" class="form-label">Marca <span class="d-inline-block BD-" tabindex="0" data-bs-toggle="popover" data-bs-placement="top" data-bs-trigger="hover focus" data-bs-content="Inserir um nome de marca/fabricante pode ajudar a melhorar a visibilidade do seu site nos motores de busca.">
                                                        <i class="bi bi-info-circle"></i>
                                                    </span>
                                                </label>
                                                <input type="text" class="form-control" id="fabricante" name="fabricante" value="" placeholder="Insira o nome da marca">

                                            </div>
                                            <div class="col-md-3 mb-2">
                                                <label for="unidade_md" class="form-label">Und *</label>
                                                <select name="unidade_md" class="form-select chosen-select" id="unidade_md">
                                                    <option value="0">Selecione..</option>
                                                    <?php
                                                    $resultados = consulta_linhas_tb($conecta, 'tb_unidade_medida');
                                                    if ($resultados) {
                                                        foreach ($resultados as $linha) {
                                                            $id_und = $linha['cl_id'];
                                                            $descricao_und = utf8_encode($linha['cl_descricao']);
                                                            $sigla_und = utf8_encode($linha['cl_sigla']);

                                                            echo "<option  value='$id_und'> $descricao_und - $sigla_und </option>";
                                                        }
                                                    }
                                                    ?>
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
                                    Informações detalhadas
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show ">
                                <div class="accordion-body">
                                    <div class="row mb-5">
                                        <div class="col-md mb-2">
                                            <div id="descricao_completa"></div>
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
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="true" aria-controls="panelsStayOpen-collapseThree">
                                    Estoque
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show ">
                                <div class="accordion-body">
                                    <div class="row d-flex  align-items-end">
                                        <div class="col-md-2 mb-2">
                                            <label for="estoque" class="form-label">Estoque</label>
                                            <input type="number" step="any" <?php if (!empty($form_id) and $altera_estoque == "N") {
                                                                                echo "disabled";
                                                                            } ?> class="form-control" id="estoque" name="estoque" value="">
                                        </div>
                                        <div class="col-md-2 mb-2">
                                            <label for="peso_produto" class="form-label">Peso</label>
                                            <div class="input-group">
                                                <span class="input-group-text">KG</span>
                                                <input type="number" step="any" class="form-control" id="peso_produto" name="peso_produto" value="" placeholder="0.0">
                                            </div>
                                        </div>

                                        <div class="col-md-2  mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tipo" id="tipo_nacional" checked value="NACIONAL">
                                                <label class="form-check-label" for="tipo_nacional">
                                                    Nacional
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tipo" id="tipo_internacional" value="INTERNACIONAL">
                                                <label class="form-check-label" for="tipo_internacional">
                                                    Internacional
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-2  mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="condicao" id="condicao_novo" checked value="NOVO">
                                                <label class="form-check-label" for="condicao_novo">
                                                    Novo
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="condicao" id="condicao_usado" value="USADO">
                                                <label class="form-check-label" for="condicao_usado">
                                                    Usado
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-md-1  mb-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" checked>
                                                <label class="form-check-label" for="status">Ativo</label>
                                            </div>
                                        </div>
                                        <div class="col-md-auto  mb-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="destaque" name="destaque">
                                                <label class="form-check-label" for="destaque">Destaque</label>
                                            </div>
                                        </div>
                                        <div class="col-md-auto  mb-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="fixo" name="fixo">
                                                <label class="form-check-label" for="fixo">Fixo <span class="d-inline-block BD-" tabindex="0" data-bs-toggle="popover" data-bs-placement="top" data-bs-trigger="hover focus" data-bs-content="Fixe o produto para destacá-lo ainda mais em seu catálogo.">
                                                        <i class="bi bi-info-circle"></i>
                                                    </span></label>
                                            </div>
                                        </div>
                                        <!-- <div class="col-md-1  mb-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" id="lancamento" name="lancamento" checked>
                                <label class="form-check-label" for="lancamento">Lançamento?</label>
                            </div>
                        </div> -->
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="true" aria-controls="panelsStayOpen-collapseFour">
                                    Valores
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse show ">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-md-2   mb-2">
                                            <label for="prc_venda" class="form-label">Preço</label>
                                            <div class="input-group ">
                                                <span class="input-group-text" id="basic-addon1">R$</span>
                                                <input type="number" step="any" <?php if (!empty($form_id) and $altera_preco == "N") {
                                                                                    echo "disabled";
                                                                                } ?> class="form-control" onchange="preco_venda_sugerido()" id="prc_venda" name="prc_venda" placeholder="0.0" value="">
                                            </div>
                                            <!-- <button class="btn btn-info bnt-sm tabela_preco" type="button">Tabela</button> -->

                                        </div>
                                        <div class="col-md-2   mb-2">

                                            <label for="prc_custo" class="form-label">Custo</label>
                                            <div class="input-group ">
                                                <span class="input-group-text" id="basic-addon1">R$</span>
                                                <input type="number" step="any" <?php if (!empty($form_id) and $altera_preco == "N") {
                                                                                    echo "disabled";
                                                                                } ?> class="form-control" onchange="preco_venda_sugerido()" id="prc_custo" name="prc_custo" placeholder="0.0" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-2  mb-2">
                                            <label for="margem_lucro" class="form-label">Lucro</label>
                                            <div class="input-group ">
                                                <span class="input-group-text" id="basic-addon1">%</span>
                                                <input type="number" step="any" class="form-control " onchange="preco_venda_sugerido()" id="margem_lucro" name="margem_lucro" placeholder="0.0" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-2   mb-2">
                                            <label for="prc_venda_sug" class="form-label">Preço Sugerido</label>
                                            <div class="input-group ">
                                                <span class="input-group-text" id="basic-addon1">R$</span>
                                                <input type="text" disabled class="form-control " id="prc_venda_sug" name="prc_venda_sug" placeholder="0.0" value="">
                                            </div>
                                        </div>

                                        <div class="col-md-2   mb-2">
                                            <label for="prc_promocao" class="form-label">Preço Promocional</label>
                                            <div class="input-group ">
                                                <span class="input-group-text" id="basic-addon1">R$</span>
                                                <input type="number" step="any" <?php if (!empty($form_id) and $altera_preco == "N") {
                                                                                    echo "disabled";
                                                                                } ?> class="form-control" id="prc_promocao" name="prc_promocao" placeholder="0.0" value="">
                                            </div>
                                        </div>
                                        <div class=" col-md-2   mb-2">
                                            <label for="data_valida_promocao" class="form-label">Promoção Válida</label>
                                            <input type="date" class="form-control " <?php if (!empty($form_id) and $altera_preco == "N") {
                                                                                            echo "disabled";
                                                                                        } ?> id="data_valida_promocao" name="data_valida_promocao" value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="true" aria-controls="panelsStayOpen-collapseFive">
                                    Informações fiscais
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse show ">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class=" col-md-3   mb-2">
                                            <label for="cest" class="form-label">Cest</label>
                                            <div class="input-group  mb-3">
                                                <input type="text" class="form-control inputNumber" id="cest" name="cest" placeholder="Ex. 488798456">

                                                <button class="btn btn-outline-secondary" id="modal_cest" type="button"><i class="bi bi-search"></i></button>
                                            </div>
                                        </div>
                                        <div class=" col-md-3   mb-2">
                                            <label for="ncm" class="form-label">Ncm</label>
                                            <div class="input-group c mb-3">
                                                <input type="text" class="form-control inputNumber" id="ncm" name="ncm" placeholder="Ex. 15489765">

                                                <button class="btn btn-outline-secondary" title="pesquise pelo ncm" id="modal_ncm" type="button"><i class="bi bi-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md   mb-2">
                                            <label for="cst_icms" class="form-label">Cst Icms</label>
                                            <input class="form-control inputNumber" list="datalistOptionsIcms" id="cst_icms" name="cst_icms" placeholder="Ex. 102">
                                        </div>

                                        <div class=" col-md   mb-2">
                                            <label for="cst_pis_s" class="form-label">Cst Pis S</label>
                                            <input type="text" list="datalistOptionsPisS" class="form-control inputNumber" id="cst_pis_s" name="cst_pis_s" placeholder="Ex. 01" value="">
                                        </div>

                                        <div class=" col-md   mb-2">
                                            <label for="cst_pis_s" class="form-label">Cst Pis E</label>
                                            <input type="text" list="datalistOptionsPisE" class="form-control inputNumber" id="cst_pis_e" name="cst_pis_e" placeholder="Ex. 01" value="">
                                        </div>


                                        <div class="col-md   mb-2">
                                            <label for="cst_cofins_s" class="form-label">Cst Cofins S</label>
                                            <input type="text" list="datalistOptionsCofinsS" class="form-control inputNumber" id="cst_cofins_s" name="cst_cofins_s" placeholder="Ex. 01" value="">
                                        </div>

                                        <div class="col-md   mb-2">
                                            <label for="cst_cofins_e" class="form-label">Cst Cofins E</label>
                                            <input type="text" list="datalistOptionsCofinsE" class="form-control inputNumber" id="cst_cofins_e" name="cst_cofins_e" placeholder="Ex. 01" value="">
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
                                            <div class="row">
                                                <div class="col-md">
                                                    <textarea class="form-control" rows="4" name="observacao" id="observacao" aria-label="With textarea" placeholder="Observação interna do produto ex. atualizar código de barras."></textarea>
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
                </form>
            </div>
            <div class="modal_externo"></div>
        </div>
    </div>
</div>

<?php include '../../../funcao/funcaojavascript.jar'; ?>

<!-- Initialize Quill editor -->
<script>
    // var toolbarOption = [
    //     ["bold", "italic", "underline", "strike"],

    // ]
    var quill = new Quill('#descricao_completa', {
        theme: 'snow',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                [{
                    'script': 'sub'
                }, {
                    'script': 'super'
                }],
                [{
                    'indent': '-1'
                }, {
                    'indent': '+1'
                }],
                [{
                    'direction': 'rtl'
                }],
                [{
                    'size': ['small', false, 'large', 'huge']
                }],
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                [{
                    'color': []
                }, {
                    'background': []
                }],
                ['image', 'code-block', 'link'],
                [{
                    'align': []
                }],
                ['clean'],

            ],
        },
        table: true, // Habilita o módulo de tabela
    });
</script>

<script src="js/funcao.js"></script>
<!-- <script src="js/configuracao/users/user_logado.js"></script> -->
<script src="js/estoque/produto_ecommerce/produto_tela.js"></script>
<!-- <script src="js/estoque/produto/funcao/consultar.js"></script> -->
<!-- 


<script src="js/estoque/produto/cadastrar_produto.js"></script> -->