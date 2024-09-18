<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/configuracao/forma_pagamento/gerenciar_forma_pagamento.php";

?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Código</th>
                <th scope="col">Descrição</th>
                <th scope="col">Conta financeira</th>
                <th scope="col">Status</th>
                <th scope="col">Tipo</th>
                
                <th scope="col">Ativo</th>
                <th scope="col"></th>
            </tr>
        </thead>
        <tbody>
            <?php while ($linha = mysqli_fetch_assoc($consultar_forma_pagamento)) {
                $id_b = $linha['formaPagamentoID'];
                $descricao_b = utf8_encode($linha['formaPagamentoDescricao']);
                $conta_financeira = utf8_encode($linha['cl_banco']);
                $status_recebimento = $linha['statusRecebimento'];
                $tipoPagamento = utf8_encode($linha['tipoPagamento']);
                $ativo = $linha['cl_ativo'];
            ?>
                <tr>

                    <th scope="row"><?php echo $id_b ?></th>
                    <td class="max_width_descricao"><?php echo $descricao_b; ?></td>
                    <td class="max_width_descricao"><?php echo $conta_financeira; ?></td>
                    <td class="max_width_descricao"><?php echo $status_recebimento; ?></td>
                    <td class="max_width_descricao"><?php echo $tipoPagamento; ?></td>
                    <td><span class='badge text-bg-<?php echo ($ativo == "S") ? 'success' : 'danger' ?>'><?php echo ($ativo == "S") ? 'Ativo' : 'Inativo' ?></td>
                    <td class="td-btn"><button type="button" forma_de_pagamento_id=<?php echo $id_b; ?> class="btn btn-info   btn-sm editar_forma_pagamento ">Editar</button>
                    </td>
                </tr>

            <?php } ?>
        </tbody>
    </table>
    <label>
        Registros <?php echo $qtd; ?>
    </label>
<?php
} else {
    include "../../../../view/alerta/alerta_pesquisa.php"; // mesnsagem para usuario pesquisar
}
?>
<script src="js/configuracao/forma_pagamento/table/editar_forma_pagamento.js">