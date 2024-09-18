<?php
include "../../../modal/empresa/atendimento/gerenciar_atendimento.php";
?>
<div class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-hidden="true" id="modal_atendimento" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Atendimento</h1>
                <button type="button" class="btn-close btn-close-atendimento" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="atendimento">
                <input type="hidden" id="id" name="id" value="<?= $form_id; ?>">
                <input type="hidden" id="codigo_nf" name="codigo_nf" value="<?php echo $codigo_nf; ?>">

                <div class="modal-body">
                    <div class="title mb-2">
                        <label class="form-label sub-title"></label>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end btn-acao">
                        <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                        <?php if ($form_id != "") {
                            echo "<button type='button' id='remover' class='btn btn-sm btn-danger'>Remover</button>";
                        } ?>
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                    <div class="row  mb-2">

                        <div class="col-md  mb-2">
                            <label for="parceiro" class="form-label">Solicitante</label>
                            <select style="width:100%" id="parceiro" class="js-example-basic-single" name="parceiro">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_parceiros');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = $linha['cl_id'];
                                        $razao_social = utf8_encode($linha['cl_razao_social']);
                                        if ($id == $parceiro_id) {
                                            $selected = "selected";
                                        } else {
                                            $selected = null;
                                        }
                                        echo "<option $selected value='$id'>$razao_social</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3 mb-2">
                            <label for="atendente" class="form-label">Atendente *</label>
                            <select id="atendente" class="form-select chosen-select" name="atendente">
                                <option value="0">Selecione</option>
                                <?php
                                $resultados = consulta_linhas_tb_filtro($conecta, 'tb_users', "cl_ativo", "1");
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = $linha['cl_id'];
                                        $atendente = utf8_encode($linha['cl_usuario']);
                                        if ($id == $usuario_id) {
                                            $selected = "selected";
                                        } else {
                                            $selected = null;
                                        }
                                        echo "<option $selected value='$id'>$atendente</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="status" class="form-label">Status *</label>
                            <select id="status_atd" class="form-select chosen-select" name="status">
                                <option value="0">Selecione..</option>
                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_status_tarefas');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = $linha['cl_id'];
                                        $descricao = utf8_encode($linha['cl_descricao']);

                                        echo "<option value='$id'>$descricao</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="visualizacao" class="form-label">Visualização</label>
                            <select id="visualizacao" class="form-select chosen-select" name="visualizacao">
                                <option value="T">Todos</option>
                                <option value="I">Apenas para mim</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label for="agendar" class="form-label">Agendar</label>
                            <input type="date" class="form-control " id="agendar" name="agendar" value="">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md  mb-2">
                            <label for="descricao" class="form-label">Descrição *</label>
                            <textarea class="form-control" name="descricao" id="descricao" aria-label="With textarea"></textarea>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<script src="js/empresa/atendimento/atendimento_tela.js"></script>