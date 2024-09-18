<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
$parceiro_id = isset($_GET['parceiro_id']) ? $_GET['parceiro_id'] : '';
$razao_social = utf8_encode(consulta_tabela($conecta, "tb_parceiros", "cl_id", $parceiro_id, "cl_razao_social"));

?>
<div class="modal fade" id="modal_historico_credito_parceiro" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header mb-2">
                <h1 class="modal-title fs-5">Histórico de crédito <?= ($razao_social); ?></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


            <div class="modal-body">
                <div class="title mb-2">
                    <label class="form-label sub-title">Histórico</label>
                </div>

                <div class="row">
                    <input type="hidden" id="id" value="<?= $parceiro_id; ?>">

                    <div class="col-md  mb-2">
                        <div class="input-group">
                            <input type="search" class="form-control" id="pesquisa_conteudo_historico" placeholder="Pesquise pela justificativa" aria-label="Recipient's username" aria-describedby="button-addon2">
                            <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa_historico">Pesquisar</button>
                        </div>
                    </div>
                    <div class="col-md-auto  d-grid gap-2 d-sm-block mb-1">
                        <button type="button" class="btn  btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>

                <div class="row">
                    <div class="tabela_externa">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="js/include/parceiro/historico_credito.js"></script>