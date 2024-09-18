<?php
include "../../../modal/delivery/relatorio/gerenciar_relatorio.php";
include "../../../funcao/funcao.php";
?>


<div class="title mb-2">
    <label class="form-label">Relatorio Delivery</label>
    <div class="msg_title">
        <p>Consulte os mais diversos relatorios para a tomada de decisão para o seu Delivery </p>
    </div>
</div>
<div class="row">
    <div class="col-md-auto mb-2">
        <div class="input-group">
            <span class="input-group-text">Período</span>
            <input type="date" class="form-control" id="data_inicial" name="data_incial" title="Data vencimento" placeholder="Data Inicial" value="<?php echo $data_inicial_mes_bd ?>">
            <input type="date" class="form-control" id="data_final" name="data_final" title="Data vencimento" placeholder="Data Final" value="<?php echo $data_final_mes_bd; ?>">
            <button class="btn btn-secondary" type="button" id="pesquisar_filtro_pesquisa">Consultar</button>

        </div>
    </div>
</div>
<hr>
<div class="row">
    <div class="vendas border-0 col-md-6 mb-2">

    </div>
    <div class="produtos_vendas border-0 col-md-6 mb-2" >

    </div>
    <div class="parametros border-0 col-md-6 mb-2">

    </div>
    <div class="img_card_combo_tela_principal border-0 col-md-6">

    </div>

</div>

<div class="modal_show">

</div>
<script src="js/delivery/relatorio/consultar_relatorio.js"></script>