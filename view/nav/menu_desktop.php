<div class="categoria">
    <div class="topo">
        <!-- <i class="bi bi-circle-half"></i> -->
        <!-- <p> System Day</p> -->
        <img class="img-logo" src="img/logo.png?<?php echo time(); ?>" alt="">


    </div>
    <nav>
        <ul class="nav-pills">
            <li>
                <p><i class="bi bi-house"></i><a href="?menu">Inicial</a></p>
            </li>
            <?php

            while ($row = mysqli_fetch_assoc($consultar_categoria)) {
                $id_categoria = $row['cl_id'];
                $categoria = utf8_encode($row['cl_categoria']);
                $icone = $row['cl_icone'];
                if (consultar_acesso_categoria($id_user, $id_categoria) > 0 or ($tipo == "adm") or ($tipo == "suporte")) { // verificar se o usuario tem acesso / usuario adm tem todos os acessos

            ?>
                    <li>
                        <p><?php echo $icone ?> <?php echo $categoria; ?></p>
                        <ul id="sub-list">
                            <?php
                            $select = "SELECT * from tb_subcategorias where cl_categoria = $id_categoria order by cl_ordem_menu";
                            $consultar_subcategoria = mysqli_query($conecta, $select);

                            while ($row = mysqli_fetch_assoc($consultar_subcategoria)) {
                                $id_subcategoria = $row['cl_id'];
                                $subcategoria = utf8_encode($row['cl_subcategoria']);
                                $diretorio = $row['cl_diretorio'];
                                $url = $row['cl_url'];
                                $status_ativo = $row['cl_status_ativo'];

                                if ((consultar_acesso_subcategoria($id_user, $id_subcategoria) > 0  or  ($tipo == "adm") or ($tipo == "suporte")) and $status_ativo == "SIM") { // usuario adm tem todos os acessos
                            ?>

                                    <li><a href="?menu&ctg=<?php echo $categoria; ?>&<?php echo $url; ?>&id=<?php echo $id_subcategoria; ?>"><?php echo $subcategoria; ?></a>
                                    </li>
                            <?php
                                }
                            }


                            ?>
                        </ul>
                    </li>

            <?php
                }
            }
            ?>
        </ul>

    </nav>
    <!-- <div class="footer">
        <p>@Todos os direitos reservados a effmax</p>
    </div> -->
</div>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<script>
    $('.nav-pills ul').hide();

    // Adiciona um ouvinte de clique a cada item pai no segundo menu
    $('.nav-pills > li > p').click(function() {
        // Remove a classe 'active' de todos os outros itens
        $('.nav-pills > li > p').not(this).removeClass('active');

        // Adiciona a classe 'active' ao item clicado
        $(this).toggleClass('active');

        // Oculta todos os outros elementos ul
        $('.nav-pills > li > ul').not($(this).siblings('ul')).slideUp();

        // Alterna a visibilidade do ul filho do item clicado
        $(this).siblings('ul').slideToggle();
    });

    // Adiciona um ouvinte de clique a cada link no segundo menu
    $('.nav-pills > li > ul > li > a').click(function() {
        // Impede o comportamento padrão do link para evitar recarregar a página

        // Remove a classe 'active' de todos os outros links
        $('.nav-pills > li > ul > li > a').not(this).removeClass('active');

        // Adiciona a classe 'active' ao link clicado
        $(this).addClass('active');
    });
</script>