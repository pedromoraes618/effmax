<?php
if (isset($_GET['numero_nf'])) {
    $numero_nf = $_GET['numero_nf'];
} else {
    $numero_nf = "";
}
?>
<div class="modal fade" id="modal_consulta_nf" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h1 class="modal-title fs-5">Status Nota</h1>
                <button type="text" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>


            <div class="modal-body">
                <div class="row g-3 align-items-center mb-2">
                    <div class="col-auto mb-2">
                        <input type="number" aria-describedby="ninutlizada" id="numero_nf_inutilizado" placeholder="Informe a númeração a ser Inutilizada" class="form-control" value="<?php echo $numero_nf; ?>">
                    </div>
                    <div class="col-auto">
                        <span id="ninutlizada" class="form-text">
                            Informe a númeração a ser Inutilizada
                        </span>
                    </div>
                </div>
                <div class="row g-3 align-items-center mb-2">
                    <div class="col-md mb-2">
                        <input type="text" class="form-control" id="justificativa_inutilizacao_nf" placeholder="Informe a justificativa">
                    </div>
                    <div class="col-auto">
                        <span id="ninutlizada" class="form-text">
                            Mínimo de caracteres 15
                        </span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md  mb-2">
                        <textarea class="form-control" readonly id="status_processamento" placeholder="" name="" cols="30" rows="10"></textarea>
                    </div>
                </div>
                <!-- loading -->
                <?php include "../../loading/spinner.php"; ?>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm enviar_inutilizacao_nf">Enviar</button>
                <button type="button" class="btn btn-secondary btn-sm " data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script src="js/include/nf_fiscal/inutilizar_nf.js"></script>
<!-- <script src="js/gerenciar_fiscal.js"></script> -->