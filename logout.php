<?php        
unset($_SESSION["user_session_portal"]);
if (isset($_COOKIE['algn'])) {//verificar se tem algum cookie de lembrar
    setcookie("algn",null, -1,'/');//remover o cookie de login automatico
}
header("Location: ../$empresa");