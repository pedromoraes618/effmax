<?php

include "../../../conexao/conexao.php";
include "../../../modal/autorizador/usuario.php";
?>

<div class="modal fade" id="modal_autorizar_acao" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Autorizar</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="form_autorizacao">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md shadow mb-2">
                            <p><?=  isset($_GET['mensagem']) ? $_GET['mensagem'] : ''; ?></p>
                        </div>
                    </div>
                    <hr>
                    <div class="title mb-3">
                        <label class="form-label sub-title">Usuário autorizador</label>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <select name="" class="form-select chosen-select" id="id_usuario_autorizador">
                                <option value="0">Selecione</option>
                                <?php
                                while ($linha = mysqli_fetch_assoc($consultar_usuarios_autorizados)) {
                                    $id_usuario = $linha['cl_id'];
                                    $usuario = $linha['cl_usuario'];
                                    echo "<option value='$id_usuario'>$usuario</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md">
                            <input type="password" placeholder="Digite a senha" id="senha_autorizador" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-sm btn-success" id="autorizar_acao">Autorizar</button>
                    <button type="button" class="btn btn-sm btn-secondary fechar_modal_autorizado" data-bs-dismiss="modal">Fechar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="alert"></div>

<script src="js/include/autorizador/autorizar_acao.js"></script>