<?php
include "../../../modal/configuracao/users/usuario.php";
?>
<form id="resetar_senha_usuario">
    <div class="acao">
        <div class="title">
            <label class="form-label">Resetar Senha</label>
        </div>
        <hr>
        <div class="row mb-2">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                <button type="submit" class="btn btn-sm btn-success">Alterar senha</button>
                <button type="button" id="voltar_cadastro" class="btn btn-sm btn-secondary">Voltar</button>
            </div>
        </div>
        <div class="row">
            <input type="hidden" name="formulario_resetar_senha_usuario">
            <?php include "../../input_include/usuario_logado.php" ?>
            <input type="hidden" value="<?php echo $id_user; ?>" name="id_user">

            <div class="col-sm-3  mb-1">
                <label for="usuario" class="form-label">Usúario</label>
                <input type="text" readonly class="form-control inputUser" id="usuario" name="usuario" placeholder="Apenas letras e números" value="<?php echo $usuario_b; ?>">
            </div>
            <div class="col-sm-3 mb-1">
                <label for="senha" class="form-label">Nova Senha</label>
                <input type="text" class="form-control inputUser" id="senha" autocomplete="off" name="senha" placeholder="Apenas letras, números e símbolos" value="">
            </div>
            <div class="col-sm-3 mb-1">
                <label for="senha" class="form-label">Confirmar senha</label>
                <input type="text" class="form-control inputUser" autocomplete="off" id="confirmar_senha" name="confirmar_senha" placeholder="Apenas letras, números e símblos" value="">
            </div>



        </div>
    </div>
</form>
<script src="js/funcao.js"></script>
<script src="js/configuracao/users/user_logado.js"></script>
<script src="js/configuracao/users/resetar_senha.js"></script>