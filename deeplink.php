<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concatenador e Encurtador de Links</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-bottom: 10px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }

        p {
            font-size: 18px;
            margin-top: 20px;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <h2>Concatenador e Encurtador de Links</h2>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="text" name="link" placeholder="Digite seu link aqui" required>
        <input type="submit" value="Concatenar e Encurtar">
    </form>

    <?php
    // Função para remover 'http://' ou 'https://'
    function removerProtocolo($link)
    {
        $protocolos = array("http://", "https://");
        foreach ($protocolos as $protocolo) {
            if (strpos($link, $protocolo) === 0) {
                return substr($link, strlen($protocolo));
            }
        }
        return $link;
    }

    // Função para obter o domínio base do link
    function obterDominioBase($link)
    {
        $parsed_url = parse_url($link);
        if (isset($parsed_url['scheme']) && isset($parsed_url['host'])) {
            return $parsed_url['scheme'] . '://' . $parsed_url['host'];
        }
        return null;
    }


    // function shortenUrl($originalUrl) //funçção para encurtar link
    // {
    //     $apiKey = 'ece7544dc479476c920f131f1665549e'; // Substitua pela sua chave de API da Rebrandly

    //     $data = [
    //         'destination' => $originalUrl,
    //         'domain' => ['fullName' => 'rebrand.ly']
    //     ];

    //     $ch = curl_init('https://api.rebrandly.com/v1/links');
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, [
    //         'Content-Type: application/json',
    //         'apikey: ' . $apiKey
    //     ]);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    //     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    //     $response = curl_exec($ch);
    //     curl_close($ch);

    //     $result = json_decode($response, true);

    //     return $result;
    // }


    // Verificar se o formulário foi submetido e o campo 'link' está presente
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["link"])) {
        // Limpar e sanitizar o URL fornecido pelo usuário
        $link = htmlspecialchars($_POST["link"]);

        // Obter o domínio base do link
        $dominio_base = obterDominioBase($link);

        // Remover 'http://' ou 'https://'
        $link_sem_protocolo = removerProtocolo($link);

        // // Concatenar o link sem protocolo com o esquema de redirecionamento 'intent://'
        // $link_concatenado = "intent://$link_sem_protocolo/#Intent;package=com.android.chrome;scheme=https;end";//abrir no chrome
        $link_concatenado = "intent://$link_sem_protocolo/#Intent;scheme=https;end"; //abrir no navegador padrão

        // Encurtar o URL usando a função encurtarURL()

       // $link_encurtado = shortenUrl($link_concatenado);
        /*/var_dump ($link_encurtado);*/
    ?>

    <?php
    }
    ?>

</body>

</html>