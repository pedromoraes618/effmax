<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/venda/venda_mercadoria/gerenciar_venda.php";

?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col"></th>
                <th scope="col">Dt. Devolução</th>
                <th scope="col">Doc</th>
                <th scope="col">Cliente</th>
                <th scope="col">Vendedor</th>
                <th scope="col">Status</th>
                <th scope="col">Forma Pgt</th>
                <th scope="col">Desconto</th>
                <th scope="col">V.Liquido</th>
                <th scope="col"></th>
                <th scope="col"></th>

            </tr>
        </thead>
        <tbody>
            <?php
            $valor_total = 0;

            while ($linha = mysqli_fetch_assoc($consultar_venda_mercadoria)) {
                $id_b = ($linha['id']);
                $data_movimento_b = ($linha['cl_data_movimento']);
                $codigo_nf = ($linha['cl_codigo_nf']);
                $numero_nf_b = ($linha['cl_numero_nf']);
                $serie_nf_b = ($linha['cl_serie_nf']);
                $status_recebmento_b = ($linha['cl_status_recebimento']);
                $status_recebmento_b_2 = ($linha['cl_status_recebimento']);
                $forma_pagamento_b = utf8_encode($linha['formapgt']);
                $parceiro_id = utf8_encode($linha['cl_parceiro_id']);
                $uf = ($linha['ufestado']);

                $razao_social_b = utf8_encode($linha['cl_razao_social']);
                $nome_fantasia_b = utf8_encode($linha['cl_nome_fantasia']);
                $valor_desconto_b = ($linha['cl_valor_desconto']);
                $valor_liquido_b = ($linha['cl_valor_liquido']);
                $vendedor_b = utf8_encode($linha['vendedor']);
                $tipo_pagamento = ($linha['tipopg']);
                $status_venda = ($linha['cl_status_venda']);
                $status_venda_id = ($linha['cl_status_venda']);
                $numero_nf_devolucao = ($linha['cl_numero_nf_devolucao']);
                $valor_total = $valor_liquido_b + $valor_total;
                $serie_fiscal = consulta_tabela($conecta, 'tb_serie', "cl_descricao", $serie_nf_b, "cl_serie_fiscal");
                $numero_protocolo = ($linha['cl_numero_protocolo']);
                $pdf_nf = ($linha['cl_pdf_nf']);
                $cpf_cnpj_avulso = ($linha['cl_cpf_cnpj_avulso_nf']);
                $ambiente = verficar_paramentro($conecta, "tb_parametros", "cl_id", "35"); // 1 - homologacao 2 - producao
                $uf = $uf != '' ? " - UF $uf" : '';
                if ($ambiente == "1") { //consultar o pdf da nota fiscal
                    $server = verficar_paramentro($conecta, "tb_parametros", "cl_id", "60");
                } elseif ($ambiente == "2") {
                    $server =  verficar_paramentro($conecta, "tb_parametros", "cl_id", "61");
                }

                if ($numero_nf_devolucao != "") {
                    $dev = "<span title='Existe uma devolução referente a esse doc' class='badge rounded-pill text-bg-primary'>$numero_nf_devolucao</span>";
                } else {
                    $dev = null;
                }
                if ($tipo_pagamento != "3") {
                    $tipo_pagamento = "cartao";
                } else {
                    $tipo_pagamento = "faturado";
                }
            ?>
                <tr>
                    <th> <input style="width: 20px;
    height: 20px;" class="form-check-input check_gerar_doc" type="checkbox" value="<?php echo $id_b; ?>>" id="flexCheckDefault"></th>

                    <th scope="row"><?php echo formatDateB($data_movimento_b) ?></th>
                    <td><?php echo $serie_nf_b . "" . $numero_nf_b . " " . $dev;
                        if ($numero_protocolo != "") {
                            echo "<a  href='$server$pdf_nf' target='_blank'><i class='bi bi-stickies'></i></a>";
                        } ?></td>
                  <td class="max_width_descricao"><?php echo $razao_social_b;  ?><br>
                        <hr class="mb-0"><?php echo $nome_fantasia_b . esconderParteCPF($cpf_cnpj_avulso) . $uf; ?>
                    </td>
                    <td><?php echo $vendedor_b; ?></td>
                    <td><span class='badge rounded-pill  text-bg-<?php if ($status_venda == "1") {
                                                                        echo 'warning';
                                                                        $status_venda = "Finalizado";
                                                                    } elseif ($status_venda == "2") {
                                                                        echo 'success';
                                                                        $status_venda = "Em andamento";
                                                                    } else {
                                                                        echo 'danger';
                                                                        $status_venda = "Cancelado";
                                                                    } ?>'><?php echo $status_venda; ?></td>

                    <td><span class="badge rounded-pill text-bg-primary"><?php echo ($forma_pagamento_b); ?></span></td>
                    <td><?php echo real_format($valor_desconto_b); ?></td>
                    <td><?php echo real_format($valor_liquido_b); ?></td>

                    <?php if ($status_recebmento_b == "1" and $status_venda_id != "3") {

                        echo  "<td class='td-btn'> <button type='button'  tipo_pagamento='$tipo_pagamento' 
                        nf_saida_id='$id_b' class='btn btn-sm receber_nf'><i class='bi bi-clipboard-check-fill text-success fs-4'>
                        </i></button></td>";
                    } else {
                        echo "<td></td>";
                    }
                    ?>

                    <td class="td-btn">
                        <div class="btn-group">
                            <div class="input-group mb-3">
                                <button type="button" codigo_nf='<?php echo $codigo_nf; ?>' serie_fiscal='<?php echo $serie_fiscal ?>' nf_saida_id="<?php echo $id_b; ?>" class="btn btn-sm  btn-info editar_venda_mercadoria">Editar</button>
                            </div>
                            <?php if ($serie_fiscal == "SIM") {
                            ?>
                                <div class="input-group mb-3">
                                    <button type="button" codigo_nf='<?php echo $codigo_nf; ?>' nf_saida_id="<?php echo $id_b; ?>" serie_fiscal='<?php echo $serie_fiscal ?>' class="btn btn-sm  btn-warning fiscal">Fiscal</button>
                                </div>
                            <?php
                            } ?>
                            <div class="input-group mb-3">
                                <button class="btn btn-sm btn-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Ações</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item cancelar_nf_saida" style="cursor: pointer;" codigo_nf='<?php echo $codigo_nf; ?>' nf_saida_id="<?php echo $id_b; ?>">Cancelar</a></li>
                                    <li><a class="dropdown-item dados_parceiro" style="cursor: pointer;" codigo_nf='<?php echo $codigo_nf; ?>' parceiro_id='<?php echo $parceiro_id; ?>' nf_saida_id="<?php echo $id_b; ?>">Cadastro do Cliente</a></li>

                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <th scope="col" colspan="8">Total</th>

            <th scope="col"><?php echo real_format($valor_total); ?></th>
            <th scope="col"></th>
            <th scope="col"></th>


        </tfoot>
    </table>
    <label>
        Registros <?php echo $qtd; ?>
    </label>
<?php
} else {
    include "../../../../view/alerta/alerta_pesquisa.php"; // mesnsagem para usuario pesquisar
}
?>
<script src="js/venda/venda_mercadoria/table/editar_venda.js">