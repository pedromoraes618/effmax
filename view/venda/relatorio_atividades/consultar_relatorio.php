<?php include "../../../modal/venda/relatorio_atividades/gerenciar_relatorio.php"; ?>

<div class="title">
    <label class="form-label">Relátorio de atividades</label>
</div>
<div class="row mb-2">
    <div class="col-auto mb-2">
        <div class="input-group">
            <span class="input-group-text">Período</span>
            <input type="date" class="form-control " id="data_inicial" name="data_incial" title="Data vencimento" placeholder="Data Inicial" value="<?php echo $data_inicial_mes_bd; ?>">
            <input type="date" class="form-control " id="data_final" name="data_final" title="Data vencimento" placeholder="Data Final" value="<?php echo $data_lancamento; ?>">
        </div>
    </div>
    <div class="col-md-auto  d-grid gap-2 d-sm-block mb-2">
        <div class="btn-group">
            <button class="btn btn-dark consultar_relatorio" id="vendas"><i class="bi bi-search"></i> Venda</button>
            <button class="btn btn-dark consultar_relatorio" id="cotacao_geral"><i class="bi bi-search"></i> Cotação</button>
        </div>
    </div>
</div>
<!-- <div class="tabela print">

</div> -->

<div class="dashboard"></div>

<script src="funcao/funcao.js"></script>
<script src="js/venda/relatorio_atividades/consultar_relatorio.js"></script>