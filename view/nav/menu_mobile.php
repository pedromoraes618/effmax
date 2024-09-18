<div class="menu-mobile">
    <button class="btn " type="button"><i data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions"
            aria-controls="offcanvasWithBothOptions" class="bi bi-list"></i></button>
</div>
<div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions"
    aria-labelledby="offcanvasWithBothOptionsLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Effmax</h5>
        <button type="button" data-bs-dismiss="offcanvas" aria-label="Close"><i class="bi bi-x-lg"></i> </button>
    </div>
    <nav>
        <ul>
            <li>
                <p><i class="bi bi-house"></i><a style="text-decoration: none;color:white" href="?menu">Inicial</a>
                </p>
            </li>
            <?php
      
            while($row = mysqli_fetch_assoc($consultar_categoria_mobile)){
                $id_categoria = $row['cl_id'];
                $categoria = utf8_encode($row['cl_categoria']);
                $icone = $row['cl_icone'];
                if(consultar_acesso_categoria($id_user,$id_categoria)>0 or ($tipo == "adm") or ($tipo == "suporte")){ // verificar se o usuario tem acesso / usuario adm tem todos os acessos
                 
            ?>
            <li>
                <p><?php echo $icone?> <?php echo $categoria; ?></p>
                <ul>
                    <?php 
                        $select = "SELECT * from tb_subcategorias where cl_categoria = $id_categoria order by cl_ordem_menu";
                        $consultar_subcategoria= mysqli_query($conecta,$select);
                        
                    while($row = mysqli_fetch_assoc($consultar_subcategoria)){
                        $id_subcategoria = $row['cl_id'];
                        $subcategoria = utf8_encode($row['cl_subcategoria']);
                        $diretorio = $row['cl_diretorio'];
                        $url = $row['cl_url'];
                        
                        if(consultar_acesso_subcategoria($id_user,$id_subcategoria) > 0 or  ($tipo == "adm") or ($tipo == "suporte")){ // usuario adm tem todos os acessos
                    ?>

                    <li><a
                            href="?menu&ctg=<?php echo $categoria; ?>&<?php echo $url; ?>&id=<?php echo $id_subcategoria; ?>"><?php echo $subcategoria; ?></a>
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

</div>