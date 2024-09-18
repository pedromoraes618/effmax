<?php
include "../../../modal/configuracao/users/usuario.php";
?>
<form id="editar_usuario">
    <div class="acao">
        <div class="title">
            <label class="form-label">Editar Usuário</label>
            <div class="msg_title">
                <p>Esse menu tem como função o gerênciamento de usuários. Cadastrar, editar, resetar senha e inativar
                    usúario. </p>
            </div>
        </div>
        <hr>
        <div class="row mb-2">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                <button type="subbmit" class="btn btn-sm btn-success">Alterar</button>
                <button type="button" id_user=<?php echo $id_user; ?> id="resetar_senha" class="btn btn-sm btn-dark">Resetar senha</button>
                <button type="button" id="voltar_cadastro" class="btn btn-sm btn-secondary">Voltar</button>
            </div>
        </div>
        <div class="row mb-2">
            <input type="hidden" name="formulario_editar_usuario">
            <?php include "../../input_include/usuario_logado.php" ?>

            <input type="hidden" value="<?php echo $id_user; ?>" name="id_user">
            <div class="col-md  mb-2">
                <label for="nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="nome" name="nome" placeholder="" value="<?php echo $nome_b ?>">
            </div>
            <div class="col-md  mb-2">
                <label for="usuario" class="form-label">Usuário</label>
                <input type="text" readonly class="form-control inputUser" id="usuario" name="usuario" placeholder="Apenas letras e números" value="<?php echo $usuario_b; ?>">
            </div>

            <div class="col-md  mb-1">
                <label for="perfil" class="form-label">Perfil</label>
                <select name="perfil" id="perfil" class="form-select chosen-select">
                    <option value="0">Selecione...</option>
                    <option <?php if ($perfil_b == "adm") {
                                echo "selected";
                            } ?> value="adm">Adminstrador</option>
                    <option <?php if ($perfil_b == "usuario") {
                                echo "selected";
                            } ?> value="usuario">Usúario</option>

                </select>
            </div>
            <div class="col-md  mb-2">
                <label for="situacao" class="form-label">Situação</label>
                <select name="situacao" class="form-select chosen-select" id="situacao">
                    <option value="s">Selecione...</option>
                    <option <?php if ($situacao_b == 1) {
                                echo "selected";
                            } ?> value="1">Ativo</option>
                    <option <?php if ($situacao_b == 0) {
                                echo "selected";
                            } ?> value="0">Inativo</option>
                </select>
            </div>

        </div>
        <div class="row mb-2">
            <div class="col-md-2  mb-2">
                <label for="cargo" class="form-label">Cargo</label>
                <select name="cargo" class="form-select chosen-select" id="cargo">
                    <option value="0">Selecione...</option>
                    <option <?php if ($cargo_b == "VENDAS") {
                                echo 'selected';
                            }  ?> value="VENDAS">Vendas</option>
                    <option <?php if ($cargo_b == "FINANCEIRO") {
                                echo 'selected';
                            }  ?> value="FINANCEIRO">Financeiro</option>
                    <option <?php if ($cargo_b == "GERENTE") {
                                echo 'selected';
                            }  ?> value="GERENTE">Gerente</option>
                    <option <?php if ($cargo_b == "ESTOQUE") {
                                echo 'selected';
                            }  ?> value="ESTOQUE">Estoque</option>
                </select>
            </div>
            <div class="col-md-2  mb-2">
                <label for="restricao_horario" class="form-label">Restrição de Horários</label>
                <select name="restricao_horario" class="form-select chosen-select" id="restricao_horario">
                    <option value="sn">Selecione...</option>
                    <option <?php if ($restricao_horario == "1") {
                                echo 'selected';
                            }  ?> value="1">Sim</option>
                    <option <?php if ($restricao_horario == "0") {
                                echo 'selected';
                            }  ?> value="0">Não</option>
                </select>
            </div>
            <div class="col-md-4  mb-2">
                <label for="nome" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" autocomplete="off" name="email" value="<?php echo $email_b ?>">
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
                    <input  type="number" step="any" class="form-control" id="comissao" autocomplete="off" name="comissao" placeholder="" value="<?php echo $comissao ?>">
                </div>
            </div>
            <div class="col-md-auto mb-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" <?php if ($vendedor_b == "SIM") {
                                                        echo 'checked';
                                                    } ?> name="vendedor" type="checkbox" id="vendedor">
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
                    <input class="form-check-input" <?php if ($cancelar_venda == "SIM") {
                                                        echo 'checked';
                                                    } ?> name="cancelar_venda" type="checkbox" id="cancelar_venda">
                    <label class="form-check-label" for="cancelar_venda">Cancelar venda</label>
                </div>
            </div>
            <div class="col-md-auto  mb-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" name="autorizar_desconto" <?php if ($autorizar_desconto == "SIM") {
                                                                                    echo 'checked';
                                                                                } ?> type="checkbox" id="autorizar_desconto">
                    <label class="form-check-label" for="autorizar_desconto">Autorizar desconto</label>
                </div>
            </div>
            <div class="col-md-auto  mb-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" name="receber_alerta" <?php if ($receber_alerta == "SIM") {
                                                                                echo 'checked';
                                                                            } ?> type="checkbox" id="receber_alerta">
                    <label class="form-check-label" for="receber_alerta">Receber alerta</label>
                </div>
            </div>
            <div class="col-md-auto  mb-2">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" name="cancelar_pedido" <?php if ($cancelar_pedido == "SIM") {
                                                                                echo 'checked';
                                                                            } ?> type="checkbox" id="cancelar_pedido">
                    <label class="form-check-label" for="cancelar_pedido">Cancelar Pedido</label>
                </div>
            </div>

        </div>

    </div>
</form>


<script src="js/funcao.js"></script>
<script src="js/configuracao/users/user_logado.js"></script>
<script src="js/configuracao/users/editar_user.js"></script>