<?php
include "../../../../modal/delivery/configuracao/gerenciar_configuracao.php";
?>






<div class="card p-3 ">
    <div class="title">
        <label class="form-label">Taxa de Entrega</label>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-3 mb-2">
            <select name="promocao" class="form-select chosen-select" id="promocao">
                <option value="0">Promoção..</option>
                <option value="SIM">Sim</option>
                <option value="NAO">Não</option>
            </select>
        </div>

        <div class="col-md  mb-2">
            <div class="input-group">
                <input type="text" class="form-control" id="pesquisa_conteudo_frete" placeholder="Tente pesquisar pelo bairro ou valor" aria-label="Recipient's username" aria-describedby="button-addon2">
                <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa_frete">Pesquisar</button>
            </div>
        </div>

    </div>

    <form id="frete" class="p-2">

        <div class="row  mb-2">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                <button class="btn btn-sm btn-dark" type="button" id="adicionar_frete">Adicionar</button>
                <button type="submit" id="button_form" class="btn btn-sm btn-success">Alterar</button>
            </div>
        </div>
        <div class="tabela">

        </div>
    </form>
</div>




<script src="js/delivery/configuracao/modulo/frete.js"></script>