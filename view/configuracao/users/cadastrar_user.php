<form id="cadastrar_usuario">
    <div class="acao">
        <div class="title">
            <label class="form-label">Cadastrar Usuário</label>
            <div class="msg_title">
                <p>Esse menu tem como função o gerênciamento de usuários. Cadastrar, editar, resetar senha e inativar usúario. </p>
            </div>
        </div>
        <hr>
        <div class="row mb-2">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                <button type="submit" class="btn btn-sm btn-success">Cadastrar</button>
            </div>
        </div>
        <div class="row mb-2">
            <input type="hidden" name="formulario_cadastrar_usuario">

            <?php include "../../input_include/usuario_logado.php" ?>

            <div class="col-md-3 mb-2">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" placeholder="" value="">
            </div>
            <div class="col-md-3  mb-2">
                <label for="usuario" class="form-label">Usuário</label>
                <input type="text" class="form-control inputUser" id="usuario" name="usuario" autocomplete="off" placeholder="Apenas letras, números e símbolos" value="">
            </div>
            <div class="col-md-3 mb-2">
                <label for="senha" class="form-label">Senha</label>
                <input type="text" class="form-control inputUser" id="senha" name="senha" autocomplete="off" placeholder="Apenas letras, números e símbolos" value="">
            </div>
            <div class="col-md-3  mb-2">
                <label for="nome" class="form-label">Confirma senha</label>
                <input type="text" class="form-control inputUser" id="confirmar_senha" autocomplete="off" name="confirmar_senha" placeholder="Apenas letras, números e símbolos" value="">
            </div>
            <div class="col-md-2  mb-2">
                <label for="perfil" class="form-label">Perfil</label>
                <select name="perfil" id="perfil" class="form-select chosen-select">
                    <option value="0">Selecione...</option>
                    <option value="adm">Adminstrador</option>
                    <option value="usuario">Usúario</option>

                </select>
            </div>
            <div class="col-md-2  mb-2">
                <label for="situacao" class="form-label">Situação</label>
                <select name="situacao" class="form-select chosen-select" id="situacao">
                    <option value="s">Selecione...</option>
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                </select>
            </div>
            <div class="col-md-2  mb-2">
                <label for="cargo" class="form-label">Cargo</label>
                <select name="cargo" class="form-select chosen-select" id="cargo">
                    <option value="0">Selecione...</option>
                    <option value="VENDAS">Vendas</option>
                    <option value="FINANCEIRO">Financeiro</option>
                    <option value="GERENTE">Gerente</option>
                    <option value="ESTOQUE">Estoque</option>
                </select>
            </div>
            <div class="col-md-2  mb-2">
                <label for="restricao_horario" class="form-label">Restrição de Horários</label>
                <select name="restricao_horario" class="form-select chosen-select" id="restricao_horario">
                    <option value="sn">Selecione...</option>
                    <option value="1">SIM</option>
                    <option value="0">Não</option>
                </select>
            </div>
            <div class="col-md  mb-2">
                <label for="nome" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" autocomplete="off" name="email" value="">
            </div>

        </div>
        <div class="row">
            <div class="col-sm">
                <span class="badge rounded-2 mb-3 d-area dv">Vendedor</span>
            </div>
        </div>
        <div class="row mb-2 d-flex align-items-end">

            <div class="col-md-2  mb-2">
                <label for="comissao" class="form-label">Comissão</label>
                <div class="input-group mb-2">
                    <span class="input-group-text">%</span>
                    <input type="number" step="any" class="form-control" id="comissao" autocomplete="off" name="comissao" placeholder="" value="">
                </div>
            </div>
            <div class="col-md-auto  mb-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" name="vendedor" type="checkbox" id="vendedor">
                    <label class="form-check-label" for="vendedor">Vendedor</label>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-sm">
                <span class="badge rounded-2 mb-3 d-area dv">Autoização de usuário</span>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col-md-auto  mb-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" name="cancelar_venda" type="checkbox" id="cancelar_venda">
                    <label class="form-check-label" for="cancelar_venda">Cancelar venda</label>
                </div>
            </div>
            <div class="col-md-auto  mb-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" name="autorizar_desconto" type="checkbox" id="autorizar_desconto">
                    <label class="form-check-label" for="autorizar_desconto">Autorizar desconto</label>
                </div>
            </div>

            <div class="col-md-auto  mb-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" name="receber_alerta" type="checkbox" id="receber_alerta">
                    <label class="form-check-label" for="receber_alerta">Receber alerta</label>
                </div>
            </div>
            <div class="col-md-auto  mb-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" name="cancelar_pedido" type="checkbox" id="cancelar_pedido">
                    <label class="form-check-label" for="cancelar_pedido">Cancelar Pedido</label>
                </div>
            </div>
        </div>


    </div>
</form>

<script src="js/funcao.js"></script>
<script src="js/configuracao/users/user_logado.js"></script>
<script src="js/configuracao/users/cadastro_user.js"></script>