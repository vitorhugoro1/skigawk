<?php
/**
 * Itens Principais para funcionamento do cadastro
 */
require '../../../../wp-blog-header.php';
global $wpdb;
error_reporting();
date_default_timezone_set('America/Sao_Paulo');
$pages_ids = pages_group_ids();
$user_id = $_POST['user_id'];
$post_id = $_POST['post_id'];
$price = $_POST['priceTotal'];
$insider = $_POST['insider'];
$inscricoes = get_the_author_meta('insiders', $user_id, true);
$data_inscricao = date('d/m/Y G:i');
$pagamento = array();
$type = get_post_type($post_id);
$usuario = get_userdata($user_id);

/**
 * Tratamento de itens unicos para inicio
 * das funções estabelecidas
 */
switch ($type) {
    case 'campeonatos':
            $pay = get_post_meta($post_id, '_vhr_price_option', true);
            $peso = json_decode(stripslashes($_POST['peso']), true);
            $groups = json_decode(stripslashes($_POST['groups']), true);
        break;
    case 'eventos':
            $pay = esc_attr($_POST['pay']);
            $category = esc_attr($_POST['category']);
        break;
}

/*
 * Inserindo pagamento na Base de Dados
 */
$table = $wpdb->prefix.'payments';

$query = 'INSERT INTO '.esc_sql($table).' (user_id, post_id, valor, cat_inscricao, meio_pag, data_pag ) ';

if($type == 'campeonatos'){
    if ($price > 00.00) {
        $query .= 'VALUES ('.esc_sql($user_id).', '.esc_sql($post_id).', '.esc_sql($price).", '".esc_sql(serialize($peso))."', 'pagseg', '".date('Y-m-d H:i:s')."' )";
    } else {
        $query .= 'VALUES ('.esc_sql($user_id).', '.esc_sql($post_id).', '.esc_sql($price).", '".esc_sql(serialize($peso))."', 'gratuito', '".date('Y-m-d H:i:s')."' )";
    }

    $wpdb->query($query);
    $lastItem = $wpdb->get_var("SELECT MAX(id) FROM $table");

    $pagamento = array(
        'id_pagamento'  => $lastItem,
        'valor'         => $price,
        'status'        => 'v'
        );
} else {
    if ($price > 00.00) {
        $query .= 'VALUES ('.esc_sql($user_id).', '.esc_sql($post_id).', '.esc_sql($price).", '".esc_sql($category)."', 'pagseg', '".date('Y-m-d H:i:s')."' )";
    } else {
        $query .= 'VALUES ('.esc_sql($user_id).', '.esc_sql($post_id).', '.esc_sql($price).", '".esc_sql($category)."', 'gratuito', '".date('Y-m-d H:i:s')."' )";
    }

    $wpdb->query($query);
    $lastItem = $wpdb->get_var("SELECT MAX(id) FROM $table");

    $pagamento = array(
        'id_pagamento'  => $lastItem,
        'category'      => $category, // *
        'valor'         => $price, // *
        'status'        => '1' // * , 'p' => pago, 'v' => à verificar
    );
}



/*
 * Inserindo as informações de cadastro dentro da user_meta
 */
