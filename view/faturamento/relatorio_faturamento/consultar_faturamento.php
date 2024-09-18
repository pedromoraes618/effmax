<?php
include "../../../conexao/conexao.php";
include "../../../modal/financeiro/pagamentos_recebimentos/gerenciar_pagamentos_recebimentos.php";
include "../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Faturamento</label>
</div>
<hr>
<div class="row mb-2">
    <div class="col-auto mb-2">
        <div class="input-group">
            <span class="input-group-text">Periodo</span>
            <input type="date" class="form-control " id="data_inicial" name="data_incial" title="Periodo" placeholder="Periodo" value="<?php echo $data_inicial_mes_bd ?>">
            <input type="date" class="form-control " id="data_final" name="data_final" title="Periodo" placeholder="Periodo" value="<?php echo $data_final_mes_bd; ?>">
        </div>
    </div>

    <div class="col-md mb-2">
        <input type="text" id="palavra_chave" class="form-control" placeholder="Pesquise pela descrição ou código">
    </div>
</div>
<div class="row">
    <div class="col-md-auto  d-grid gap-2 d-sm-block mb-2">
        <button class="btn btn-dark" id="faturamento_cliente">Por Cliente</button>
        <button class="btn btn-dark" id="faturamento_produto">Por Produto</button>
        <button class="btn btn-dark" id="faturamento_vendedor">Por Vendedor</button>
        <button class="btn btn-dark" id="faturamento_grupo_produto">Por Grupo de Produto</button>
        <button class="btn btn-dark" id="faturamento_pagamento">Por Pagamento</button>
        <button class="btn btn-dark" id="faturamento_diario">Faturamento Diario</button>
        <button class="btn btn-dark" id="openReport" type="button">Imprimir</button>
    </div>

</div>

<div class="tabela">

</div>

<script src="js/relatorio/formar_relatorio.js"></script>
<script src="js/faturamento/relatorio_faturamento/consultar_faturamento.js"></script>