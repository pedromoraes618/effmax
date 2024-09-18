<?php
if (isset($_FILES)) {
    if (!empty($_FILES)) {
        include "../../../conexao/conexao.php";
        include "../../../funcao/funcao.php";

        $id_user_logado = verifica_sessao_usuario();
        $nome_usuario_logado = consulta_tabela($conecta, "tb_users", "cl_id", $id_user_logado, "cl_usuario");


        /* formatos de imagem permitidos */
        $permitidos = array(".xml");
        $retornar = array();
        $chave    = $_FILES['file-input']['name'];


        /* pega a extensão do arquivo */
        $ext = strtolower(strrchr($chave, "."));

        /*  verifica se a extensão está entre as extensões permitidas */
        if (in_array($ext, $permitidos)) {

            $nome_atual = md5(uniqid(time())) . $ext;

            //caminho temporário da imagem
            copy($_FILES['file-input']['tmp_name'], '../../../arquivos/xml_nf_entrada/' . $nome_atual);



            if ($chave == "") {
                $retornar["dados"] =  array("sucesso" => false, "title" => "Arquivo sem chave de acesso");
            } else {
                $arquivo = "../../../arquivos/xml_nf_entrada/" . $nome_atual;
                if (file_exists($arquivo)) {

                    $xml = simplexml_load_file($arquivo);
                    // imprime os atributos do objeto criado
                    $chave = $xml->NFe->infNFe->attributes()->Id;
                    $chave = strtr(strtoupper($chave), array("NFE" => NULL));

                    if (empty($xml->protNFe->infProt->nProt)) {
                        $retornar["dados"] =  array("sucesso" => false, "title" => "Arquivo sem dados de autorização");
                    } elseif ($tpAmb = $xml->NFe->infNFe->ide->tpAmb != 1) {
                        $retornar["dados"] =  array("sucesso" => false, "title" => "Documento emitido em ambiente de homologação, operação cancelado, só é possivel importar arquivos emitidos em ambiente de produção");
                    } elseif (consulta_tabela($conecta, "tb_nf_entrada", "cl_chave_acesso", $chave, "cl_id") != "") {
                        $retornar["dados"] =  array("sucesso" => false, "title" => 'Já exite uma nota com essa chave de acesso no sistema, favor, verifique');
                    } else {
                        // Iniciar uma transação MySQL
                        mysqli_begin_transaction($conecta);
                        $erro = false;


                        // $valida_chave_acesso = (consulta_tabela($conecta, 'tb_nfe_entrada', 'chave_acesso', $chave, 'chave_acesso')); //verfica se a nota já está no sistema para não haver duplicidade
                        $codigo_nf = md5(uniqid(time()));


                        //===============================================================================================================================================		
                        //<ide>
                        @$cUF = $xml->NFe->infNFe->ide->cUF;                //<cUF>41</cUF>  C�digo do Estado do Fator gerador
                        @$cNF = $xml->NFe->infNFe->ide->cNF;               //<cNF>21284519</cNF>   C�digo n�mero da nfe
                        @$natOp = $xml->NFe->infNFe->ide->natOp;         //<natOp>V E N D A</natOp>  Resumo da Natureza de opera��o
                        @$indPag = $xml->NFe->infNFe->ide->indPag;      //<indPag>2</indPag> 0 � pagamento � vista; 1 � pagamento � prazo; 2 - outros
                        @$mod = $xml->NFe->infNFe->ide->mod;            //<mod>55</mod> Modelo do documento Fiscal
                        @$serie = $xml->NFe->infNFe->ide->serie;           //<serie>2</serie> 
                        @$nNF =  $xml->NFe->infNFe->ide->nNF;              //<nNF>19685</nNF> N�mero da Nota Fiscal
                        @$dEmi = $xml->NFe->infNFe->ide->dhEmi; //data emissao
                        /*        //<dEmi>2011-09-06</dEmi> Data de emiss�o da Nota Fiscal
	@$dEmi = explode('-', $dEmi);
	@$dEmi = $dEmi[2]."/".$dEmi[1]."/".$dEmi[0];
	*/
                        @$dSaiEnt = $xml->NFe->infNFe->ide->dhSaiEnt;
                        /*  //<dSaiEnt>2011-09-06</dSaiEnt> Data de entrada ou saida da Nota Fiscal
    @$dSaiEnt = explode('-', $dSaiEnt);
	@$dSaiEnt = $dSaiEnt[2]."/".$dSaiEnt[1]."/".$dSaiEnt[0];
	*/
                        @$tpNF = $xml->NFe->infNFe->ide->tpNF;         //<tpNF>1</tpNF>  0-entrada / 1-sa�da
                        @$cMunFG = $xml->NFe->infNFe->ide->cMunFG;     //<cMunFG>4106407</cMunFG> C�digo do municipio Tabela do IBGE
                        @$tpImp = $xml->NFe->infNFe->ide->tpImp;       //<tpImp>1</tpImp> 
                        @$tpEmis = $xml->NFe->infNFe->ide->tpEmis;     //<tpEmis>1</tpEmis>
                        @$cDV = $xml->NFe->infNFe->ide->cDV;           //<cDV>0</cDV>
                        $tpAmb = $xml->NFe->infNFe->ide->tpAmb;       //<tpAmb>1</tpAmb>

                        $nProt = $xml->protNFe->infProt->nProt;
                        $xMotivo = $xml->protNFe->infProt->xMotivo;


                        $finNFe = $xml->NFe->infNFe->ide->finNFe;     //<finNFe>1</finNFe>
                        $procEmi = $xml->NFe->infNFe->ide->procEmi;   //<procEmi>0</procEmi>
                        $verProc = $xml->NFe->infNFe->ide->verProc;   //<verProc>2.0.0</verProc>
                        //</ide>

                        //===============================================================================================================================================	
                        // <emit> Emitente
                        $emit_CPF = $xml->NFe->infNFe->emit->CPF;
                        $emit_CNPJ = $xml->NFe->infNFe->emit->CNPJ;
                        $emit_xNome = $xml->NFe->infNFe->emit->xNome;
                        $emit_xFant = $xml->NFe->infNFe->emit->xFant;
                        //<enderEmit>
                        $emit_xLgr = $xml->NFe->infNFe->emit->enderEmit->xLgr;
                        $emit_nro = $xml->NFe->infNFe->emit->enderEmit->nro;
                        $emit_xBairro = $xml->NFe->infNFe->emit->enderEmit->xBairro;
                        $emit_cMun = $xml->NFe->infNFe->emit->enderEmit->cMun;
                        $emit_xMun = $xml->NFe->infNFe->emit->enderEmit->xMun;
                        $emit_UF = $xml->NFe->infNFe->emit->enderEmit->UF;
                        $emit_CEP = $xml->NFe->infNFe->emit->enderEmit->CEP;
                        $emit_cPais = $xml->NFe->infNFe->emit->enderEmit->cPais;
                        $emit_xPais = $xml->NFe->infNFe->emit->enderEmit->xPais;
                        $emit_fone = $xml->NFe->infNFe->emit->enderEmit->fone;
                        //</enderEmit>
                        $emit_IE = $xml->NFe->infNFe->emit->IE;
                        $emit_IM = $xml->NFe->infNFe->emit->IM;
                        $emit_CNAE = $xml->NFe->infNFe->emit->CNAE;
                        $emit_CRT = $xml->NFe->infNFe->emit->CRT;
                        //</emit>

                        //===============================================================================================================================================		
                        //<dest>
                        $dest_cnpj =  $xml->NFe->infNFe->dest->CNPJ;                       //<CNPJ>01153928000179</CNPJ>
                        //<CPF></CPF>
                        $dest_xNome = $xml->NFe->infNFe->dest->xNome;                     //<xNome>AGROVENETO S.A.- INDUSTRIA DE ALIMENTOS  -002825</xNome>

                        //***********************************************************************************************************************************************	


                        //<enderDest>
                        $dest_xLgr = $xml->NFe->infNFe->dest->enderDest->xLgr;            //<xLgr>ALFREDO PESSI, 2.000</xLgr>
                        $dest_nro =  $xml->NFe->infNFe->dest->enderDest->nro;               //<nro>.</nro>
                        $dest_xBairro = $xml->NFe->infNFe->dest->enderDest->xBairro;      //<xBairro>PARQUE INDUSTRIAL</xBairro>
                        $dest_cMun = $xml->NFe->infNFe->dest->enderDest->cMun;            //<cMun>4211603</cMun>
                        $dest_xMun = $xml->NFe->infNFe->dest->enderDest->xMun;            //<xMun>NOVA VENEZA</xMun>
                        $dest_UF = $xml->NFe->infNFe->dest->enderDest->UF;                //<UF>SC</UF>
                        $dest_CEP = $xml->NFe->infNFe->dest->enderDest->CEP;              //<CEP>88865000</CEP>
                        $dest_cPais = $xml->NFe->infNFe->dest->enderDest->cPais;          //<cPais>1058</cPais>
                        $dest_xPais = $xml->NFe->infNFe->dest->enderDest->xPais;          //<xPais>BRASIL</xPais>
                        $dest_IE = $xml->NFe->infNFe->dest->IE;                        //<IE>253323029</IE>
                        $info_complementar = $xml->NFe->infNFe->infAdic->infCpl;         //infomações adicionais

                        $info_complementar = utf8_decode($info_complementar);
                        $info_complementar = str_replace("'", "", $info_complementar);


                        $vBC = $xml->NFe->infNFe->total->ICMSTot->vBC;
                        $vBC = number_format((float) $vBC, 2, ".", "");
                        $vICMS = $xml->NFe->infNFe->total->ICMSTot->vICMS;
                        $vICMS = number_format((float) $vICMS, 2, ".", "");
                        $vBCST = $xml->NFe->infNFe->total->ICMSTot->vBCST;
                        $vBCST = number_format((float) $vBCST, 2, ".", "");
                        $vST = $xml->NFe->infNFe->total->ICMSTot->vST;
                        $vST = number_format((float) $vST, 2, ".", "");
                        $vProdTotal = $xml->NFe->infNFe->total->ICMSTot->vProd;
                        $vProdTotal = number_format((float) $vProdTotal, 2, ".", "");
                        $vNF = $xml->NFe->infNFe->total->ICMSTot->vNF;
                        $vNF = number_format((float) $vNF, 2, ".", "");
                        $vFrete = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vFrete, 2, ".", "");
                        $vSeg = number_format((float)   $xml->NFe->infNFe->total->ICMSTot->vSeg, 2, ".", "");
                        $vDesc = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vDesc, 2, ".", "");
                        $vIPI = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vIPI, 2, ".", "");

                        $vOutro = number_format((float) $xml->NFe->infNFe->total->ICMSTot->vOutro, 2, ".", "");
                        $frete = $xml->NFe->infNFe->transp->modFrete;

                        $fornecedor_id = (consulta_tabela($conecta, 'tb_parceiros', 'cl_cnpj_cpf', $emit_CNPJ, 'cl_id')); //verfica se o fornecedor está cadastrado
                        if ($fornecedor_id == "") {
                            $emit_xNome = utf8_decode($emit_xNome);
                            $emit_xFant = utf8_decode($emit_xFant);
                            $emit_CNPJ = ($emit_CNPJ);
                            $emit_IE = ($emit_IE);
                            $emit_CEP = ($emit_CEP);
                            $emit_xBairro = utf8_decode($emit_xBairro);
                            $emit_xLgr = utf8_decode($emit_xLgr);
                            $emit_nro = ($emit_nro);
                            $emit_xPais = utf8_decode($emit_xPais);

                            $cidade_id_emit = (consulta_tabela($conecta, 'tb_cidades', 'cl_ibge', $emit_cMun, 'cl_id')); //buscar a cidade do emitente
                            $estado_id_emit = (consulta_tabela($conecta, 'tb_estados', 'cl_uf', $emit_UF, 'cl_id')); //buscar a cidade do emitente
                            if ($emit_nro == "") {
                                $emit_nro = "SN";
                            }
                            $endereco_emit = $emit_xLgr;
                            if ($emit_CRT == "1") {
                                $obs_emit = "Fornecedor optante do Simples Nacional";
                            } elseif ($emit_CRT == "2") {
                                $obs_emit = "Fornecedor optante do Simples Nacional - excesso de sublimite da receita bruta";
                            } elseif ($emit_CRT == "3") {
                                $obs_emit = "Fornecedor optante do Regime Normal ou outros";
                            } else {
                                $obs_emit = null;
                            }
                            /*inserir fornecedor */
                            $insert = "INSERT INTO `tb_parceiros` ( `cl_data_cadastro`, `cl_usuario_id`,
                             `cl_razao_social`, `cl_nome_fantasia`, `cl_cnpj_cpf`, `cl_inscricao_estadual`, `cl_cep`, `cl_bairro`,
                              `cl_endereco`,`cl_numero`,`cl_cidade_id`, `cl_estado_id`, `cl_pais`, `cl_telefone`, `cl_email`, `cl_observacao`,
                               `cl_situacao_ativo`) VALUES ('$data_lancamento', '$id_user_logado', '$emit_xNome', '$emit_xFant',
                                '$emit_CNPJ', '$emit_IE', '$emit_CEP',
                                '$emit_xBairro', '$endereco_emit','$emit_nro', '$cidade_id_emit', '$estado_id_emit', '$emit_xPais', '$emit_fone', '', '$obs_emit', 'SIM') ";
                            $operacao_insert = mysqli_query($conecta, $insert);
                            if ($operacao_insert) {
                                $fornecedor_id = mysqli_insert_id($conecta); //pegar o id do fornecedor cadastrado


                                $mensagem = utf8_decode("Adicionou o fornecedor $emit_xNome atraves da nota de compra $nNF");
                                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                                // Se tudo ocorreu bem, confirme a transação
                                mysqli_commit($conecta);
                            } else {
                                $erro = true;
                                // Se ocorrer um erro, reverta a transação
                                mysqli_rollback($conecta);
                                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                                $mensagem = utf8_decode("tentativa sem sucesso de realizar o cadastro do fornecedor $emit_xNome atraves da nota de compra $nNF ");
                                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                            }
                        }



                        if ($fornecedor_id == "") {
                            $retornar["dados"] =  array("sucesso" => false, "title" => "Fornecedor não encontrado. Por favor, cadastre o fornecedor com o CNPJ: $emit_CNPJ.");
                        } else {
                            $insert = "INSERT INTO `tb_nf_entrada` ( `cl_codigo_nf`, `cl_data_entrada`, `cl_data_emissao`, `cl_chave_acesso`,
                                `cl_prot_autorizacao`, `cl_numero_nf`, `cl_serie_nf`, `cl_parceiro_id`,
                                `cl_cfop`,`cl_frete_id`,`cl_forma_pagamento_id`, `cl_valor_frete`, `cl_valor_outras_despesas`, `cl_valor_seguro`, `cl_valor_desconto`,
                                 `cl_valor_total_produtos`, `cl_valor_total_nota`,
                                  `cl_status_nf`, `cl_usuario_id`,`cl_status_provisionamento`,`cl_observacao` ) 
                               VALUES ('$codigo_nf', '$data_lancamento', '$dEmi', '$chave', '$nProt', '$nNF', 'NFE', '$fornecedor_id', 
                               '0','$frete','0', '$vFrete', '$vOutro', '$vSeg', '$vDesc', '$vProdTotal',
                                '$vNF','2', '$id_user_logado', '1', '$info_complementar' ) ";
                            $operacao_insert = mysqli_query($conecta, $insert);
                            if ($operacao_insert) {
                                $nf_id = mysqli_insert_id($conecta);

                                $mensagem = utf8_decode("Adicionou a nota de compra $nNF");
                                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);

                                // Se tudo ocorreu bem, confirme a transação
                                mysqli_commit($conecta);
                            } else {
                                $erro = true;
                                // Se ocorrer um erro, reverta a transação
                                mysqli_rollback($conecta);
                                $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                                $mensagem = utf8_decode("tentativa sem sucesso de importar a nota de compra $nNF ");
                                registrar_log($conecta, $nome_usuario_logado, $data, $mensagem);
                            }

                            if ($erro == false) {
                                $seq = 0;
                                foreach ($xml->NFe->infNFe->det as $item) {
                                    $seq++;
                                    $codigo = $item->prod->cProd;
                                    $xProd = $item->prod->xProd;
                                    $xProd = utf8_decode($xProd);
                                    $xProd = str_replace("'", "", $xProd);
                                    $xProd = str_replace(";", "", $xProd);
                                    $xProd = str_replace('&quot', "", $xProd);

                                    $NCM = $item->prod->NCM;
                                    $CFOP = $item->prod->CFOP;
                                    $uCom = $item->prod->uCom;
                                    $qCom = $item->prod->qCom;
                                    $gtin = $item->prod->cEANTrib;
                                    $xPed = $item->prod->xPed;
                                    $nItemPed = $item->prod->nItemPed;

                                    $qCom = number_format((float) $qCom, 2, ".", "");
                                    $vUnCom = $item->prod->vUnCom;
                                    $vUnCom = number_format((float) $vUnCom, 2, ".", "");
                                    $vProd = $item->prod->vProd;
                                    $vProd = number_format((float) $vProd, 2, ".", "");
                                    $vBC_item = $item->imposto->ICMS->ICMS00->vBC;
                                    $icms00 = $item->imposto->ICMS->ICMS00;
                                    $icms10 = $item->imposto->ICMS->ICMS10;
                                    $icms20 = $item->imposto->ICMS->ICMS20;
                                    $icms30 = $item->imposto->ICMS->ICMS30;
                                    $icms40 = $item->imposto->ICMS->ICMS40;
                                    $icms50 = $item->imposto->ICMS->ICMS50;
                                    $icms51 = $item->imposto->ICMS->ICMS51;
                                    $icms60 = $item->imposto->ICMS->ICMS60;

                                    $icms101 = $item->imposto->ICMS->ICMSSN101;
                                    $ICMSSN102 = $item->imposto->ICMS->ICMSSN102;
                                    $icms103 = $item->imposto->ICMS->ICMSSN103;
                                    $icms201 = $item->imposto->ICMS->ICMSSN201;
                                    $icms202 = $item->imposto->ICMS->ICMSSN202;
                                    $icms203 = $item->imposto->ICMS->ICMSSN203;
                                    $icms300 = $item->imposto->ICMS->ICMSSN300;
                                    $icms400 = $item->imposto->ICMS->ICMSSN400;
                                    $icms500 = $item->imposto->ICMS->ICMSSN500;
                                    $icms900 = $item->imposto->ICMS->ICMSSN900;


                                    if (isset($item->prod->CEST)) {
                                        $cest = $item->prod->CEST;
                                    } else {
                                        $cest = '';
                                    }

                                    if (!empty($ICMSSN102)) {
                                        $bc_icms = "0.00";
                                        $pICMS = "0	";
                                        $vlr_icms = "0.00";
                                        $cst_icms = $item->imposto->ICMS->ICMSSN102->CSOSN;
                                    }


                                    if (!empty($icms00)) {
                                        $bc_icms = $item->imposto->ICMS->ICMS00->vBC;
                                        $bc_icms = number_format((float) $bc_icms, 2, ".", "");
                                        $pICMS = $item->imposto->ICMS->ICMS00->pICMS;
                                        $pICMS = round($pICMS, 0);
                                        $vlr_icms = $item->imposto->ICMS->ICMS00->vICMS;
                                        $vlr_icms = number_format((float) $vlr_icms, 2, ".", "");

                                        $cst_icms = $item->imposto->ICMS->ICMS00->CST;
                                    }
                                    if (!empty($icms20)) {
                                        $bc_icms = $item->imposto->ICMS->ICMS20->vBC;
                                        $bc_icms = number_format((float) $bc_icms, 2, ".", "");
                                        $pICMS = $item->imposto->ICMS->ICMS20->pICMS;
                                        $pICMS = round($pICMS, 0);
                                        $vlr_icms = $item->imposto->ICMS->ICMS20->vICMS;
                                        $vlr_icms = number_format((float) $vlr_icms, 2, ".", "");
                                        $cst_icms = $item->imposto->ICMS->ICMS20->CST;
                                    }
                                    if (!empty($icms30)) {
                                        $bc_icms = "0.00";
                                        $pICMS = "0	";
                                        $vlr_icms = "0.00";
                                        $cst_icms = $item->imposto->ICMS->ICMS30->CST;
                                    }
                                    if (!empty($icms40)) {
                                        $bc_icms = "0.00";
                                        $pICMS = "0	";
                                        $vlr_icms = "0.00";
                                        $cst_icms = $item->imposto->ICMS->ICMS40->CST;
                                    }
                                    if (!empty($icms50)) {
                                        $bc_icms = "0.00";
                                        $pICMS = "0	";
                                        $vlr_icms = "0.00";
                                        $cst_icms = $item->imposto->ICMS->ICMS50->CST;
                                    }
                                    if (!empty($icms51)) {
                                        $bc_icms = $item->imposto->ICMS->ICMS51->vBC;
                                        $pICMS = $item->imposto->ICMS->ICMS51->pICMS;
                                        $pICMS = round($pICMS, 0);
                                        $vlr_icms = $item->imposto->ICMS->ICMS51->vICMS;
                                        $cst_icms = $item->imposto->ICMS->ICMS51->CST;
                                    }
                                    if (!empty($icms60)) {
                                        $bc_icms = "0,00";
                                        $pICMS = "0	";
                                        $vlr_icms = "0,00";
                                        $cst_icms = $item->imposto->ICMS->ICMS60->CST;
                                    }
                                    if (!empty($icms500)) {
                                        $bc_icms = "0,00";
                                        $pICMS = "0	";
                                        $vlr_icms = "0,00";
                                        $cst_icms = $item->imposto->ICMS->ICMSSN500->CSOSN;
                                    }
                                    if (!empty($icms900)) {
                                        $bc_icms = "0,00";
                                        $pICMS = "0	";
                                        $vlr_icms = "0,00";
                                        $cst_icms = $item->imposto->ICMS->ICMSSN900->CSOSN;
                                    }
                                    if (!empty($icms400)) {
                                        $bc_icms = "0,00";
                                        $pICMS = "0	";
                                        $vlr_icms = "0,00";
                                        $cst_icms = $item->imposto->ICMS->ICMSSN400->CSOSN;
                                    }
                                    if (!empty($icms300)) {
                                        $bc_icms = "0,00";
                                        $pICMS = "0	";
                                        $vlr_icms = "0,00";
                                        $cst_icms = $item->imposto->ICMS->ICMSSN300->CSOSN;
                                    }
                                    if (!empty($icms203)) {
                                        $bc_icms = "0,00";
                                        $pICMS = "0	";
                                        $vlr_icms = "0,00";
                                        $cst_icms = $item->imposto->ICMS->ICMSSN203->CSOSN;
                                    }
                                    if (!empty($icms202)) {
                                        $bc_icms = "0,00";
                                        $pICMS = "0	";
                                        $vlr_icms = "0,00";
                                        $cst_icms = $item->imposto->ICMS->ICMSSN202->CSOSN;
                                    }
                                    if (!empty($icms201)) {
                                        $bc_icms = "0,00";
                                        $pICMS = "0	";
                                        $vlr_icms = "0,00";
                                        $cst_icms = $item->imposto->ICMS->ICMSSN201->CSOSN;
                                    }
                                    if (!empty($icms103)) {
                                        $bc_icms = "0,00";
                                        $pICMS = "0	";
                                        $vlr_icms = "0,00";
                                        $cst_icms = $item->imposto->ICMS->ICMSSN103->CSOSN;
                                    }
                                    if (!empty($icms101)) {
                                        $bc_icms = "0,00";
                                        $pICMS = "0	";
                                        $vlr_icms = "0,00";
                                        $cst_icms = $item->imposto->ICMS->ICMSSN101->CSOSN;
                                    }
                                    $IPITrib = $item->imposto->IPI->IPITrib;
                                    if (!empty($IPITrib)) {
                                        $bc_ipi = $item->imposto->IPI->IPITrib->vBC;
                                        $bc_ipi = number_format((float) $bc_ipi, 2, ".", "");
                                        $perc_ipi =  $item->imposto->IPI->IPITrib->pIPI;
                                        $perc_ipi = round($perc_ipi, 0);
                                        $vlr_ipi = $item->imposto->IPI->IPITrib->vIPI;
                                        $vlr_ipi = number_format((float) $vlr_ipi, 2, ".", "");
                                    }
                                    $IPINT = $item->imposto->IPI->IPINT; {
                                        $bc_ipi = "0,00";
                                        $perc_ipi =  "0";
                                        $vlr_ipi = "0,00";
                                    }


                                    if ($cst_icms == '102') {
                                        $bc_pis = $item->imposto->PIS->PISOutr->vBC;
                                        $bc_pis = number_format((float) $bc_pis, 2, ".", "");

                                        $vlr_pis = $item->imposto->PIS->PISOutr->vPIS;
                                        $vlr_pis = number_format((float) $vlr_pis, 2, ".", "");


                                        $cst_pis = $item->imposto->PIS->PISOutr->CST;


                                        /*cofins */
                                        $bc_cofins = $item->imposto->COFINS->COFINSOutr->vBC;
                                        $bc_cofins = number_format((float) $bc_cofins, 2, ".", "");

                                        $vlr_cofins = $item->imposto->COFINS->COFINSOutr->vCOFINS;
                                        $vlr_cofins = number_format((float) $vlr_cofins, 2, ".", "");

                                        $cst_cofins = $item->imposto->COFINS->COFINSOutr->CST;
                                        $cst_cofins = $item->imposto->COFINS->COFINSOutr->CST;
                                    } else {
                                        $bc_pis = $item->imposto->PIS->PISAliq->vBC;
                                        $bc_pis = number_format((float) $bc_pis, 2, ".", "");

                                        $vlr_pis = $item->imposto->PIS->PISAliq->vPIS;
                                        $vlr_pis = number_format((float) $vlr_pis, 2, ".", "");


                                        $cst_pis = $item->imposto->PIS->PISAliq->CST;


                                        /*cofins */
                                        $bc_cofins = $item->imposto->COFINS->COFINSAliq->vBC;
                                        $bc_cofins = number_format((float) $bc_cofins, 2, ".", "");

                                        $vlr_cofins = $item->imposto->COFINS->COFINSAliq->vCOFINS;
                                        $vlr_cofins = number_format((float) $vlr_cofins, 2, ".", "");

                                        $cst_cofins = $item->imposto->COFINS->COFINSAliq->CST;
                                        $cst_cofins = $item->imposto->COFINS->COFINSAliq->CST;
                                    }

                                    $valida_item = consulta_tabela($conecta, "tb_produtos", "cl_referencia", $codigo, "cl_id"); //verificar se já existe o produto para realizar a associação automaticamente
                                    if ($valida_item != "") {
                                        $codigo_item = $valida_item;
                                    } else {
                                        $codigo_item = "";
                                    }

                                    $cfop_entrada = consulta_tabela($conecta, "tb_cfop", "cl_codigo_cfop", $CFOP, "cl_cfop_entrada"); //converter o cfop para o de entrada
                                    if ($cfop_entrada != "") {
                                        $CFOP = $cfop_entrada;
                                    }


                                    $insert = "INSERT INTO `tb_nf_entrada_item` (`cl_codigo_nf`, 
                                    `cl_produto_id`, `cl_descricao`, `cl_ncm`, `cl_cest`, `cl_cfop`, `cl_und`, `cl_quantidade`,
                                     `cl_valor_unitario`, `cl_valor_total`, `cl_bc_icms`, `cl_valor_icms`, `cl_aliq_icms`, `cl_cst_icms`,
                                    `cl_valor_ipi`, `cl_aliq_ipi`, `cl_bc_pis`, `cl_valor_pis`,
                                       `cl_cst_pis`, `cl_bc_cofins`, `cl_valor_cofins`, `cl_cst_cofins`, `cl_gtin`, 
                                        `cl_referencia`,`cl_numero_pedido`,`cl_item_pedido` ) VALUES
                                        ('$codigo_nf', '$codigo_item', '$xProd', '$NCM', '$cest', '$CFOP', '$uCom', 
                                        '$qCom', '$vUnCom', '$vProd',
                                         '$bc_icms', '$vlr_icms',
                                     '$pICMS', '$cst_icms', '$vlr_ipi', '$perc_ipi', '$bc_pis',
                                      '$vlr_pis', '$cst_pis', '$bc_cofins', '$vlr_cofins', '$cst_cofins',
                                       '$gtin','$codigo','$xPed' ,'$nItemPed' ) ";
                                    $operacao_insert = mysqli_query($conecta, $insert);
                                    if ($operacao_insert) {
                                        recalcular_valor_nf_entrada($conecta, $codigo_nf); //recalcular o valor da nota
                                        update_registro($conecta, "tb_nf_entrada", "cl_id", $nf_id, "", "", "cl_cfop", $CFOP); //atualizar o cfop da nota
                                        $retornar["dados"] =  array("sucesso" => true, "title" => "Item Incluido com sucesso");
                                        // Se tudo ocorreu bem, confirme a transação
                                        mysqli_commit($conecta);
                                    } else {

                                        // Se ocorrer um erro, reverta a transação
                                        mysqli_rollback($conecta);
                                        $retornar["dados"] =  array("sucesso" => false, "title" => "Erro, favor contatar o suporte");
                                    }
                                }



                                if (!empty($xml->NFe->infNFe->cobr->dup)) {
                                    foreach ($xml->NFe->infNFe->cobr->dup as $dup) {
                                        $titulo = $dup->nDup;
                                        $vencimento = $dup->dVenc;
                                        $vlr_parcela = number_format((float)$dup->vDup, 2, ".", "");

                                        // Adicione os valores ao array
                                        $valoresArray[] = ['titulo' => $titulo, 'vencimento' => $vencimento, 'vlr_parcela' => $vlr_parcela];
                                    }

                                    // Transforme o array em uma representação JSON para armazenamento em cookie
                                    $valoresJSON = json_encode($valoresArray);

                                    // Defina o cookie com os valores
                                    setcookie($nNF, $valoresJSON, time() + 3600, '/'); // Valores expiram após 1 hora
                                }
                            }
                        }
                        if ($erro == false) {
                            $informacao = array(
                                "nf_id" => $nf_id, // Converta para string se necessário
                                "codigo_nf" => $codigo_nf,

                            );

                            $retornar["dados"] = array("sucesso" => true, "title" => $informacao);
                        }
                    }
                    /* se enviar a foto, insere o nome da foto no banco de dados */
                } else {
                    $retornar["dados"] = array("sucesso" => false, "title" => "Arquivo não encontrado, favor, repete a operação");
                }
            }
        } else {
            $retornar["dados"] = array("sucesso" => false, "title" => "Somente são aceitos arquivos do tipo xml, favor, verifique");
        }
    } else {
        $retornar["dados"] = array("sucesso" => false, "title" => "Favor selecione um arquivo com a extensão xml");
    }
    echo json_encode($retornar);
}

//
