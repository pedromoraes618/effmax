<?php
// //cadastrar formulario
if (isset($_POST['relatorio'])) {
    include "../../conexao/conexao.php";
    include "../../funcao/funcao.php";
    $retornar = array();
    $acao = $_POST['acao'];

    if ($acao == "cabecalho") {
        $query = "SELECT * FROM tb_empresa where cl_id ='1' ";
        $consulta = mysqli_query($conecta, $query);
        if ($consulta) {
            $linha = mysqli_fetch_assoc($consulta);
            $razao_social = ($linha['cl_razao_social']);
            $nome_fantasia = utf8_encode($linha['cl_nome_fantasia']);
            $cnpj = ($linha['cl_cnpj']);
            $email = utf8_encode($linha['cl_email']);
            $telefone = ($linha['cl_telefone']);
            $cabecalho = "
            <div id='cabecalho' >
                <div style='display: flex;'>
                    <div class='logo' style='margin-right: 10px;'>
                        <img src='img/logo.png?$data_lancamento' width='80' style='object-fit: scale-down;' alt='Logo'>
                    </div>
                    <div style='display: flex; flex-direction: column;'>
                        <p style='margin: 0;font-size:0.9em'><strong>$nome_fantasia</strong></p>
                        <p style='margin: 0;font-size:0.9em'>CNPJ: $cnpj</p>
                        <p style='margin: 0;font-size:0.9em'>Tel: $telefone</p>
                        <p style='margin: 0;font-size:0.9em'>$email</p>
                    </div>
                </div>
            </div>";
            $informacao = array(
                "cabecalho" => $cabecalho,
            );

            $retornar["dados"] = array("sucesso" => true, "valores" => $informacao);
        }
    }

    echo json_encode($retornar);
}
