<?php
include "../../../conexao/conexao.php";
include "../../../modal/estoque/kardex/gerenciar_kardex.php";
include "../../../funcao/funcao.php"
?>

<div class="title">
    <label class="form-label">Kardex</label>
    <div class="msg_title">
        <p>Consulte todas as movimentação desse produto</p>
    </div>
</div>
<hr>
<div class="row mb-3">
    <div class="col-md-8">
        <label class="label_box mb-2">Histórico: <?php echo $descricao_b; ?></label>
    </div>
    <div class="col d-grid gap-1 mb-1  ">
        <button type="button" <?php if(isset($_GET['consulta_produto'])){ echo "id='voltar_visualizar_consulta'"; }else{echo "id='voltar_consulta'"; } ?>  class="btn btn-sm btn-outline-secondary">Voltar</button>
    </div>
</div>
<div class="row">
    <input type="hidden" id="id_produto" value="<?php echo $id_produto; ?>">
</div>

<div class="alerta">

</div>
<div class="tabela">

</div>


<script src="js/estoque/kardex/gerenciar_kardex.js">