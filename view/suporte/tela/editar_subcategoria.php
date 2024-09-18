<?php include "../../../conexao/conexao.php"; ?>
<?php include "../../../modal/suporte/tela/gerenciar_tela.php" ?>
<form id="editar_subcategoria">
    <div class="row">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Alterar</button>
            <button type="button" id="voltar_cadastro" class="btn btn-sm btn-secondary">Voltar</button>
        </div>
    </div>

    <div class="row">
        <input type="hidden" name="formulario_editar_subcategoria">

        <?php include "../../input_include/usuario_logado.php" ?>
        <input type="hidden" value="<?php echo $id_subcategoria_b; ?>" name="id_subcategoria">
        <div class="col-sm  mb-2">
            <label for="subcategoria" class="form-label">Subcategoria</label>
            <input type="text" class="form-control" id="subcategoria" name="subcategoria" placeholder="" value="<?php echo $subcategoria_b ?>">
        </div>
        <div class="col-sm  mb-2">
            <label for="ordem" class="form-label">Ordem</label>
            <input type="text" class="form-control" id="ordem" name="ordem" placeholder="" value="<?php echo $ordem_b ?>">
        </div>
        <div class="col-sm mb-2">
            <label for="diretorio_subc" class="form-label">Diretório Subcategoria</label>
            <input type="text" class="form-control" id="diretorio_subc" name="diretorio_subc" placeholder="" value="<?php echo $diretorio_b ?>">
        </div>
    </div>
    <div class="row">
        <div class="col-sm  mb-2">
            <label for="url_sub" class="form-label">Url Subcategoria</label>
            <input type="text" class="form-control" id="url_sub" name="url_sub" placeholder="" value="<?php echo $url_b ?>">
        </div>
        <div class="col-sm  mb-2">
            <label for="diretorio_bd" class="form-label">Diretório Banco de dados</label>
            <input type="text" class="form-control" id="diretorio_bd" name="diretorio_bd" placeholder="" value="<?php echo $diretorio_banco_b ?>">
        </div>

        <div class="col-sm-2  mb-2">
            <label for="categoria" class="form-label">Categoria</label>
            <select name="categoria" class="form-select chosen-select" id="categoria">
                <option value="0">Selecione</option>
                <?php while ($linha = mysqli_fetch_assoc($consultar_categorias_selecao)) {
                    $id_categoria = $linha['cl_id'];
                    $categoria_b = utf8_encode($linha['cl_categoria']);
                    if ($categoria_subcategoria_b == $id_categoria) {
                ?>
                        <option value="<?php echo $id_categoria; ?> " selected><?php echo $categoria_b  ?></option>
                    <?php
                    } else {
                    ?>
                        <option value="<?php echo $id_categoria; ?>"><?php echo $categoria_b  ?></option>
                <?php
                    }
                } ?>
            </select>
        </div>

        <div class="col-sm-2  mb-2">
            <label for="categoria" class="form-label">Status</label>
            <select name="status_ativo" class="form-select chosen-select" id="status_ativo">
                <option value="0">Selecione</option>
                <option <?php echo ($status_ativo_b == "SIM") ? 'selected' : ''; ?> value="SIM">Ativo</option>
                <option <?php echo ($status_ativo_b == "NAO") ? 'selected' : ''; ?> value="NAO">Inativo</option>

            </select>
        </div>
    </div>


</form>


<script src="js/configuracao/users/user_logado.js"></script>
<!-- cadastro da subcategoria -->
<script src="js/suporte/tela/editar_subcategoria.js"></script>