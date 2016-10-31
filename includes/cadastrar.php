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
            $armas = json_decode(stripslashes($_POST['armas']), true);
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

              switch ($term_name) {
                case 'formaslivres':
                case 'formastradicionais':
                case 'formasinternas':
                  if($term_name == 'formastradicionais'){

                    $valida_item = array(7,8,20,21);

                  } else if($term_name == 'formaslivres'){

                    $valida_item = array(8, 9, 12, 13);

                  } else if($term_name == 'formasinternas'){

                    $valida_item = array(7, 8);

                  }

                  if(is_array($term_value)){
                    foreach($term_value as $item){

                        if(!in_array($item, $inscricoes[$post_id]['categorias'][$term_name]) && in_array($item, $valida_item)){

                            $peso_new[$term_name][] = array(

                               'peso'  => $item,

                               'groups' => $groups[$term_name][$item],

                               'id_pagamento' => $lastItem

                           );

                        } else if(!in_array($item, $inscricoes[$post_id]['categorias'][$term_name])) {

                          $peso_new[$term_name][] = array(

                              'peso'  => $item,

                              'id_pagamento' => $lastItem

                          );

                        }

                    }
                  }

                  if(isset($inscricoes[$post_id]['categorias'][$term_name])){

                      if( ! is_array($inscricoes[$post_id]['categorias'][$term_name])){
                        $inscricoes[$post_id]['categorias'][$term_name] = array();
                      }

                      $peso_new[$term_name] = array_merge($inscricoes[$post_id]['categorias'][$term_name],$peso_new[$term_name]);
                  }

                  break;
                case 'formasolimpicas':
                  foreach($term_value as $item){

                     if (!in_array($item, $inscricoes[$post_id]['categorias'][$term_name])){

                         $peso_new[$term_name][] = array(

                             'peso'  => $item,

                             'id_pagamento' => $lastItem

                         );

                     }

                  }

                  if(isset($inscricoes[$post_id]['categorias'][$term_name])){

                      if( ! is_array($inscricoes[$post_id]['categorias'][$term_name])){
                        $inscricoes[$post_id]['categorias'][$term_name] = array();
                      }

                      $peso_new[$term_name] = array_merge($inscricoes[$post_id]['categorias'][$term_name],$peso_new[$term_name]);
                  }
                  break;
                case 'tree':
                  foreach($term_value as $item){

                      if(!in_array($item, $inscricoes[$post_id]['categorias'][$term_name])){

                          $peso_new[$term_name][] = array(

                             'peso'  => $item,

                             'arma' => $armas[$item],

                             'id_pagamento' => $lastItem

                           );

                      }

                  }

                  if(isset($inscricoes[$post_id]['categorias'][$term_name])){

                      if( ! is_array($inscricoes[$post_id]['categorias'][$term_name])){
                        $inscricoes[$post_id]['categorias'][$term_name] = array();
                      }

                      $peso_new[$term_name] = array_merge($inscricoes[$post_id]['categorias'][$term_name],$peso_new[$term_name]);
                  }
                  break;
                default:
                    if (!array_key_exists($term_name, $inscricoes[$post_id]['categorias'])) {

                        $peso_new[$term_name] = array(

                            'peso' => $term_value,

                            'id_pagamento' => $lastItem,

                        );

                    } else if(isset($inscricoes[$post_id]['categorias'][$term_name])) {
                        $peso_new[$term_name] = array(
                            'peso' => $term_value,
                            'id_pagamento' => $lastItem,
                        );
                    }
                  break;
              }

            }



            if (!$peso_new == '' || null) { // Se não retornar valor nulo da anterior, adiciona os novos dados

                $categorias = array_merge($inscricoes[$post_id]['categorias'], $peso_new);

                $inscricoes[$post_id]['pagamento'][] = $pagamento;

                $pagamentos = $inscricoes[$post_id]['pagamento'];

                $datas_inscricao = array_merge($inscricoes[$post_id]['data_inscricao'], array($data_inscricao));

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

                    $valid = update_user_meta($user_id, 'insiders', $new_meta);
                } else {
                    $valid = update_user_meta($user_id, 'insiders', $save);
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

            if (empty($old_meta) || is_null($old_meta)) {
                $new_meta = array($user_id);
                update_post_meta($post_id, 'user_subscribers', $new_meta);
            } else {
                array_push($old_meta, $user_id);
                update_post_meta($post_id, 'user_subscribers', $old_meta);
            }

            foreach ($peso as $cat => $value) {
                $array = array();

                switch ($cat) {
                  case 'formaslivres':
                  case 'formastradicionais':
                  case 'formasinternas':
                    if($cat == 'formastradicionais'){
                      $valida_item = array(7,8,20,21);
                    } else if($cat == 'formaslivres'){
                      $valida_item = array(8, 9, 12, 13);
                    } else if($cat == 'formasinternas'){
                      $valida_item = array(7, 8);
                    }

                    foreach ($value as $item){
                        if(in_array($item, $valida_item)){
                            $array[] = array(
                                'peso'           => $item,
                                'groups'         => $groups[$cat][$item],
                                'id_pagamento'   => $lastItem
                            );
                        } else {
                          $array[] = array(
                              'peso'           => $item,
                              'id_pagamento'   => $lastItem
                          );
                        }
                    }

                    break;
                  case 'formasolimpicas':
                    foreach ($value as $item){
                           $array[] = array(
                               'peso'           => $item,
                               'id_pagamento'   => $lastItem
                           );
                       }
                    break;
                  case 'tree':
                    foreach ($value as $item){
                        $array[] = array(
                            'peso'           => $item,
                            'arma'         => $armas[$item],
                            'id_pagamento'   => $lastItem
                        );
                    }
                    break;
                  default:
                    $array = array(
                        'peso'          => $value,
                        'id_pagamento'  => $lastItem
                    );
                    break;
                }

                $categorias[$cat] = $array;
            }

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

     $options = unserialize(get_option('deposito'));

    $home = home_url();

    $nome = get_the_author_meta('display_name', $user_id );

    $titulo = get_the_title($post_id);

    $tipo = (get_post_type($post_id) == 'campeonatos') ? 'Campeonato' : 'Evento';

    $conta = sprintf('%s<br> %s<br>Agência: %s<br>Conta: %s', $options['banco'], $options['beneficiario'], $options['agencia'], $options['conta']);

    $admin_email = get_option('admin_email');



    $to =  get_the_author_meta('user_email', $user_id);

    $subject = sprintf("Inscrição no %s da Skigawk", $tipo);

    // $message = "Inscrição realizada com sucesso. <br> Para realizar o pagamento acesse <a href='".$url."'>aqui</a>";

    $message = "<div style='background-color:#fff;padding:10px;'>

  		<div style='width: auto;display: flex;background-color:#f1c40f'>

  			<a href='{$home}' style='margin: 0 auto;'>

  				<img src='http://skigawk.com.br/testes/wordpress/wp-content/uploads/2016/07/logo-home2.png' alt='SKIGAWK' title='Skigawk' />

  			</a>

  		</div>

  		<div>

  			<p>Olá, <b>{$nome}</b> sua inscrição para o <b>{$titulo}</b> foi realizada com sucesso.</p>

  			<p>&nbsp;&nbsp;Para darmos continuidade ao processo de validação, por favor realize o pagamento para os dados abaixo: </p>

  			<p>

  				{$conta}

  			</p>

  			<p>&nbsp;Ao realizar o pagamento, envie o seu <b>nome completo</b> e o <b>comprovante</b> para <a href='mailto:adriel@skigawk.com.br'>adriel@skigawk.com.br</a>.</p>

  			<p style='font-size:11px;'>

  				<i>Se o e-mail não foi para você, desconsidere este e-mail e avise o administrador do sistema em <a href='mailto:{$admin_email}'>{$admin_email}</a>.</i>

  				<br>

  			</p>

  		</div>

  	</div>";

    $headers[] = "From: Skigawk <{$admin_email}>". "\r\n";

    $headers[] = "MIME-Version: 1.0";

    $headers[] = "Content-type:text/html;charset=UTF-8";

    wp_mail( $to, $subject, $message, $headers);



    $url = get_permalink($pages_ids['inscricoes']);

    header('Location: '.$url);

} elseif ($pay == 'n') {

    header('Location: '.get_permalink($pages_ids['inscricoes']));

}
