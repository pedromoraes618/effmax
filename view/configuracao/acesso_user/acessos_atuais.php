<?php 
include "../../../conexao/conexao.php";
include "../../../modal/configuracao/controle_de_acesso/acesso.php";
if(isset($_GET['user_id'])){
    $usuario_id = $_GET['user_id'];
}
if(isset($_GET['idsubcategoria'])){
    $id_subcategoria = $_GET['idsubcategoria'];
}



$select = "SELECT acess.cl_usuario_id,subc.cl_subcategoria,subc.cl_categoria as categoriaid,ctg.cl_categoria as categoria from tb_acessos as acess inner join tb_subcategorias as subc on subc.cl_id = acess.cl_subcategoria 
inner join tb_categorias as ctg on ctg.cl_id = subc.cl_categoria where  cl_usuario_id = '$usuario_id' and acess.cl_acesso_ativo = '1' group by categoria order by cl_ordem";
$consulta_acessos_atuais_usuario = mysqli_query($conecta,$select);
while($linha = mysqli_fetch_assoc($consulta_acessos_atuais_usuario)){
    $categoria = utf8_encode($linha['categoria']);
    $categoria_id_b = $linha['categoriaid'];
?>
<ul>
    <li><?php echo $categoria ?>
        <ul>
            <?php 
            $select = "SELECT acess.cl_usuario_id,subc.cl_subcategoria as subcategoria,subc.cl_categoria,subc.cl_id as subcategoriaid, ctg.cl_categoria as categoria from tb_acessos as acess inner join tb_subcategorias as subc on subc.cl_id = acess.cl_subcategoria 
            inner join tb_categorias as ctg on ctg.cl_id = subc.cl_categoria where  cl_usuario_id = '$usuario_id' and ctg.cl_id = '$categoria_id_b' and acess.cl_acesso_ativo = '1'";
            $consulta_acessos_atuais_usuario_subcategoria = mysqli_query($conecta,$select);
            while($linha = mysqli_fetch_assoc($consulta_acessos_atuais_usuario_subcategoria)){
                $subcategoria = utf8_encode($linha['subcategoria']);
                $subcategoria_id = $linha['subcategoriaid'];
            ?>
            <li id="<?php echo $subcategoria_id; ?>">
                <p><?php echo $subcategoria; ?></p><a class="card_acess col-auto btn btn-outline-danger" id="<?php echo $subcategoria_id; ?>">Remover</a>
            </li>
            <?php 
            }
            ?>
        </ul>
    </li>
    <hr>
</ul>
<?PHP 
}?>


<script src="js/configuracao/controle_de_acesso/script_remove_access.js"></script>
