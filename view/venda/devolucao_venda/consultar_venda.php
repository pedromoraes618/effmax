<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Consultar Devolução de Venda</label>
</div>
<hr>

<div class="row mb-2">
    <div class="col-auto  mb-2">
        <div class="input-group">
            <span class="input-group-text">Dt Devolução</span>
            <input type="date" class="form-control " id="data_inicial" name="data_incial" title="Data Inicial" placeholder="Data Inicial" value="<?php echo $data_lancamento ?>">
            <input type="date" class="form-control " id="data_final" name="data_final" title="Data Final" placeholder="Data Final" value="<?php echo $data_lancamento; ?>">
        </div>
    </div>


    <!-- <div class="col-md-2 mb-2">
        <select name="status_recebimento" class="form-select chosen-select" id="status_recebimento">
            <option value="0">Status Recebimento</option>
            <option value="1">Pendente</option>
            <option value="2">Recebido</option>
        </select>
    </div> -->

    <div class="col-md  mb-2">
        <div class="input-group">
            <input type="text" class="form-control" id="pesquisa_conteudo" placeholder="Tente pesquisar pelo Nº da venda ou cliente" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
        </div>
    </div>
</div>
<div class="row mb-2">
    <div class="col-md-10 mb-2">
        <select name="cfop_gerar" class="select2" id="cfop_gerar">
            <option value="0">Cfop..</option>
            <?php
            $resultados = consulta_linhas_tb($conecta, 'tb_cfop');
            if ($resultados) {
                foreach ($resultados as $linha) {
                    $codigo_cfop = $linha['cl_codigo_cfop'];
                    $desc_cfop = utf8_encode($linha['cl_desc_cfop']);

                    echo "<option  value='$codigo_cfop'>$codigo_cfop $desc_cfop</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="col-md-2  d-grid gap-2 mb-2">
        <button class="btn btn-sm btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Gerar Doc</button>
        <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" style="cursor: pointer;" id="gerar_nfe">Gerar NFE</a></li>
            <li><a class="dropdown-item" style="cursor: pointer;" id="gerar_nfc">Gerar NFC</a></li>
        </ul>
    </div>
</div>
<div class="tabela">

</div>
<div class="modal_show">

</div>


<script src="js/funcao.js"></script>
<script src="js/venda/devolucao_venda/consultar_venda.js"></script>