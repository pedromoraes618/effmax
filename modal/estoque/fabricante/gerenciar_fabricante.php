<?php  
//consultar informações para tabela
if(isset($_GET['consultar_fabricante'])){
include "../../../../conexao/conexao.php";
include "../../../../funcao/funcao.php";
        $consulta = $_GET['consultar_fabricante'];
        if($consulta== "inicial"){
        $consultar_tabela_inicialmente =  verficar_paramentro($conecta,"tb_parametros","cl_id","1");//VERIFICAR PARAMETRO ID - 1

        $select = "SELECT * from tb_fabricantes order by cl_id";
        $consultar_fabricante= mysqli_query($conecta, $select);
        if(!$consultar_fabricante){
        die("Falha no banco de dados");
        }
    
        }else{
        $pesquisa = utf8_decode($_GET['conteudo_pesquisa']);//filtro
        $select = "SELECT * from tb_fabricantes where cl_descricao like '%{$pesquisa}%'  order by cl_id";
        $consultar_fabricante= mysqli_query($conecta, $select);
        if(!$consultar_fabricante){
        die("Falha no banco de dados"); 
        }
    }
}

//cadastrar formulario
if(isset($_POST['formulario_cadastrar_fabricante'])){
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
        $retornar = array();
        $nome_usuario_logado = $_POST["nome_usuario_logado"];
        $id_usuario_logado = $_POST["id_usuario_logado"];
        $perfil_usuario_logado = $_POST['perfil_usuario_logado'];

        $descricao = utf8_decode($_POST["descricao"]);
       
        if($descricao == ""){
            $retornar["dados"] = array("sucesso"=>"false","title"=>mensagem_alerta_cadastro("descricão"));
        }else{
         
        $inset = "INSERT INTO tb_fabricantes (cl_descricao)
         VALUES ('$descricao')";
        $operacao_inserir = mysqli_query($conecta, $inset);
        if($operacao_inserir){

          $retornar["dados"] =array("sucesso"=>true,"title"=>"Fabricante cadastrado com sucesso");
        //registrar no log
        $mensagem =  ( utf8_decode("Usúario") . " $nome_usuario_logado cadastrou o fabricante $descricao ");
        registrar_log($conecta,$nome_usuario_logado,$data,$mensagem);
        }
        

        }
        
    echo json_encode($retornar);
}


// //Editar formulario
if(isset($_POST['formulario_editar_fabricante'])){
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
        $retornar = array();
        $nome_usuario_logado = $_POST["nome_usuario_logado"];
        $id_usuario_logado = $_POST["id_usuario_logado"];
        $perfil_usuario_logado = $_POST['perfil_usuario_logado'];

        $id_fabricante = $_POST["id_fabricante"];
        $descricao = utf8_decode($_POST["descricao"]);
    
        if($descricao == ""){
            $retornar["dados"] = array("sucesso"=>"false","title"=>mensagem_alerta_cadastro("descricão"));
        }else{

        $update = "UPDATE tb_fabricantes set cl_descricao = '$descricao' where cl_id = $id_fabricante";
        $operacao_update = mysqli_query($conecta, $update);
        if($operacao_update){
            $retornar["dados"] =array("sucesso"=>true,"title"=>"Fabricante alterado com sucesso");
        //registrar no log
        $mensagem =  (utf8_decode("Usúario") . " $nome_usuario_logado alterou dados do fabricante de codigo $id_fabricante");
        registrar_log($conecta,$nome_usuario_logado,$data,$mensagem);
        }  
        
    }
    echo json_encode($retornar);
}


//remover formulario
if(isset($_POST['remover_fabricante'])){
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
        $retornar = array();
      
        $nome_usuario_logado = $_POST["nome_usuario_logado"];
        // $id_usuario_logado = $_POST["id_usuario_logado"];
        // $perfil_usuario_logado = $_POST['perfil_usuario_logado'];

        $id_fabricante = $_POST["id_fabricante"];

        if(verificar_dados_existentes($conecta,"tb_produtos","cl_fabricante_id",$id_fabricante) > 0){ // verificar se o fabricante está vinculado com algum produto cadastrado no sistema
            $retornar["dados"] = array("sucesso"=>false,"title"=>"Não é possivel remover esse Fabricante, pois esse fabricante está vinculado a um ou mais produtos em nosso sistema.");
        }else{

            $fabricante = consulta_tabela($conecta,"tb_fabricantes","cl_id",$id_fabricante,"cl_descricao");//consultar a descricao do fabricante

            $update = "DELETE FROM tb_fabricantes WHERE cl_id = $id_fabricante";
            $operacao_delete = mysqli_query($conecta, $update);
            if($operacao_delete){
            $retornar["dados"] = array("sucesso"=>true,"title"=>"Fabricante removido com sucesso");
            //registrar no log
            $mensagem =  (utf8_decode("Usúario") . " $nome_usuario_logado removeu o fabricante  $fabricante");
            registrar_log($conecta,$nome_usuario_logado,$data,$mensagem);
            }  
        }
     
        echo json_encode($retornar);

}

//trazer informaçãoes
if(isset($_GET['editar_fabricante'])==true){
    include "../../../conexao/conexao.php";
    include "../../../funcao/funcao.php";
    $id_fabricante = $_GET['id_fabricante'];

    $select = "SELECT * from tb_fabricantes where cl_id = $id_fabricante";
    $consultar_fabricante= mysqli_query($conecta, $select);
    $linha  = mysqli_fetch_assoc($consultar_fabricante);

    $descricao_b = utf8_encode($linha['cl_descricao']);

 
}