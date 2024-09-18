<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Consultar débitos por periodo</label>
</div>
<hr>

<div class="row mb-2">
    <div class="col-md-auto  mb-2">
        <div class="input-group">
            <span class="input-group-text">Dt. Venc</span>
            <input type="date" class="form-control  " id="data_inicial" name="data_incial" title="Inicio" placeholder="Data Inicial" value="<?php echo $data_inicial_mes_bd ?>">
            <input type="date" class="form-control " id="data_final" name="data_final" title="Fim" placeholder="Data Final" value="<?php echo $data_final_mes_bd; ?>">
        </div>
    </div>
    <div class="col-md  mb-2">
        <div class="input-group">
            <input type="search" class="form-control" id="pesquisa_conteudo" placeholder="Tente pesquisar pela razão social, nome fantasia, cpf/cnpj, telefone ou email" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
        </div>
    </div>
</div>

<div class="tabela"></div>
<div class="modal_show"></div>


<script src="js/financeiro/resumo_cobranca/consultar_resumo_periodo.js"></script>