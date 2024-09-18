<?php include "../../../conexao/conexao.php"; ?>
<?php include "../../../modal/suporte/serie/gerenciar_serie.php";
?>


<div class="title">
    <label class="form-label">Cadastrar Série</label>
    <div class="msg_title">
        <p>Cadastrar Série </p>
    </div>
</div>
<hr>
<form id="cadastrar_serie">
    <div class="row mb-2">
        <input type="hidden" name="formulario_cadastrar_serie">
        <?php include "../../input_include/usuario_logado.php" ?>
        <div class="col-md-2 mb-2">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" class="form-control" id="descricao" name="descricao" placeholder="" value="">
        </div>
        <div class="col-md-2 mb-2">
            <label for="descricao" class="form-label">Valor</label>
            <input type="text" class="form-control" id="valor" name="valor" placeholder="" value="">
        </div>
        <div class="col-md-5 mb-2">
            <label for="informacao" class="form-label">Informação</label>
            <input type="text" class="form-control" id="informacao" name="informacao" placeholder="" value="">
        </div>
        <div class="col-md-auto  mb-2">
            <label for="serie_fiscal" class="form-label">Série Fiscal</label>
            <select name="serie_fiscal" id="serie_fiscal" class="form-select chosen-select">
                <option value="0">Selecione...</option>
                <option value="SIM">Sim</option>
                <option value="NAO">Não</option>
            </select>
        </div>
        <div class="col-md-auto  mb-2">
            <label for="serie_fiscal" class="form-label">Série Recibo</label>
            <select name="serie_recibo" id="serie_recibo" class="form-select chosen-select">
                <option value="0">Selecione...</option>
                <option value="SIM">Sim</option>
                <option value="NAO">Não</option>
            </select>
        </div>
    </div>
    <div class="row">
        <div class="d-grid gap-2 d-sm-block">
            <button type="subbmit" class="btn btn-sm btn-success">Cadastrar</button>
        </div>
    </div>


</form>

<script src="js/configuracao/users/user_logado.js"></script>

<script src="js/suporte/serie/cadastrar_serie.js"></script>