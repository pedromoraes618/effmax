<?php 
include "../../../../conexao/conexao.php";
include "../../../../modal/estoque/produto/gerenciar_produto.php";

?>
<div class="consulta">
    <table class="table table-striped-columns">
        <thead>
            <tr>
                <th scope="col">Cest</th>
                <th scope="col">Ncm</th>
                <th scope="col">Descrição</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php while($linha  = mysqli_fetch_assoc($buscar_cep)){ 
                            $cest_b = ($linha['cl_cest']);
                            $ncm_b = ($linha['cl_ncm']);
                            $descricao = utf8_encode($linha['cl_descricao']);

                            echo "<tr><td>$cest_b</td><td>$ncm_b</td><td>$descricao</td><td><button type='button' valor='$cest_b' class='selecionar_cest btn btn-danger'>Selecionar</button></td></tr>";


                }
        ?>
        </tbody>

    </table>
</div>

<script src="js/estoque/produto/funcao/consultar.js"></script>