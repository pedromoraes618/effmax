    <?php 
    include("conexao/conexao.php");
    session_start();
    //ini_set('session.gc_maxlifetime', 10800); // Expire em 1 hora (3600 segundos)

    if(!$_GET){ //verificar se foi definido valor via get
        if(isset($_SESSION["user_session_portal"])){//verificar se o usuario está logado
            include "view/menu/menu.php";   //se o usuario estiver logado passar para tela do menu principal
        }else{
            include "view/login/login.php";//se o usuario não tiver logado, voltar para tela de login
        }
    }else{
        if(isset($_SESSION["user_session_portal"])){//verificar se o usuario estálogado
            include "controllers/cotroller.php";    //passar para o controlador
        }elseif(isset($_GET['resetar_password'])){
            include "view/resetar_senha/resetar_password.php";//tela para resetar senha
        }else{//ir para a tela de login
            include "view/login/login.php";
        }
    }
    mysqli_close($conecta);//fechar conexão com o basnco
    ?>
    <script src="js/index/index.js"></script>
