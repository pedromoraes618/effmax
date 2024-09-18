<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Consultar Documentos</label>
</div>
<hr>
<div class="row mb-2">
    <div class="col-auto  mb-2">
        <div class="input-group">
            <span class="input-group-text">Data</span>
            <input type="date" class="form-control " id="data_inicial" name="data_incial" title="Data Inicial" placeholder="Data Inicial" value="<?php echo $data_lancamento ?>">
            <input type="date" class="form-control " id="data_final" name="data_final" title="Data Final" placeholder="Data Final" value="<?php echo $data_lancamento; ?>">
        </div>
    </div>

    <div class="col-md-2 mb-2">
        <select name="status_recebimento" class=" chosen-select select2" id="status_recebimento">
            <option value="0">Recebimento..</option>
            <option value="1">Pendente</option>
            <option value="2">Recebido</option>
        </select>
    </div>

    <div class="col-md  mb-2">
        <div class="input-group">
            <input type="text" class="form-control" id="pesquisa_conteudo" placeholder="Tente pesquisar pelo Nº da venda ou cliente" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
        </div>
    </div>
</div>
<div class="tabela"></div>
<div class="modal_show"></div>



<script src="js/servico/consultar_nf/consultar_nf.js"></script>