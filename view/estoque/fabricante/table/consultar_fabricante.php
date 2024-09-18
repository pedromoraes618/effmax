<?php 
include "../../../../conexao/conexao.php";
include "../../../../modal/estoque/fabricante/gerenciar_fabricante.php";

?>

<?php 
if(!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")){ //consultar parametro para carrregar inicialmente a tabela
    ?>
<table class="table">
    <thead>
        <tr>
            <th scope="col">Código</th>
            <th scope="col">Descrição</th>
            <th scope="col"></th>

        </tr>
    </thead>
    <tbody>
        <?php while($linha = mysqli_fetch_assoc($consultar_fabricante)){
                $id_fabricante_b = $linha['cl_id'];
                $descricao_b = $linha['cl_descricao'];
            ?>
        <tr>
            <th scope="row"><?php echo $id_fabricante_b ?></th>
            <td><?php echo $descricao_b; ?></td>

            <td class="td-btn"><button type="button" id_fabricante=<?php echo $id_fabricante_b; ?>
                    class="btn btn-sm btn-info editar_fabricante">Editar</button>
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

<script src="js/estoque/fabricante/table/editar_fabricante.js">