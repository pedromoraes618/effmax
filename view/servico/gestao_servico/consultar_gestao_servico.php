<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Gestão de serviço</label>
</div>
<hr>

<div class="row mb-2">
    <div class="col-auto  mb-2">
        <div class="input-group">
            <span class="input-group-text">Data Abertura</span>
            <input type="date" class="form-control " id="data_inicial" name="data_incial" title="Data Inicial" placeholder="Data Inicial" value="<?php echo $data_inicial_mes_bd ?>">
            <input type="date" class="form-control " id="data_final" name="data_final" title="Data Final" placeholder="Data Final" value="<?php echo $data_lancamento; ?>">
        </div>
    </div>

    <div class="col-md-2 mb-2">
        <select name="status_ordem" class="select2 chosen-select" id="status_ordem">
            <option value="0">Status Ordem..</option>
            <?php
            $resultados = consulta_linhas_tb($conecta, 'tb_status_os');
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
            <input type="search" class="form-control" id="pesquisa_conteudo" placeholder="Tente pesquisar pelo Nº da ordem, serie, cliente ou equipamento" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
        </div>
    </div>

</div>


<div class="tabela"></div>
<div class="modal_show"></div>


<script src="js/servico/gestao_servico/consultar_gestao_servico.js"></script>