if (userInsider($user_id, $post_id)) { // Verifica se o usuario está inscrito
    switch ($type) {
        case 'campeonatos':
            $peso_new = array();

            foreach ($peso as $term_name => $term_value) { // Vê se as categorias que está se candidatando estão disponiveis
                if ($term_name == 'formaslivres' || $term_name == 'formasinternas' || $term_name == 'formastradicionais' || $term_name == 'formasolimpicas'){
                    if($term_name == 'formastradicionais'){
                        foreach($term_value as $item){
                            if(!in_array($item, $inscricoes[$post_id]['categorias'][$term_name]) && in_array($item, array(7,8,20,21))){
                                $peso_new[$term_name][] = array(
                                   'peso'  => $item,
                                   'groups' => $groups[$item],
                                   'id_pagamento' => $lastItem
                               );
                            }
                        }
                    } else {
                        foreach($term_value as $item){
                           if (!in_array($item, $inscricoes[$post_id]['categorias'][$term_name])){
                               $peso_new[$term_name][] = array(
                                   'peso'  => $item,
                                   'id_pagamento' => $lastItem
                               );
                           }
                        }
                    }

                    if(isset($inscricoes[$post_id]['categorias'][$term_name])){
                        $peso_new[$term_name] = array_merge($inscricoes[$post_id]['categorias'][$term_name],$peso_new[$term_name]);
                    }

                } else {
                    if (!array_key_exists($term_name, $inscricoes[$post_id]['categorias'])) {
                        $peso_new[$term_name] = array(
                            'peso' => $term_value,
                            'id_pagamento' => $lastItem,
                        );
                    }
                }
            }

            if (!$peso_new == '' || null) { // Se não retornar valor nulo da anterior, adiciona os novos dados
                $categorias = array_merge($inscricoes[$post_id]['categorias'], $peso_new);
                $inscricoes[$post_id]['pagamento'][] = $pagamento;
                $pagamentos = $inscricoes[$post_id]['pagamento'];
                $datas_inscricao = array_merge($inscricoes[$post_id]['data_inscricao'], array($data_inscricao));
                var_dump($categorias);
                $save = array(
                    $post_id => array(
                        'categorias' => $categorias,
                        'pagamento' => $pagamentos,
                        'data_inscricao' => $datas_inscricao,
                    ),
                );

                if (!empty($inscricoes)) {
                    $inscricoes[$post_id] = array(
                        'categorias'     => $categorias,
                        'pagamento'      => $pagamentos,
                        'data_inscricao' => $datas_inscricao,
                    );

                    $new_meta = $inscricoes;
                    update_user_meta($user_id, 'insiders', $new_meta);
                } else {
                    update_user_meta($user_id, 'insiders', $save);
                }
            }

        break;
        case 'eventos':

        break;
    }
} else {
    switch ($type) {
        case 'campeonatos':
        $old_meta = array();
            $old_meta = get_post_meta($post_id, 'user_subscribers', true);

            if (empty($old_meta)) {
                $new_meta[] = $user_id;
                update_post_meta($post_id, 'user_subscribers', $new_meta);
            } else {
                array_push($old_meta, $user_id);
                update_post_meta($post_id, 'user_subscribers', $old_meta);
            }

            foreach ($peso as $cat => $value) {
                $array = '';
                if ($cat == 'formaslivres' || $cat == 'formasinternas' || $cat == 'formastradicionais' || $cat == 'formasolimpicas'){
                   if($cat == 'formastradicionais'){
                        foreach ($value as $item){
                            if(in_array($item, array(7,8,20,21))){
                                $array[] = array(
                                    'peso'           => $item,
                                    'groups'         => $groups[$item],
                                    'id_pagamento'   => $lastItem
                                );
                            }
                        }
                   } else {
                    foreach ($value as $item){
                           $array[] = array(
                               'peso'           => $item,
                               'id_pagamento'   => $lastItem
                           );
                       }
                   }
                    echo '<br>'.$cat.'<br>';
                    var_dump($array);
                    echo '<br>';
                } else {
                    $array = array(
                        'peso'          => $value,
                        'id_pagamento'  => $lastItem
                    );
                }

                $categorias[$cat] = $array;
            }
            var_dump($categorias);
            $inscricoes[$post_id]['pagamento'][] = $pagamento;
            $pagamentos = $inscricoes[$post_id]['pagamento'];

            $save = array(
                $post_id => array(
                    'categorias' => $categorias,
                    'pagamento' => $pagamentos,
                    'data_inscricao' => array($data_inscricao),
                ),
            );

            if (!empty($inscricoes)) {
                $inscricoes[$post_id] = array(
                    'categorias' => $categorias,
                    'pagamento' => $pagamentos,
                    'data_inscricao' => array($data_inscricao),
                );

                $new_meta = $inscricoes;
                update_user_meta($user_id, 'insiders', $new_meta);
            } else {
                update_user_meta($user_id, 'insiders', $save);
            }

        break;
        case 'eventos':

            $old_meta = get_post_meta($post_id, 'user_subscribers', true);

            if (empty($old_meta)) {
                $new_meta[] = $user_id;
                update_post_meta($post_id, 'user_subscribers', $new_meta);
            } else {
                $new_meta = array_push($old_meta, $user_id);
                update_post_meta($post_id, 'user_subscribers', $new_meta);
            }

            $save = array(
                $post_id => array(
                    'pagamento' => $pagamento,
                    'data_inscricao' => array($data_inscricao),
                ),
            );

            if (!empty($inscricoes)) {
                $inscricoes[$post_id] = array(
                    'pagamento' => $pagamento,
                    'data_inscricao' => array($data_inscricao),
                );

                $new_meta = $inscricoes;
                update_user_meta($user_id, 'insiders', $new_meta);
            } else {
                update_user_meta($user_id, 'insiders', $save);
            }

        break;
    }
}

