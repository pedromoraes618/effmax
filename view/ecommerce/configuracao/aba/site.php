<?php
include "../../../../modal/ecommerce/configuracao/gerenciar_configuracao.php";
?>

<div class="container">
    <div class="mb-3">
        <h4 class="fw-semibold">Site</h4>
        <span> Personalize seu site e configure a forma de receber pagamentos dos clientes..</span>
    </div>

    <div class="accordion " id="accordionPanelsStayOpenExample">
        <div class="accordion-item mb-2 ">
            <h2 class="accordion-header ">
                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                    Sobre o site
                </button>
            </h2>
            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show ">
                <div class="accordion-body">
                    <div class="accordion-body p-0">
                        <div class="mb-2">
                            <form id="logo_empresa">
                                <div class="row d-flex align-items-end mb-2">
                                    <div class="col-md-auto ">
                                        <img src="img/ecommerce/logo/logo.png?<?php echo time(); ?>" width="150" class="img-fluid img-thumbnail" alt="">
                                    </div>
                                    <div class="col-md-auto">
                                        <input class="form-control form-control-sm " aria-describedby="imgHelp" type="file" id="file-input" name="file-input">
                                    </div>
                                </div>
                            </form>
                            <div id="imgHelp" class="form-text">A imagem deve estar no formato .png e ter um tamanho máximo de 700 KB.</div>
                        </div>
                        <form id="site">
                            <div class="row mb-2">
                                <div class="col-md-5">
                                    <label for="nome_site">Nome do site</label>
                                    <input type="text" name="nome_site" disabled id="nome_site" placeholder="Ex. lojax" class="form-control">
                                </div>
                            </div>

                            <!-- <div class="row  mb-2">
                               <div class="d-grid gap-2 d-md-flex justify-content-md-start ">
                                    <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                                </div> 
                            </div>-->
                        </form>


                    </div>

                </div>
            </div>
        </div>
        <div class="accordion-item mb-2">
            <h2 class="accordion-header">
                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
                    Redes sociais
                </button>
            </h2>
            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    <form id="redes_sociais">
                        <div class="row mb-2">
                            <div class="col-md-7">
                                <label for="facebook"><i class="bi bi-facebook"></i> Facebook</label>
                                <input type="text" name="facebook" id="facebook" placeholder="ex. https://www.facebook.com/user123" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-7">
                                <label for="instagram"><i class="bi bi-instagram"></i> Instagram</label>
                                <input type="text" name="instagram" id="instagram" placeholder="ex. https://www.instagram.com/user123" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-7">
                                <label for="tiktok"><i class="bi bi-tiktok"></i> Tiktok</label>
                                <input type="text" name="tiktok" id="tiktok" placeholder="ex. https://www.tiktok.com/user123" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-7">
                                <label for="whatsapp"><i class="bi bi-whatsapp"></i> Whatsapp</label>
                                <input type="text" name="whatsapp" id="whatsapp" placeholder="ex. https://api.whatsapp.com/send?phone=559812345678" class="form-control">
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
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false" aria-controls="panelsStayOpen-collapseFour">
                    Sobre
                </button>
            </h2>
            <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    <form id="sobre" style="max-height: fit-content;">
                        <div class="row">
                            <div class="col-md" style="margin-bottom: 7%">
                                <label for="sobre_nos">Sobre nós</label>
                                <div id="sobre_nos"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md" style="margin-bottom: 7%">
                                <label for="apresentacao">Apresentação, um pequeno resumo da loja (rodapé)</label>
                                <div id="apresentacao"></div>
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
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                    Forma de pagamento
                </button>
            </h2>
            <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    <form id="forma_pagamento" style="max-height: fit-content;">
                        <?php
                        $resultados = consulta_linhas_tb_filtro($conecta, 'tb_forma_pagamento', 'cl_ativo_delivery', 'S');
                        if ($resultados) {
                            foreach ($resultados as $linha) {
                                $id = $linha['cl_id'];
                                $ativo = utf8_encode($linha['cl_ativo']);
                                $descricao = utf8_encode($linha['cl_descricao']);
                                $parcelamento_sem_juros = utf8_encode($linha['cl_parcelamento_sem_juros']);
                                $avista = utf8_encode($linha['cl_avista']);
                                $desconto = utf8_encode($linha['cl_desconto']);
                        ?>
                                <div class="mb-3 border p-2 small-shadow">
                                    <div class="row mb-2">
                                        <h6><?= $descricao; ?></h6>
                                        <div class="col-md">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" <?php if ($ativo == "S") {
                                                                                    echo 'checked';
                                                                                } ?> type="checkbox" role="switch" id="forma_pagamento_<?= $id; ?>" name="forma_pagamento_<?= $id; ?>">
                                                <label class="form-check-label" for="forma_pagamento_<?= $id; ?>">Ativo</label>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ($avista == "N") { ?>
                                        <div class="row">
                                            <div class="col-md-auto">
                                                <label for="parcelamento_<?= $id; ?>">Parcelamento sem juros</label>
                                                <input type="number" step="any" name="parcelamento_<?= $id; ?>" id="parcelamento_<?= $id; ?>" placeholder=" Ex. 2" class="form-control" value="<?= $parcelamento_sem_juros; ?>">
                                            </div>
                                            <div class="col-md-auto">
                                                <label for="desconto_<?= $id; ?>">Desconto</label>
                                                <div class="input-group ">
                                                    <span class="input-group-text" id="basic-addon1">%</span>
                                                    <input type="number" step="any" name="desconto_<?= $id; ?>" id="desconto_<?= $id; ?>" placeholder="Ex. 8" value="<?= $desconto; ?>" class="form-control">
                                                </div>

                                            </div>
                                        </div>
                                    <?php } else { ?>
                                        <div class="row">
                                            <div class="col-md-auto">
                                                <label for="desconto_<?= $id; ?>">Desconto</label>
                                                <div class="input-group ">
                                                    <span class="input-group-text" id="basic-addon1">%</span>
                                                    <input type="number" step="any" name="desconto_<?= $id; ?>" id="desconto_<?= $id; ?>" placeholder="Ex. 8" value="<?= $desconto; ?>" class="form-control">
                                                </div>

                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>

                        <?php
                            }
                        } ?>
                        <div class="row  mb-2">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-start ">
                                <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="accordion-item mb-2">
            <h2 class="accordion-header">
                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="true" aria-controls="panelsStayOpen-collapseFour">
                    Crie cupons personalizados
                </button>
            </h2>
            <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    <div class="accordion-body p-0">
                        <div class="mb-2">

                            <div class="row mb-2">
                                <div class="col-auto  mb-2">
                                    <div class="input-group">
                                        <span class="input-group-text">Data</span>
                                        <input type="date" class="form-control " id="data_inicial" name="data_incial" title="Data Inicial" placeholder="Data Inicial" value="<?php echo $data_inicial_ano_bd ?>">
                                        <input type="date" class="form-control " id="data_final" name="data_final" title="Data Final" placeholder="Data Final" value="<?php echo $data_lancamento; ?>">
                                    </div>
                                </div>

                                <div class="col-md-2 mb-2">
                                    <select name="status_cupom" class="form-select chosen-select" id="status_cupom">
                                        <option value="sn">Status Cupom..</option>
                                        <option value="1">Ativo</option>
                                        <option value="0">Intivo</option>
                                    </select>
                                </div>

                                <div class="col-md  mb-2">
                                    <div class="input-group">
                                        <input type="search" class="form-control" id="pesquisa_conteudo" placeholder="Tente pesquisar pelo nº do cupom ou descrição" aria-label="Recipient's username" aria-describedby="button-addon2">
                                        <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
                                    </div>
                                </div>
                                <div class="col-md-auto  d-grid gap-2 d-sm-block mb-2">
                                    <button type="button" id="adicionar_cupom" class="btn btn-dark"><i class="bi bi-plus-circle"></i> Cupom</button>
                                </div>
                            </div>

                            <div class="tabela_cupom tabela"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    var quill = new Quill('#sobre_nos', {
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
    var quill = new Quill('#apresentacao', {
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
<script src="js/ecommerce/configuracao/aba/site.js"></script>