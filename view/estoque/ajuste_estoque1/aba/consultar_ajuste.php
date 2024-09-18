<?php
include "../../../../conexao/conexao.php";
include "../../../../funcao/funcao.php";
?>
<div class="container">
    <div class="mb-3">
        <h4 class="fw-semibold">Veja todos os ajustes realizados</h4>
        <span>Verifique cada ajuste efetuado</span>
    </div>

    <div class="accordion " id="accordionPanelsStayOpenExample">
        <div class="accordion-item mb-2 ">
            <h2 class="accordion-header ">
                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                    Consultar
                </button>
            </h2>
            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show ">
                <div class="accordion-body">

                    <div class="row">
                        <div class="col-auto  mb-2">
                            <div class="input-group">
                                <span class="input-group-text">Data</span>
                                <input type="date" class="form-control" id="data_inicial" name="data_incial" title="Data Inicial" placeholder="Data Inicial" value="<?php echo $data_inicial_ano_bd ?>">
                                <input type="date" class="form-control" id="data_final" name="data_final" title="Data Final" placeholder="Data Final" value="<?php echo $data_final_ano_bd; ?>">
                            </div>
                        </div>
                        <div class="col-md  mb-2">
                            <div class="input-group">
                                <input type="text" class="form-control" id="pesquisa_conteudo" placeholder="Tente pesquisar código do ajuste, código do produto ou pela descrição do produto" aria-label="Recipient's username" aria-describedby="button-addon2">
                                <button class="btn btn-outline-secondary" type="button" id="pesquisar_filtro_pesquisa">Pesquisar</button>
                            </div>
                            <div class="alerta">
                            </div>
                        </div>

                    </div>
                    <div class="row mb-2">

                        <div class="col-auto mb-2">
                            <select name="usuario_id" class="form-select chosen-select" id="usuario_id">
                                <option value="0">Usuário..</option>
                                <?php
                                $resultados = consulta_linhas_tb_query($conecta, "SELECT * FROM tb_users where cl_ativo = '1' ");
                                if ($resultados) {
                                    foreach ($resultados as $linha) {
                                        $id = $linha['cl_id'];
                                        $descricao = utf8_encode($linha['cl_usuario']);
                                        echo "<option  value='$id'>$descricao</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                    </div>
                    <div class="tabela"></div>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="js/estoque/ajuste_estoque1/aba/consultar_ajuste.js"></script>