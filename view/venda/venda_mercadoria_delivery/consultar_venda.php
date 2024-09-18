<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Consultar Vendas</label>
</div>
<hr>

    <div class="row mb-2">
        <div class="col-md  mb-2">
            <div class="input-group">
                <span class="input-group-text">Dt. Venda</span>
                <input type="date" class="form-control " id="data_inicial" name="data_incial" title="Data vencimento" placeholder="Data Inicial" value="<?php echo $data_lancamento ?>">
                <input type="date" class="form-control " id="data_final" name="data_final" title="Data vencimento" placeholder="Data Final" value="<?php echo $data_lancamento; ?>">
            </div>
        </div>
        <div class="col-md-2 mb-2">
            <select name="status_recebimento" class="form-select chosen-select" id="status_recebimento">
                <option value="0">Status Recebimento..</option>
                <option value="1">Pendente</option>
                <option value="2">Recebido</option>
            </select>
        </div>
        <div class="col-md-2  mb-2">
            <select name="forma_pgt" class="form-select chosen-select" id="forma_pgt">
                <option value="0">Forma Pagamento..</option>
                <?php
                $resultados = consulta_linhas_tb($conecta, 'tb_forma_pagamento');
                if ($resultados) {
                    foreach ($resultados as $linha) {
                        $id = $linha['cl_id'];
                        $descricao = utf8_encode($linha['cl_descricao']);

                        echo "<option value='$id'>$descricao</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="col-md  mb-2">
            <div class="input-group">
                <input type="text" class="form-control" id="pesquisa_conteudo" placeholder="Tente pesquisar pelo Nº da venda ou cliente" aria-label="Recipient's username" aria-describedby="button-addon2">
                <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
            </div>
        </Div>
        <div class="col-md-auto  d-grid gap-2 d-sm-block mb-2">
            <button type="button" id="adicionar_venda" class="btn btn-dark"><i class="bi bi-plus-circle"></i> Venda</button>

        </div>
    </div>


<div class="tabela">

</div>
<div class="modal_show">

</div>


<script src="js/funcao.js"></script>
<script src="js/venda/venda_mercadoria_delivery/consultar_venda.js"></script>