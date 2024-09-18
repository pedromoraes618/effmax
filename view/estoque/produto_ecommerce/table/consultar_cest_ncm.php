<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/estoque/produto/gerenciar_produto.php";

if(isset($_GET['consultar_cest'])){
    $selecionar = "selecionar_cest";
}else{
    $selecionar = "selecionar_ncm";
}
?>
<div  class="consulta">
    <table  class="table table-striped-columns">
        <thead>
            <tr>
                <th scope="col">Cest</th>
                <th scope="col">Ncm</th>
                <th scope="col">Descrição</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php while ($linha  = mysqli_fetch_assoc($buscar_cest_ncm)) {
                $cest_b = ($linha['cl_cest']);
                $ncm_b = ($linha['cl_ncm']);
                $descricao = utf8_encode($linha['cl_descricao']);
                
                if($selecionar =="selecionar_cest"){
                    $valor =  $cest_b;
                }else{
                    $valor =  $ncm_b;
                }

                echo "<tr><td>$cest_b</td><td>$ncm_b</td><td>$descricao</td><td><button type='button' valor='$valor' class='$selecionar btn btn-sm btn-danger'>Selecionar</button></td></tr>";
            }
            ?>
        </tbody>

    </table>
</div>

<script src="js/estoque/produto/include/modal_cest_ncm.js"></script>