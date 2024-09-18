<?php
include "../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Consultar Compras</label>
</div>
<hr>
<div class="row">
    <div class="col-auto  mb-2">
        <div class="input-group">
            <span class="input-group-text">Dt. Entrada</span>
            <input type="date" class="form-control" id="data_inicial" name="data_incial" title="" placeholder="Data Inicial" value="<?php echo $data_inicial_mes_bd ?>">
            <input type="date" class="form-control" id="data_final" name="data_final" title="" placeholder="Data Final" value="<?php echo $data_final_mes_bd; ?>">
            <input type="hidden" id="tipo_dt">
            <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                <span class="visually-hidden">Toggle Dropdown</span>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" style="cursor: pointer;" id="select_dt_emissao">Data Emissão</a></li>
                <li><a class="dropdown-item" style="cursor: pointer;" id="select_dt_entrada">Data Entrada</a></li>
            </ul>
        </div>
    </div>

    <div class="col-md  mb-2">
        <div class="input-group">
            <input type="text" class="form-control" id="pesquisa_conteudo" placeholder="Tente pesquisar pelo Nº da compra ou Fornecedor" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
        </div>
    </div>
    <div class="col-md-auto  d-grid gap-2 d-sm-block mb-1">
        <button type="button" id="adicionar_compra" class="btn btn-dark">
            <i class="bi bi-plus-circle"></i> Compra
        </button>
        <button type="button" id="importar_xml" class="btn btn-dark">
            Importar Xml
        </button>
    </div>
</div>
<div class="tabela"></div>
<div class="modal_show"></div>

<script src="js/compra/compra_mercadoria/consultar_compra.js"></script>