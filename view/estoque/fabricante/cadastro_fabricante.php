<?php  include "../../../conexao/conexao.php"; ?>
<?php include "../../../modal/estoque/grupo_estoque/gerenciar_grupo_estoque.php";  
?>


<div class="title">
    <label class="form-label">Cadastrar Fabricante</label>
    <div class="msg_title">
        <p>Registre aqui os fabricantes dos seus produtos e ofereça qualidade e confiança aos seus clientes. </p>
    </div>
</div>
<hr>
<form id="cadastrar_fabricante">
    <div class="row mb-2">
        <input type="hidden" name="formulario_cadastrar_fabricante">
        <?php include "../../input_include/usuario_logado.php"?>
        <div class="col-sm  mb-2">
            <label for="descricao" class="form-label">Descrição</label>
            <input type="text" class="form-control" id="descricao" name="descricao" placeholder="" value="">
        </div>
    </div>
        <div class="row">     
            <div class="col-md-4  d-grid gap-2 d-sm-block mb-1">
            <button type="subbmit" class="btn btn-sm btn-success">Cadastrar</button>
            </div>
        </div>
    </div>            


</form>

<script src="js/configuracao/users/user_logado.js"></script>

<script src="js/estoque/fabricante/cadastrar_fabricante.js"></script>