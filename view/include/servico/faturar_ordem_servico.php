<?php
include "../../../modal/servico/ordem_servico/gerenciar_ordem_servico.php";
?>
<div class="modal fade " id="modal_faturar_ordem_servico" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border ">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Faturar ordem de serviço</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="" id="faturar">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end btn-acao">
                        <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Faturar</button>
                        <button type="button" class="btn btn-sm btn-secondary btn-fechar" data-bs-dismiss="modal">Fechar</button>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-4 mb-2">
                            <label for="recibo">Recibo</label>
                            <select id="recibo" class="select2-modal-modal chosen-select select2" name="recibo">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb_filtro($conecta, 'tb_serie', 'cl_serie_recibo', 'SIM');
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

                        <div class="col-md-4 mb-2">
                            <label for="serie_material">Documento material</label>
                            <select id="serie_material" class="select2-modal-modal chosen-select" name="serie_material">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb_filtro($conecta, 'tb_serie', 'cl_serie_fiscal', 'SIM');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = $linha['cl_id'];
                                        $descricao = utf8_encode($linha['cl_descricao']);
                                        if ($id != "18") { //serie diferente a serie de serviço
                                            echo "<option value='$id'>$descricao</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <label for="serie_servico">Documento serviço</label>
                            <select id="serie_servico" class="select2-modal-modal chosen-select" name="serie_servico">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb_filtro($conecta, 'tb_serie', 'cl_serie_fiscal', 'SIM');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = $linha['cl_id'];
                                        $descricao = utf8_encode($linha['cl_descricao']);
                                        if ($id == 18) {
                                            echo "<option value='$id' $span_selected>$descricao</option>";
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-2">
                            <label for="forma_pagamento">Forma Pagamento</label>
                            <select id="forma_pagamento" class="select2-modal-modal chosen-select select2" name="forma_pagamento">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_forma_pagamento where cl_ativo ='S'");
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
                        <div class="col-md-8">
                            <label for="atividade">Atividade realizado</label>
                            <select id="atividade" class="select2-modal-modal chosen-select select2" name="atividade">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_atividade_servico");
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = $linha['cl_id'];
                                        $servico_id = $linha['cl_servico_id'];
                                        $nome_atividade = utf8_encode($linha['cl_nome_atividade']);

                                        echo "<option value='$id'>$servico_id - $nome_atividade</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="mb-2"><span>Defina o tipo de documento que vai ser emitido</span></div>
                        <div class="form-check form-check-inline mb-2">
                            <input class="form-check-input" type="radio" name="opcao_faturamento" id="inlineRadio1" value="recibo">
                            <label class="form-check-label" for="inlineRadio1">Gerar recibo (unificado, <br>material e serviço)</label>
                        </div>
                        <div class="form-check form-check-inline mb-2">
                            <input class="form-check-input" type="radio" name="opcao_faturamento" id="inlineRadio2" value="doc_material_recibo_servico">
                            <label class="form-check-label" for="inlineRadio2">Gerar nota para o material <br>e recibo para o serviço</label>
                        </div>
                        <div class="form-check form-check-inline mb-2">
                            <input class="form-check-input" type="radio" name="opcao_faturamento" id="inlineRadio3" value="doc_servico_recibo_material">
                            <label class="form-check-label" for="inlineRadio3">Gerar nota para o serviço e<br> recibo para o material</label>
                        </div>
                        <div class="form-check form-check-inline mb-2">
                            <input class="form-check-input" type="radio" name="opcao_faturamento" checked id="inlineRadio4" value="doc_material_doc_servico">
                            <label class="form-check-label" for="inlineRadio4">Gerar nota para o serviço e<br> nota para o material</label>
                        </div>
                    </div>
                    <div class="accordion" id="accordionPanelsStayOpenExample">
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOneOsFatura" aria-expanded="true" aria-controls="panelsStayOpen-collapseOneOsFatura">
                                    Serviços pendentes de faturamento ao cliente
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseOneOsFatura" class="accordion-collapse collapse show ">
                                <div class="accordion-body">
                                    <div class="row mb-2">
                                        <div class="col-auto  mb-2">
                                            <div class="input-group">
                                                <span class="input-group-text">Data</span>
                                                <input type="date" class="form-control " id="data_inicial_os_pendente" name="data_incial" title="Data Inicial" placeholder="Data Inicial" value="<?php echo $data_inicial_ano_bd ?>">
                                                <input type="date" class="form-control " id="data_final_os_pendente" name="data_final" title="Data Final" placeholder="Data Final" value="<?php echo $data_lancamento; ?>">
                                            </div>
                                        </div>
                                        <div class="col-md  mb-2">
                                            <div class="input-group">
                                                <input type="search" class="form-control" id="pesquisa_conteudo_os_pendente" placeholder="Tente pesquisar pelo Nº da ordem, serie ou cliente" aria-label="Recipient's username" aria-describedby="button-addon2">
                                                <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa_os_pendente">Pesquisar</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tabela_os_pendentes"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="js/include/servico/faturar_ordem_servico.js"></script>