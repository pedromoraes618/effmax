<?php
include "../../../../conexao/conexao.php";
include "../../../../funcao/funcao.php";
?>
<div class="container">
    <div class="mb-3">
        <h4 class="fw-semibold">Ajuste Individual de Itens</h4>
        <span>Atualize o preço de cada item do seu estoque</span>
    </div>

    <div class="accordion " id="accordionPanelsStayOpenExample">
        <div class="accordion-item mb-2 ">
            <h2 class="accordion-header ">
                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                    Ajuste
                </button>
            </h2>
            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show ">
                <div class="accordion-body">

                    <div class="row" style="position:sticky">
                        <div class="col-md  mb-2">
                            <div class="input-group">
                                <input type="text" class="form-control" id="pesquisa_conteudo" placeholder="Tente pesquisar pelo titulo, referência, código, código de barras ou localização" aria-label="Recipient's username" aria-describedby="button-addon2">
                                <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
                            </div>
                            <div class="alerta">
                            </div>
                        </div>
                        <div class="col-md-auto  d-grid gap-2 d-sm-block mb-1">
                            <button class="btn btn btn-success" type="button" id="salvar_ajuste"><i class="bi bi-check-all"></i> Salvar ajuste</button>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-2 mb-2">
                            <select name="status_prod" class="form-select chosen-select" id="status_prod">
                                <option value="0">Status..</option>
                                <option selected value="SIM">Ativo</option>
                                <option value="NAO">Inativo</option>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <select name="subgrupo" class="form-select chosen-select" id="subgrupo">
                                <option value="0">Subgrupo..</option>
                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_subgrupo_estoque');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = $linha['cl_id'];
                                        $descricao_subgrupo = utf8_encode($linha['cl_descricao']);
                                        $grupo_id = ($linha['cl_grupo_id']);
                                        $descricao_grupo = consulta_tabela($conecta, "tb_grupo_estoque", "cl_id", $grupo_id, "cl_descricao");
                                        echo "<option  value='$id'>$descricao_grupo - $descricao_subgrupo</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <select name="tipo_produto_consulta_individual" aria-describedby="subgrupoHelp" class="form-select chosen-select" id="tipo_produto_consulta_individual">
                                <option value="0">Tipo..</option>
                                <?php
                                $resultados = consulta_linhas_tb($conecta, 'tb_tipo_produto');
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = $linha['cl_id'];
                                        $descricao = utf8_encode($linha['cl_descricao']);
                                        echo "<option  value='$id'>$descricao</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-2 mb-2">
                            <select name="estoque_consulta" class="form-select chosen-select" id="estoque_consulta">
                                <option value="0">Estoque..</option>
                                <option value="S">Com estoque</option>
                                <option value="N">Sem estoque</option>
                            </select>
                        </div>
                    </div>
                    <form id="ajuste_preco_item" style="max-height: 900px;">
                        <div class="tabela"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="js/estoque/ajuste_preco/aba/item.js"></script>