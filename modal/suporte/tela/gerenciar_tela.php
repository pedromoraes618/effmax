<?php  
//consultar categoria
if(isset($_GET['consultar_tela_categoria'])){
    include "../../../../funcao/funcao.php";

    $consulta = $_GET['consultar_tela_categoria'];
    if($consulta == "inicial"){
        $consultar_tabela_inicialmente =  verficar_paramentro($conecta,"tb_parametros","cl_id","1");//consultar parametro para carrregar inicialmente a tabela

        $select = "SELECT * from tb_categorias order by cl_ordem asc";
        $consultar_categorias= mysqli_query($conecta, $select);
        if(!$consultar_categorias){
        die("Falha no banco de dados"); // colocar o svg do erro
        }
    }else{
        $pesquisa = utf8_decode($_GET['pesquisa']);
        $select = "SELECT * from tb_categorias where cl_categoria like '%{$pesquisa}%' order by cl_ordem asc ";
        $consultar_categorias= mysqli_query($conecta, $select);
        if(!$consultar_categorias){
        die("Falha no banco de dados"); // colocar o svg do erro
        }
    }

}
//consultar subcategoria
if(isset($_GET['consultar_tela_subcategoria'])){
    include "../../../../funcao/funcao.php";
    $consulta = $_GET['consultar_tela_subcategoria'];
    if($consulta =="inicial"){

        $consultar_tabela_inicialmente =  verficar_paramentro($conecta,"tb_parametros","cl_id","1");//consultar parametro para carrregar inicialmente a tabela
        
        $select = "SELECT subc.cl_id,subc.cl_subcategoria,subc.cl_status_ativo,subc.cl_ordem_menu,subc.cl_diretorio,subc.cl_url,ctg.cl_categoria as categoria, 
        subc.cl_diretorio_bd from tb_subcategorias as subc inner join tb_categorias as ctg on ctg.cl_id = subc.cl_categoria 
        order by subc.cl_categoria desc";
        $consultar_subcategorias= mysqli_query($conecta, $select);
        if(!$consultar_subcategorias){
        die("Falha no banco de dados"); // colocar o svg do erro
        }
    }else{
        $pesquisa = utf8_decode($_GET['pesquisa']);
        $select = "SELECT subc.cl_id,subc.cl_subcategoria,subc.cl_status_ativo,subc.cl_ordem_menu,subc.cl_diretorio,subc.cl_url,ctg.cl_categoria as categoria, 
        subc.cl_diretorio_bd from tb_subcategorias as subc inner join tb_categorias as ctg on ctg.cl_id = subc.cl_categoria WHERE subc.cl_subcategoria
         like '%{$pesquisa}%' or ctg.cl_categoria  like '%{$pesquisa}%' order by subc.cl_categoria desc ";
        $consultar_subcategorias= mysqli_query($conecta, $select);
        if(!$consultar_subcategorias){
        die("Falha no banco de dados"); // colocar o svg do erro
        }
    }

}

//cadastrar categoria
if(isset($_POST['formulario_cadastrar_categoria'])){
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
        $retornar = array();
        $nome_usuario_logado = $_POST["nome_usuario_logado"];
        $id_usuario_logado = $_POST["id_usuario_logado"];
        $perfil_usuario_logado = $_POST['perfil_usuario_logado'];

        $categoria = utf8_decode($_POST["categoria"]);
        $icone = $_POST["icone"];
        $ordem = $_POST["ordem"];
    
    
        if($categoria == ""){
            $retornar["mensagem"] =mensagem_alerta_cadastro("categoria");
        }elseif($icone =="" ){
            $retornar["mensagem"] =mensagem_alerta_cadastro("icone");
        }elseif($ordem == "" ){
            $retornar["mensagem"] =mensagem_alerta_cadastro("ordem");
        }elseif($perfil_usuario_logado !="suporte"){
            $retornar["mensagem"] = mensagem_alerta_permissao();
        }else{
         

            $inset = "INSERT INTO tb_categorias (cl_categoria,cl_icone,cl_ordem) VALUES ('$categoria','$icone','$ordem')";
            $operacao_inserir = mysqli_query($conecta, $inset);
            if($operacao_inserir){
            $retornar["sucesso"] = true;
            //registrar no log
            $mensagem =  ( utf8_decode("Usúario") . " $nome_usuario_logado cadastrou a categoria $categoria");
            registrar_log($conecta,$nome_usuario_logado,$data,$mensagem);
            }
            
            
        }
        
    echo json_encode($retornar);
}



