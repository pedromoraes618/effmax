<?php include "../../../modal/ecommerce/dashboard/gerenciar_dashboard.php"; ?>

<div class="title">
    <label class="form-label">Dashobard</label>
</div>
<div class="d-flex justify-content-end" class="">

    <div class="col-md-auto mb-2 ">
        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
            <input type="radio" class="btn-check periodo" name="btnradio" <?php if ($periodo_dashboard == "DIA") {
                                                                                echo 'checked';
                                                                            } ?> id="DIA" autocomplete="off" value="DIA">
            <label class="btn btn-outline-primary" for="DIA">Dia</label>

            <input type="radio" class="btn-check periodo" name="btnradio" <?php if ($periodo_dashboard == "MES") {
                                                                                echo 'checked';
                                                                            } ?> id="MES" autocomplete="off" value="MES">
            <label class="btn btn-outline-primary" for="MES">Mês</label>

            <input type="radio" class="btn-check periodo" name="btnradio" <?php if ($periodo_dashboard == "ANO") {
                                                                                echo 'checked';
                                                                            } ?> id="ANO" autocomplete="off" value="ANO">
            <label class="btn btn-outline-primary" for="ANO">Anual</label>
        </div>
    </div>
</div>

<div>
    <div class="row">
        <div class="col-md">
            <div class="row resumo_pedidos_1"></div>
            <div class="row"></div>
        </div>
        <div class="row">

        </div>
    </div>
</div>

<script src="js/ecommerce/dashboard/consultar_dashboard.js"></script>