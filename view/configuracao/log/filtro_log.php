<?php
include "../../../funcao/funcao.php";
include "../../../modal/configuracao/log/consulta.php";

//incluir data inial e final
?>
<div class="title">
    <label class="form-label">Consultar log do sistema</label>
    <div class="msg_title">
        <p>Esse menu tem como função o gerênciamento de log.Aqui fica armazenado registros de eventos ocorridos no sistema, como
            erros, alertas, atividades de usuários e outras informações relevantes. </p>
    </div>
</div>
<hr>

<div class="row">


    <div class="col-auto  mb-2">
        <div class="input-group">
            <span class="input-group-text">Período</span>
            <input type="date" class="form-control" id="data_incial" name="data_incial" placeholder="Data inicial" value="<?php echo $data_inicial_mes_bd; ?>">

            <input type="date" class="form-control" id="data_final" name="data_final" placeholder="Data Final" value="<?php echo $data_final_mes_bd; ?>">
        </div>
    </div>

    <div class="col-md-2 mb-1">
        <select name="usuario" class="form-select chosen-select" id="usuario">
            <option value="0">Usuário</option>
            <?php while ($linha = mysqli_fetch_assoc($consultar_usuarios)) {
                $id_user = $linha['cl_id'];
                $usuario = $linha['cl_usuario'];
            ?>
                <option value="<?php echo $usuario; ?>"><?php echo $usuario  ?></option>

            <?php
            } ?>
        </select>
    </div>
    <div class="col-md  mb-2">
        <div class="input-group">
            <input type="text" class="form-control" id="conteudo" placeholder="Tente pesquisar pela mensagem" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="button" id="pesquisa_conteudo">Pesquisar</button>
        </div>
    </Div>
</div>
<?php include '../../../funcao/funcaojavascript.jar'; ?>

<script src="js/configuracao/log/filtro_log.js"></script>