/*
 * Verificando se o Evento ou Campeonato é gratuito
 * @var string
 */

if ($pay == 's') {
//    $paymentRequest = new PagSeguroPaymentRequest();
//    $paymentRequest->addItem($lastItem, get_the_title($post_id), 1, $price);
//    preg_match("/^([^ ]+)[^\\d]+(\\d*+[\\-]+\\d*)/", get_the_author_meta('phone', $user_id ), $m);
//    $ddd = str_replace(array('(', ')'), "", $m[1]);
//    $number = str_replace('-', "", $m[2]);
//    $paymentRequest->setSender(
//      get_the_author_firstname($user_id),
//      get_the_author_email($user_id),
//      $ddd,
//      $number
//    );
//    $paymentRequest->setCurrency('BRL');
//    $paymentRequest->setShippingType(3);
//    $paymentRequest->setShippingAddress(
//      get_the_author_meta('cep', $user_id ),
//      get_the_author_meta('address', $user_id ),
//      get_the_author_meta('addressnumber', $user_id ),
//      get_the_author_meta('addresscomplement', $user_id ),
//      get_the_author_meta('district', $user_id ),
//      get_the_author_meta('city', $user_id ),
//      get_the_author_meta('state', $user_id ),
//      'BRA'
//    );
//    $paymentRequest->setReference($lastItem);
//    $paymentRequest->setRedirectUrl(get_permalink($pages_ids['inscricoes']));
//
//    $credentials = new PagSeguroAccountCredentials(get_option('email-credentials'), get_option('token-credentials'));
//
//    try {
//        $url = $paymentRequest->register($credentials);
//    } catch (Exception $e) {
//        echo $e->getMessage();
//        header('Location: '.get_permalink($pages_ids['inscricoes']));
//    }
//

    /**
     * Envio de e-mail de confirmação da inscrição no campeonato ou evento
     */
    $to =  get_the_author_meta('user_email', $user_id);
    $subject = "Inscrição no ".((get_post_type($post_id) == 'campeonatos') ? 'Campeonato' : 'Evento' ).' '.get_the_title($post_id);
    $message = "Inscrição realizada com sucesso. <br> Para realizar o pagamento acesse <a href='".$url."'>aqui</a>";
    $headers[] = "From: Skigawk <".get_option('admin_email').">". "\r\n";
    $headers[] = "MIME-Version: 1.0";
    $headers[] = "Content-type:text/html;charset=UTF-8";
    wp_mail( $to, $subject, $message, $headers);

    $url = get_permalink($pages_ids['inscricoes']);
    header('Location: '.$url);
} elseif ($pay == 'n') {
    header('Location: '.get_permalink($pages_ids['inscricoes']));
}
