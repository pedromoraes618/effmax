<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/empresa/atendimento/gerenciar_atendimento.php";
?>

<div class="row mx-0">
    <?php
    while ($linha = mysqli_fetch_assoc($consultar_atendimento)) {
        $id = $linha['cl_id'];
        $descricao = utf8_encode($linha['cl_descricao']);
        $data = formatarTimeStamp($linha['cl_data']);
        $status = ($linha['status']);
        $status = ($linha['status']);
        $status_id = ($linha['cl_status_id']);
        $parceiro = utf8_encode($linha['cl_razao_social']);
        $usuario = utf8_encode($linha['cl_usuario']);
        $agendamento = ($linha['cl_data_agendamento']);
        if ($status_id == "1") {
            $status = "<span class='badge text-bg-primary'>$status</span>";
        } elseif ($status_id == "2") {
            $status = "<span class='badge text-bg-Info'> $status</span>";
        } else {
            $status = "<span class='badge text-bg-success'>$status</span>";
        }
    ?>
        <div class="col-sm-3 mb-2">
            <div atendimento_id='<?php echo $id; ?>' class="card card-tarefa card-atendimento <?php if ($agendamento == $data_lancamento) {
                                                                                                    echo 'bg-warning';
                                                                                                } else {
                                                                                                    echo 'bg-Light';
                                                                                                } ?>">
                <div class="card-body">
                    <p class="card-title"> <?php echo $status ?> <span class='badge text-bg-dark'><?php echo $usuario; ?> <?php echo $data; ?></span></p>
                    <?php if ($agendamento != '') {
                    ?>
                        <p class="card-text mb-0">Agendamento: <?php echo formatDateB($agendamento); ?></p>
                    <?php
                    } ?>
                    <p class="card-text mb-0">Atendimento: <?php echo $parceiro; ?></p>
                    <p class="card-text "><?php echo $descricao; ?></p>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<script src="js/empresa/atendimento/table/editar_atendimento.js"></script>