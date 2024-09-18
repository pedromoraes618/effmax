<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Consultar Ordem de Serviço - Diagnóstico Técnico</label>
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
    <div class="col-md-2 mb-2">
        <select name="tecnico_os" class="select2 chosen-select" id="tecnico_os">
            <option value="0">Técnico..</option>
            <?php
            $resultados = consulta_linhas_tb_filtro($conecta, 'tb_users', "cl_ativo", "1");
            if ($resultados) {
                foreach ($resultados as $linha) {
                    $id = $linha['cl_id'];
                    $responsavel = utf8_encode($linha['cl_usuario']);

                    echo "<option $selected value='$id'>$responsavel</option>";
                }
            }
            ?>
        </select>
    </div>
</div>
<div class="row mb-2">
    <div class="col-md  mb-2">
        <div class="input-group">
            <input type="search" class="form-control" id="pesquisa_conteudo" placeholder="Tente pesquisar pelo Nº da ordem, Nº Serie ou cliente" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
        </div>

    </div>
</div>

<div class="tabela"></div>
<div class="modal_show"></div>


<script src="js/servico/diagnostico_tecnico/consultar_diagnostico_tecnico.js"></script>