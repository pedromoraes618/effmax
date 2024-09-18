<?php
include "../../../../modal/empresa/atendimento/gerenciar_atendimento.php";
?>

<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">Data</th>
            <th>Cliente/Fornecedor</th>
            <th>Descrição</th>
            <th>Status</th>
            <th>Usuário</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($linha = mysqli_fetch_assoc($consultar_atendimento)) {
            $id = $linha['cl_id'];
            $descricao = utf8_encode($linha['cl_descricao']);
            $data = formatarTimeStamp($linha['cl_data']);
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
            <tr>
                <th><?php echo ($data); ?><hr><?php if ($agendamento != 0) {
                                                echo ' <i title="Agendamento" class="bi bi-alarm-fill"></i> '. formatDateB($agendamento);
                                            } ?></th>
                <td><?php echo $parceiro; ?></td>
                <td><?php echo ($descricao); ?><br></td>
                <td><?php echo $status; ?></td>
                <td><?php echo $usuario; ?></td>
                <td class="td-btn">
                    <div class="btn-group">
                        <button type="buttom" atendimento_id='<?php echo $id; ?>' class="btn btn-sm btn-info editar">Editar</button>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<script src="js/empresa/atendimento/table/editar_atendimento.js"></script>