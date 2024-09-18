<?php include "../../../conexao/conexao.php"; ?>
<?php include "../../../modal/suporte/tela/gerenciar_tela.php" ?>
<form id="cadastrar_subcategoria">


    <div class="row">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Cadastrar</button>
        </div>
    </div>

    <div class="row">
        <input type="hidden" name="formulario_cadastrar_subcategoria">

        <?php include "../../input_include/usuario_logado.php" ?>

        <div class="col-sm  mb-2">
            <label for="subcategoria" class="form-label">Subcategoria</label>
            <input type="text" class="form-control" id="subcategoria" name="subcategoria" placeholder="" value="">
        </div>
        <div class="col-sm  mb-2">
            <label for="ordem" class="form-label">Ordem</label>
            <input type="text" class="form-control" id="ordem" name="ordem" placeholder="" value="">
        </div>
        <div class="col-sm mb-2">
            <label for="diretorio_subc" class="form-label">Diretório Subcategoria</label>
            <input type="text" class="form-control" id="diretorio_subc" name="diretorio_subc" placeholder="" value="">
        </div>
    </div>
    <div class="row">
        <div class="col-sm  mb-2">
            <label for="url_sub" class="form-label">Url Subcategoria</label>
            <input type="text" class="form-control" id="url_sub" name="url_sub" placeholder="" value="">
        </div>
        <div class="col-sm  mb-2">
            <label for="diretorio_bd" class="form-label">Diretório Banco de dados</label>
            <input type="text" class="form-control" id="diretorio_bd" name="diretorio_bd" placeholder="" value="">
        </div>

        <div class="col-sm-2  mb-2">
            <label for="categoria" class="form-label">Categoria</label>
            <select name="categoria" class="form-select chosen-select" id="categoria">
                <option value="0">Selecione</option>
                <?php while ($linha = mysqli_fetch_assoc($consultar_categorias_selecao)) {
                    $id_categoria = $linha['cl_id'];
                    $categoria_b = utf8_encode($linha['cl_categoria']);
                ?>
                    <option value="<?php echo $id_categoria; ?>"><?php echo $categoria_b  ?></option>

                <?php
                } ?>
            </select>
        </div>
        <div class="col-sm-2  mb-2">
            <label for="categoria" class="form-label">Status</label>
            <select name="status_ativo" class="form-select chosen-select" id="status_ativo">
                <option value="0">Selecione</option>
                <option value="SIM">Ativo</option>
                <option value="NAO">Inativo</option>

            </select>
        </div>


    </div>


</form>


<script src="js/configuracao/users/user_logado.js"></script>
<!-- cadastro da subcategoria -->
<script src="js/suporte/tela/cadastro_subcategoria.js"></script>