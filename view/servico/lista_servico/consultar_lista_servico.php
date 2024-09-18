<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Consultar Serviços</label>
</div>
<hr>
<div class="row mb-2">
    <div class="col-md-2 mb-2">
        <select name="status_servico" class="form-select chosen-select" id="status_servico">
            <option value="sn">Status Serviço..</option>
            <option value="1">Ativo</option>
            <option value="0">Inativo</option>
        </select>
    </div>
    <div class="col-md  mb-2">
        <div class="input-group">
            <input type="search" class="form-control" id="pesquisa_conteudo" placeholder="Tente pesquisar pelo código ou descrição" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
        </div>
    </div>
    <div class="col-md-auto  d-grid gap-2 d-sm-block mb-2">
        <button type="button" id="adicionar_servico" class="btn btn-dark"><i class="bi bi-plus-circle"></i> Serviço</button>
    </div>
</div>
<div class="tabela"></div>
<div class="modal_show"></div>


<script src="js/servico/lista_servico/consultar_lista_servico.js"></script>