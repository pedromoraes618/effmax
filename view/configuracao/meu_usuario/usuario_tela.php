<div class="row acao">

    <div class="col mb-2">
        <div class="title">
            <label class="form-label">Minhas Informações</label>
            <div class="msg_title">
                <p>Veja seus dados cadastrais </p>
            </div>
        </div>
        <hr>
        <div class="row mb-3 p-2  ">
            <input type="hidden" name="formulario_cadastrar_usuario">
            <?php include "../../input_include/usuario_logado.php" ?>

            <div class="col-auto mb-2 ">
                <div class="bg-secondary img-upload bg-img-user rounded-circle">
                    <button type="button" class="btn btn-secondary border-0"  id="open_upload_img_user"><i class="bi bi-camera"></i></button>
                </div>
            </div>

            <div class="col-auto mb-2">
                <nav id="nav_info_user">
                    <ul>
                        <li>
                            <div class="info_user">
                                <div class="titulo">Nome: </div>
                                <div class="descricao_nome"></div>
                            </div>
                        </li>
                        <li>
                            <div class="info_user">
                                <div class="titulo">Usuário: </div>
                                <div class="descricao_user"></div>
                            </div>
                        </li>
                        <li>
                            <div class="info_user">
                                <div class="titulo">Nivel: </div>
                                <div class="descricao_tipo"></div>
                            </div>
                        </li>
                        <li>
                            <div class="info_user">
                                <div class="titulo">Email: </div>
                                <div class="descricao_email"></div>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <div class="col-md-6  mb-2">
        <div class="title">
            <label class="form-label">Resete sua senha</label>
            <div class="msg_title">
                <p>Resete a sua senha </p>
            </div>
        </div>
        <hr>
    
        <div class="col-md-8 mb-2 ">
            <input type="text" class="form-control" placeholder="Digite a nova senha" id="senha_nova" name="senha_nova" value="">
        </div>
        <div class="col-md-8 mb-3">
            <input type="text" class="form-control" placeholder="Confirme a nova senha" id="confirmar_senha" name="confirmar_senha" value="">
        </div>
        <div class="col-auto  d-grid gap-2 d-sm-block mb-1  ">
            <button type="submit" class="btn btn-dark col-auto" id="alterar_senha">Alterar</button>
        </div>
    </div>

    <div class="modal_show">
    </div>


</div>




<script src="js/funcao.js"></script>
<script src="js/configuracao/users/user_logado.js"></script>
<script src="js/configuracao/meu_usuario/meu_usuario_tela.js"></script>