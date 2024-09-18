<div class="container">
    <div class="mb-3">
        <h4 class="fw-semibold">Integrações</h4>
        <span> Gerencie as integrações de pagamento, frete do seu site.</span>
    </div>


    <div class="accordion " id="accordionPanelsStayOpenExample">
        <div class="accordion-item mb-2 ">
            <h2 class="accordion-header ">
                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                    Pagamento
                </button>
            </h2>
            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show ">
                <div class="accordion-body">
                    <div class="accordion-body p-0">
                        <form id="pagamento" style="max-height: 1200px;">
                            <div class="row mb-2  p-2">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="mx-2">
                                        <img width="120" src="https://jequitai.khalsms.com.br/product_images/g/644/mercado-pago-logo__38687_std.png" alt="">
                                    </div>
                                    <div>
                                        <div>
                                            <h5 class="mb-1">Mercado Pago</h5>
                                        </div>
                                        <div>
                                            <small class="mb-1">Provedor do pagamento: Mercado Pago</small>
                                        </div>
                                        <div>
                                            <small class="mb-1">Permite que você aceite cartões de crédito, boleto bancário, OXXO e outros métodos de pagamento locais.</small>
                                        </div>
                                    </div>
                                    <div class="ms-auto">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" name="flexSwitchCheckCheckedFpgMercadoPago" id="flexSwitchCheckCheckedFpgMercadoPago">
                                        </div>
                                    </div>
                                </div>
                                <div class="p-2 span_fpg_mercado_pago">
                                    <div class="card mb-2">
                                        <div class="card-header">Instruções para conectar o Mercado Pago ao E-commerce</div>
                                        <div class="card-body">
                                            Para conectar sua conta Mercado Pago:
                                            <ol>
                                                <li>Crie uma conta no Mercado Pago. Ainda não tem uma conta? <a target="_blank" class="text-decoration-none" href="https://www.mercadopago.com.br/hub/registration/landing?redirect_url=https%3A%2F%2Fwww.mercadopago.com.br%2F">Clique aqui para criar uma conta no Mercado Pago</a>.</li>
                                                <li>Habilite o token de acesso na sua conta Mercado Pago.</li>
                                                <li>Copie e cole o token de acesso no campo logo abixo.</li>
                                            </ol>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">Defina se o ambiente de pagamento do Mercado Pago está em Homologação (Teste) ou em Produção.</div>

                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="flexRadioDefaultMercadoPago" value="mercado_pago_homologacao" checked id="flexRadioMercadoPagoHomologacao">
                                                <label class="form-check-label" for="flexRadioMercadoPagoHomologacao">
                                                    homologação
                                                </label>
                                                <div class="row mb-2 span_homologacao_mercado_pago">
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" aria-describedby="helpTokenHomologacaoMercadoPago" id="token_homologacao_mercado_pago" name="token_homologacao_mercado_pago">
                                                        <div id="helpTokenHomologacaoMercadoPago" class="form-text">Digite o token para homologação</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-check ">
                                                <input class="form-check-input" type="radio" name="flexRadioDefaultMercadoPago" value="mercado_pago_producao" id="flexRadioMercadoPagoProducao">
                                                <label class="form-check-label" for="flexRadioMercadoPagoProducao">
                                                    Produção
                                                </label>
                                                <div class="row mb-2 span_producao_mercado_pago">
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" aria-describedby="helpTokenProducaoMercadoPago" id="token_producao_mercado_pago" name="token_producao_mercado_pago">
                                                        <div id="helpTokenProducaoMercadoPago" class="form-text">Digite o token para produção</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-2  p-2 ">
                                <div class="d-flex align-items-center">
                                    <div class="mx-2">
                                        <img width="120" src="https://fintech.com.br/app/uploads/2020/10/paypal.jpg" alt="">
                                    </div>
                                    <div>
                                        <div>
                                            <h5 class="mb-1">PayPal
                                            </h5>
                                        </div>
                                        <div>
                                            <small class="mb-1">Permite que você aceite pagamentos com PayPal. </small>
                                        </div>
                                    </div>
                                    <div class="ms-auto">
                                        <div class="form-check form-switch">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" role="switch" name="flexSwitchCheckCheckedFpgPaypal" id="flexSwitchCheckCheckedFpgPaypal">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-2 span_fpg_pay_pal">
                                    <div class="card mb-2">
                                        <div class="card-header">Instruções para conectar PayPal</div>
                                        <div class="card-body">
                                            Para conectar sua conta PayPal:
                                            <ol>
                                                <li>Crie uma conta no PayPal. Ainda não tem uma conta? <a target="_blank" class="text-decoration-none" href="https://www.paypal.com/bizsignup/partner#/checkAccount">Clique aqui para criar uma conta PayPal</a>.</li>
                                                <li>Insira seu email da conta Paypal no formulário abaixo.</li>
                                                <li>Habilite o token de acesso na sua conta PayPal.</li>
                                                <li>Copie e cole o token de acesso no campo logo abixo.</li>
                                            </ol>
                                        </div>
                                    </div>

                                    <div class="card mb-2">
                                        <div class="card-header">Defina se o ambiente do PayPal está em homologação(Teste) ou em produção</div>
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="flexRadioDefaultPayPal" value="pay_pal_homologacao" checked id="flexRadioPayPalHomologacao">
                                                <label class="form-check-label" for="flexRadioPayPalHomologacao">
                                                    homologação
                                                </label>
                                                <div class="row mb-2 span_homologacao_pay_pal">
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" aria-describedby="helpTokenHomologacaoPayPal" id="token_homologacao_pay_pal" name="token_homologacao_pay_pal">
                                                        <div id="helpTokenHomologacaoPayPal" class="form-text">Digite o token para homologação</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" type="radio" name="flexRadioDefaultPayPal" value="pay_pal_producao" id="flexRadioPayPalProducao">
                                                <label class="form-check-label" for="flexRadioPayPalProducao">
                                                    Produção
                                                </label>
                                                <div class="row mb-2  span_producao_pay_pal">
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" aria-describedby="helpTokenProducaoPayPal" id="token_producao_pay_pal" name="token_producao_pay_pal">
                                                        <div id="helpTokenProducaoPayPal" class="form-text">Digite o token para produção</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mb-2 ">
                                                <div class="col-md-4">
                                                    <label for="email_paypal">Insira um endereço de email</label>
                                                    <input type="text" class="form-control" id="email_paypal" name="email_paypal">
                                                </div>
                                            </div>
                                        </div>

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
        <div class="accordion-item mb-2">
            <h2 class="accordion-header">
                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="true" aria-controls="panelsStayOpen-collapseTwo">
                    Frete
                </button>
            </h2>
            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    <form id="frete" style="max-height: 1200px;">
                        <div class="row mb-2  p-2 ">
                            <div class="d-flex align-items-center">
                                <div class="mx-2">
                                    <img width="120" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQLXcnUQiv_77NY9wao_7lh_UvUYX1Xuzf8Zg&s" alt="">
                                </div>
                                <div>
                                    <div>
                                        <h5 class="mb-1">Kangu
                                        </h5>
                                    </div>
                                    <div>
                                        <small class="mb-1">Facilite o envio dos seus produtos. </small>
                                    </div>
                                </div>
                                <div class="ms-auto">
                                    <div class="form-check form-switch">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" name="flexSwitchCheckCheckedFrete" value="kangu" id="flexSwitchCheckCheckedFreteKangu">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2 span_frete_kangu">
                                <div class="card mb-2">
                                    <div class="card-header">Instruções para conectar ao kangu</div>
                                    <div class="card-body">
                                        Para conectar sua conta kangu:
                                        <ol>
                                            <li>Crie uma conta no kangu. Ainda não tem uma conta? <a target="_blank" class="text-decoration-none" href="https://www.kangu.com.br/cadastro-seller/">Clique aqui para criar uma conta kangu</a>.</li>
                                            <li>Habilite o token de acesso na sua conta kangu.</li>
                                            <li>Copie e cole o token de acesso no campo logo abixo.</li>
                                        </ol>
                                    </div>
                                </div>

                                <div class="card mb-2">
                                    <div class="card-header">Defina se o ambiente da kangu está em homologação(Teste) ou em produção</div>
                                    <div class="card-body">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="flexRadioDefaultKangu" checked value="kangu_producao" id="flexRadioKanguProducao">
                                            <label class="form-check-label" for="flexRadioKanguProducao">
                                                Produção
                                            </label>
                                            <div class="row mb-2">
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" aria-describedby="helpTokenProducaoKangu" id="token_producao_kangu" name="token_producao_kangu">
                                                    <div id="helpTokenProducaoKangu" class="form-text">Digite o token para produção</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
        <div class="accordion-item mb-2">
            <h2 class="accordion-header">
                <button class="accordion-button text-dark fw-semibold" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="true" aria-controls="panelsStayOpen-collapseThree">
                    Conversão
                </button>
            </h2>
            <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse show">
                <div class="accordion-body">
                    <form id="conversao" style="max-height: 1200px;">
                        <div class="row mb-2  p-2 ">
                            <div class="d-flex align-items-center">
                                <div class="mx-2">
                                    <img width="120" src="https://img.odcdn.com.br/wp-content/uploads/2023/09/facebook-logo.png" alt="">
                                </div>
                                <div>
                                    <div class="d-flex">
                                        <h5 class="mb-1">Pixel e API de Conversões da Meta
                                        </h5><span class="span_conf_api_meta border p-1 mx-2" style="font-size: 0.8em;"></span>
                                    </div>
                                    <div>
                                        <small class="mb-1">Acompanhe as conversões e meça o sucesso das campanhas do Facebook. </small>
                                    </div>
                                </div>
                                <div class="ms-auto">
                                    <div class="form-check form-switch">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" name="flexSwitchCheckCheckedApiMeta" id="flexSwitchCheckCheckedApiMeta">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-2 span_api_meta">
                                <div class="card mb-2">
                                    <div class="card-header">Instruções para conectar a API de conversão da meta</div>
                                    <div class="card-body">
                                        Para conectar a api da meta:
                                        <span>Com o novo Pixel e API de Conversões da Meta, você pode acompanhar as taxas de conversão, controlar os dados que compartilha, obter insights sobre as jornadas dos clientes e otimizar suas campanhas do Facebook. Para se conectar, você precisa ser administrador de uma página do Facebook e ter uma conta do pixel da Meta e do Gerenciador de Negócios da Meta.</span>
                                        <div><a href="https://www.facebook.com/business/help/2041148702652965?id=818859032317965" target="_blank" class="text-decoration-none">Saiba mais sobre o pixel e Api de conversões da Meta</a></div>
                                    </div>
                                </div>

                                <div class="card mb-2">
                                    <div class="card-header">Defina as credenciais para utilizar a API da Meta</div>
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" aria-describedby="helpDatasetIDApiMeta" id="datasetid_api_meta" name="datasetid_api_meta">
                                                <div id="helpDatasetIDApiMeta" class="form-text">Digite o DatasetId API Meta</div>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" aria-describedby="helpTokenProducaoApiMeta" id="token_producao_api_meta" name="token_producao_api_meta">
                                                <div id="helpTokenProducaoApiMeta" class="form-text">Digite o Token de produçao</div>
                                            </div>
                                        </div>
                                    </div>
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
</div>


<script src="js/ecommerce/configuracao/aba/integracao.js"></script>