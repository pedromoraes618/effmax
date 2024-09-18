<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/estoque/subgrupo_estoque/gerenciar_subgrupo_estoque.php";

?>

<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">Código</th>
                <?php if ($imagem_tabela == "S" and $sistema_delivery =='S' ) {
                    echo "<th scope='col'>Imagem</th>";
                } ?>
                <th scope="col">Descrição</th>
                <th scope="col">Grupo</th>
                <th scope="col">Cfop Interno</th>
                <th scope="col">Cfop Externo</th>
                <th scope="col">Estoque Inicial</th>
                <th scope="col">Und</th>
                <th scope="col"></th>

            </tr>
        </thead>
        <tbody>
            <?php while ($linha = mysqli_fetch_assoc($consultar_subgrupo_estoque)) {
                $id_subgrupo_b = $linha['cl_id'];
                $descricao_b = utf8_encode($linha['cl_descricao']);
                $grupo_b = utf8_encode($linha['grupo']);
                $cfop_interno = $linha['cl_cfop_interno'];
                $cfop_externo = $linha['cl_cfop_externo'];
                $estoque_inicial_b = $linha['cl_estoque_inicial'];

                $local_estoque_b = utf8_encode($linha['cl_local']);
                $unidade_b = utf8_encode($linha['unidade']);
                $img_subgrupo_estoque_b = ($linha['cl_img_subgrupo_estoque']);

            ?>
                <tr>
                    <th scope="row"><?php echo $id_subgrupo_b ?></th>
                    <?php if ($imagem_tabela == "S" and $sistema_delivery =='S' ) {
                        echo "<td><img src='img/subgrupo_estoque/$img_subgrupo_estoque_b' class='card-thumbnail mx-2 p-0 mb-0 rounded-circle' style='width: 35px; height: 35px;'></td>";
                    } ?>
                    <td><?php echo $descricao_b; ?></td>
                    <td><?php echo $grupo_b; ?></td>
                    <td><?php echo $cfop_interno; ?></td>
                    <td><?php echo $cfop_externo; ?></td>
                    <td><?php echo $estoque_inicial_b; ?></td>
                    <td><?php echo $unidade_b; ?></td>
                    
                    <td class="td-btn"><button type="button" id_subgrupo=<?php echo $id_subgrupo_b; ?> class="btn btn-info btn-sm editar_subgrupo">Editar</button>
                    </td>
                </tr>

            <?php } ?>
        </tbody>
    </table>
<?php

} else {
    include "../../../../view/alerta/alerta_pesquisa.php"; // mesnsagem para usuario pesquisar
}
?>
<script src="js/estoque/subgrupo_estoque/table/editar_subgrupo_estoque.js">