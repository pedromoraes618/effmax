<?php
include "../../../conexao/conexao.php";
include "../../../modal/estoque/movimento_estoque/gerenciar_movimento.php";
include "../../../funcao/funcao.php";
?>
<div class="title">
    <label class="form-label">Movimento do Estoque</label>
</div>
<hr>
<div class="row mb-2">
    <div class="col-auto mb-2">
        <div class="input-group">
            <span class="input-group-text">Período</span>
            <input type="date" class="form-control " id="data_inicial" name="data_incial" title="Periodo" placeholder="Periodo" value="<?php echo $data_inicial_mes_bd ?>">
            <input type="date" class="form-control " id="data_final" name="data_final" title="Periodo" placeholder="Periodo" value="<?php echo $data_final_mes_bd; ?>">
        </div>
    </div>

    <div class="col-md mb-2">
        <input type="text" id="palavra_chave" class="form-control" placeholder="Pesquise pela descrição ou código">
    </div>
    <div class="col-md mb-2">
        <select class="form-select chosen-select" id="grupo" multiple aria-label="Multiple select example">
            <?php
            $resultados = consulta_linhas_tb($conecta, 'tb_subgrupo_estoque');
            if ($resultados) {
                foreach ($resultados as $linha) {
                    $id = $linha['cl_id'];
                    $descricao_subgrupo = utf8_encode($linha['cl_descricao']);
                    $grupo_id = ($linha['cl_grupo_id']);
                    $descricao_grupo = consulta_tabela($conecta, "tb_grupo_estoque", "cl_id", $grupo_id, "cl_descricao");
                    echo "<option  value='$id'>$descricao_grupo - $descricao_subgrupo</option>";
                }
            }
            ?>
        </select>
    </div>
</div>
<div class="row">
    <div class="col-md-auto  d-grid gap-2 d-sm-block mb-2">
        <div class="btn-group">
            <button class="btn btn-sm btn-dark" id="movimento_estoque"><i class="bi bi-search"></i> Movimentação</button>
            <button class="btn btn-sm btn-dark" id="estoque_minimo_maximo"><i class="bi bi-search"></i> Abaixo do mínimo</button>
            <button class="btn btn-sm btn-dark" id="estoque_zerado"><i class="bi bi-search"></i> Sem estoque</button>
            <button class="btn btn-sm btn-dark" id="sugestao_compra"><i class="bi bi-search"></i> Sugestão de compra</button>
            <button class="btn btn-sm btn-dark" id="openReport" type="button"><i class="bi bi-printer"></i> Imprimir</button>
        </div>
    </div>

</div>

<div class="tabela">

</div>


<script src="js/estoque/movimento_estoque/consultar_movimento.js"></script>
<script src="js/relatorio/formar_relatorio.js"></script>