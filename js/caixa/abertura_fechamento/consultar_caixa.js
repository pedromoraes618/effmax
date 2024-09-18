

// $(document).ready(function(){
// var data_caixa = document.getElementById("data")
// $.ajax({
//     type: 'GET',
//     data: "status_caixa=true&data_caixa="+data_caixa.value.value,
//     url: "view/caixa/abertura_fechamento/status_caixa.php",
//     success: function(result) {
//         return $('.tabela').html(result);
//     },
// });

// })


// $("#consultar").click(function(){
//     var data_caixa = document.getElementById("data")
//     $.ajax({
//         type: 'GET',
//         data: "status_caixa=true&data_caixa="+data_caixa.value.value,
//         url: "view/caixa/abertura_fechamento/status_caixa.php",
//         success: function(result) {
//             return $('.tabela').html(result);
//         },
//     });
    
// })

// $("#abrir").click(function(){
//     var data_caixa = document.getElementById("data")
//     $.ajax({
//         type: 'GET',
//         data: "status_caixa=abir&data_caixa="+data_caixa.value.value,
//         url: "view/caixa/abertura_fechamento/status_caixa.php",
//         success: function(result) {
//             return $('.tabela').html(result);
//         },
//     });
    
// })


var data_caixa =document.getElementById("data")

$(document).ready(function() {
        consultar_status(data_caixa.value);
})

$("#consultar").click(function() {
    consultar_status(data_caixa.value);
   // alert(id_user_logado.value)
})


$("#abrir_caixa").click(function() {
    consultar_status(data_caixa.value);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja abrir o caixa do periodo informado?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = abrir_caixa(data_caixa.value,id_user_logado.value,user_logado.value)
        }
    })
})

$("#fechar_caixa").click(function() {
    consultar_status(data_caixa.value);
    Swal.fire({
        title: 'Tem certeza?',
        text: "Deseja fechar o caixa desse periodo?",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonText: 'Não',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim'
    }).then((result) => {
        if (result.isConfirmed) {
            var retorno = fechar_caixa(data_caixa.value,id_user_logado.value,user_logado.value)

        }
    })

})


function abrir_caixa(data_caixa,id_user,user) {
    
    $.ajax({
        type: "POST",
        data: "abertura_caixa=true&data_caixa=" + data_caixa +"&id_user="+id_user+"&user_logado="+user,
        url: "modal/caixa/abertura_fechamento/gerenciar_caixa.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
        
        $dados = $.parseJSON(data)["dados"];
       
        if ($dados.sucesso == true) {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: $dados.title,
                showConfirmButton: false,
                timer: 1500
            })

            consultar_status(data_caixa);
            $(".card-status-cx").load(); // Substitua pelo novo conteúdo desejado

        }
    }

    function falha() {
        console.log("erro")
    }
}


function fechar_caixa(data_caixa,id_user,user) {
    
    $.ajax({
        type: "POST",
        data: "fechar_caixa=true&data_caixa=" + data_caixa +"&id_user="+id_user+"&user_logado="+user,
        url: "modal/caixa/abertura_fechamento/gerenciar_caixa.php",
        async: false
    }).then(sucesso, falha);

    function sucesso(data) {
       
        $dados = $.parseJSON(data)["dados"];
        if ($dados.sucesso == true) {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: $dados.title,
                showConfirmButton: false,
                timer: 1500
            })

            consultar_status(data_caixa);

        }else{
            Swal.fire({
                icon: 'error',
                title: 'Verifique!',
                text: $dados.title,
                timer: 7500,

            })
        }
        
    }

    function falha() {
        console.log("erro")
    }
}


function consultar_status(data_caixa){
    $.ajax({
        type: 'GET',
        data: "status_caixa=true&data_caixa="+data_caixa,
        url: "view/caixa/abertura_fechamento/status_caixa.php",
        success: function(result) {
            return $('.tabela').html(result);
        },
    });
    $(".card-status-cx").load("view/dashboard/inicial/bloco/gerente/container_right.php?dashboard_inicial .card-status-cx"); // Substitua pelo novo conteúdo desejado

}
// //remover tarefa
// function caixa(caixa) {
//     $.ajax({
//         type: "POST",
//         data: "caixa=true&data=" + caixa,
//         url: "modal/caixa/abertura_fechamento/gerenciar_caixa.php",
//         async: false
//     }).then(sucesso, falha);

//     function sucesso(data) {
    
//         $sucesso = $.parseJSON(data)["sucesso"];
//         $mensagem = $.parseJSON(data)["mensagem"];
    
//         if ($sucesso) {
//            alert("opk")
//         } else {
//             Swal.fire({
//                 icon: 'error',
//                 title: 'Verifique!',
//                 text: $mensagem,
//                 timer: 7500,
            
//             })

//         }
//     }

//     function falha() {
//         console.log("erro");
//     }

// }