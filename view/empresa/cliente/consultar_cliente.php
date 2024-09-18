<div class="title">
    <label class="form-label">Consultar Parceiros</label>
    <div class="msg_title">
        <p> A tela de consulta de parceiros permite buscar
            e visualizar informações sobre os parceiros cadastrados no sistema.</p>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-2 mb-2">
        <select name="status" class="form-select chosen-select" id="status">
            <option value="0">Status..</option>
            <option value="SIM">Ativo</option>
            <option value="NAO">Inativo</option>
        </select>
    </div>
    <div class="col-md  mb-2">
        <div class="input-group">
            <input type="text" class="form-control" id="pesquisa_conteudo" placeholder="Tente pesquisar pela Razação social, cnpj ou cpf ou pelo nome fantasia" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
        </div>
    </div>
    <div class="col-md-auto  d-grid gap-2 d-sm-block mb-1">
        <button class="btn btn-dark" type="button" id="adicionar_cliente"><i class="bi bi-plus-circle"></i> Parceiro</button>
    </div>
</div>
<div class="alerta">

</div>
<div class="tabela">

</div>
<div class="modal_show">

</div>

<script src="js/empresa/cliente/consultar_cliente.js"></script>