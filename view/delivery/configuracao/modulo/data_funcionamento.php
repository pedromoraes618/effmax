<?php
include "../../../../modal/delivery/configuracao/gerenciar_configuracao.php";
?>


<form id="data_funcionamento" style="max-height:1500px">

    <div class="card p-3 ">
        <div class="row">
            <div class="title">
                <label class="form-label">Data de funcionamento</label>
            </div>
        </div>
        <div class="row  mb-2">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                <button type="submit" id="button_form" class="btn btn-sm btn-success">Alterar</button>
            </div>
        </div>
        <div class="row">
            <?php while ($linha = mysqli_fetch_assoc($consulta_data_funcionamento)) {
                $id = $linha['cl_id'];
                $dia_semana = $linha['cl_dia_semana'];
                $status_funcionamento = $linha['cl_status_funcionamento'];
                $hora_abertura = $linha['cl_hora_abertura'];
                $hora_fechamento = $linha['cl_hora_fechamento'];

                $hora_abertura = DateTime::createFromFormat('Y-m-d H:i:s', $hora_abertura);
                $hora_fechamento = DateTime::createFromFormat('Y-m-d H:i:s', $hora_fechamento);

                $hora_abertura = $hora_abertura->format('H:i');
                $hora_fechamento = $hora_fechamento->format('H:i');



                if ($dia_semana == 'Monday') {
                    $dia_semana = "Segunda feira.";
                } elseif ($dia_semana == 'Tuesday') {
                    $dia_semana = "Terça-feira.";
                } elseif ($dia_semana == 'Wednesday') {
                    $dia_semana = "Quarta-feira.";
                } elseif ($dia_semana == 'Thursday') {
                    $dia_semana = "Quinta-feira.";
                } elseif ($dia_semana == 'Friday') {
                    $dia_semana = "sexta-feira.";
                } elseif ($dia_semana == 'Saturday') {
                    $dia_semana = "Sábado.";
                } elseif ($dia_semana == 'Sunday') {
                    $dia_semana = "Domingo.";
                }

            ?>
                <div class="col-md mb-2">
                    <label class="form-label fw-semibold"><?php echo $dia_semana; ?></label>
                    <div class="d-grid gap-2 d-md-flex  align-items-center">
                        Abertura <input class="form-control" type="time" name="hra<?php echo $id; ?>" id="" value="<?php echo $hora_abertura; ?>"> Fechamento
                        <input class="form-control" type="time" name="hrf<?php echo $id; ?>" id="" value="<?php echo $hora_fechamento; ?>">

                        <div class="form-check form-check-inline">
                            <input class="form-check-input segunda" name="abt<?php echo $id; ?>" type="checkbox" id="flexCheckDefault<?php echo $id ?>" <?php if ($status_funcionamento == 'SIM') {
                                                                                                                                                            echo 'checked';
                                                                                                                                                        } ?> value="option1">
                            <label class="form-check-label segunda" for="flexCheckDefault<?php echo $id ?>">Aberto</label>
                        </div>
                    </div>
                </div>
                <hr class="mb-0">
            <?php } ?>
        </div>
    </div>

</form>




<script src="js/delivery/configuracao/modulo/data_funcionamento.js"></script>