//cadastrar subcategoria
if(isset($_POST['formulario_cadastrar_subcategoria'])){
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
        $retornar = array();
        $nome_usuario_logado = $_POST["nome_usuario_logado"];
        $id_usuario_logado = $_POST["id_usuario_logado"];
        $perfil_usuario_logado = $_POST['perfil_usuario_logado'];

        $subcategoria = utf8_decode($_POST["subcategoria"]);
        $orden = $_POST["ordem"];
        $diretorio_subc = $_POST["diretorio_subc"];
        $url_sub = $_POST["url_sub"];
        $diretorio_bd = $_POST["diretorio_bd"];
        $categoria = $_POST["categoria"];
        $status_ativo = $_POST["status_ativo"];
    
    
        if($subcategoria == ""){
            $retornar["mensagem"] =mensagem_alerta_cadastro("subcategoria");
        }elseif($orden =="" ){
            $retornar["mensagem"] =mensagem_alerta_cadastro("ordem");
        }elseif($diretorio_subc == "" ){
            $retornar["mensagem"] =mensagem_alerta_cadastro("diretorio subcategoria");
        }elseif($url_sub == "" ){
            $retornar["mensagem"] =mensagem_alerta_cadastro("url subcategoria");
        }elseif($diretorio_bd == "" ){
            $retornar["mensagem"] =mensagem_alerta_cadastro("diretorio banco de dados");
        }elseif($categoria == "0" ){
            $retornar["mensagem"] ="Favor selecione a Categoria";
        }elseif($perfil_usuario_logado !="suporte"){
            $retornar["mensagem"] = mensagem_alerta_permissao();
        }elseif($status_ativo=="0"){
            $retornar["mensagem"] =mensagem_alerta_cadastro("status");
        }else{
         
            $inset = "INSERT INTO tb_subcategorias (cl_subcategoria,cl_ordem_menu,cl_diretorio,cl_url,cl_categoria,cl_diretorio_bd,cl_status_ativo) 
            VALUES ('$subcategoria','$orden','$diretorio_subc','$url_sub','$categoria','$diretorio_bd','$status_ativo')";
            $operacao_inserir = mysqli_query($conecta, $inset);
            if($operacao_inserir){
            $retornar["sucesso"] = true;
            //registrar no log
            $mensagem =  ( utf8_decode("Usuário") . " $nome_usuario_logado cadastrou a subcategoria $subcategoria");
            registrar_log($conecta,$nome_usuario_logado,$data,$mensagem);
            }
            
            
        }
        
    echo json_encode($retornar);
}


//Editar categoria
if(isset($_POST['formulario_editar_categoria'])){
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
        $retornar = array();
        $nome_usuario_logado = $_POST["nome_usuario_logado"];
        $id_usuario_logado = $_POST["id_usuario_logado"];
        $perfil_usuario_logado = $_POST['perfil_usuario_logado'];

        $id_categoria = $_POST["id_categoria"];
        $categoria = utf8_decode($_POST["categoria"]);
        $icone = $_POST["icone"];
        $ordem = $_POST["ordem"];
       
        if($categoria == ""){
            $retornar["mensagem"] =mensagem_alerta_cadastro("categoria");
        }elseif($icone == "" ){
            $retornar["mensagem"] =mensagem_alerta_cadastro("icone");
        }elseif($ordem == "" ){
            $retornar["mensagem"] =mensagem_alerta_cadastro("ordem");
        }elseif($perfil_usuario_logado !="suporte"){
            $retornar["mensagem"] = mensagem_alerta_permissao();
        }else{
         
      
        $update = "UPDATE tb_categorias set cl_categoria = '$categoria', cl_icone = '$icone', cl_ordem = '$ordem' where cl_id = $id_categoria ";
        $operacao_update = mysqli_query($conecta, $update);
        if($operacao_update){
        $retornar["sucesso"] = true;
        //registrar no log
        $mensagem =  (utf8_decode("Usúario") . " $nome_usuario_logado alterou dados da categoria $categoria");
        registrar_log($conecta,$nome_usuario_logado,$data,$mensagem);
            }  
        }
        echo json_encode($retornar);
    }

  


