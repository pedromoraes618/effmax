<form id="cadastrar_categoria">

    <div class="row">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
            <button type="submit" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Cadastrar</button>
        </div>
    </div>
    
    <div class="row mb-2">
        <input type="hidden" name="formulario_cadastrar_categoria">

        <?php include "../../input_include/usuario_logado.php" ?>

        <div class="col-md  mb-1">
            <label for="categoria" class="form-label">Categoria</label>
            <input type="text" class="form-control" id="categoria" name="categoria" placeholder="" value="">
        </div>
        <div class="col-md  mb-1">
            <label for="icone" class="form-label">Icone</label>
            <input type="text" class="form-control" id="icone" name="icone" placeholder="Ex. <i class='<i class='bi bi-person'></i></i>" value="">
        </div>
        <div class="col-md mb-1">
            <label for="ordem" class="form-label">Ordem</label>
            <input type="text" class="form-control" id="ordem" name="ordem" placeholder="Ex. 5" value="">
        </div>
    </div>


</form>

<script src="js/configuracao/users/user_logado.js"></script>
<!-- cadastro da categoria -->
<script src="js/suporte/tela/cadastro_categoria.js"></script>