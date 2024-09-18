<?php
include "../../../conexao/conexao.php";
include "../../../modal/empresa/cliente/gerenciar_cliente.php";
?>


<div class="title">
    <label class="form-label">Cadastrar Parceiro</label>
    <div class="msg_title">
        <p>Cadastro de Parceiro: Adicione novos clientes, fornecedores e transportadoras</p>
    </div>
</div>
<hr>
<form id="cadastrar_cliente">

    <div class="row  mb-2">
        <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
            <button type="submit" class="btn btn-sm btn-success">Cadastrar</button>
            <button type="button" id="voltar_consulta" class="btn btn-sm btn-secondary">Voltar</button>
        </div>
    </div>

    <div class="row mb-2">
        <input type="hidden" name="formulario_cadastrar_cliente">
        <?php include "../../input_include/usuario_logado.php" ?>
        <div class="col-sm col-md-6  mb-2">
            <label for="rzaosocial" class="form-label">Razão social</label>
            <input type="text" class="form-control " id="rzaosocial" name="rzaosocial" value="">
        </div>
        <div class="col-sm col-md-6  mb-2">
            <label for="descricao" class="form-label">Nome fantasia</label>
            <input type="text" class="form-control " id="nfantasia" name="nfantasia" value="">
        </div>

    </div>
    <div class="row mb-2">
        <div class="col-md-2   mb-2">
            <label for="cest" class="form-label">Cnpj \ Cpf</label>
            <div class="input-group">
                <input type="text" class="form-control inputNumber" id="cnpjcpf" name="cnpjcpf" placeholder="Apenas números">
                <button class="btn btn-secondary" id="consutar_cnpj" type="button" title="Consultar cnpj apenas"><i class="bi bi-search"></i></button>
            </div>
        </div>


        <div class="col-md-2   mb-2">
            <label for="ie" class="form-label">Inscrição Estadual</label>
            <input type="text" class="form-control inputNumber" id="ie" name="ie" placeholder="Apenas números" value="">
        </div>
        <div class="col-md-auto  mb-2">
            <label for="grupo_estoque" class="form-label">Status</label>
            <select name="grupo_estoque" class="form-select chosen-select" id="grupo_estoque">
                <option value="0">Selecione..</option>
                <option selected value="SIM">Ativo</option>
                <option value="NAO">Inativo</option>
            </select>
        </div>



    </div>

    <div class="row">
        <div class="col-sm">
            <span class="badge rounded-2 mb-3 d-area dv">Informações de endereço</span>
        </div>
    </div>


    <div class="row  mb-2">
        <div class="col-md-2   mb-2">
            <label for="cep" class="form-label">Cep</label>
            <div class="input-group">
                <input type="text" class="form-control inputNumber" id="cep" name="cep" placeholder="Ex. 65052700">
                <button class="btn btn-secondary" id="buscar_cep" type="button" title="Consultar cep"><i class="bi bi-search"></i></button>
            </div>
        </div>
        <div class="col-md-3   mb-2">
            <label for="endereco" class="form-label">Endereço</label>
            <input type="text" class="form-control" id="endereco" name="endereco" value="" placeholder="Ex. rua coração x">
        </div>

        <div class="col-md-2  mb-2">
            <label for="bairro" class="form-label">Bairro</label>
            <input type="text" class="form-control" id="bairro" name="bairro" value="" placeholder="Ex. bairro">
        </div>
        <div class="col-md-1  mb-2">
            <label for="numero" class="form-label">Número</label>
            <input type="text" class="form-control" id="numero" name="numero" value="" placeholder="03">
        </div>
        <div class="col-md-2  mb-2">
            <label for="estado" class="form-label">Estado</label>
            <select name="estado" class="form-select chosen-select" id="estado">
                <option value="0">Selecione..</option>
                <?php while ($linha  = mysqli_fetch_assoc($consultar_estados)) {
                    $id_estado_b = $linha['cl_id'];
                    $uf_b = ($linha['cl_uf']);
                    $nome_estado_b = utf8_encode($linha['cl_nome']);
                    $codigo_estado_b = ($linha['cl_ibge']);
                    echo "<option uf_estado='$uf_b' cdestado='$codigo_estado_b' id_estado='$id_estado_b' value='$id_estado_b'>$nome_estado_b - $uf_b</option>";
                } ?>
            </select>
        </div>

        <div class="col-md-2  mb-2">
            <label for="cidade" class="form-label">Cidade</label>
            <select disabled class="form-select chosen-select" name="cidade" id="cidade">
                <option value="0">Defina o estado..</option>

            </select>
        </div>


    </div>

    <div class="row">
        <div class="col-sm">
            <span class="badge rounded-2 mb-3 d-area dv">Informações de Contato</span>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-sm-6 col-md-3   mb-2">
            <label for="telefone" class="form-label">Telefone</label>
            <input type="text" class="form-control " id="telefone" name="telefone" value="">
        </div>

        <div class="col-sm-6 col-md-3   mb-2">
            <label for="email" class="form-label">Email</label>
            <input type="text" class="form-control " id="email" name="email" value="">
        </div>

    </div>

    <div class="row mb-2">
        <div class="col-sm-6 col-md-8   mb-2">
            <label for="observacao" class="form-label">Observação</label>
            <textarea class="form-control" name="observacao" id="observacao" aria-label="With textarea"></textarea>

        </div>
    </div>


    <!-- loading -->
    <?php include "../../loading/spinner.php"; ?>



</form>
<script src="js/funcao.js"></script>
<script src="js/configuracao/users/user_logado.js"></script>
<script src="js/empresa/cliente/cadastrar_cliente.js"></script>
<script src="js/empresa/funcao/api_cliente.js"></script>