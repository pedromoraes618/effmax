<?php
include "../../../conexao/conexao.php";
include "../../../modal/caixa/movimento_caixa/gerenciar_movimento_caixa.php";
include "../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Movimento do caixa</label>
    <div class="msg_title">
        <p>Apenas a movimentação de vendas com a forma de pagamento vinculada à conta financeira 'Caixa' será exibida em movimento em espécie </p>
    </div>
</div>
<hr>
<div class="row mb-2">
    <div class="col-auto mb-2">
        <div class="input-group">
            <span class="input-group-text">Período</span>
            <input type="date" class="form-control " id="data_inicial" name="data_incial" title="Data vencimento" placeholder="Data Inicial" value="<?php echo $data_lancamento; ?>">
            <input type="date" class="form-control " id="data_final" name="data_final" title="Data vencimento" placeholder="Data Final" value="<?php echo $data_lancamento; ?>">
        </div>
    </div>

    <div class="col-md-auto  d-grid gap-2 d-sm-block mb-2">
        <div class="btn-group">
            <button class="btn btn-dark" id="resumo_caixa"><i class="bi bi-search"></i> Movimento em Espécie</button>
            <button class="btn btn-dark" id="resumo_geral"><i class="bi bi-search"></i> Movimento Geral</button>
            <button class="btn btn-dark" id="venda_fpg_caixa"><i class="bi bi-search"></i> Vendas</button>
            <button class="btn btn-dark" id="openReport" type="button"><i class="bi bi-printer"></i> Imprimir</button>
        </div>
    </div>
</div>

<div class="tabela print">

</div>
<!-- <script src="js/funcao.js"></script> -->
<script src="js/relatorio/formar_relatorio.js"></script>
<script src="js/caixa/movimento_caixa/consultar_movimento_caixa.js"></script>