<?php
include "../../../../modal/ecommerce/configuracao/gerenciar_configuracao.php";
?>

<div class="container">
    <div class="mb-3">
        <h4 class="fw-semibold">Site</h4>
        <span> Personalize seu site com diversas opções para deixá-lo estilizado de várias maneiras</span>
    </div>

    <div class="accordion " id="accordionPanelsStayOpenExample">
        <div class="accordion-item mb-2 ">
            <h2 class="accordion-header ">
                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                    Visualização de baner no topo do site
                </button>
            </h2>
            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show ">
                <div class="accordion-body">
                    <div class="accordion-body p-0">
                        <div class="mb-2">
                            <form id="baner_topo">
                                <div class="row mb-2">
                                    <div class="col-md">
                                        <label class="form-label" for="baner_topo_status">Selecione se o banner estará ativo no site.</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="baner_topo_status" name="baner_topo_status">
                                            <label class="form-check-label" for="baner_topo_status">Ativo</label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <form id="baner_topo_img">
                                <div class="row d-flex align-items-end mb-2">
                                    <div class="col-md-auto">
                                        <div class="input-group">
                                            <input class="form-control form-control-sm " aria-describedby="banerHelp" type="file" id="file-input-baner-topo" name="file-input-baner-topo">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="link_redirect" class="form-control form-control-sm " placeholder="Link para redirecionar">
                                    </div>
                                </div>
                            </form>
                            <div id="banerHelp" class="form-text">A imagem deve estar no formato .png e ter um tamanho máximo de 900 KB.</div>
                        </div>


                        <div class=" d-flex group-baner">
                            <?php
                            $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_baner_delivery where cl_inicial ='1' order by cl_ordem asc");
                            if ($resultados) {
                                foreach ($resultados as $linha) {
                                    $id = $linha['cl_id'];
                                    $arquivo = $linha['cl_arquivo'];
                            ?>

                                    <div class="position-relative draggable draggable-baner-topo" id="<?= $id; ?>">
                                        <img style=" height: 150px;" src="img/ecommerce/baner/<?= $arquivo; ?>" width="300" class="img-thumbnail img-layout-loja" alt="">
                                        <div class="z-1 position-absolute bg-light  top-0 end-0 p-2 rounded excluir_baner_topo" id="<?= $id; ?>" style="cursor: pointer;" title="Remover">
                                            <i class="bi bi-x-circle-fill "></i>
                                        </div>
                                    </div>

                            <?php }
                            } ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item mb-2">
            <h2 class="accordion-header">
                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
                    Visualização de cupons no topo do site
                </button>
            </h2>
            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    <div class="accordion-body p-0">
                        <div class="mb-2">
                            <form id="baner_topo_cupom">
                                <div class="row mb-2">
                                    <div class="col-md">
                                        <label class="form-label" for="baner_topo_cupom_status">Selecione se o banner estará ativo no site.</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="baner_topo_cupom_status" name="baner_topo_cupom_status">
                                            <label class="form-check-label" for="baner_topo_cupom_status">Ativo</label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item mb-2">
            <h2 class="accordion-header">
                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="true" aria-controls="panelsStayOpen-collapseThree">
                    Visualização de seções do site
                </button>
            </h2>
            <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    <div class="accordion-body p-0">
                        <div class="mb-2">
                            <form id="secao_site" style="max-height: 800px;">
                                <div class="row mb-2">

                                    <div class="col-md-5">
                                        <label class="form-label" for="limite_produto_secao">Limite de produtos por seção *</label>
                                        <input class="form-control" type="number" aria-describedby="limiteProdutoSecaoHelp" id="limite_produto_secao" name="limite_produto_secao" placeholder="ex. 10">
                                        <div id="limiteProdutoSecaoHelp" class="form-text">O limite deve ser maior que 4</div>

                                    </div>

                                    <div class="col-md">
                                        <label class="form-label" for="limite_produto_pagina">Limite de produtos por página (paginação por categorias). *</label>
                                        <input class="form-control" type="number" aria-describedby="limiteProdutoPaginHelp" id="limite_produto_pagina" name="limite_produto_pagina" placeholder="ex. 7">
                                        <div id="limiteProdutoPaginHelp" class="form-text">O limite deve ser maior que 4</div>

                                    </div>
                                </div>

                                <hr>
                                <div class="row mb-2">
                                    <div class="col-md-5">
                                        <label class="form-label" for="secao_novidade_status">Selecione se a seção <b>Novidades</b> estará ativo no site.</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="secao_novidade_status" name="secao_novidade_status">
                                            <label class="form-check-label" for="secao_novidade_status">Ativo</label>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label" for="titulo_secao_novidade">Informe o titulo dessa seção *</label>
                                        <input class="form-control" type="text" id="titulo_secao_novidade" name="titulo_secao_novidade" placeholder="ex. Novidades">
                                    </div>
                                </div>
                                <hr>
                                <div class="row mb-2">
                                    <div class="row mb-2">
                                        <div class="col-md-5">
                                            <label class="form-label" for="secao_desconto_status">Selecione se a seção <b>Desconto</b> estará ativo no site.</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="secao_desconto_status" name="secao_desconto_status">
                                                <label class="form-check-label" for="secao_desconto_status">Ativo</label>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label" for="titulo_secao_desconto">Informe o titulo dessa seção *</label>
                                            <input class="form-control" type="text" id="titulo_secao_desconto" name="titulo_secao_desconto" placeholder="ex. Ofertas">
                                        </div>
                                    </div>

                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-5">
                                        <label class="form-label" for="secao_destaque_status">Selecione se a seção <b>Destaque</b> estará ativo no site.</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="secao_destaque_status" name="secao_destaque_status">
                                            <label class="form-check-label" for="secao_destaque_status">Ativo</label>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label" for="titulo_secao_destaque">Informe o titulo dessa seção *</label>
                                        <input class="form-control" type="text" id="titulo_secao_destaque" name="titulo_secao_destaque" placeholder="ex. Destaques">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-5">
                                        <label class="form-label" for="secao_mais_buscado_status">Selecione se a seção <b>Produtos mais buscados</b> estará ativo no site.</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="secao_mais_buscado_status" name="secao_mais_buscado_status">
                                            <label class="form-check-label" for="secao_mais_buscado_status">Ativo</label>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label" for="titulo_secao_prd_mais_buscado">Informe o titulo dessa seção *</label>
                                        <input class="form-control" type="text" id="titulo_secao_prd_mais_buscado" name="titulo_secao_prd_mais_buscado" placeholder="ex. Produtos mais buscados">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-md-5">
                                        <label class="form-label" for="secao_catalogo_status">Selecione se a seção <b>Catálogo</b> estará ativo no site.</label>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" checked disabled type="checkbox" role="switch" id="secao_catalogo_status" name="secao_catalogo_status">
                                            <label class="form-check-label" for="secao_catalogo_status">Ativo</label>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label" for="titulo_secao_catalogo">Informe o titulo dessa seção *</label>
                                        <input class="form-control" type="text" id="titulo_secao_catalogo" name="titulo_secao_catalogo" placeholder="ex. Nosso acervo">
                                    </div>
                                </div>

                                <div class="row mb-2 border p-2">
                                    <div class="col-md-7">
                                        <div class="col-md mb-2">
                                            <label class="form-label" for="secao_inscreva_se_status">Selecione se a seção <b>Inscreva-se</b> estará ativo no site.</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="secao_inscreva_se_status" name="secao_inscreva_se_status">
                                                <label class="form-check-label" for="secao_inscreva_se_status">Ativo</label>
                                            </div>
                                        </div>
                                        <div class="col-md mb-2">
                                            <label class="form-label" for="titulo_secao_inscreva_se">Informe o titulo da seção <b> inscreva-se </b> dessa seção</label>
                                            <input class="form-control" type="text" id="titulo_secao_inscreva_se" name="titulo_secao_inscreva_se" placeholder="ex. Inscrevas-se e ganhe cupons especiais">
                                        </div>
                                    </div>
                                    <div class="col-md">
                                        <div class="d-flex justify-content-center align-items-center">
                                            <div><img width="100" src="img/ecommerce/assets/persona_login.svg" alt=""></div>
                                            <div class="ms-md-4 text-member">
                                                <p style="font-size: 0.8em;" class="text-body-highlight mb-0">Quer ter a melhor <span class="fw-semibold">experiência do cliente?</span></p>
                                                <p style="font-size: 0.9em; max-width: 450px;" class="fw-medium text-secao-inscreva_se"></p>
                                                <button type="button" class="btn btn-sm btn-danger register 
                fw-semibold rounded text-center text-md-start border-0">Inscreva-se</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row  mb-2">
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-start ">
                                        <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item mb-2">
            <h2 class="accordion-header">
                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="true" aria-controls="panelsStayOpen-collapseFour">
                    Baner por seção ou categoria
                </button>
            </h2>
            <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    <div class="accordion-body p-0">
                        <div class="mb-2">
                            <div class="row mb-2">
                                <div class="col-md">
                                    <form id="baner_secao">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input status_baner_secao" type="checkbox" role="switch" id="status_baner_secao" name="status_baner_secao">
                                            <label class="form-check-label" for="status_baner_secao">Defina se o baner de seção estará ativo no site</label>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-2 d-flex align-items-top">
                                <div class="col-md-5">
                                    <label class="form-label">Adicione um baner para a seção de <b>Novidades</b>.</label>
                                    <form id="baner_section_new">
                                        <div class="row d-flex align-items-end mb-2">
                                            <div class="col-md-12 mb-2">
                                                <div class="input-group">
                                                    <input class="form-control form-control-sm file-input-baner-section" data-section='new' aria-describedby="banerHelp" type="file" id="file-input-baner-section" name="file-input-baner-section">
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-5">
                                    <?php
                                    $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_baner_delivery where cl_secao ='new'");
                                    if ($resultados) {
                                        foreach ($resultados as $linha) {
                                            $id = $linha['cl_id'];
                                            $arquivo = $linha['cl_arquivo'];
                                    ?>
                                            <div class="position-relative draggable draggable-baner-secao mb-3" id="<?= $id; ?>">
                                                <img style="width: 100%;height:100px;" src="img/ecommerce/baner/<?= $arquivo; ?>" alt="">
                                                <div class="z-1 position-absolute bg-light  top-0 end-0 p-2 rounded excluir_baner_secao" id="<?= $id; ?>" style="cursor: pointer;" title="Remover">
                                                    <i class="bi bi-x-circle-fill "></i>
                                                </div>
                                            </div>
                                    <?php }
                                    } ?>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-2 d-flex align-items-top">
                                <div class="col-md-5">
                                    <label class="form-label">Adicione um baner para a seção de <b>Desconto</b>.</label>
                                    <form id="baner_section_discount">
                                        <div class="row d-flex align-items-end mb-2">
                                            <div class="col-md-12 mb-2">
                                                <div class="input-group">
                                                    <input class="form-control form-control-sm file-input-baner-section" data-section='discount' aria-describedby="banerHelp" type="file" id="file-input-baner-section" name="file-input-baner-section">
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-5">
                                    <?php
                                    $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_baner_delivery where cl_secao ='discount'");
                                    if ($resultados) {
                                        foreach ($resultados as $linha) {
                                            $id = $linha['cl_id'];
                                            $arquivo = $linha['cl_arquivo'];
                                    ?>
                                            <div class="position-relative draggable draggable-baner-secao mb-3" id="<?= $id; ?>">
                                                <img style="width: 100%;height:100px;" src="img/ecommerce/baner/<?= $arquivo; ?>" alt="">
                                                <div class="z-1 position-absolute bg-light  top-0 end-0 p-2 rounded excluir_baner_secao" id="<?= $id; ?>" style="cursor: pointer;" title="Remover">
                                                    <i class="bi bi-x-circle-fill "></i>
                                                </div>
                                            </div>

                                    <?php }
                                    } ?>
                                </div>
                            </div>
                            <div class="row mb-2 d-flex align-items-top">
                                <div class="col-md-5">
                                    <label class="form-label">Adicione um baner para a seção de <b>Destaque</b>.</label>
                                    <form id="baner_section_emphasis">
                                        <div class="row d-flex align-items-end mb-2">
                                            <div class="col-md-12 mb-2">
                                                <div class="input-group">
                                                    <input class="form-control form-control-sm file-input-baner-section" data-section='emphasis' aria-describedby="banerHelp" type="file" id="file-input-baner-section" name="file-input-baner-section">
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-5">
                                    <?php
                                    $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_baner_delivery where cl_secao ='emphasis'");
                                    if ($resultados) {
                                        foreach ($resultados as $linha) {
                                            $id = $linha['cl_id'];
                                            $arquivo = $linha['cl_arquivo'];
                                    ?>
                                            <div class="position-relative draggable draggable-baner-secao mb-3" id="<?= $id; ?>">
                                                <img style="width: 100%;height:100px;" src="img/ecommerce/baner/<?= $arquivo; ?>" alt="">
                                                <div class="z-1 position-absolute bg-light  top-0 end-0 p-2 rounded excluir_baner_secao" id="<?= $id; ?>" style="cursor: pointer;" title="Remover">
                                                    <i class="bi bi-x-circle-fill "></i>
                                                </div>
                                            </div>

                                    <?php }
                                    } ?>
                                </div>
                            </div>
                            <div class="row mb-2 d-flex align-items-top">
                                <div class="col-md-5">
                                    <label class="form-label">Adicione um baner para a seção de <b>Catálogo</b>.</label>
                                    <form id="baner_section_catalog">
                                        <div class="row d-flex align-items-end mb-2">
                                            <div class="col-md-12 mb-2">
                                                <div class="input-group">
                                                    <input class="form-control form-control-sm file-input-baner-section" data-section='catalog' aria-describedby="banerHelp" type="file" id="file-input-baner-section" name="file-input-baner-section">
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-5">
                                    <?php
                                    $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_baner_delivery where cl_secao ='catalog'");
                                    if ($resultados) {
                                        foreach ($resultados as $linha) {
                                            $id = $linha['cl_id'];
                                            $arquivo = $linha['cl_arquivo'];
                                    ?>
                                            <div class="position-relative draggable draggable-baner-secao mb-3" id="<?= $id; ?>">
                                                <img style="width: 100%;height:100px;" src="img/ecommerce/baner/<?= $arquivo; ?>" alt="">
                                                <div class="z-1 position-absolute bg-light  top-0 end-0 p-2 rounded excluir_baner_secao" id="<?= $id; ?>" style="cursor: pointer;" title="Remover">
                                                    <i class="bi bi-x-circle-fill "></i>
                                                </div>
                                            </div>

                                    <?php }
                                    } ?>
                                </div>
                            </div>
                            <div class="row mb-2 d-flex align-items-top">
                                <div class="col-md-5">
                                    <label class="form-label">Adicione um baner para a seção de <b>Formato</b>.</label>
                                    <form id="baner_section_format">
                                        <div class="row d-flex align-items-end mb-2">
                                            <div class="col-md-12 mb-2">
                                                <div class="input-group">
                                                    <input class="form-control form-control-sm file-input-baner-section" data-section='format' aria-describedby="banerHelp" type="file" id="file-input-baner-section" name="file-input-baner-section">
                                                </div>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-5">
                                    <?php
                                    $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_baner_delivery where cl_secao ='format'");
                                    if ($resultados) {
                                        foreach ($resultados as $linha) {
                                            $id = $linha['cl_id'];
                                            $arquivo = $linha['cl_arquivo'];
                                    ?>
                                            <div class="position-relative draggable draggable-baner-secao mb-3" id="<?= $id; ?>">
                                                <img style="width: 100%;height:100px;" src="img/ecommerce/baner/<?= $arquivo; ?>" alt="">
                                                <div class="z-1 position-absolute bg-light  top-0 end-0 p-2 rounded excluir_baner_secao" id="<?= $id; ?>" style="cursor: pointer;" title="Remover">
                                                    <i class="bi bi-x-circle-fill "></i>
                                                </div>
                                            </div>

                                    <?php }
                                    } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/ecommerce/configuracao/aba/layout.js"></script>