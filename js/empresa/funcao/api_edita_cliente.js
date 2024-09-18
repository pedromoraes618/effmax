
$(document).ready(function () {
    var estado = document.getElementById("estado");
    let cidade = document.getElementById("cidade")
    var options_cidades = cidade.options;

    let inputcidade_id = document.getElementById("cidade_id").value
    let option_cidade = document.querySelector('#cidade option[value="' + 0 + '"]');
    //popular as cidades de acordo com o estado+
    cidade.removeAttribute("disabled", "disabled");
    // Altere o texto do <option>
    option_cidade.innerHTML = 'Selecione..';
    $.ajax({
        type: "POST",
        data: "consultar_cidade=true&estado_id=" + estado.value,
        url: "modal/empresa/cliente/consultar_cidade.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        $dados = $.parseJSON(data)["dados"];

        if ($dados.sucesso) {
            $tamanho = $dados.informacao.length

            cidade.options.length = 1;

            for ($i = 0; $i < $tamanho; $i++) {
                const newOption = document.createElement('option');

                // define o valor e o texto da opção
                newOption.value = $dados.informacao[$i].cidade_id;
                newOption.text = $dados.informacao[$i].cidade;
                newOption.setAttribute("codigo_ibge", $dados.informacao[$i].codigo_ibge)

                // adiciona a nova opção ao select
                cidade.add(newOption);

            }


        }
    }

    function falha() {
        console.log("erro ao requisitar ao bd")
    }

    
    if (inputcidade_id != "") {
   
        //selecionar a cidade pelo codigo ibge do cep consultado
        for (var i = 0; i < options_cidades.length; i++) {
            if (options_cidades[i].getAttribute("value") == inputcidade_id) {
                cidade.selectedIndex = i
                break; // Para o loop assim que o elemento for encontrado
            }
        }

    }

})