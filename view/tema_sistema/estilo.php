<style>
    <?php if ($tema_sistema == "DARK" or $tema_sistema == "") {
        $cor_principal = '#131520';
        $borda_princiapl = '#40475e';
        $cor_principal_hover = '#31333e';
        $cor_texto = '#9fa6bc';
        $cor_label = '#141824';
        $cor_texto_sub = '#e9e9e9';
        $cor_sub_bloco = '#141824';
        $cor_sub_topo = '#0f111a';
        
    }

    if ($tema_sistema == 'CLARO') {
        $cor_principal = '#215EBE';
        $borda_princiapl = '#2a7bd0';
        $cor_principal_hover = '#4785e4';
        $cor_texto = '#9fa6bc';
        $cor_label = '#141824';
        $cor_texto_sub = '#e9e9e9';
        $cor_sub_bloco = '#141824';
        $cor_sub_topo = '#215EBE';
    }

    if($tema_sistema =="CLARO2"){
        $cor_principal = '#6D51EC';
        $borda_princiapl = '#2a7bd0';
        $cor_principal_hover = '#8066f0';
        $cor_texto = '#9fa6bc';
        $cor_label = '#141824';
        $cor_texto_sub = '#e9e9e9';
        $cor_sub_bloco = '##6D51EC';
        $cor_sub_topo = '#6D51EC';
    }
   
    ?>.bloco .bloco-left .categoria .topo {
        color: var(--cor_texto);
        
    }

    .bloco .bloco-left {
        background-color: <?php echo $cor_principal; ?>;
        border-right: 1px solid <?php echo $borda_princiapl; ?>
    }

    .bloco .bloco-right .bloco-pesquisa-menu .title label {
        background-color: <?php echo $cor_principal; ?>;
    }

    .bloco .bloco-left .categoria nav ul li a {
        color: white;
    }

    .bloco .bloco-left .categoria nav ul li {
        color: white;
    }

    .bloco .bloco-left .categoria nav ul li:hover {
        background-color:<?php echo $cor_principal_hover; ?>;
    }

    .bloco .bloco-left .categoria nav ul ul li:hover {
        border-left: 2px solid white;
    }

    .bloco .bloco-left .categoria nav ul ul li a {

        color: <?php echo $cor_texto_sub; ?>;

    }

    .bloco .bloco-left .categoria nav ul ul li a:hover {
        color: white;
    }

    .bloco .bloco-left .categoria .footer p {
        color: #bbbbbb;
    }

    /*topo */

    .bloco .bloco-right {
        background-color: #F8F8F8;
    }

    .bloco .bloco-right .topo {
        background-color: <?php  echo $cor_principal; ?>;
        border-bottom: 1px solid <?php  echo $borda_princiapl; ?>;
        border-left: 1px solid  <?php  echo $borda_princiapl; ?>;

    }



    .bloco .bloco-right .topo .left p {

        color: white;

    }

    .bloco .bloco-right .topo .right ul li {
        color:<?php echo $cor_texto_sub; ?>;
    }


    .bloco .bloco-right .topo .right ul ul {

        background: rgba(3, 48, 61, 0.85);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        border: 1px solid rgba(255, 255, 255, 0.18);
    }

    .bloco .bloco-left .categoria nav ul ul {

        background-color: <?php  echo $cor_principal; ?>;

    }

    .bloco .bloco-left .categoria nav ul ul::before {

        border: 10px solid transparent;
        border-right-color:<?php  echo $cor_principal; ?>;
        border-left: none;
        /* removemos a borda à esquerda */


    }

    .bloco .bloco-right .topo .right ul ul li a {
        color: white;

    }

    .bloco .bloco-right .topo .right ul ul li:hover {
        color: #bbbbbb;
    }

    .bloco-topo {

        background-color: <?php echo $cor_sub_topo ?>;
    }

    .bloco-topo p {
        color: <?php echo $cor_texto_sub ?>;
    }
    .texto-reduzido:hover .texto-caixa{

    background-color: <?php echo $cor_principal; ?>;

}

.texto-reduzido:hover .texto-caixa::before{

    transform: translateX(0) rotate(-90deg); /* rotação da ponta */
    border: 10px solid transparent;
    border-right-color: <?php echo $cor_principal; ?>;

}
  
</style>