<?php
include "../../../conexao/conexao.php";
include "../../../funcao/funcao.php";
include "../../../modal/configuracao/empresa/gerenciar_empresa.php";
?>



<div class="title">
    <label class="form-label">Registro de Empresa</label>
    <div class="msg_title">
        <p>Informações da Empresa</p>
    </div>
</div>
<hr>

<div class="accordion " id="accordionPanelsStayOpenExample">


    <form id="logo_empresa">
        <div class="row d-flex align-items-top mb-2">
            <div class="col-md-auto ">
                <img src="img/logo.png?<?php echo time(); ?>" width="100" class="img-fluid img-thumbnail" alt="" style="object-fit: scale-down;">
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input class="form-control form-control-sm mb-2" type="file" id="file-input" name="file-input">
                    <button type="submit" id="upload_logo" class="btn btn-dark btn-sm">Anexar logo da empresa</button>
                </div>
                <div id="imgHelp" class="form-text">A imagem deve estar no formato .png e ter um tamanho máximo de 700 KB.</div>
            </div>
        </div>
    </form>


    <form id="registro_empresa" class="P-1" style="max-height: 1500px">
        <div class="accordion " id="accordionPanelsStayOpenExample">
            <div class="accordion-item mb-2 ">
                <h2 class="accordion-header ">
                    <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                        Dados cadastrais
                    </button>
                </h2>
                <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show ">
                    <div class="accordion-body">
                        <input type="hidden" class="form-control" id="img" name="img" value="">

                        <div class="row  mb-1">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end ">
                                <button type="submit" id="button_form" class="btn btn-sm btn-success"><i class="bi bi-check-all"></i> Alterar</button>
                                <button type="button" class="btn btn-sm btn-warning modal_anexo"><i class="bi bi-file-earmark"></i> Anexo</button>

                            </div>
                        </div>

                        <div class="row mb-2">
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
                            <div class="col-md-3   mb-2">
                                <label for="cnpjcpf" class="form-label">Cnpj \ Cpf</label>
                                <div class="input-group">
                                    <input type="text" class="form-control inputNumber" id="cnpjcpf" name="cnpjcpf" placeholder="Apenas números">
                                    <button class="btn btn-secondary" id="consutar_cnpj" type="button" title="Consultar cnpj apenas"><i class="bi bi-search"></i></button>
                                </div>
                            </div>

                            <div class="col-md-2   mb-2">
                                <label for="ie" class="form-label">Inscrição estadual</label>
                                <input type="number" class="form-control" id="ie" name="ie" placeholder="Apenas números" value="">
                            </div>
                            <div class="col-md-2   mb-2">
                                <label for="inscricao_municipal" class="form-label">Inscrição municipal</label>
                                <input type="number" class="form-control" id="inscricao_municipal" name="inscricao_municipal" placeholder="Ex. 6515748755" value="">
                            </div>
                            <div class="col-sm-6 col-md-3   mb-2">
                                <label for="email" class="form-label">Regime tributário</label>
                                <select class="form-select" id="regime_tributario" name="regime_tributario">
                                    <option value="0">Selecione</option>
                                    <option value="1">Simples nacional</option>
                                    <option value="3">Regime normal</option>
                                </select>
                            </div>
                            <div class="col-md-2   mb-2">
                                <label for="cnae" class="form-label">Cnae</label>
                                <input type="text" class="form-control" id="cnae" name="cnae" placeholder="Ex. 5548958" value="">
                            </div>
                        </div>


                        <div class="row  mb-2">
                            <div class="col-md-2   mb-2">
                                <label for="cep" class="form-label">Cep</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="cep" name="cep" placeholder="Ex. 62057712">
                                    <button class="btn btn-secondary" id="buscar_cep" type="button" title="Consultar cep"><i class="bi bi-search"></i></button>
                                </div>
                            </div>
                            <div class="col-md-3   mb-2">
                                <label for="endereco" class="form-label">Endereço</label>
                                <input type="text" class="form-control" id="endereco" name="endereco" value="">
                            </div>
                            <div class="col-md-1   mb-2">
                                <label for="numero" class="form-label">Número</label>
                                <input type="text" class="form-control" id="numero" name="numero" value="">
                            </div>

                            <div class="col-md-2  mb-2">
                                <label for="bairro" class="form-label">Bairro</label>
                                <input type="text" class="form-control" id="bairro" name="bairro" value="">
                            </div>

                            <div class="col-md-2  mb-2">
                                <label for="cidade" class="form-label">Cidade</label>
                                <input type="text" class="form-control" id="cidade" name="cidade" value="">

                            </div>
                            <div class="col-md-auto mb-2">
                                <label for="estado" class="form-label">Estado</label>

                                <select name="estado" class="form-select chosen-select" id="estado">
                                    <option value="0">Selecione..</option>
                                    <?php
                                    $resultados = consulta_linhas_tb($conecta, 'tb_estados');
                                    if ($resultados) {
                                        foreach ($resultados as $linha) {
                                            $id = $linha['cl_id'];
                                            $descricao = utf8_encode($linha['cl_uf']);
                                            echo "<option value='$descricao'>$descricao</option>";
                                        }
                                    }
                                    ?>
                                </select>
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
                    </div>
                </div>
            </div>
            <div class="accordion-item mb-2 ">
                <h2 class="accordion-header ">
                    <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
                        Integrações
                    </button>
                </h2>
                <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show ">
                    <div class="accordion-body">
                        <div class="row mb-2  p-2">
                            <div class="d-flex align-items-center mb-2">
                                <div class="mx-2">
                                    <img width="120" src="https://focusnfe.com.br/wp-content/uploads/2023/07/logo-focusnfe-assinatura-email.png" alt="">
                                </div>
                                <div>
                                    <div>
                                        <h5 class="mb-1">Focus NFE</h5>
                                    </div>
                                    <div>
                                        <small class="mb-1">Api de nota fiscal</small>
                                    </div>
                                    <div>
                                        <small class="mb-1">Permite que você envie nfe, nfs, nfc entre outros tipos de notas fiscais.</small>
                                    </div>
                                </div>

                            </div>
                            <div class="p-2 span_fpg_focusnfe">
                                <div class="card">
                                    <div class="card-header">Defina se o ambiente de pagamento da focus está em Homologação (Teste) ou em Produção.</div>

                                    <div class="card-body">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="flexRadioDefaultfocusnfe" value="focusnfe_homologacao" checked id="flexRadiofocusnfeHomologacao">
                                            <label class="form-check-label" for="flexRadiofocusnfeHomologacao">
                                                homologação
                                            </label>
                                            <div class="row mb-2 span_homologacao_focusnfe">
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" aria-describedby="helpTokenHomologacaofocusnfe" id="token_homologacao_focusnfe" name="token_homologacao_focusnfe">
                                                    <div id="helpTokenHomologacaofocusnfe" class="form-text">Digite o token para homologação</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-check ">
                                            <input class="form-check-input" type="radio" name="flexRadioDefaultfocusnfe" value="focusnfe_producao" id="flexRadiofocusnfeProducao">
                                            <label class="form-check-label" for="flexRadiofocusnfeProducao">
                                                Produção
                                            </label>
                                            <div class="row mb-2 span_producao_focusnfe">
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" aria-describedby="helpTokenProducaofocusnfe" id="token_producao_focusnfe" name="token_producao_focusnfe">
                                                    <div id="helpTokenProducaofocusnfe" class="form-text">Digite o token para produção</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div><!-- loading -->
            <div class="accordion-item mb-2 ">
                <h2 class="accordion-header ">
                    <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="true" aria-controls="panelsStayOpen-collapseThree">
                        Horário de funcionamento
                    </button>
                </h2>
                <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show ">
                    <div class="accordion-body">
                        <div class="row ">
                            <?php
                            $resultados = consulta_linhas_tb($conecta, 'tb_data_funcionamento');
                            foreach ($resultados as $linha) {


                                $id = $linha['cl_id'];
                                $dia_semana = $linha['cl_dia_semana'];
                                $status_funcionamento = $linha['cl_status_funcionamento'];
                                $hora_abertura = $linha['cl_hora_abertura'];
                                $hora_fechamento = $linha['cl_hora_fechamento'];

                                $hora_abertura = DateTime::createFromFormat('Y-m-d H:i:s', $hora_abertura);
                                $hora_fechamento = DateTime::createFromFormat('Y-m-d H:i:s', $hora_fechamento);

                                $hora_abertura = $hora_abertura->format('H:i');
                                $hora_fechamento = $hora_fechamento->format('H:i');



                                if ($dia_semana == 'Monday') {
                                    $dia_semana = "Segunda feira.";
                                } elseif ($dia_semana == 'Tuesday') {
                                    $dia_semana = "Terça-feira.";
                                } elseif ($dia_semana == 'Wednesday') {
                                    $dia_semana = "Quarta-feira.";
                                } elseif ($dia_semana == 'Thursday') {
                                    $dia_semana = "Quinta-feira.";
                                } elseif ($dia_semana == 'Friday') {
                                    $dia_semana = "sexta-feira.";
                                } elseif ($dia_semana == 'Saturday') {
                                    $dia_semana = "Sábado.";
                                } elseif ($dia_semana == 'Sunday') {
                                    $dia_semana = "Domingo.";
                                }
                            ?>
                                <div class="col-md-5 mb-2 border border-1 m-1">
                                    <label class="form-label"><?php echo $dia_semana; ?></label>
                                    <div class="d-grid gap-2 d-md-flex  align-items-center">
                                        Abertura <input class="form-control" type="time" name="hra<?php echo $id; ?>" id="" value="<?php echo $hora_abertura; ?>"> Fechamento
                                        <input class="form-control" type="time" name="hrf<?php echo $id; ?>" id="" value="<?php echo $hora_fechamento; ?>">

                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input segunda" name="abt<?php echo $id; ?>" type="checkbox" id="flexCheckDefault<?php echo $id ?>" <?php if ($status_funcionamento == 'SIM') {
                                                                                                                                                                            echo 'checked';
                                                                                                                                                                        } ?> value="option1">
                                            <label class="form-check-label segunda" for="flexCheckDefault<?php echo $id ?>">Aberto</label>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include "../../loading/spinner.php"; ?>
    </form>
    <div class="modal_externo"></div>
</div>


<script src="js/funcao.js"></script>
<script src="js/configuracao/empresa/empresa_tela.js"></script>