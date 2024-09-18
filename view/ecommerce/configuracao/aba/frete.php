<?php
include "../../../../conexao/conexao.php";
include "../../../../funcao/funcao.php";
?>
<div class="container">
    <div class="mb-3">
        <h4 class="fw-semibold">Frete e processamento</h4>
        <span> Gerencie como os clientes recebem seus pedidos. Defina as opções de entrega e conecte-se aos serviços de processamento.</span>
    </div>


    <div class="accordion " id="accordionPanelsStayOpenExample">
        <div class="accordion-item mb-2 ">
            <h2 class="accordion-header ">
                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="true" aria-controls="panelsStayOpen-collapseFour">
                    Frete
                </button>
            </h2>
            <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse show ">
                <div class="accordion-body">
                    <div class="accordion-body p-0">
                        <form id="form_frete">
                            <div class="row mb-3">
                                <label for="qtd_postagem" class="form-label">Quantidade de dias para a postagem da mercadoria à transportadora</label>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <input type="number" name="qtd_postagem" id="qtd_postagem" placeholder="Ex. 2" class="form-control">
                                        <span class="input-group-text" id="basic-addon1">Dias</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row  mb-3">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-start ">
                                    <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="accordion-item mb-2 ">
            <h2 class="accordion-header ">
                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                    Frete Grátis
                </button>
            </h2>
            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show ">
                <div class="accordion-body">
                    <div class="accordion-body p-0">
                        <form id="form_frete_gratis">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="frete_gratis" class="form-label">Selecione se está habiltado.</label>
                                    <select class="form-select chosen-select" name="frete_gratis" id="frete_gratis">
                                        <option value="0">Selecione..</option>
                                        <option value="true">Sim</option>
                                        <option value="false">Nao</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="frete_gratis" class="form-label">Taxa para pedidos dentro do estado</label>
                                    <input type="number" name="taxa_frete_gratis_dentro_estado" id="taxa_frete_gratis_dentro_estado" step="any" placeholder="Ex. 200" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label for="frete_gratis" class="form-label">Taxa para pedidos fora do estado</label>
                                    <input type="number" name="taxa_frete_gratis_fora_estado" id="taxa_frete_gratis_fora_estado" step="any" placeholder="Ex. 300" class="form-control">
                                </div>
                            </div>
                            <div class="row  mb-3">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-start ">
                                    <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <div class="accordion-item mb-2">
            <h2 class="accordion-header">
                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
                    Entrega Local
                </button>
            </h2>
            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    <form id="frete_local">
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <label for="valor_entrega_local" class="form-label">Valor</label>
                                <div class="input-group ">
                                    <span class="input-group-text" id="basic-addon1">R$</span>
                                    <input type="number" step="any" name="valor_entrega_local" id="valor_entrega_local" placeholder="Ex. 15" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-3">
                                <label for="prazo_entrega_local" class="form-label">Prazo estimado de entrega (dias)</label>
                                <input type="number" step="any" name="prazo_entrega_local" id="prazo_entrega_local" placeholder="Ex. 2" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="codigo_postal_entrega_local" class="form-label">Códigos postais com a mesma taxa de entrega (apenas os três primeiros números)</label>
                                <textarea rows="3" id="codigo_postal_entrega_local" name="codigo_postal_entrega_local" placeholder="Separe os códigos postais por espaço, exe 650 617 658" class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row  mb-2">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-start ">
                                <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="accordion-item">
            <h2 class="accordion-header">
                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                    Retirada
                </button>
            </h2>
            <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    <form id="frete_retirada_local">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="frete_retirada" class="form-label">Selecione se está habiltado.</label>
                                <select class="form-select chosen-select" name="frete_retirada" id="frete_retirada">
                                    <option value="0">Selecione..</option>
                                    <option value="S">Sim</option>
                                    <option value="N">Nao</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-7">
                                <label for="endereco_retirada" class="form-label">Endereço de retirada</label>
                                <input type="text" name="endereco_retirada" id="endereco_retirada" placeholder="Ex. Rua 3, bairro 4, cidade 5 - estado 6" class="form-control">
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label for="instrucao_retirada" class="form-label">Instruções de retirada</label>
                                <textarea rows="3" id="instrucao_retirada" name="instrucao_retirada" placeholder="Por exemplo: Você receberá uma notificação para retirar seu pedido. A confirmação e o ID do pedido serão necessários para retirada. Horário de funcionamento da loja: segunda a sexta das 9:00 às 17:00." class="form-control"></textarea>
                            </div>
                        </div>
                        <div class="row  mb-2">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-start ">
                                <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="accordion-item mb-2 ">
            <h2 class="accordion-header ">
                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseSix" aria-expanded="true" aria-controls="panelsStayOpen-collapseSix">
                    Modelos de caixa
                </button>
            </h2>
            <div id="panelsStayOpen-collapseSix" class="accordion-collapse collapse show ">
                <div class="accordion-body">
                    <div class="accordion-body p-0">
                        <form id="form_caixa_modelo" style="max-height:700px">
                            <div class="row mb-2">
                                <input type="hidden" name="caixa_id" id="caixa_id">
                                <div class="col-md-9 mb-2">
                                    <label for="nome_caixa" class="form-label">Nome</label>
                                    <input type="text" name="nome_caixa" id="nome_caixa" placeholder="Escolhar um nome para ajudar a identificar a caixa no futuro. Ex (Caixa grande)." class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <label for="limite_produto_caixa" class="form-label">Limite de Produto</label>
                                    <input type="number" name="limite_produto_caixa" id="limite_produto_caixa" placeholder="Limite de produto" class="form-control">
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-2">
                                    <label for="largura_caixa" class="form-label">Largura</label>
                                    <div class="input-group">
                                        <input type="number" step="any" name="largura_caixa" id="largura_caixa" placeholder="0.00" class="form-control">
                                        <span class="input-group-text" id="basic-addon1">cm</span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="altura_caixa" class="form-label">Altura</label>
                                    <div class="input-group">
                                        <input type="number" step="any" name="altura_caixa" id="altura_caixa" placeholder="0.00" class="form-control">
                                        <span class="input-group-text" id="basic-addon1">cm</span>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <label for="comprimento_caixa" class="form-label">Comprimento</label>
                                    <div class="input-group">
                                        <input type="number" step="any" name="comprimento_caixa" id="comprimento_caixa" placeholder="0.00" class="form-control">
                                        <span class="input-group-text" id="basic-addon1">cm</span>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label for="peso_caixa" class="form-label">Peso</label>
                                    <div class="input-group">
                                        <input type="number" step="any" name="peso_caixa" id="peso_caixa" placeholder="0.00" class="form-control">
                                        <span class="input-group-text" id="basic-addon1">KG</span>

                                    </div>
                                </div>
                            </div>
                            <div class="row  mb-3">
                                <div class="d-grid gap-2 d-md-flex justify-content-md-start ">
                                    <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Salvar</button>
                                </div>
                            </div>
                        </form>
                        <div class="card border-0 mb-2 ">
                            <div class="card-header header-card-dashboard" title="Receitas consistem em lançamentos que foram feitos atraveis de recebimento">
                                <h6>Modelos</h6>
                            </div>
                            <div class="card-body tabela table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col">Nome</th>
                                            <th scope="col">Limite Produtos</th>
                                            <th scope="col">Dimensões</th>
                                            <th scope="col">Peso</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_modelo_caixa_ecommerce");
                                        if ($resultados) {
                                            foreach ($resultados as $linha) {
                                                $id = $linha['cl_id'];
                                                $nome = utf8_encode($linha['cl_nome']);
                                                $limite_produto = ($linha['cl_limite_produto']);
                                                $altura = ($linha['cl_altura']);
                                                $comprimento = ($linha['cl_comprimento']);
                                                $largura = ($linha['cl_largura']);
                                                $peso = ($linha['cl_peso']);
                                        ?>
                                                <tr>
                                                    <td><?= $nome; ?></td>
                                                    <td><?= $limite_produto; ?></td>
                                                    <td><?= "Altura: $altura cm <br> Comprimento: $comprimento cm <br> Largura: $largura cm<br> " ?></td>
                                                    <td><?= ($peso); ?></td>
                                                    <td><button type="buttom" id='<?= $id; ?>' class="btn btn-sm btn-info editar_caixa">Editar</button></td>
                                                </tr>
                                        <?php
                                            }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="js/ecommerce/configuracao/aba/frete.js"></script>