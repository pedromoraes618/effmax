<?php
include "../../../modal/empresa/atendimento/gerenciar_atendimento.php";
?>

<div class="modal fade" id="modal_contato_parceiro" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header mb-2">
                <h1 class="modal-title fs-5">Contato</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="title mb-2">
                    <label class="form-label sub-title">Contatos</label>
                </div>
                <div class="row">
                    <div class="col-md-auto col-auto  mb-2">
                        <input type="hidden" id="codigo_nf" value="<?php echo $codigo_nf;  ?>">
                        <input type="hidden" id="parceiro_id" value="<?php echo $parceiro_id; ?>">
        
                        <div class="input-group">
                            <span class="input-group-text">Movimento</span>
                            <input type="date" class="form-control" id="data_inicial" name="data_incial" title="Data Inicial" placeholder="Data Inicial" value="<?php echo $data_inicial_ano_bd; ?>">
                            <input type="date" class="form-control" id="data_final" name="data_final" title="Data Final" placeholder="Data Final" value="<?php echo $data_final_ano_bd; ?>">
                        </div>
                    </div>
                    <div class="col-md  mb-2">
                        <div class="input-group">
                            <input type="text" class="form-control" id="pesquisa_conteudo_contato_parceiro" placeholder="Pesquise cliente/fornecedor, descrição ou usuário" aria-label="Recipient's username" aria-describedby="button-addon2">
                            <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa_contato_parceiro">Pesquisar</button>
                        </div>
                    </div>
                    <div class="col-md-auto  d-grid gap-2 d-sm-block mb-1">
                        <button type="button" id="adicionar_atendimento" class="btn btn-dark">
                            <i class="bi bi-plus-circle"></i> Contato
                        </button>
                    </div>
                </div>
                <div class="modal_externa_2"></div>
                <div class="row">
                    <div class="tabela_externa_modal">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="js/include/contato/contato_parceiro.js"></script>