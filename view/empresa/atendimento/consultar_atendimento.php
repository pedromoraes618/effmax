<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Atendimentos</label>
</div>
<hr>

<div class="row">
    <div class="col-auto  mb-2">
        <div class="input-group">
            <span class="input-group-text">Data</span>
            <input type="date" class="form-control " id="data_inicial" name="data_incial" title="Data vencimento" placeholder="Data Inicial" value="<?php echo $data_inicial_ano_bd ?>">
            <input type="date" class="form-control " id="data_final" name="data_final" title="Data vencimento" placeholder="Data Final" value="<?php echo $data_final_ano_bd; ?>">
        </div>
    </div>
    <div class="col-md-2 mb-2">
        <select name="status" class="form-select chosen-select" id="status">
            <option value="0">Status..</option>
            <?php
            $resultados = consulta_linhas_tb($conecta, 'tb_status_tarefas');
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
            <input type="text" class="form-control" id="pesquisa_conteudo" placeholder="Tente pesquisar pela descrição, usuário ou cliente/fornecedor" aria-label="Recipient's username" aria-describedby="button-addon2">
            <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
        </div>
    </div>
    <div class="col-md-auto  d-grid gap-2 d-sm-block mb-1">
        <button type="button" id="adicionar_atendimento" class="btn btn-dark">
        <i class="bi bi-plus-circle"></i> Adicionar Atendimento
        </button>
    </div>
</div>
<div class="tabela mb-2">

</div>
<div class="modal_show">

</div>

<script src="js/empresa/atendimento/consultar_atendimento.js"></script>