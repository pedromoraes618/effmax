<?php 
include "../../../../conexao/conexao.php";
include "../../../../modal/suporte/tela/gerenciar_tela.php";
?>

<?php 
if(!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")){ //consultar parametro para carrregar inicialmente a tabela
?>

<table class="table table-hover">
    <thead>
        <tr>
        <th scope="col">Ordem</th>
            <th scope="col">Categoria</th>
            <th scope="col">Icone</th>
        

        </tr>
    </thead>
    <tbody>
        <?php while($row = mysqli_fetch_assoc($consultar_categorias)){
                $id_categoria_b = $row['cl_id'];
                $categoria_b = utf8_encode($row['cl_categoria']);
                $icone_b = $row['cl_icone'];
                $ordem_b = $row['cl_ordem'];

            ?>
        <tr>
            <th scope="row"><?php echo $ordem_b ?></th>
            <td><?php echo $categoria_b; ?></td>
            <td><?php echo $icone_b; ?></td>
         
            <td class="td-btn"><button type="button" id_categoria=<?php echo $id_categoria_b; ?>
                    class="btn btn-sm btn-info editar_categoria">Editar</button>
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
<script src="js/suporte/tela/table/editar_categoria.js"></script>