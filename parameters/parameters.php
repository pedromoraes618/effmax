<?php
$consulta  = "SELECT * FROM tb_empresa WHERE cl_id = 1";
$consulta_empresa = mysqli_query($conecta, $consulta);
$row = mysqli_fetch_assoc($consulta_empresa);
$empresa = $row['cl_site'];

/*tema do sistema*/
$select  = "SELECT * FROM tb_parametros WHERE cl_id = 7";
$consulta_parametro_tema = mysqli_query($conecta, $select);
$linha = mysqli_fetch_assoc($consulta_parametro_tema);
$tema_sistema = $linha['cl_valor'];


?>
