<?php
include "../../../../modal/configuracao/usuario/gerenciar_usuario.php";
?>
<?php
if (!isset($consultar_tabela_inicialmente) or ($consultar_tabela_inicialmente == "S")) { //consultar parametro para carrregar inicialmente a tabela
?>
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Código</th>
                <th scope="col">Nome</th>
                <th scope="col">Usuário</th>
                <th scope="col">Perfil</th>
                <th scope="col">Situação</th>
                <th scope="col">Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($consultar_usuarios)) {
                $id_user_b = $row['cl_id'];
                $nome_b = utf8_encode($row['cl_nome']);
                $usuario_b = $row['cl_usuario'];
                $tipo_b = $row['cl_tipo'];
                $situacao_b = $row['cl_ativo'];
                if ($situacao_b == 1) {
                    $situacao_b = "Ativo";
                } else {
                    $situacao_b = "Inativo";
                }
            ?>
                <tr>
                    <th scope="row"><?php echo $id_user_b ?></th>
                    <td><?php echo $nome_b; ?></td>
                    <td><?php echo $usuario_b; ?></td>
                    <td><?php echo $tipo_b; ?></td>
                    <td><?php if ($situacao_b == "Ativo") {
                            echo  '<span class="badge rounded-pill text-bg-success">' .  $situacao_b . '</span>';
                        } else {
                            echo    '<span class="badge rounded-pill text-bg-danger">' . $situacao_b . '</span>';
                        } ?>

                    </td>
                    <td class="td-btn"><button type="button" id=<?php echo $id_user_b; ?> class="btn btn-sm btn-info editar">Editar</button>
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
<script src="js/configuracao/usuario/table/editar_usuario.js"></script>