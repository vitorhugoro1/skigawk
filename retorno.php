<?php
global $wpdb;
$table = $wpdb->prefix . 'payments';
$email = get_option('email-credentials');
$token = get_option('token-credentials');

if($_SERVER['SERVER_NAME'] == 'localhost'){
    $proxy_port = "3128";
    $proxy_ip   = "10.10.190.25";
    $loginpassw = "p051262:Al1ne2601";
}
if($_POST['notificationType'] && $_POST['notificationType'] == 'transaction'){
    $url = 'https://ws.pagseguro.uol.com.br/v3/transactions/'.$_POST['notificationCode'].'?email='.$email.'&token='.$token;
} else {
    exit;
}



/**
 * Busca a transação e retorna os dados da transação em array
 */

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_PROXYPORT, $proxy_port);
curl_setopt($curl, CURLOPT_PROXYTYPE, 'HTTP');
curl_setopt($curl, CURLOPT_PROXY, $proxy_ip);
curl_setopt($curl, CURLOPT_PROXYUSERPWD, $loginpassw);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$transactionCurl = curl_exec($curl);
curl_close($curl);
$Transation = simplexml_load_string($transactionCurl);

/*
 * Fim Busca
 */

$referenceID = $Transation->reference; // Pega o ID da transação
$status = absint($Transation->status); // Pega o novo Status da transação

/**
 * Busca as informações referentes ao ID da transação
 */
$query = "SELECT user_id, post_id FROM $table WHERE id = $referenceID";
$result = $wpdb->get_results($query);
$userInside = get_the_author_meta('insiders', $result[0]->user_id);
$change = $userInside[$result[0]->post_id]['pagamento'];

/**
 * Altera o status do pagamento
 */
foreach($change as $key => $item){
    if(array_search($referenceID,$item)){
        $userInside[$result[0]->post_id]['pagamento'][$key]['status'] = $status;
        update_user_meta($result[0]->user_id, 'insiders', $userInside);
    }
}
