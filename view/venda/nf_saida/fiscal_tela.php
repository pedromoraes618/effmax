<?php
include "../../../conexao/conexao.php";
include "../../../modal/venda/nf_saida/gerenciar_nf_saida.php";
include "../../../funcao/funcao.php";
?>
<div class="modal fade" id="modal_fiscal" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Ações Fiscais</h1>

                <button type="button" class="btn-close btn-close-modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md mb-4">
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                        <button type="button" name="enviar_nf" id="enviar_nf" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Enviar Nota</button>
                        <button type="button" name="cpf_nota" id="cpf_nota" class="btn btn-sm btn-warning"><i class="bi bi-check-all"></i> Cpf na Nota</button>
                        <button type="button" title='Visualizar a nota antes de enviar' name="preview_nf" id="preview_nf" class="btn btn-sm btn-warning"><i class='bi bi-check'></i> Preview</button>

                        <a type="button" href="" target="_blank" name="consultar_pdf_nf" id="consultar_pdf_nf" class="btn btn-sm btn-info"><i class="bi bi-eye-fill"></i> Visualizar Pdf</a>
                        <a type="button" href="" target="_blank" name="consultar_xml_nf" id="consultar_xml_nf" class="btn btn-sm btn-info"><i class="bi bi-filetype-xml"></i> Visualizar XmL</a>
                        <button type="button" id="inutilizar_nf" class="btn btn-sm btn-warning"><i class="bi bi-filetype-xml"></i> Inutilizar Nota</button>

                        <button class="btn btn-sm btn-dark dropdown-toggle" id="acao_crt" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-file"></i> Carta de Correção</button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item " id="crt_correcao" style="cursor: pointer;">Enviar Carta</a></li>
                            <li><a class="dropdown-item " target="_blank" id="consultar_carta_correcao_nf" style="cursor: pointer;">Visualizar Carta</a></li>
                        </ul>
                        <button type="button" id="cancelar_nf" class="btn btn-sm btn-danger"><i class="bi bi-x"></i> Cancelar Nota</button>
                        <button type="button" class="btn btn-sm btn-secondary btn-close-modal" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-auto  col-md-2 mb-2">
                        <input type="hidden" id="codigo_nf" name="codigo_nf" value="<?php echo $codigo_nf; ?>">
                        <input type="hidden" id="nf_id" name="id" value="<?php echo $id_nf; ?>">
                        <label for="numero_nf" class="form-label">Nº NF</label>
                        <input type="text" disabled class="form-control" id="numero_nf" name="numero_nf" value="">
                    </div>
                    <div class="col-sm-auto  col-md-2 mb-2">
                        <label for="serie" class="form-label">Série</label>
                        <input type="text" disabled class="form-control" id="serie_nf" name="serie_nf" value="">
                    </div>
                    <div class="col-sm-auto  col-md mb-2">
                        <label for="parceiro_descricao" class="form-label">Cliente</label>
                        <input type="text" disabled class="form-control" id="parceiro_descricao" name="parceiro_descricao" value="">
                    </div>
                </div>

                <div class="row mb-2">
                    <div class="col-sm-6  col-md-2 mb-2">
                        <label for="valor_bruto" class="form-label">Valor</label>
                        <input type="text" disabled class="form-control" id="valor_bruto" name="valor_bruto" value="">
                    </div>
                    <div class="col-sm-6  col-md-2 mb-2">
                        <label for="desconto" class="form-label">Desconto</label>
                        <input type="text" disabled class="form-control" id="desconto" name="desconto" value="">
                    </div>
                    <div class="col-sm-6  col-md-2 mb-2">
                        <label for="valor_liquido" class="form-label">Valor Total</label>
                        <input type="text" disabled class="form-control" id="valor_liquido" name="valor_liquido" value="">
                    </div>

                </div>
                <div class="row mb-4">

                    <div class="col-sm-6  col-md mb-2">
                        <label for="chave_acesso" class="form-label">Chave de acesso</label>
                        <input type="text" disabled class="form-control" id="chave_acesso" name="chave_acesso" value="">
                    </div>
                    <div class="col-sm-6  col-md mb-2">
                        <label for="protocolo" class="form-label">Nº Protocolo</label>
                        <input type="text" disabled class="form-control" id="protocolo" name="protocolo" value="">
                    </div>

                </div>

                <div class="accordion accordion-flush P-0" id="accordionFlushShow">
                    <div class="accordion-item P-0">
                        <h2 class="accordion-header P-0">
                            <button class="accordion-button collapsed P-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTransportadora" aria-expanded="false" aria-controls="collapseTransportadora">
                                <div class="row">
                                    <div class="col-sm">
                                        <p>Visualizar Itens</p>
                                    </div>
                                </div>
                            </button>
                        </h2>
                        <div id="collapseTransportadora" class="accordion-collapse collapse" data-bs-parent="#accordionFlushFornecedor">
                            <div class="accordion-body P-0">
                                <div class="tabela_externa"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal_externo"></div>
        </div>
    </div>
</div>


<script src="js/venda/nf_saida/fiscal_tela.js"></script>