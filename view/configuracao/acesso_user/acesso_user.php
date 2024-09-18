<div class="bloco-pesquisa-menu">
    <div class="bloco-pesquisa-1">
        <div class="title">
            <label class="form-label">Acesso de usúario</label>
            <div class="msg_title">
                <p>Esse menu tem como função o gerênciamento de acessos. Adicionar e remover acessos de telas para usúario é feito aqui! </p>
            </div>
        </div>
        <hr>
        <div class="col-sm-2  mb-1">
            <label for="select_user">Usuário</label>
            <select class="form-select" id="select_user">
                <option name="0" value="0">Selecione</option>
                <?php
                while ($linha = mysqli_fetch_assoc($consultar_usuarios)) { ?>
                    <option value="<?php echo utf8_encode($linha["cl_id"]); ?>">
                        <?php echo utf8_encode($linha["cl_usuario"]); ?>
                    </option>
                <?php
                } ?>
            </select>
        </div>

        <div id="duvida" class="msg">
            <i class="bi bi-chat-fill"></i>
            <p>Apenas usuários tipo usuário estão nessa seleção de acessos</p>
        </div>
    </div>

    <div class="row bloco-pesquisa-2">
        <div class="col-sm-6 mb-3 mb-sm-0">

            <div class="card">
                <div class="card-header">
                    Acessos Disponiveis
                </div>
                <div class="card-body" style="max-height: 500px;" id="card-body-1">

                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card">
                <div class="card-header">
                    Acessos Atuais
                </div>
                <div class="card-body" style="max-height: 500px;">

                    <div class="sub_bloco_info-2" id="card-body-2">

                    </div>
                </div>
            </div>


        </div>




    </div>



</div>

<script src="js/configuracao/controle_de_acesso/script_selecao.js"></script>