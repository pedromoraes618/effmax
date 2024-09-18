<?php

include "../../../../../modal/delivery/configuracao/gerenciar_configuracao.php";
?>

<table class="table table-hover">
    <thead>
        <tr>

            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
    </thead>
    <tbody>
        <?php while ($linha = mysqli_fetch_assoc($consulta_baner)) {
            $id = ($linha['cl_id']);
            $arquivo = utf8_encode($linha['cl_arquivo']);
        ?>
            <tr>
                <td>
                    <div class="video-container">
                        <video width="50" height="50" controls >
                            <source src="img/baner/<?php echo $arquivo; ?>" type="video/mp4">
                            Seu navegador não suporta o elemento de vídeo.
                        </video>
                    </div>
                </td>
                <td><button type="button" class="btn btn-sm btn-danger remover_baner" codigo='<?php echo $id; ?>'>Remover</button></td>

            </tr>
        <?php } ?>
    </tbody>
</table>



<script src="js/delivery/configuracao/modulo/tabela/consultar_baner.js"></script>