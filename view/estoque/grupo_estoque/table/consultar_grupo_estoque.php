<?php 
include "../../../../conexao/conexao.php";
include "../../../../modal/estoque/grupo_estoque/gerenciar_grupo_estoque.php";

?>

<?php 
if(!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")){ //consultar parametro para carrregar inicialmente a tabela
    ?>
<table class="table">
    <thead>
        <tr>
            <th scope="col">Código</th>
            <th scope="col">Descrição</th>
            <th scope="col">Pertence</th>
            <th scope="col"></th>

        </tr>
    </thead>
    <tbody>
        <?php while($linha = mysqli_fetch_assoc($consultar_grupo_estoque)){
                $id_grupo_b = $linha['cl_id'];
                $descricao_b = utf8_encode($linha['cl_descricao']);
                $grupo_venda_b = utf8_encode($linha['cl_grupo_venda']);
                $grupo_servico_b = utf8_encode($linha['cl_grupo_servico']);
            ?>
        <tr>
            <th scope="row"><?php echo $id_grupo_b ?></th>
            <td><?php echo $descricao_b; ?></td>

            <td>
                <?php
                if($grupo_venda_b == 1){
                echo "<span class='badge text-bg-primary'>Grupo para venda </span>";
                }
                if($grupo_servico_b == 1){
                 echo "<span class='badge text-bg-warning'>Grupo para serviço</span>";
                }
                ?>
               
            </td>

            <td class="td-btn"><button type="button" id_grupo=<?php echo $id_grupo_b; ?>
                    class="btn btn-info btn-sm editar_grupo_estoque">Editar</button>
            </td>
        </tr>

        <?php }?>
    </tbody>
</table>
<?php
}else{
    include "../../../../view/alerta/alerta_pesquisa.php"; // mesnsagem para usuario pesquisar
    
}
?>

<script src="js/estoque/grupo_estoque/table/editar_grupo_estoque.js">