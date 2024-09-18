<?php
include "../../../../modal/estoque/produto/gerenciar_produto.php";

$resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_marcadores where cl_codigo_nf ='$codigo_nf'");
if ($resultados) {
    foreach ($resultados as $linha) {
        $id = $linha['cl_id'];
        $descricao = utf8_encode($linha['cl_descricao']);
?>
        <span class="badge rounded-pill text-bg-dark mb-2">
            <?= $descricao; ?>
            <button type="button" class="btn ms-1 border-0 p-0 remover_marcador" data-id=<?= $id; ?>>
                <i class="bi bi-x-circle-fill" style="color: white;"></i> </button>
        </span>
<?php
    }
}
?>


<script src="js/include/produto/table/consultar_marcadores.js">