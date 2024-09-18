<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
include "../../../modal/caixa/abertura_fechamento/gerenciar_caixa.php";
?>
<div class="modal fade" id="modal_abertura_fechamento_cx" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Abertura e Fechamento Caixa</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="row mb-2">
                    <div class="col-md-3 mb-2">
                        <input type="date" class="form-control" id="data" name="data" value="<?php echo $data_lancamento ?>">
                    </div>

                    <div class="col  mb-2">
                        <button class="btn btn-sm btn-dark  " id="consultar">Consultar</button>
                        <button class="btn btn-sm btn-success" id="abrir_caixa">Abrir</button>
                        <button class="btn btn-sm btn-danger" id="fechar_caixa">Fechar</button>
                    </div>
                    <div class="tabela">

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<script src="js/caixa/abertura_fechamento/consultar_caixa.js"></script>