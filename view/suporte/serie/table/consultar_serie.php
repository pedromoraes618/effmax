<?php
include "../../../../conexao/conexao.php";
include "../../../../modal/suporte/serie/gerenciar_serie.php";

?>

<?php

if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Código</th>
                <th scope="col">Descrição</th>
                <th scope="col">Valor</th>
                <th scope="col">Informção</th>
                <th scope="col"></th>
                <th></th>

            </tr>
        </thead>
        <tbody>
            <?php while ($linha = mysqli_fetch_assoc($consultar_serie)) {
                $id_serie_b = $linha['cl_id'];
                $descricao_b = $linha['cl_descricao'];
                $valor_b = $linha['cl_valor'];
                $informacao_b = utf8_encode($linha['cl_informacao']);
                $serie_fiscal = utf8_encode($linha['cl_serie_fiscal']);
                $serie_recibo = utf8_encode($linha['cl_serie_recibo']);
                $span_serie_fiscal = $serie_fiscal == "SIM" ? "<span class='badge text-bg-warning'>Série fiscal</span>" : '';
                $span_serie_recibo = $serie_recibo == "SIM" ? "<span class='badge text-bg-primary'>Série recibo</span>" : '';
            ?>
                <tr>
                    <th scope="row"><?php echo $id_serie_b ?></th>
                    <td><?php echo $descricao_b; ?></td>
                    <td><?php echo $valor_b; ?></td>
                    <td><?php echo $informacao_b; ?></td>
                    <td><?= $span_serie_fiscal . $span_serie_recibo; ?></td>
                    <td class="td-btn"><button type="button" id_serie=<?php echo $id_serie_b; ?> class="btn btn-sm btn-info editar_serie">Editar</button>
                    </td>
                </tr>

            <?php } ?>
        </tbody>
    </table>
<?php
} else {
    include "../../../../view/alerta/alerta_pesquisa.php"; // mesnsagem para usuario pesquisar

}
?>

<script src="js/suporte/serie/table/editar_serie.js">