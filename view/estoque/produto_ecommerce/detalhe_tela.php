<?php
include "../../../modal/estoque/produto/gerenciar_produto.php";
?>
<div class="modal fade" id="modal_detalhe" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header mb-2">
                <h1 class="modal-title fs-5">Detalhe</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <input type="hidden" class="form-control" id="produto_id" value="<?= $form_id; ?>">

                <div class="row mb-3">
                    <div class="col-md-6 ">
                        <div class="shadow-sm p-2 rounded">
                            <div class="row align-items-center mb-2">
                                <div class="col-4 col-md-3">
                                    <img src="<?= $img_produto; ?>" class="img-fluid img-thumbnail rounded-circle border-0" alt="">
                                </div>
                                <div class="col-8 col-md">
                                    <h6 class="mb-1"><?= $descricao; ?></h6>
                                    <P class="text-800"><?= $referencia; ?></P>
                                </div>
                            </div>
                            <hr class="mb-1">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <p class="text-muted mb-1">Carrinho</p>
                                    <small class="text-dark"><?= $qtd_carrinho; ?></small>
                                </div>
                                <div>
                                    <p class="text-muted mb-1">Favoritos</p>
                                    <small><?= $qtd_favorito; ?></small>
                                </div>
                                <div>
                                    <p class="text-muted mb-1">Visualização</p>
                                    <small><?= $qtd_visualizacao; ?></small>
                                </div>
                                <div>
                                    <p class="text-muted mb-1">Pedidos</p>
                                    <small><?= $qtd_pedidos; ?></small>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border"></div>
                        </div>
                    </div>
                </div>

                <div class="accordion " id="accordionPanelsStayOpenExample">
                    <div class="accordion-item mb-2 ">
                        <h2 class="accordion-header ">
                            <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                Perguntas dos clientes
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show ">
                            <div class="accordion-body">
                                <div class="row mb-2">
                                    <div class="col-auto  mb-2">
                                        <div class="input-group">
                                            <span class="input-group-text">Data</span>
                                            <input type="date" class="form-control " id="data_inicial" name="data_incial" title="Data Inicial" placeholder="Data Inicial" value="<?php echo $data_inicial_ano_bd ?>">
                                            <input type="date" class="form-control " id="data_final" name="data_final" title="Data Final" placeholder="Data Final" value="<?php echo $data_final_ano_bd; ?>">
                                        </div>
                                    </div>

                                    <div class="col-md  mb-2">
                                        <div class="input-group">
                                            <input type="search" class="form-control" id="pesquisa_conteudo_pergunta" placeholder="Tente pesquisar pelo produto, cliente ou a mensagem" aria-label="Recipient's username" aria-describedby="button-addon2">
                                            <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa_pergunta">Pesquisar</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="tabela table_perguntas"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal_externo"></div>
</div>
<script src="js/estoque/produto_ecommerce/detalhe_tela.js"></script>