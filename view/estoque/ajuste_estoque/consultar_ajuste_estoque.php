<?php
include "../../../modal/estoque/ajuste_estoque/gerenciar_ajuste_estoque.php";
?>
<div class="title">
    <label class="form-label">Consultar ajustes</label>
</div>

<hr>

<div class="row">
    <div class="row">
        <div class="col-sm-4 col-auto  mb-2">
            <div class="input-group">
                <span class="input-group-text">Dt. Ajuste</span>
                <input type="date" class="form-control" maxlength="10"  id="data_inicial" name="data_incial" title="Data vencimento" placeholder="Data Inicial" value="<?php echo $data_inicial_mes_bd ?>">
                <input type="date" class="form-control" maxlength="10"  id="data_final" name="data_final" title="Data vencimento" placeholder="Data Final" value="<?php echo $data_final_mes_bd; ?>">
            </div>
        </div>

        <div class="col-md  mb-2">
            <div class="input-group">
                <input type="text" class="form-control" id="conteudo_pesquisa" placeholder="Tente pesquisar pelo usuário, código" aria-label="Recipient's username" aria-describedby="button-addon2">
                <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
            </div>

            <div class="alerta"></div>
        </div>
        <div class="col-md-auto  d-grid gap-2 d-sm-block mb-1">
            <button class="btn btn-dark" type="button" id="adicionar_ajuste"><i class="bi bi-plus-circle"></i> Ajuste</button>
        </div>
    </div>
</div>

<div class="tabela"></div>

<div class="modal_show">

</div>

<script src="js/estoque/ajuste_estoque/consultar_ajuste_estoque.js"></script>