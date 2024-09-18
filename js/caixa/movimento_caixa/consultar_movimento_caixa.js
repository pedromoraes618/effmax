



var data_inicial = document.getElementById('data_inicial')
var data_final = document.getElementById('data_final')

resumo_caixa(data_inicial.value,data_final.value);
$("#resumo_caixa").addClass('active').siblings().removeClass('active');

$("#resumo_caixa").click(function() {
    $(this).addClass('active').siblings().removeClass('active');

    resumo_caixa(data_inicial.value,data_final.value);
})

$("#venda_fpg_caixa").click(function() {
    $(this).addClass('active').siblings().removeClass('active');

    vendas_fpg(data_inicial.value,data_final.value);
})

$("#resumo_geral").click(function() {
    $(this).addClass('active').siblings().removeClass('active');
    resumo_geral(data_inicial.value,data_final.value);
})


function resumo_caixa(data_inicial,data_final){
    $.ajax({
        type: 'GET',
        data: "movimento_caixa=true&acao=resumo&data_inicial="+data_inicial+"&data_final="+data_final,
        url: "view/caixa/movimento_caixa/table/resumo.php",
        success: function(result) {
            return $('.tabela').html(result);
        },
    });
}
function resumo_geral(data_inicial,data_final){
    $.ajax({
        type: 'GET',
        data: "movimento_caixa=true&acao=resumo_geral&data_inicial="+data_inicial+"&data_final="+data_final,
        url: "view/caixa/movimento_caixa/table/resumo_geral.php",
        success: function(result) {
            return $('.tabela').html(result);
        },
    });
}
function vendas_fpg(data_inicial,data_final){
    $.ajax({
        type: 'GET',
        data: "movimento_caixa=true&acao=vendas_fpg&data_inicial="+data_inicial+"&data_final="+data_final,
        url: "view/caixa/movimento_caixa/table/vendas_fpg.php",
        success: function(result) {
            return $('.tabela').html(result);
        },
    });
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