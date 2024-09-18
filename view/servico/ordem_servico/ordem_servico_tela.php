<?php
include "../../../modal/servico/ordem_servico/gerenciar_ordem_servico.php";
?>

<div class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-hidden="true" id="modal_ordem_servico" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Ordem Serviço</h1>
                <button type="button" class="btn-close btn-fechar" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form action="" id="ordem_servico">
                    <input type="hidden" id="id" name="id" value="<?= $form_id; ?>">
                    <div class="title mb-2">
                        <label class="form-label sub-title"></label>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end btn-acao">
                        <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                        <button type="button" id="cancelar" class="btn btn-sm btn-danger"><i class="bi bi-check-all"></i> Cancelar</button>

                        <button type="button" id="btn_valores" class="btn btn-sm btn-dark modal_resumo_valores"><i class="bi bi-eye-fill"></i> Detalhes</button>

                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm  btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Documentos
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item documento_recibo" href="#">Recibo cliente</a></li>
                                <li><a class="dropdown-item documento_pdf" href="#">Pdf resumo</a></li>
                                <li><a class="dropdown-item documento_pdf_detalhado" href="#">Pdf detalhado</a></li>
                            </ul>
                        </div>
                        <button type="button" id="btn_valores" class="btn btn-sm btn-warning modal_anexo"><i class="bi bi-file-earmark"></i> Anexo</button>
                        <button type="button" class="btn btn-sm btn-secondary btn-fechar" data-bs-dismiss="modal">Fechar</button>
                    </div>
                    <div class="row  mb-2">
                        <div class="col-md-auto  mb-2">
                            <label for="data_abertura" class="form-label">Data abertura</label>
                            <input type="datetime-local" disabled id="data_abertura" class="form-control" value="<?php echo $data; ?>">
                        </div>
                        <div class="col-md-2  mb-2">
                            <label for="numero_ordem" class="form-label">Nº documento</label>
                            <input type="text" disabled id="numero_ordem" class="form-control" value="">
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
                                    <div class="row mb-2">
                                        <div class="col-md  mb-2">
                                            <label for="data_abertura" class="form-label">Atendente *</label>
                                            <select id="atendente" class="select2-modal chosen-select" name="atendente">
                                                <option value="0">Selecione..</option>
                                                <?php
                                                $resultados = consulta_linhas_tb_filtro($conecta, 'tb_users', "cl_ativo", "1");
                                                if ($resultados) {
                                                    foreach ($resultados as $linha) {
                                                        $id = $linha['cl_id'];
                                                        $atendente = utf8_encode($linha['cl_usuario']);
                                                        if ($id == $usuario_id) {
                                                            $selected = "selected";
                                                        } else {
                                                            $selected = null;
                                                        }
                                                        echo "<option $selected value='$id'>$atendente</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md mb-2">
                                            <label for="forma_pagamento" class="form-label">Forma Pagamento *</label>
                                            <select id="forma_pagamento" class="select2-modal  chosen-select select2" style="width: 100%;" name="forma_pagamento">
                                                <option value="0">Selecione..</option>
                                                <?php
                                                $resultados = consulta_linhas_tb_filtro($conecta, 'tb_forma_pagamento', 'cl_ativo', 'S');
                                                if ($resultados) {
                                                    foreach ($resultados as $linha) {
                                                        $id = $linha['cl_id'];
                                                        $descricao = utf8_encode($linha['cl_descricao']);

                                                        echo "<option value='$id'>$descricao</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md mb-2">
                                            <label for="tipo_servico" class="form-label">Tipo Serviço *</label>
                                            <select id="tipo_servico" class="select2-modal chosen-select chosen-select" name="tipo_servico">
                                                <option value="0">Selecione..</option>
                                                <?php
                                                $resultados = consulta_linhas_tb($conecta, 'tb_tipo_servico_os');
                                                if ($resultados) {
                                                    foreach ($resultados as $linha) {
                                                        $id = $linha['cl_id'];
                                                        $descricao = utf8_encode($linha['cl_descricao']);

                                                        echo "<option value='$id'>$descricao</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-md mb-2">
                                            <label for="status" class="form-label">Status *</label>
                                            <select id="status" class="select2-modal chosen-select chosen-select select2" style="width: 100%;" name="status">
                                                <option value="0">Selecione..</option>
                                                <?php
                                                $resultados = consulta_linhas_tb($conecta, 'tb_status_os');
                                                if ($resultados) {
                                                    foreach ($resultados as $linha) {
                                                        $id = $linha['cl_id'];
                                                        $descricao = utf8_encode($linha['cl_descricao']);

                                                        echo "<option value='$id'>$descricao</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-2">
                                        <div class="col-md  mb-2">
                                            <label for="parceiro_id" class="form-label">Cliente *</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" readonly id="parceiro_descricao" name="parceiro_descricao" placeholder="Pesquise pelo Cliente/Fornecedor" aria-label="Recipient's username" aria-describedby="button-addon2">
                                                <input type="hidden" class="form-control" name="parceiro_id" id="parceiro_id" value="">
                                                <button class="btn btn-secondary" type="button" name="modal_parceiro" id="modal_parceiro">Pesquisar</button>
                                            </div>
                                        </div>
                                        <div class="col-md-3  mb-2">
                                            <label for="contato" class="form-label">Contato</label>
                                            <input type="text" class="form-control" name="contato" id="contato" placeholder="988854897..">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
                                    Equipamento
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse  <?php echo $tipo_ordem == 1 ? 'collapse show' : 'collapse' ?> ">
                                <div class="accordion-body">
                                    <div class="row mb-2 d-flex align-items-end">
                                        <div class="col-md  mb-2">
                                            <label for="equipamento" class="form-label">Equipamento *</label>
                                            <input type="text" class="form-control" name="equipamento" id="equipamento" placeholder="Matelo..">
                                        </div>
                                        <div class="col-md-3  mb-2">
                                            <label for="numero_serie" class="form-label">Nº série</label>
                                            <input type="text" class="form-control" name="numero_serie" id="numero_serie" placeholder="1568751..">
                                        </div>
                                        <div class="col-md-2  mb-2">
                                            <label for="numero_caixa" class="form-label">Nº caixa</label>
                                            <input type="text" class="form-control" name="numero_caixa" id="numero_caixa" placeholder="4897..">
                                        </div>
                                        <div class="col-md  mb-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" id="pedido_pecas" name="pedido_pecas">
                                                <label class="form-check-label" for="pedido_pecas">Pedido de Peça já Realizado?</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-2 d-flex align-items-end">
                                        <div class="col-md  mb-2">
                                            <label for="defeito_informado" class="form-label">Defeito informado</label>
                                            <input type="text" class="form-control" name="defeito_informado" id="defeito_informado" placeholder="Parou de funcionar..">
                                        </div>
                                        <div class="col-md  mb-2">
                                            <label for="defeito_constatado" class="form-label">Defeito constatado</label>
                                            <input type="text" class="form-control" name="defeito_constatado" disabled id="defeito_constatado" placeholder="">
                                        </div>
                                        <!-- <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="" name="ordem_fechada" id="ordem_fechada">
                                                <label class="form-check-label" for="ordem_fechada">
                                                    Ordem de Serviço fechada?
                                                </label>
                                            </div>
                                        </div> -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="true" aria-controls="panelsStayOpen-collapseThree">
                                    Detalhe do serviço
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseThree" class="accordion-collapse  <?php echo $tipo_ordem == 1 ? 'collapse' : 'collapse.show' ?>">
                                <div class="accordion-body">
                                    <div class="row mb-2">
                                        <div class="col-md  mb-2">
                                            <label for="descricao_obra" class="form-label">Descrição</label>
                                            <input type="text" class="form-control" name="descricao_obra" id="descricao_obra" placeholder="Ex Manutenção de telhado, Conserto de maquinas..">
                                        </div>

                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-md-5  mb-2">
                                            <label for="local_obra" class="form-label">Local</label>
                                            <input type="text" class="form-control" name="local_obra" id="local_obra" placeholder="Ex Rua.. Estado..">
                                        </div>
                                        <div class="col-md mb-2">
                                            <label for="prazo_entrega" class="form-label">Prazo entrega</label>
                                            <input type="date" class="form-control" name="prazo_entrega" id="prazo_entrega" placeholder="">
                                        </div>
                                        <div class="col-md mb-2">
                                            <label for="entrega_obra" class="form-label">Entrega</label>
                                            <input type="date" class="form-control" name="entrega_obra" id="entrega_obra" placeholder="">
                                        </div>
                                        <div class="col-md  mb-2">
                                            <label for="valor_fechado" class="form-label">Valor fechado </label>
                                            <div class="input-group mb-2">
                                                <span class="input-group-text">R$</span>
                                                <input type="text" class="form-control" name="valor_fechado" id="valor_fechado" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="true" aria-controls="panelsStayOpen-collapseFour">
                                    Documentos
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseFour" class="accordion-collapse  <?php echo $tipo_ordem != 1 ? 'collapse' : 'collapse.show' ?>">
                                <div class="accordion-body">
                                    <div class="row mb-2">
                                        <div class="col-md  mb-2">
                                            <label for="nf_garantia_fabrica" class="form-label">NF Garantia fábrica</label>
                                            <input type="text" class="form-control" name="nf_garantia_fabrica" id="nf_garantia_fabrica" placeholder="NFE 41568..">
                                        </div>
                                        <div class="col-md mb-2">
                                            <label for="data_garantia_fabrica" class="form-label">Data Garantia fábrica</label>
                                            <input type="date" class="form-control" name="data_garantia_fabrica" id="data_garantia_fabrica" placeholder="">
                                        </div>
                                        <div class="col-md  mb-2">
                                            <label for="nf_garantia_loja" class="form-label">NF Garantia loja</label>
                                            <input type="text" class="form-control" name="nf_garantia_loja" id="nf_garantia_loja" placeholder="NFE 45878..">
                                        </div>
                                        <div class="col-md mb-2">
                                            <label for="data_garantia_loja" class="form-label">Data garantia Loja</label>
                                            <input type="date" class="form-control" name="data_garantia_loja" id="data_garantia_loja" placeholder="">
                                        </div>
                                        <div class="col-md mb-2">
                                            <label for="validade_garantia_loja" class="form-label">Validade garantia (Dias)</label>
                                            <input type="number" class="form-control" name="validade_garantia_loja" id="validade_garantia_loja" placeholder="30">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md  mb-2">
                            <label for="observacao" class="form-label">Observação</label>
                            <textarea class="form-control" name="observacao" id="observacao" aria-label="With textarea"></textarea>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal_externo"></div>
</div>


<script src="js/servico/ordem_servico/ordem_servico_tela.js"></script>