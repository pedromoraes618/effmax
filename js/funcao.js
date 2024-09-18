
//funcão para input receber apenas numero
const onlyNumbers = (input) => {
  input.addEventListener("input", function () {
    this.value = this.value.replace(/[^0-9.,-,/]/g, "");
  });
};

const inputnumer = document.querySelectorAll(".inputNumber");
inputnumer.forEach(input => onlyNumbers(input));


//funcão para input receber apenas numero
const onlyUsers = (input) => {
  input.addEventListener("input", function () {
    this.value = this.value.replace(/[^a-zA-Z0-9@#$%&*-+]/g, "");
  });
};

const inputuser = document.querySelectorAll(".inputUser");
inputuser.forEach(input => onlyUsers(input));




//gerar pdf 
function criapdf() {
    var minhaTabela = document.getElementById('tabela').innerHTML;
    alert(minhaTabela)
    var style = "<style>";
    style = style + "table {width: 100%;font: 20px Calibri;}";
    style = style + "table, th, td {border: solid 1px #DDD; border-collapse: collapse;";
    style = style + "padding: 2px 3px;text-align: center;}";
    style = style + "</style>";
    // CRIA UM OBJETO WINDOW
    var win = window.open('', '', 'height=700,width=700');
    win.document.write('<html><head>');
    //win.document.write('<title>Empregados</title>');   // <title> CABEÇALHO DO PDF.
    win.document.write(style);                                     // INCLUI UM ESTILO NA TAB HEAD
    win.document.write('</head>');
    win.document.write('<body>');
    win.document.write(minhaTabela);                          // O CONTEUDO DA TABELA DENTRO DA TAG BODY
    win.document.write('</body></html>');
    win.document.close(); 	                                         // FECHA A JANELA
    win.print();                                                            // IMPRIME O CONTEUDO
}



// //bloquear caracteres espaço e virgula
// const campo_usuario = document.getElementById("usuario");
// campo_usuario.addEventListener("keypress", function(e) {
//     if((e.key === " ") || (e.key ===","))  {
//       e.preventDefault();
//     }
  
// });



