<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
include "../../../modal/caixa/abertura_fechamento/gerenciar_caixa.php";
?>
<div class="title">
    <label class="form-label">Abertura e fechamento do caixa</label>
</div>
<hr>
<div class="row mb-2">
    <div class="col-md-3 mb-2">
        <input type="date" class="form-control" id="data" name="data" value="<?php echo $data_lancamento ?>">
    </div>

    <div class="col  mb-2">
        <button class="btn btn-dark  " id="consultar">Consultar</button>
        <button class="btn btn-success" id="abrir_caixa">Abrir</button>
        <button class="btn btn-danger" id="fechar_caixa">Fechar</button>
    </div>


</div>

<div class="tabela">

</div>

<script src="js/caixa/abertura_fechamento/consultar_caixa.js"></script>