<?php
include "../../../modal/configuracao/usuario/gerenciar_usuario.php";
?>

<div class="modal fade" tabindex="-1" role="dialog" data-bs-backdrop="static" aria-hidden="true" id="modal_usuario" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Usuário</h1>
                <button type="button" class="btn-close btn-fechar" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" id="usuario">
                <input type="hidden" id="id" name="id" value="<?= $form_id; ?>">
                <div class="modal-body">
                    <div class="title mb-2">
                        <label class="form-label sub-title"></label>
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end btn-acao">
                        <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                        <?php if (!empty($form_id)) {
                        ?>
                            <button type="button" class="btn btn-sm btn-warning resetar_senha"><i class="bi bi-arrow-clockwise"></i> Resetar Senha</button>
                        <?php
                        } ?>
                        <button type="button" class="btn btn-sm btn-secondary btn-fechar" data-bs-dismiss="modal">Fechar</button>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md mb-2">
                            <label for="nome" class="form-label">Nome *</label>
                            <input type="text" class="form-control" id="nome" name="nome" placeholder="" value="">
                        </div>
                        <div class="col-md  mb-2">
                            <label for="usuario" class="form-label">Usuário *</label>
                            <input type="text" class="form-control" <?php if (!empty($form_id)) {
                                                                        echo 'disabled';
                                                                    } ?> id="usuario" name="usuario" autocomplete="off" placeholder="Apenas letras, números e símbolos" value="">
                        </div>
                    </div>
                    <div class="row mb-2">
                        <?php if (empty($form_id)) { ?>
                            <div class="col-md-6 mb-2">
                                <label for="senha" class="form-label">Senha *</label>
                                <input type="text" class="form-control " id="senha" name="senha" autocomplete="off" placeholder="Apenas letras, números e símbolos" value="">
                            </div>
                            <div class="col-md-6  mb-2">
                                <label for="nome" class="form-label">Confirma senha *</label>
                                <input type="text" class="form-control " id="confirmar_senha" autocomplete="off" name="confirmar_senha" placeholder="Apenas letras, números e símbolos" value="">
                            </div>
                        <?php } ?>
                        <div class="col-md-6  mb-2">
                            <label for="perfil" class="form-label">Perfil *</label>
                            <select name="perfil" id="perfil" class="form-select chosen-select">
                                <option value="0">Selecione...</option>
                                <option value="suporte">Suporte</option>
                                <option value="adm">Adminstrador</option>
                                <option value="usuario">Usúario</option>

                            </select>
                        </div>
                        <div class="col-md-3  mb-2">
                            <label for="situacao" class="form-label">Situação *</label>
                            <select name="situacao" class="form-select chosen-select" id="situacao">
                                <option value="s">Selecione...</option>
                                <option value="1">Ativo</option>
                                <option value="0">Inativo</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-3  mb-2">
                            <label for="cargo" class="form-label">Cargo *</label>
                            <select name="cargo" class="form-select chosen-select" id="cargo">
                                <option value="0">Selecione...</option>
                                <option value="VENDAS">Vendas</option>
                                <option value="FINANCEIRO">Financeiro</option>
                                <option value="GERENTE">Gerente</option>
                                <option value="ESTOQUE">Estoque</option>
                            </select>
                        </div>
                        <div class="col-md-3  mb-2">
                            <label for="restricao_horario" class="form-label">Restrição de horários *</label>
                            <select name="restricao_horario" class="form-select chosen-select" id="restricao_horario">
                                <option value="sn">Selecione...</option>
                                <option value="1">SIM</option>
                                <option value="0">Não</option>
                            </select>
                        </div>
                        <div class="col-md mb-2">
                            <label for="nome" class="form-label">Email</label>
                            <input type="text" class="form-control" id="email" autocomplete="off" name="email" value="">
                        </div>

                    </div>
                    <div class="accordion " id="accordionPanelsStayOpenExample">
                        <div class="accordion-item mb-2 ">
                            <h2 class="accordion-header ">
                                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                    Atributos do usuário
                                </button>
                            </h2>
                            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show ">
                                <div class="accordion-body">
                                    <div class="row mb-2 d-flex align-items-end">

                                        <div class="col-md-3 mb-2">
                                            <label for="comissao" class="form-label">Comissão</label>
                                            <div class="input-group ">
                                                <span class="input-group-text">%</span>
                                                <input type="number" step="any" class="form-control" id="comissao" autocomplete="off" name="comissao" placeholder="" value="">
                                            </div>
                                        </div>
                                        <div class="col-md-auto  mb-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="vendedor" type="checkbox" role="switch" id="vendedor">
                                                <label class="form-check-label" for="vendedor">Vendedor</label>
                                            </div>
                                        </div>
                                        <div class="col-md-auto  mb-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="tecnico" type="checkbox" role="switch" id="tecnico">
                                                <label class="form-check-label" for="tecnico">Técnico</label>
                                            </div>
                                        </div>
                                        <div class="col-md-auto  mb-2">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" name="comprador" type="checkbox" role="switch" id="comprador">
                                                <label class="form-check-label" for="comprador">Comprador</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="accordion-item mb-2 ">
                                <h2 class="accordion-header ">
                                    <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
                                        Autorização
                                    </button>
                                </h2>
                                <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show ">
                                    <div class="accordion-body">
                                        <div class="row mb-2">
                                            <div class="col-md-auto  mb-2">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" name="cancelar_venda" type="checkbox" role="switch" id="cancelar_venda">
                                                    <label class="form-check-label" for="cancelar_venda">Cancelar venda</label>
                                                </div>
                                            </div>
                                            <div class="col-md-auto  mb-2">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" name="cancelar_pedido" type="checkbox" role="switch" id="cancelar_pedido">
                                                    <label class="form-check-label" for="cancelar_pedido">Cancelar Pedido</label>
                                                </div>
                                            </div>
                                            <div class="col-md-auto  mb-2">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" name="cancelar_lancamento_financeiro" type="checkbox" role="switch" id="cancelar_lancamento_financeiro">
                                                    <label class="form-check-label" for="cancelar_lancamento_financeiro">Cancelar Lançamento financeiro</label>
                                                </div>
                                            </div>
                                            <div class="col-md-auto  mb-2">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" name="autorizar_desconto" type="checkbox" role="switch" id="autorizar_desconto">
                                                    <label class="form-check-label" for="autorizar_desconto">Autorizar desconto</label>
                                                </div>
                                            </div>
                                            <div class="col-md-auto  mb-2">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" name="autorizar_dados_pedido_loja" type="checkbox" role="switch" id="autorizar_dados_pedido_loja">
                                                    <label class="form-check-label" for="autorizar_dados_pedido_loja">Autorizar Alteração Pedidos - Loja</label>
                                                </div>
                                            </div>

                                            <div class="col-md-auto  mb-2">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" name="receber_alerta" type="checkbox" role="switch" id="receber_alerta">
                                                    <label class="form-check-label" for="receber_alerta">Receber alerta</label>
                                                </div>
                                            </div>
                                            <div class="col-md-auto  mb-2">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" name="remover_faturamento" type="checkbox" role="switch" id="remover_faturamento">
                                                    <label class="form-check-label" for="remover_faturamento">Remover do faturamento</label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal_show_2"></div>



<script src="js/configuracao/usuario/usuario_tela.js"></script>