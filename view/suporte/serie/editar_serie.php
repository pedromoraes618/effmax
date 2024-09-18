<?php
include "../../../modal/suporte/serie/gerenciar_serie.php"; // trazer as informações da categoria
?>

<div class="title">
    <label class="form-label">Editar Série</label>
    <div class="msg_title">
        <p>Edite Série </p>
    </div>
</div>
<hr>
<form id="editar_serie">
    <div class="row mb-2">
        <input type="hidden" name="formulario_editar_serie">

        <?php include "../../input_include/usuario_logado.php" ?>

        <input type="hidden" value="<?php echo $id_serie; ?>" name="id_serie">

        <div class="col-md-2 mb-2">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" disabled class="form-control" id="descricao" name="descricao" placeholder="" value="<?php echo $descricao_b ?>">
        </div>
        <div class="col-md-2 mb-2">
            <label for="descricao" class="form-label">Valor</label>
            <input type="text" class="form-control" id="valor" name="valor" placeholder="" value="<?php echo $valor_b ?>">
        </div>
        <div class="col-md-5 mb-2">
            <label for="informacao" class="form-label">Informação</label>
            <input type="text" class="form-control" id="informacao" name="informacao" placeholder="" value="<?php echo $informacao_b; ?>">
        </div>
        <div class="col-md-auto  mb-2">
            <label for="serie_fiscal" class="form-label">Série Fiscal</label>
            <select name="serie_fiscal" id="serie_fiscal" class="form-select chosen-select">
                <option value="0">Selecione...</option>
                <option <?php if ($serie_fiscal == "SIM") {
                            echo "selected";
                        } ?> value="SIM">Sim</option>
                <option <?php if ($serie_fiscal == "NAO") {
                            echo "selected";
                        } ?> value="NAO">Não</option>
            </select>
        </div>
        <div class="col-md-auto  mb-2">
            <label for="serie_recibo" class="form-label">Série Recibo</label>
            <select name="serie_recibo" id="serie_recibo" class="form-select chosen-select">
                <option value="0">Selecione...</option>
                <option <?php if ($serie_recibo == "SIM") {
                            echo "selected";
                        } ?> value="SIM">Sim</option>
                <option <?php if ($serie_recibo == "NAO") {
                            echo "selected";
                        } ?> value="NAO">Não</option>
            </select>
        </div>


    </div>
    <div class="row">
        <div class="d-grid gap-2 d-sm-block">
            <button type="submit" class="btn btn-sm btn-success">Alterar</button>
            <button type="button" id="voltar_cadastro" class="btn btn-sm btn-dark">Voltar</button>
        </div>
    </div>


</form>

<script src="js/configuracao/users/user_logado.js"></script>

<script src="js/suporte/serie/editar_serie.js"></script>