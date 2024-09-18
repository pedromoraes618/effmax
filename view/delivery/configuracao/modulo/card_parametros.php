<?php
include "../../../../modal/delivery/configuracao/gerenciar_configuracao.php";
?>


<div class="card p-2">
    <div class="title">
        <label class="form-label">Parâmetros</label>
    </div>

    <form id="parametros" class="p-2" style="max-height: fit-content;">
        <div class="row  mb-2">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                <button type="submit" id="button_form" class="btn btn-sm btn-success">Alterar</button>
            </div>
        </div>
        <div class="title">
            <label class="form-label">Aréa de Lançamentos</label>
        </div>
        <div class="row mb-2">
            <div class="col-auto mb-2 ">
                <label for="area_lancamento" class="form-label">Aréa de lançamento</label>
                <select name="area_lancamento" class="form-select chosen-select" id="area_lancamento">
                    <option <?php if ($habiltar_area_lancamentos == "S") {
                                echo 'selected';
                            } ?> value="S">Habilitado</option>

                    <option <?php if ($habiltar_area_lancamentos == "N") {
                                echo 'selected';
                            } ?> value="N">Desabilitado</option>
                </select>

            </div>
            <div class="col-md-3 mb-2 ">
                <label for="tempo_produto_lancamento" class="form-label">Tempo de vida (dias) </label>
                <input type="number" name="tempo_produto_lancamento" value="<?php echo $diferencia_dias_desabiltar_produtos_lancamentos; ?>" class="form-control">
            </div>

        </div>
        <hr>
        <div class="title">
            <label class="form-label">Populares</label>
        </div>
        <div class="row mb-2">
            <div class="col-auto mb-2 ">
                <label for="area_lancamento" class="form-label">Qtd Produtos </label>
                <input type="number" name="qtd_populares" value="<?php echo $quantidade_produtos_populares; ?>" class="form-control">

            </div>
        </div>

        <hr>
        <div class="title">
            <label class="form-label">Tempo de Entrega</label>
        </div>

        <div class="row mb-2">
            <div class="col-auto mb-2 ">
                <label for="habilitar_tempo_entrega_aut" class="form-label">Calcular</label>
                <select name="habilitar_tempo_entrega_aut" class="form-select chosen-select" id="habilitar_tempo_entrega_aut">
                    <option <?php if ($habilitar_tempo_entrega_aut == "S") {
                                echo 'selected';
                            } ?> value="S">Sim</option>

                    <option <?php if ($habilitar_tempo_entrega_aut == "N") {
                                echo 'selected';
                            } ?> value="N">Não</option>
                </select>

            </div>
            <div class="col-md-3 mb-2 ">
                <label for="qtd_minima_pedidos" title="Quantidade minima de pedidos na espera" class="form-label">Minimo de pedidos </label>
                <input type="number" title="Quantidade minima de pedidos na espera" name="qtd_minima_pedidos" value="<?php echo $qtd_minima_pedidos; ?>" class="form-control">
            </div>
            <div class="col-md-3 mb-2 ">
                <label for="qtd_pouca_demanda_pd" class="form-label">Pouca demanda (min)</label>
                <input type="number" name="qtd_pouca_demanda_pd" value="<?php echo $qtd_pouca_demanda_pd; ?>" class="form-control">
            </div>
            <div class="col-md-3 mb-2 ">
                <label for="qtd_alta_demanda_pd" class="form-label">Alta demanda (min) </label>
                <input type="number" name="qtd_alta_demanda_pd" value="<?php echo $qtd_alta_demanda_pd; ?>" class="form-control">
            </div>
        </div>
        <hr>
        <div class="title">
            <label class="form-label">Contatos</label>
        </div>
        <div class="row mb-2">
            <div class="col-md mb-2 ">
                <label for="link_instagram" class="form-label">Link do Instagram </label>
                <input type="text" name="link_instagram" value="<?php echo $link_instagram; ?>" class="form-control">
            </div>
            <div class="col-md mb-2 ">
                <label for="whatsapp" class="form-label">Whatsapp </label>
                <input type="text" name="whatsapp" placeholder="EX. 5598989786126" value="<?php echo $whatsapp; ?>" class="form-control">
            </div>
        </div>
    </form>
</div>




<script src="js/delivery/configuracao/modulo/parametros.js"></script>