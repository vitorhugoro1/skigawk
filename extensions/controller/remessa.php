<?php
/*
* @descr: Gera o arquivo de remessa para cobranca no padrao CNAB 400 vers. 7.0 ITAU
*/

function limit($palavra,$limite)
{
if(strlen($palavra) >= $limite)
{
$var = substr($palavra, 0,$limite);
}
else
{
$max = (int)($limite-strlen($palavra));
$var = $palavra.complementoRegistro($max,"brancos");
}
return $var;
}

function sequencial($i)
{
if($i < 10)
{
return zeros(0,5).$i;
}
else if($i > 10 && $i < 100)
{
return zeros(0,4).$i;
}
else if($i > 100 && $i < 1000)
{
return zeros(0,3).$i;
}
else if($i > 1000 && $i < 10000)
{
return zeros(0,2).$i;
}
else if($i > 10000 && $i < 100000)
{
return zeros(0,1).$i;
}
}

function zeros($min,$max)
{
$x = ($max - strlen($min));
for($i = 0; $i < $x; $i++)
{
$zeros .= '0';
}
return $zeros.$min;
}

function complementoRegistro($int,$tipo)
{
if($tipo == "zeros")
{
$space = '';
for($i = 1; $i <= $int; $i++)
{
$space .= '0';
}
}
else if($tipo == "brancos")
{
$space = '';
for($i = 1; $i <= $int; $i++)
{
$space .= ' ';
}
}

return $space;
}

$fusohorario = 3; // como o servidor de hospedagem é a dreamhost pego o fuso para o horario do brasil
$timestamp = mktime(date("H") - $fusohorario, date("i"), date("s"), date("m"), date("d"), date("Y"));

$DATAHORA['PT'] = gmdate("d/m/Y H:i:s", $timestamp);
$DATAHORA['EN'] = gmdate("Y-m-d H:i:s", $timestamp);
$DATA['PT'] = gmdate("d/m/Y", $timestamp);
$DATA['EN'] = gmdate("Y-m-d", $timestamp);
$DATA['DIA'] = gmdate("d",$timestamp);
$DATA['MES'] = gmdate("m",$timestamp);
$DATA['ANO'] = gmdate("y",$timestamp);
$HORA = gmdate("H:i:s", $timestamp);
$HORA1 = gmdate("His", $timestamp);

define("REMESSA",$PATH."imagem/remessa/",true);

$filename = REMESSA.$DATA['DIA'].$DATA['MES'].$DATA['ANO'].$HORA1.".txt";
$conteudo = '';

## REGISTRO HEADER
#NOME DO CAMPO #SIGNIFICADO #POSICAO #PICTURE
$conteudo .= '0'; // tipo de registro id registro header 001 001 9(01)
$conteudo .= 1; // operacao tipo operacao remessa 002 002 9(01)
$conteudo .= 'REMESSA'; // literal remessa escr. extenso 003 009 X(07)
$conteudo .= '01'; // codigo servico id tipo servico 010 011 9(02)
$conteudo .= limit('COBRANCA',15); // literal cobranca escr. extenso 012 026 X(15)
$conteudo .= 1234; // agencia mantenedora conta 027 030 9(04)
$conteudo .= complementoRegistro(2,"zeros");// zeros complemento d registro 031 032 9(02)
$conteudo .= '01234'; // conta conta da empresa 033 037 9(05)
$conteudo .= 2; // dac digito autoconf conta 038 038 9(01)
$conteudo .= complementoRegistro(8,"brancos");// complemento registro 039 046 X(08)
$conteudo .= limit('NOME DA SUA EMPRESA',30);//nome da empresa 047 076 X(30)
$conteudo .= 341; // codigo banco Nº BANCO CÂMARA COMP. 077 079 9(03)
$conteudo .= limit('BANCO ITAU SA',15); // nome do banco por ext. 080 094 X(15)
$conteudo .= $DATA['DIA'].$DATA['MES'].$DATA['ANO'];//data geracao arquivo 095 100 9(06)
$conteudo .= complementoRegistro(294,"brancos");// complemento de registr 101 394 X(294)
$conteudo .= sequencial(1); // numero sequencial registro no arquivo 395 400 9(06)

