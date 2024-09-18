<div class="title">
    <label class="form-label">Ajuste de Crédito</label>
</div>
<hr>
<form id="ajuste_credito" style="overflow:unset;max-height:max-content">
    <div class="row">
        <div class="col-md  mb-2">
            <div class="input-group">
                <input type="search" class="form-control" id="pesquisa_conteudo" placeholder="Tente pesquisar pela Razação social, cnpj ou cpf ou pelo nome fantasia" aria-label="Recipient's username" aria-describedby="button-addon2">
                <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
            </div>
        </div>
        <div class="col-md-auto  d-grid gap-2 d-sm-block mb-1">
            <button type="submit" id="ajuste_credito" class="btn btn-success">
                Realizar Ajuste
            </button>
        </div>

        <div class="alerta">

        </div>
        <div class="tabela">

        </div>
    </div>
</form>
<div class="modal_show"></div>

<script src="js/empresa/credito/ajustar_credito_tela.js"></script>