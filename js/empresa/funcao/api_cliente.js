$("#consutar_cnpj").click(function () {
    var cnpj = document.getElementById('cnpjcpf').value
    let cidade = document.getElementById("cidade")
    let option_cidade = document.querySelector('#cidade option[value="' + 0 + '"]');


    var estado = document.getElementById("estado");
    var options = estado.options;
    var option;


    if (cnpj == "") {
        Swal.fire({
            icon: 'error',
            title: 'Verifique!',
            text: "Informe o campo cnpj",
            timer: 7500,

        })
    } else {
        document.getElementById("carregando").style.display = "block";
        $.ajax({
            'url': 'https://www.receitaws.com.br/v1/cnpj/' + cnpj.replace(/[^0-9]/g, '', ".", "-"),
            'type': "GET",
            'dataType': 'jsonp',
            'success': function (data) {

                if (data.nome == undefined) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Verifique!',
                        text: "Cnpj não encontrado, favor verifique",
                        timer: 7500,

                    })
                    document.getElementById("carregando").style.display = "none";
                } else {
                    document.getElementById('rzaosocial').value = data.nome;
                    document.getElementById('nfantasia').value = data.fantasia;
                    document.getElementById('cep').value = data.cep;
                    document.getElementById('endereco').value = data.logradouro;
                    document.getElementById('bairro').value = data.bairro;
                    document.getElementById('telefone').value = data.telefone;
                    document.getElementById('email').value = data.email;


                    //selecionar o estado do cnpj consultado
                    for (var i = 0; i < options.length; i++) {
                        if (options[i].getAttribute("uf_estado") == data.uf) {
                            estado.selectedIndex = i
                            break; // Para o loop assim que o elemento for encontrado
                        }
                    }

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

                            cidade.options.length = 1; // resetar os options de cidade

                            for ($i = 0; $i < $tamanho; $i++) {
                                const newOption = document.createElement('option');



                                newOption.value = $dados.informacao[$i].cidade_id;
                                newOption.text = $dados.informacao[$i].cidade;
                                newOption.setAttribute("codigo_ibge", $dados.informacao[$i].codigo_ibge)

                                // adiciona a nova opção ao select
                                cidade.add(newOption);



                                //com a consulta do cnpj irá retornr o cep, con esse cep será consultada na api de busca de cep,
                                // irá retornar o codigo ibge da cidade com isso será selecionado a cidade
                                let cidade_select = document.getElementById("cidade")
                                var options_cidades = cidade_select.options;
                                var cep = document.getElementById("cep").value
                                //consultando cep 
                                var cep_replace = cep.replace(/[^0-9]/g, '', ".", "-");
                                const url = `https://viacep.com.br/ws/${cep_replace}/json/`;
                                fetch(url)
                                    .then(response => response.json())
                                    .then(data => {
                                        // logradouro.value = data.logradouro;
                                        // bairro.value = data.bairro;
                                        if (data.ibge != "") {
                                            //selecionar a cidade pelo codigo ibge do cep consultado
                                            for (var i = 0; i < options_cidades.length; i++) {
                                                if (options_cidades[i].getAttribute("codigo_ibge") == data.ibge) {
                                                    cidade_select.selectedIndex = i
                                                    break; // Para o loop assim que o elemento for encontrado
                                                }
                                            }

                                        }
                                    })


                            }

                        }
                    }

                    function falha() {
                        console.log("erro ao requisitar ao bd")
                    }

                    document.getElementById("carregando").style.display = "none";
                }

            }

        })

    }
})


//popular as cidades de acordo com o estado
$("#estado").change(function () {
    let estado = document.getElementById("estado").value
    let cidade = document.getElementById("cidade")
    let option_cidade = document.querySelector('#cidade option[value="' + 0 + '"]');

    //popular as cidades de acordo com o estado
    if (estado != "0") {
        cidade.removeAttribute("disabled", "disabled");
        // Altere o texto do <option>
        option_cidade.innerHTML = 'Selecione..';
        $.ajax({
            type: "POST",
            data: "consultar_cidade=true&estado_id=" + estado,
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

                    // adiciona a nova opção ao select
                    cidade.add(newOption);

                }

            }
        }

        function falha() {
            console.log("erro ao requisitar ao bd")
        }

    } else {
        cidade.setAttribute("disabled", "disabled");
        // Altere o texto do <option>
        option_cidade.innerHTML = 'Defina o estado..';
    }




})

//consultar cep
$("#buscar_cep").click(function (e) {


    let option_cidade = document.querySelector('#cidade option[value="' + 0 + '"]');

    var estado = document.getElementById("estado");
    var options_estado = estado.options;

    let cidade = document.getElementById("cidade")
    var options_cidades = cidade.options;

    var cep = document.getElementById("cep").value
    var logradouro = document.getElementById("endereco")
    var bairro = document.getElementById("bairro")

    if (cep == "") {//verificar se o capo está preenchido
        Swal.fire({
            icon: 'error',
            title: 'Verifique!',
            text: "Favor informe o cep",
            timer: 7500,

        })
    } else {
        document.getElementById("carregando").style.display = "block";
        var cep_replace = cep.replace(/[^0-9]/g, '', ".", "-");

        const url = `https://viacep.com.br/ws/${cep_replace}/json/`;
        fetch(url)
            .then(response => response.json())
            .then(data => {

                if (data.erro) { // Verifica se a propriedade "erro" está presente no objeto JSON
                    Swal.fire({
                        icon: 'error',
                        title: 'Verifique!',
                        text: 'O CEP informado não foi encontrado. Verifique e tente novamente.',
                    });
                    document.getElementById("carregando").style.display = "none";
                } else {
                    logradouro.value = data.logradouro;
                    bairro.value = data.bairro;

                    //selecionar o estado do cep consultado
                    for (var i = 0; i < options_estado.length; i++) {
                        if (options_estado[i].getAttribute("uf_estado") == data.uf) {
                            estado.selectedIndex = i
                            break; // Para o loop assim que o elemento for encontrado
                        }
                    }

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

                    if (data.ibge != "") {
                        //selecionar a cidade pelo codigo ibge do cep consultado
                        for (var i = 0; i < options_cidades.length; i++) {
                            if (options_cidades[i].getAttribute("codigo_ibge") == data.ibge) {
                                cidade.selectedIndex = i
                                break; // Para o loop assim que o elemento for encontrado
                            }
                        }

                    }

                    document.getElementById("carregando").style.display = "none";
                }

            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Verifique!',
                    text: 'O CEP informado não foi encontrado. Verifique e tente novamente.',
                });
                document.getElementById("carregando").style.display = "none";
            })
    }

})
