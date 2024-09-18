<?php
// if (isset($_GET['acao'])) {
//     $acao = $_GET['acao'];
// } else {
//     $acao = "";
// }
// if (isset($_GET['numero_nf'])) {
//     $numero_nf = $_GET['numero_nf'];
// } else {
//     $numero_nf = "";
// }
?>
<div class="modal fade" id="modal_consulta_nf" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h1 class="modal-title fs-5">Status Nota</h1>
                <button type="button" class="btn-close close_preview_nf" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


            <div class="modal-body">
                <div class="row">
                    <div class="col-md  mb-2">
                        <textarea class="form-control" readonly id="status_processamento" placeholder="" name="" cols="30" rows="10">

                        </textarea>
                    </div>
                </div>

                <!-- loading -->
                <?php include "../../loading/spinner.php"; ?>
            </div>

            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-warning btn-sm consultar_status_nf">Consultar Status</button> -->
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script src="js/include/nf_fiscal/preview_nf.js"></script>
<!-- <script src="js/gerenciar_fiscal.js"></script> -->