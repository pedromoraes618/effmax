<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Consultar débitos</label>
</div>
<hr>

<div class="row mb-2">
   
    <div class="col-md  mb-2">
        <div class="input-group">
            <input type="search" class="form-control" id="pesquisa_conteudo" placeholder="Tente pesquisar pela razão social, nome fantasia, cpf/cnpj, telefone ou email" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
        </div>
    </div>
</div>
<div class="tabela"></div>
<div class="modal_show"></div>


<script src="js/financeiro/resumo_cobranca/consultar_resumo_cobranca.js"></script>