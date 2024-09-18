<?php
include "../../../modal/estoque/produto/gerenciar_produto.php";
?>
<div class="d-flex d-flex  align-items-center img-produto-group">
    <div class="d-flex group-produto">
        <?php
        $resultados = consulta_linhas_tb_query($conecta, "select * from tb_imagem_produto where cl_codigo_nf='$codigo_nf' order by cl_ordem asc");
        if ($resultados) {
            foreach ($resultados as $linha) {
                $id = $linha['cl_id'];
                $arquivo = $linha['cl_descricao'];
                $extensao = $linha['cl_extensao'];

        ?>
                <div class="position-relative draggable-produto" id="<?= $id; ?>">
                    <img style="height: 150px;width:150px;cursor:pointer" src="img/produto/<?= $arquivo; ?>" class="img-thumbnail img-produto" alt="<?= $arquivo; ?>">
                    <div class="z-1 position-absolute bg-light  top-0 end-0 p-2 rounded excluir_img_produto" id="<?= $id; ?>" style="cursor: pointer;" title="Remover">
                        <i class="bi bi-x-circle-fill "></i>
                    </div>
                </div>
        <?php }
        } ?>
    </div>

    <form id="upload_img_produto" enctype="multipart/form-data">
        <div class="row d-flex align-items-end mb-2">
            <div class="col-md-auto">
                <label for="file-input-img-produto" class="icone-add-square mx-3 d-flex justify-content-center align-items-center rounded">
                    <i class="bi bi-plus-lg"></i>
                </label>
                <input style="display: none;" class="form-control form-control-sm" aria-describedby="produtoHelp" type="file" id="file-input-img-produto" name="file-input-img-produto[]" multiple>
                <div id="produtoHelp" class="form-text p-2">Adicione aqui a imagem do produto, Tamanho max 900kb<br>Extensões .png .jpg .jpeg .svg <br>Altura recomendada: 236 pixels</div>
            </div>
        </div>
        <div id="preview"></div>
    </form>
</div>

<script src="js/include/produto/imagens_produto.js"></script>