//Editar subcategoria
if(isset($_POST['formulario_editar_subcategoria'])){
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
        $retornar = array();
        $nome_usuario_logado = $_POST["nome_usuario_logado"];
        $id_usuario_logado = $_POST["id_usuario_logado"];
        $perfil_usuario_logado = $_POST['perfil_usuario_logado'];

        $id_subcategoria = $_POST["id_subcategoria"];
        $subcategoria = utf8_decode($_POST["subcategoria"]);
        $ordem = $_POST["ordem"];
        $diretorio_subc = $_POST["diretorio_subc"];
        $url_sub = $_POST["url_sub"];
        $diretorio_bd = $_POST["diretorio_bd"];
        $categoria = $_POST["categoria"];
        $status_ativo = $_POST["status_ativo"];
    

        if($subcategoria == ""){
            $retornar["mensagem"] =mensagem_alerta_cadastro("subcategoria");
        }elseif($ordem =="" ){
            $retornar["mensagem"] =mensagem_alerta_cadastro("ordem");
        }elseif($diretorio_subc == "" ){
            $retornar["mensagem"] =mensagem_alerta_cadastro("diretorio subcategoria");
        }elseif($url_sub == "" ){
            $retornar["mensagem"] =mensagem_alerta_cadastro("url subcategoria");
        }elseif($diretorio_bd == "" ){
            $retornar["mensagem"] =mensagem_alerta_cadastro("diretorio banco de dados");
        }elseif($categoria == "0" ){
            $retornar["mensagem"] ="Favor selecione a Categoria";
        }elseif($perfil_usuario_logado !="suporte"){
            $retornar["mensagem"] = mensagem_alerta_permissao();;
        }elseif($status_ativo=="0"){
            $retornar["mensagem"] =mensagem_alerta_cadastro("status");
        }else{
         

        $update = "UPDATE tb_subcategorias set cl_subcategoria = '$subcategoria', cl_status_ativo='$status_ativo', cl_ordem_menu = '$ordem' ,cl_diretorio = '$diretorio_subc', cl_url = '$url_sub', cl_categoria ='$categoria', cl_diretorio_bd = '$diretorio_bd' where cl_id = $id_subcategoria";
        $operacao_update = mysqli_query($conecta, $update);
        if($operacao_update){
        $retornar["sucesso"] = true;
        //registrar no log
        $mensagem =  (utf8_decode("Usuário") . " $nome_usuario_logado alterou dados da subategoria $subcategoria");
        registrar_log($conecta,$nome_usuario_logado,$data,$mensagem);
            }  
        }
        echo json_encode($retornar);
}

  





//trazer informaçãoes da categoria
if(isset($_GET['editar_categoria'])==true){
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $id_categoria_b = $_GET['id_categoria'];
    $select = "SELECT * from tb_categorias where cl_id = $id_categoria_b ";
    $consultar_categorias= mysqli_query($conecta, $select);
    $linha  = mysqli_fetch_assoc($consultar_categorias);
    $categoria_b = utf8_encode($linha['cl_categoria']);
    $icone_b = $linha['cl_icone'];
    $ordem_b = $linha['cl_ordem'];
   
    
}


//trazer informaçãoes da subcategoria
if(isset($_GET['editar_subcategoria'])==true){
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $id_subcategoria_b = $_GET['id_subcategoria'];
    $select = "SELECT * from tb_subcategorias where cl_id = $id_subcategoria_b ";
    $consultar_subcategorias= mysqli_query($conecta, $select);
    $linha  = mysqli_fetch_assoc($consultar_subcategorias);
    $subcategoria_b = utf8_encode($linha['cl_subcategoria']);
    $ordem_b = $linha['cl_ordem_menu'];
    $diretorio_b = $linha['cl_diretorio'];
    $url_b = $linha['cl_url'];
    $categoria_subcategoria_b = $linha['cl_categoria'];
    $diretorio_banco_b = $linha['cl_diretorio_bd'];
    $status_ativo_b = $linha['cl_status_ativo'];
    
    
}



//consultar categoria
$select = "SELECT * from tb_categorias order by cl_ordem ";
$consultar_categorias_selecao= mysqli_query($conecta, $select);
if(!$consultar_categorias_selecao){
die("Falha no banco de dados"); // colocar o svg do erro
}