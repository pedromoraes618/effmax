<?php
include "../../../../conexao/conexao.php";
include "../../../../funcao/funcao.php";

?>
<div class="d-flex align-items-start">
    <?php for ($i = 1; $i <= 4; $i++) {

        $codigo_nf = $_GET['codigo_nf'];
        $nome_imagem = $codigo_nf . "_" . $i;
        //echo $nome_imagem;
        $imagem =  consulta_tabela($conecta, "tb_imagem_produto", "cl_descricao", $nome_imagem, "cl_descricao");
        $ext =  consulta_tabela($conecta, "tb_imagem_produto", "cl_descricao", $nome_imagem, "cl_extensao");
        $nome_imagem = $imagem . $ext;
    ?>
        <div class="imagem-container m-2" id="<?php echo $i; ?>">
            <div class="icone-central">
                <i class="bi bi-arrow-bar-up"></i>
            </div>
            <?php if ($imagem != "") { ?>
                <img class="img-fluid img-thumbnail"  style="object-fit:scale-down;" src="img/produto/<?php echo $nome_imagem; ?>?<?php echo time(); ?>">
                <div class="icone">
                    <span class="icone-remover" id="<?php echo $imagem; ?>"><i class="bi bi-x-circle-fill"></i></span>
                </div>
            <?php } else { ?>
                <div class="icone-add">
                    <i class="bi bi-plus-lg"></i>
                </div>
            <?php
            } ?>

        </div>
    <?php } ?>
</div>

<div class="upload-img"></div>