<?php  include "parameters/parameters.php"; ?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link href="css/login.css" rel="stylesheet">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/e8ff50f1be.js" crossorigin="anonymous"></script>

</head>

<body>
    <header>

        <div class="bloco">
            <div class="group-bloco">
                <div class="titulo">
                    <!-- <i class="fa-solid fa-right-to-bracket"></i> -->
                    <img class="img-logo" width="150" src="img/logo.png" alt="">
             
                    <p>Login</p>
                
                </div>

                <form id="form-login">
                    <div class="group-input">
                        <div class="gp-input">
                            <input type="text" onchange="verificarPreenchimento()"  class="form-control" name="usuario" id="usuario" value=""
                                placeholder="Usuário">
                        </div>

                        <div class="gp-input">
                            <input type="password" onchange="verificarPreenchimento()" class="form-control" name="senha" id="senha" value=""
                                placeholder="Senha">
                            <i id="mostrar_senha" class="fa-solid fa-eye"></i>
                        </div>
                    </div>
                    <div class="btn-gp">
                        <button type="submit" name="btn_login" id="btn_login" disabled
                            class="btn btn-success">Login</button>
                        <!-- <button type="submit" name="btn_cadastrar" id="btn_cadastar"
                            class="btn btn-success">cadastrar</button> -->
                    </div>
                    <div class="sub-titulo">
                        <div class="bloco-1">
                            <label> 
                                <input name="lembrar_senha"  id="lembrar_senha" type="checkbox" />
                                Lembrar Senha
                            </label>

                        </div>
                        <div class="bloco-2">
                            <a href="?resetar_password">Alterar senha</a>
                        </div>


                    </div>
                    <p class="reservado">©[2023] Effmax. Todos os direitos reservados</p>
                </form>

            </div>

        </div>


    </header>
    <footer>
        <svg class="waves" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
            <path fill="#43818A" fill-opacity="0.3"
                d="M0,128L40,149.3C80,171,160,213,240,192C320,171,400,85,480,53.3C560,21,640,43,720,85.3C800,128,880,192,960,197.3C1040,203,1120,149,1200,138.7C1280,128,1360,160,1400,176L1440,192L1440,320L1400,320C1360,320,1280,320,1200,320C1120,320,1040,320,960,320C880,320,800,320,720,320C640,320,560,320,480,320C400,320,320,320,240,320C160,320,80,320,40,320L0,320Z">
            </path>

            <path fill="#57a7b3" fill-opacity="0.3"
                d="M0,96L24,117.3C48,139,96,181,144,186.7C192,192,240,160,288,160C336,160,384,192,432,192C480,192,528,160,576,165.3C624,171,672,213,720,224C768,235,816,213,864,218.7C912,224,960,256,1008,234.7C1056,213,1104,139,1152,112C1200,85,1248,107,1296,133.3C1344,160,1392,192,1416,208L1440,224L1440,320L1416,320C1392,320,1344,320,1296,320C1248,320,1200,320,1152,320C1104,320,1056,320,1008,320C960,320,912,320,864,320C816,320,768,320,720,320C672,320,624,320,576,320C528,320,480,320,432,320C384,320,336,320,288,320C240,320,192,320,144,320C96,320,48,320,24,320L0,320Z">
            </path>

        </svg>
    </footer>

</body>

</html>
<script src="js/jquery.js"></script>
<script src="js/login.js"></script>

<script src="js/login/login.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
    integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous">
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"
    integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous">
</script>


</script>