$conteudo .= chr(13).chr(10); //essa é a quebra de linha

### DADOS DOS CLIENTES PARA TESTE
$clientes[] = array("BOLETO006","Cliente A","11111111111","100,00");
$clientes[] = array("BOLETO002","Cliente B","22222222222","200,00");
$clientes[] = array("BOLETO003","Cliente C","33333333333","300,00");
$clientes[] = array("BOLETO004","Cliente D","44444444444","400,00");

$i = 2;
foreach($clientes as $cliente)
{
## REGISTRO DETALHE (OBRIGATORIO)
#NOME DO CAMPO #SIGNIFICADO #POSICAO #PICTURE
$conteudo .= 1; // tipo registro id registro transacac. 001 001 9(01)
$conteudo .= '02'; // codigo inscricao tipo inscricao empresa 002 003 9(02)
$conteudo .= '00965290700010'; // cnpj da empresa 004 017 9(14)
$conteudo .= 6896; // agencia mantenedora da conta 018 021 9(04)
$conteudo .= '00'; // zeros complemento registro 022 023 9(02)
$conteudo .= '08436'; // conta numero da conta 024 028 9(05)
$conteudo .= 2; // dac dig autoconf conta 029 029 9(01)
$conteudo .= complementoRegistro(4,"brancos"); // brancos complemento registro 030 033 X(04)
$conteudo .= complementoRegistro(4,"zeros"); // CÓD.INSTRUÇÃO/ALEGAÇÃO A SER CANC NOTA 27 034 037 9(04)
$conteudo .= limit($cliente[0],25); // USO / IDENT. DO TÍTULO NA EMPRESA NOTA 2 038 062 X(25)
$conteudo .= complementoRegistro(8,"zeros"); // NOSSO NUMERO / ID TITULO DO BANCO NOTA 3 063 070 9(08)
$conteudo .= '0000000000000'; //QTDE MOEDA NOTA 4 071 083 9(08)V9(5)
$conteudo .= 109; // nº da carteira nº carteira banco 084 086 9(03)
$conteudo .= complementoRegistro(21,"brancos"); // uso do banco ident. oper. no banco 087 107 X(21)
$conteudo .= 'I'; // carteira codigo da carteira NOTA 5 108 108 X(01)
$conteudo .= '01'; // codigo ocorrencia / ident da ocorrencia NOTA 6 109 110 9(02)
$conteudo .= limit('',10); // nº documento / nº documento de cobranca NOTA 18 111 120 X(10)
$conteudo .= '310808'; // vencimento data venc. titulo NOTA 7 121 126 9(06)
$conteudo .= '0000000000000'; // valor titulo valor nominal NOTA 8 127 139 9(11)V9(2)
$conteudo .= 341; // codigo do banco Nº BANCO CÂMARA COMP. 140 142 9(03)
$conteudo .= zeros(0,5); //agencia cobradora / ONDE TÍTULO SERÁ COBRADO NOTA 9 143 147 9(05)
$conteudo .= 15; // especie especie do titulo NOTA 10 148 149 X(02)
$conteudo .= 'A'; // aceite ident de titutlo aceito (A=aceite,N=nao aceite) 150 150 X(01)
$conteudo .= '020808'; // data emissao titulo NOTA 31 151 156 9(06)
$conteudo .= '88'; // instrucao 1 NOTA 11 157 158 X(02)
$conteudo .= '86'; // instrucao 2 NOTA 11 159 160 X(02)
$conteudo .= '0000000000000';// juros de 1 dia valor de mora NOTA 12 161 173 9(11)V9(02)
$conteudo .= zeros(0,6); // desconto até data limite p/ descont 174 179 9(06)
$conteudo .= '0000000000000';// valor desconto a ser concedido NOTA 13 180 192 9(11)V9(02)
$conteudo .= '0000000000000'; // valor I.O.F RECOLHIDO P NOTAS SEGURO NOTA 14 193 205 9(11)V9(02)
$conteudo .= '0000000000000'; // abatimento a ser concedido NOTA 13 206 218 9(11)V9(02)
$conteudo .= '02'; // codigo de inscricao tipo de insc. sacado 01=CPF 02=CNPJ 219 220 9(02)
$conteudo .= '00999999700010'; // numero de inscricao cpf ou cnpj 221 234 9(14)
$conteudo .= limit($cliente[1],30); // nome nome do sacado NOTA 15 235 264 X(30)
$conteudo .= complementoRegistro(10,"brancos");//NOTA 15 complem regist 265 274 X(10)
$conteudo .= limit('',40); // logradouro rua numero e compl sacado 275 314 X(40)
$conteudo .= limit('',12); // bairro bairro do sacado 315 326 X(12)
$conteudo .= zeros(0,8); // cep cep do sacado 327 334 9(08)
$conteudo .= limit('',15); // cidade cidade do sacado 335 349 X(15)
$conteudo .= limit('',2); // estado uf do sacado 350 351 X(02)
$conteudo .= limit('',30); // sacador/avalista sacad ou aval. NOTA 16 352 381 X(30)
$conteudo .= complementoRegistro(4,"brancos");// complemento de regist. 382 385 X(04)
$conteudo .= zeros(0,6); // data de mora data de mora 386 391 9(06)
$conteudo .= zeros(0,2); // prazo qtde de dias NOTA 11(A) 392 393 9(02)
$conteudo .= complementoRegistro(1,"brancos"); // brancos complemento de registr. 394 394 X(01)
$conteudo .= sequencial($i++); // numero sequencial do registro no arquivo 395 400 9(06)

$conteudo .= chr(13).chr(10); //essa é a quebra de linha
/*
## REGISTRO DETALHE (OPCIONAL)

#NOME DO CAMPO #SIGNIFICADO #POSICAO #PICTURE
$conteudo .= 4; // tipo transacao id do registro 001 001 9(01)
$conteudo .= ''; // codigo de inscr tipo inscr. empresa NOTA 1 002 003 9(02)
$conteudo .= ''; // numero de inscr cpf ou cnpj 004 017 9(14)
$conteudo .= ''; // agencia mantenedora conta 018 021 9(04)
$conteudo .= '00'; // zeros complemento de registro 022 023 9(02)
$conteudo .= ''; // conta da empresa 024 028 9(05)
$conteudo .= ''; // DAC autoconf conta 029 029 9(01)
$conteudo .= 109; // n carteira no banco NOTA 5 030 032 9(03)
$conteudo .= ''; // nosso número id titulo banco NOTA 3 033 040 9(08)
$conteudo .= ''; // dac nosso numero NOTA 3 041 041 9(01)
$conteudo .= ''; // sequencia n seq. tipo 4 titulo 042 043 9(02)

$conteudo .= ''; // agencia (01) agencia conta credito 044 047 9(04)
$conteudo .= ''; // conta (01) conta p credito 048 054 9(07)
$conteudo .= ''; // dac (1) autoconf conta credito 055 055 9(01)
$conteudo .= ''; // valor (1) valor p credito NOTA 32 056 068 9(11)V9(02)

$conteudo .= ''; // agencia (2) 069 072 9(04)
$conteudo .= ''; // conta (2) 073 079 9(07)
$conteudo .= ''; // dac (2) 080 080 9(01)
$conteudo .= ''; // valor (2) 081 093 9(11)V9(02)

$conteudo .= ''; // agencia (3) 094 097 9(04)
$conteudo .= ''; // conta (3) 098 104 9(07)
$conteudo .= ''; // dac (3) 105 105 9(01)
$conteudo .= ''; // valor (3) 106 118 9(11)V9(02)

$conteudo .= ''; // agencia (4) 119 122 9(04)
$conteudo .= ''; // conta (4) 123 129 9(07)
$conteudo .= ''; // dac (4) 130 130 9(01)
$conteudo .= ''; // valor (4) 131 143 9(11)V9(02)

$conteudo .= ''; // agencia (5) 144 147 9(04)
$conteudo .= ''; // conta (5) 148 154 9(07)
$conteudo .= ''; // dac (5) 155 155 9(01)
$conteudo .= ''; // valor (5) 156 168 9(11)V9(02)

$conteudo .= ''; // agencia (6) 169 172 9(04)
$conteudo .= ''; // conta (6) 173 179 9(07)
$conteudo .= ''; // dac (6) 180 180 9(01)
$conteudo .= ''; // valor (6) 181 193 9(11)V9(02)

$conteudo .= ''; // agencia (7) 194 197 9(04)
$conteudo .= ''; // conta (7) 198 204 9(07)
$conteudo .= ''; // dac (7) 205 205 9(01)
$conteudo .= ''; // valor (7) 206 218 9(11)V9(02)

$conteudo .= ''; // agencia (8) 219 222 9(04)
$conteudo .= ''; // conta (8) 223 229 9(07)
$conteudo .= ''; // dac (8) 230 230 9(01)
$conteudo .= ''; // valor (8) 231 243 9(11)V9(02)

$conteudo .= ''; // agencia (9) 244 247 9(04)
$conteudo .= ''; // conta (9) 248 254 9(07)
$conteudo .= ''; // dac (9) 255 255 9(01)
$conteudo .= ''; // valor (9) 256 268 9(11)V9(02)

$conteudo .= ''; // agencia (10) 269 272 9(04)
$conteudo .= ''; // conta (10) 273 079 9(07)
$conteudo .= ''; // dac (10) 280 280 9(01)
$conteudo .= ''; // valor (10) 281 293 9(11)V9(02)

$conteudo .= ''; // agencia (11) 294 297 9(04)
$conteudo .= ''; // conta (11) 298 304 9(07)
$conteudo .= ''; // dac (11) 305 305 9(01)
$conteudo .= ''; // valor (11) 306 318 9(11)V9(02)

$conteudo .= ''; // agencia (12) 319 322 9(04)
$conteudo .= ''; // conta (12) 323 329 9(07)
$conteudo .= ''; // dac (12) 330 330 9(01)
$conteudo .= ''; // valor (12) 331 343 9(11)V9(02)

$conteudo .= ''; // agencia (13) 344 347 9(04)
$conteudo .= ''; // conta (13) 348 354 9(07)
$conteudo .= ''; // dac (13) 355 355 9(01)
$conteudo .= ''; // valor (13) 356 368 9(11)V9(02)

$conteudo .= ''; // agencia (14) 369 372 9(04)
$conteudo .= ''; // conta (14) 373 379 9(07)
$conteudo .= ''; // dac (14) 380 380 9(01)
$conteudo .= ''; // valor (14) 381 393 9(11)V9(02)

$conteudo .= ''; // tipo de valor informado NOTA 32 394 394 9(01)
$conteudo .= ''; // numero sequencial de registro no arquivo 395 400 9(06)
*/
/*
IMPORTANTE:
· O arquivo pode conter tanto títulos de cobrança normal como títulos de cobrança com rateio de crédito;
· Para instruções de protesto, os títulos com rateio de crédito seguem os mesmos procedimentos dos títulos sem rateio;
· O rateio de crédito pode ser por percentual ou em valor (vide Nota 32);
· Títulos com rateio de crédito – Para cada Registro Detalhe Obrigatório (Tipo de Registro “1”) podem ser utilizados até 3 (três) Registros
Tipo “4” para indicação dos detalhes do rateio de crédito (máximo de 30 contas por título). Caso a Agência/Conta/Dac do cedente e Nº
da Carteira/Nosso Número do título, informados nos registros Tipo “4” não coincidam com os dados do respectivo registro Tipo “1”, a
entrada do título é aceita sem rateio de crédito (os registros Tipo “4” são desprezados);
· A entrada do título é rejeitada nos casos em que a soma dos valores ou percentuais de rateio (informados nos registros Tipo “4”)
ultrapasse o valor nominal do título do registro Tipo “1”;
· Caso os registros de rateio (Tipo “4”) não apresentem agências/contas de crédito, os registros Tipo 4 são desprezados e o título será
tratado como entrada de cobrança normal, sem rateio;
· A agência/conta do cedente e sua respectiva agência/conta centralizadora de crédito da cobrança, não podem estar entre as
agências/contas beneficiárias do rateio de crédito;
· Títulos com rateio de crédito não aceitam instruções de Desconto ou de Abatimento e não permitem alteração dos valores nominal e de
crédito;
· Não haverá incidência de CPMF quando a raiz do CPNJ da conta do cedente for igual a da conta de crédito do rateio.
*/
/*
$conteudo .= chr(13).chr(10); //essa é a quebra de linha

## REGISTRO DETALHE (OPCIONAL)

#NOME DO CAMPO #SIGNIFICADO #POSICAO #PICTURE
$conteudo .= 5; // tipo de registro id reg transac 001 001 9(01)
$conteudo .= ''; // endereco de email do sacado NOTA 29 002 121 X(120)
$conteudo .= ''; // codigo de inscr sacador/avalista NOTA 30 122 123 9(02)
$conteudo .= ''; // numero de inscr sacador/avalista NOTA 30 124 137 9(14)
$conteudo .= ''; // logradouro sacador/avalista NOTA 30 138 177 X(40)
$conteudo .= ''; // bairro sacador/avalista NOTA 30 178 189 X(12)
$conteudo .= ''; // cep sacador/avalista NOTA 30 190 197 9(08)
$conteudo .= ''; // cidade sacador/avalista NOTA 30 198 212 X(15)
$conteudo .= ''; // estado sacador/avalista NOTA 30 213 214 X(02)
$conteudo .= complementoRegistro(180,"brancos");// brancos complem regist 215 394 X(180)
$conteudo .= ''; // numero sequencial do registro no arquivo 395 400 9(06)
*/
/*
IMPORTANTE:
· Este registro é opcional e deverá ser enviado apenas quando o Cedente desejar que o BOLETO de
cobrança seja entregue pelo Banco Itaú ao Sacado por e-mail e/ou, em substituição ou complemento
dos dados referentes ao Sacador/Avalista, quando de sua existência; e
· Sempre que for informado, deverá ser na seqüência do registro obrigatório de cobrança (Código de
Registro ‘1’) a que seus dados se referem;
· As informações constantes neste registro não são informadas no “arquivo retorno”;
· Quando as informações referentes ao "Sacador / Avalista" tiverem sido indicadas nos registros “1” e “5”,
prevalecerá sempre a do registro “5";
· Na fase de testes não é possível o envio do BOLETO via e-mail, para tanto, este será emitido e
consistido fisicamente.
*/

}// fecha loop de clientes

