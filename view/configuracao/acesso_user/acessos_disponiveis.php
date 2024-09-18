<?php 
include "../../../conexao/conexao.php";
include "../../../modal/configuracao/controle_de_acesso/acesso.php";

if(isset($_GET['user_id'])){
    $usuario_id = $_GET['user_id'];
}
if(isset($_GET['idsubcategoria'])){
    $id_subcategoria = $_GET['idsubcategoria'];
}


$select = "SELECT * from tb_subcategorias";
$consulta_acessos_usuario = mysqli_query($conecta,$select);
while($linha = mysqli_fetch_assoc($consulta_acessos_usuario)){
    $id_subcategoria_b = $linha['cl_id'];
    $subcategoria_b = utf8_encode($linha['cl_subcategoria']);
    $status_ativo = utf8_encode($linha['cl_status_ativo']);
    if(consultar_ativo_acesso_usuario($usuario_id,$id_subcategoria_b) == 0 and $status_ativo =="SIM"){//verofocar se o usuario não tem o acesso e verificar se o acesso está ativo
?>
<div class="card_acess btn btn-outline-primary" id="<?php echo $id_subcategoria_b; ?>" id_subcategoria=<?php echo $id_subcategoria_b;  ?>>
    <p><?php echo $subcategoria_b; ?></p>

</Div>
<?PHP 
    }
}?>


<script src="js/configuracao/controle_de_acesso/script_add_access.js"></script>