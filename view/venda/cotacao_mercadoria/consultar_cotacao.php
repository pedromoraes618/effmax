<?php
include "../../../conexao/conexao.php";
include "../../../modal/venda/cotacao_mercadoria/gerenciar_cotacao.php";
include "../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Consultar Cotação</label>
</div>
<hr>

<div class="row">
    <div class="col-sm-4    mb-2">
        <div class="input-group">
            <span class="input-group-text">Data</span>
            <input type="date" class="form-control" id="data_inicial" name="data_incial" placeholder="Data Inicial" value="<?php echo $data_inicial_mes_bd ?>">
            <input type="date" class="form-control" id="data_final" name="data_final" placeholder="Data Final" value="<?php echo $data_final_mes_bd; ?>">
        </div>
    </div>


    <div class="col-md-2 mb-2">
        <select name="status" class="select2 chosen-select" id="status">
            <option value="0">Status..</option>

            <?php
            $resultados = consulta_linhas_tb($conecta, 'tb_status_cotacao');
            if ($resultados) {
                foreach ($resultados as $linha) {
                    $id = $linha['cl_id'];
                    $descricao = utf8_encode($linha['cl_descricao']);

                    echo "<option  value='$id'>$descricao</option>";
                }
            }
            ?>

        </select>
    </div>

    <div class="col-md  mb-2">
        <div class="input-group">
            <input type="text" class="form-control" id="pesquisa_conteudo" placeholder="Pesquise pelo Nº da cotação ou cliente" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
        </div>
    </Div>
    <div class="col-md-auto  d-grid gap-2 d-sm-block mb-2">
        <button type="button" id="adicionar_cotacao" class="btn btn-dark"><i class="bi bi-plus-circle"></i> Cotação</button>
    </div>
</div>

<div class="tabela tabela-consulta"></div>
<div class="modal_show"></div>


<script src="js/funcao.js"></script>
<script src="js/venda/cotacao_mercadoria/consultar_cotacao.js"></script>