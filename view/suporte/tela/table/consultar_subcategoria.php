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
            <th scope="col">Subcategoria</th>

            <th scope="col">Diretorio Subcategoria</th>
    
            <th scope="col">Categoria</th>
            <th scope="col">Status</th>

            <th scope="col"></th>
        
        </tr>
    </thead>
    <tbody>
        <?php while($row = mysqli_fetch_assoc($consultar_subcategorias)){
                $id_subcategoria_b = $row['cl_id'];
                $subcategoria_b = utf8_encode($row['cl_subcategoria']);
                $ordem_menu_b = $row['cl_ordem_menu'];
                $diretorio_b = $row['cl_diretorio'];
                $url_b = $row['cl_url'];
                $diretorio_banco_b = $row['cl_diretorio_bd'];
                $categoria_b = utf8_encode($row['categoria']);
                $status = utf8_encode($row['cl_status_ativo']);
            
            
            ?>
        <tr>
            <th scope="row"><?php echo $ordem_menu_b ?></th>
            <td><?php echo $subcategoria_b; ?></td>
     
            <td><?php echo $diretorio_b; ?></td>
            <td><?php echo $categoria_b; ?></td>
            <td><span class='badge text-bg-<?php echo ($status == "SIM") ? 'success' : 'danger' ?>'><?php echo ($status == "SIM") ? 'Ativo' : 'Inativo' ?></td>

            
            <td class="td-btn"> <button type="button" onclick="backToTop();" id_subcategoria=<?php echo $id_subcategoria_b; ?> class="btn btn-sm btn-info editar_subcategoria">Editar</button>
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
<script src="js/suporte/tela/table/editar_subcategoria.js"></script>