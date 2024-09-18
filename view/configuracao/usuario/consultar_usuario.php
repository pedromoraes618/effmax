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
        <select name="status_usuario" class="form-select chosen-select" id="status_usuario">
            <option value="sn">Status..</option>
            <option value="1">Ativo</option>
            <option value="0">Inativo</option>
        </select>
    </div>
    <div class="col-md  mb-2">
        <div class="input-group">
            <input type="search" class="form-control" id="pesquisa_conteudo" placeholder="Tente pesquisar pelo usuário, código ou nome" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
        </div>
    </div>
    <div class="col-md-auto  d-grid gap-2 d-sm-block mb-2">
        <button type="button" id="adicionar_usuario" class="btn btn-dark"><i class="bi bi-plus-circle"></i> Serviço</button>
    </div>
</div>

<div class="tabela"></div>
<div class="modal_show"></div>


<script src="js/configuracao/usuario/consultar_usuario.js"></script>