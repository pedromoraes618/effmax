<?php
include "../../../../modal/servico/ordem_servico/gerenciar_ordem_servico.php";

$count = 0;
while ($linha = mysqli_fetch_assoc($consulta_equipe_servico)) {
    $count++;
    $id = $linha['cl_id'];
    $nome = utf8_encode($linha['cl_nome']);
    $matricula = utf8_encode($linha['cl_matricula']);
    $funcao = utf8_encode($linha['cl_funcao']);
    $data_inicio = ($linha['cl_data_inicio']);
    $data_fim = ($linha['cl_data_fim']);
    $servico_id = ($linha['cl_servico_id']);
?>
    <div class="row mb-2 membro_equipe_<?= $count; ?>">
        <div class="d-flex flex-wrap justify-content-end gap-2 mb-0">
            <button type="button" item="<?= $count; ?>" id="<?= $id; ?>" title='Remover o membro da equipe' class="btn btn-sm remover_membro_equipe"><i class="bi bi-x fs-5"></i></button>
        </div>
        <div class="col-md-4 mb-2">
            <label for="nome_equipe_<?= $count; ?>" class="form-label">Nome</label>
            <input type="text" class="form-control" name="nome_equipe_<?= $count; ?>" id="nome_equipe_<?= $count; ?>" value="<?= $nome; ?>" placeholder="Digite o nome">
        </div>
        <div class="col-6 col-md-2 mb-2">
            <label for="funcao_equipe_<?= $count; ?>" class="form-label">Função</label>
            <input type="text" class="form-control" name="funcao_equipe_<?= $count; ?>" id="funcao_equipe_<?= $count; ?>" value="<?= $funcao; ?>" placeholder="Digite a função">
        </div>
        <div class="col-6 col-md-2 mb-2">
            <label for="matricula_equipe_<?= $count; ?>" class="form-label">Matrícula</label>
            <input type="text" class="form-control" name="matricula_equipe_<?= $count; ?>" id="matricula_equipe_<?= $count; ?>" value="<?= $matricula; ?>" placeholder="Digite a matrícula">
        </div>
        <div class="col-6 col-md-2 mb-2">
            <label for="data_inicio_equipe_<?= $count; ?>" class="form-label">Data Início</label>
            <input type="date" class="form-control" name="data_inicio_equipe_<?= $count; ?>" id="data_inicio_equipe_<?= $count; ?>" value="<?= $data_inicio; ?>">
        </div>
        <div class="col-6 col-md-2 mb-2">
            <label for="data_final_equipe_<?= $count; ?>" class="form-label">Data Final</label>
            <input type="date" class="form-control" name="data_final_equipe_<?= $count; ?>" id="data_final_equipe_<?= $count; ?>" value="<?= $data_fim; ?>">
        </div>
        <hr class="mb-2">
    </div>
<?php } ?>

<script src="js/servico/ordem_servico/table/edita_equipe_servico.js">