//$conteudo .= chr(13).chr(10); //essa é a quebra de linha

## REGISTRO TRAILER DE ARQUIVO
/*
CORRETO LAYOUT ITAU
#NOME DO CAMPO #SIGNIFICADO #POSICAO #PICTURE
$conteudo .= 9; // tipo de registro id registro trailer 001 001 9(01)
$conteudo .= complementoRegistro(393,"zeros"); // brancos complemento de registro 002 394 X(393)
$conteudo .= zeros($sequencial,6); // nº sequencial do regsitro no arquivo 395 400 9(06)
*/

/* TENTATIVA SEM SUCESSO
$conteudo .= '9201341 000000000000000000000000000000 000000000000000000000000000000 000000000000000000000000000000 000000000000000000000000000000000010000000800000000000000 '.sequencial($i);
*/

// Em nosso exemplo, nós vamos abrir o arquivo $filename
// em modo de adição. O ponteiro do arquivo estará no final
// do arquivo, e é pra lá que $conteudo irá quando o
// escrevermos com fwrite().
// 'w+' e 'w' apaga tudo e escreve do zero
// 'a+' comeca a escrever do inicio para o fim preservando o conteudo do arquivo

if (!$handle = fopen($filename, 'w+'))
{
erro("Não foi possível abrir o arquivo ($filename)");
}

// Escreve $conteudo no nosso arquivo aberto.
if (fwrite($handle, "$conteudo") === FALSE)
{
echo "Não foi possível escrever no arquivo ($filename)";
}
fclose($handle);

echo "Arquivo de remessa gerado com sucesso!";
 ?>
