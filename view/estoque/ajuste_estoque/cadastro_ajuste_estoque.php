<?php 
include "../../../conexao/conexao.php";
include "../../../modal/estoque/produto/gerenciar_produto.php";
?>

<div class="title">
    <label class="form-label">Realizar ajuste</label>
    <div class="msg_title">
        <p>Adicione tarefas aos usúarios </p>
    </div>
</div>
<hr>
<form id="cadastrar_ajuste_estoque">
    <div class="row">
        <input type="hidden" name="formulario_cadastrar_ajuste_estoque">

        <?php include "../../input_include/usuario_logado.php"?>

        <div class="row mb-2">
            <div class="col-sm-6 col-md-2 mb-2">
                <label for="codigo_produto" class="form-label">Codigo</label>
                <input type="hidden" name="id_produto" id="id_produto" value="<?php echo $id_produto ?>">
                <input type="text" readonly class="form-control" id="codigo_produto" name="codigo_produto"
                    value="<?php echo $codigo_produto_b ?>">
            </div>
            <div class="col-sm-6 col-md-2 ">
                <label for="data_ajuste" class="form-label">Data de ajuste</label>
                <input type="text" class="form-control" maxlength="10" onkeyup="mascaraData(this);" id="data_ajuste"
                    name="data_ajuste" placeholder="xx/xx/xxxx" value="<?php echo date('d/m/Y'); ?>">

            </div>
        </div>
        <div class="row mb-2">
            <div class="col-sm col-md-5 mb-2">
                <label for="produto" class="form-label">Produto</label>
                <input type="text" title="Não é possivel editar essa informação" disabled class="form-control" id="produto" name="produto" placeholder=""
                    value="<?php echo $descricao_b; ?>">
            </div>
            <div class="col-sm-6 col-md-2 ">
                <label for="est_atual" class="form-label">Estoque atual</label>
                <input type="text" title="Não é possivel editar essa informação" disabled class="form-control inputNumber" id="est_atual" name="est_atual"
                    value="<?php echo $estoque_b; ?>">
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-sm-2 mb-2">
                <label for="tipo_ajuste" class="form-label">Tipo</label>
                <select name="tipo_ajuste" class="form-select chosen-select" id="tipo_ajuste">
                    <option value="0">Selecione..</option>
                    <option value="ENTRADA">Entrada</option>
                    <option value="SAIDA">Saida</option>
                </select>
            </div>
            <div class="col-sm-6 col-md-2">
                <label for="quantidade" class="form-label">Quantidade</label>
                <input type="text"  class="form-control inputNumber" id="quantidade" name="quantidade"
                    value="">
            </div>
        </div>

        <div class="row mb-2">
            <div class="col-sm mb-3 mb-2">
                <label for="motivo" class="form-label">Motivo</label>
                <input type="text" class="form-control" id="motivo" name="motivo" placeholder="" value="">
            </div>
        </div>
    </div>


    <div class="row">
        <div class="group-btn d-grid gap-2 d-sm-block">
            <button type="subbmit" class="btn btn-success">Realizar ajuste</button>
            <button type="button" id="voltar_consulta" class="btn btn-outline-dark">Voltar</button>
        </div>

    </div>





</form>
<?php include '../../../funcao/funcaojavascript.jar'; ?>
<script src="js/funcao.js"></script>
<script src="js/configuracao/users/user_logado.js"></script>
<script src="js/estoque/ajuste_estoque/cadastro_ajuste_estoque.js"></script>