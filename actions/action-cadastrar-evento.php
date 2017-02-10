<?php

add_action('admin_post_vhr_cadastrar_evento', 'vhr_cadastrar_evento');
add_action('admin_post_nopriv_vhr_cadastrar_evento', 'vhr_cadastrar_evento' );

function vhr_cadastrar_evento(){
	global $wpdb;

	if( ! is_user_logged_in()){
		wp_die('Não tem permissão para realizar isto');
	}

  check_admin_referer('vhr_cadastrar_evento');

	$user_id = get_current_user_id();
	$pages_ids = pages_group_ids();
	$infos = $_POST['info'];
	$categorias = $_POST['categorias'];
	$post_id = $infos['post_id'];
	$inscricoes = get_the_author_meta('insiders', $user_id, true);
	$data_inscricao = date('d/m/Y G:i');

	$id_pagamento = add_pagamento($user_id, $infos['post_id'], $infos['valor'], $infos['meio_pag'] , array_keys($categorias) );

	if(!$id_pagamento){
		wp_die('Erro ao salvar o pagamento, contate o administrador.');
	}

	$pagamento = array(
        'id_pagamento'  => $id_pagamento,
        'valor'         => $infos['valor'], // *
        'status'        => 'v' // * , 'p' => pago, 'v' => à verificar
    );



	if( 's' == $infos['inscrito'] ){
		if( 'campeonatos' == $infos['tipo'] ){
			$new = array();
			$table = array();

			foreach($categorias as $term => $term_value){
				if(is_array($term_value) && !isset($term_value['id'])){
					foreach($term_value as $item){

						if(!in_array($item['id'], $inscricoes[$post_id]['categorias'][$term])){

							if(isset($item['equipe'])){
														$new[$term][] = array(
		                               'peso'  			=> $item['id'],
		                               'groups' 		=> $item['equipe'],
		                               'id_pagamento' 	=> $id_pagamento
		                        );

		                        $table[$term][] = array(
		                               'peso'  			=> $item['id'],
		                               'groups' 		=> $item['equipe'],
		                               'id_pagamento' 	=> $id_pagamento
		                        );
							} else if(isset($item['arma'])){
														$new[$term][] = array(
		                               'peso'  			=> $item['id'],
		                               'arma' 			=> $item['arma'],
		                               'id_pagamento' 	=> $id_pagamento
		                        );

		                        $table[$term][] = array(
		                               'peso'  			=> $item['id'],
		                               'arma' 			=> $item['arma'],
		                               'id_pagamento' 	=> $id_pagamento
		                        );
							} else {
														$new[$term][] = array(
		                               'peso'  			=> $item['id'],
		                               'id_pagamento'	=> $id_pagamento
		                        );

		                        $table[$term][] = array(
		                               'peso'  			=> $item['id'],
		                               'id_pagamento'	=> $id_pagamento
		                        );
							}


												if(isset($inscricoes[$post_id]['categorias'][$term])){
			                    if( ! is_array($inscricoes[$post_id]['categorias'][$term])){
			                      $inscricoes[$post_id]['categorias'][$term] = array();
			                    }
			                    $new[$term] = array_merge($inscricoes[$post_id]['categorias'][$term],$new[$term]);
		                    }
						}

					}
				} else {
							if (!array_key_exists($term, $inscricoes[$post_id]['categorias'])) {

                  $new[$term]['peso'] = $term_value['id'];
									$new[$term]['id_pagamento'] = $id_pagamento;

									if(isset($new[$term][0])){
										$new[$term] = $new[$term][0];
									}

									$table[$term] = array(
                      'peso' 			=> $term_value['id'],
                      'id_pagamento' 	=> $id_pagamento,
                  );

              } else if(isset($inscricoes[$post_id]['categorias'][$term])) {
                  $new[$term] = array(
                      'peso' 			=> $term_value['id'],
                      'id_pagamento' 	=> $id_pagamento,
                  );

									if(isset($new[$term][0])){
										$new[$term] = $new[$term][0];
									}

                  $table[$term] = array(
                      'peso' 			=> $term_value['id'],
                      'id_pagamento' 	=> $id_pagamento,
                  );
              }

				}
			}

			if( is_array($new) && ! empty($new) && ! is_null($new) ) {
								if(is_null($inscricoes[$post_id]['categorias'])){
									$categorias = $new;
								} else {
									$categorias = array_merge($inscricoes[$post_id]['categorias'], $new);
								}

								if(is_null($inscricoes[$post_id]['data_inscricao'])){
									$data_inscricao = array($data_inscricao);
								} else {
									$datas_inscricao = array_merge($inscricoes[$post_id]['data_inscricao'], array($data_inscricao));
								}

                $inscricoes[$post_id]['pagamento'][] = $pagamento;
                $pagamentos = $inscricoes[$post_id]['pagamento'];


                $save = array(
                    $post_id => array(
                        'categorias' 		=> $categorias,
                        'pagamento' 		=> $pagamentos,
                        'data_inscricao' 	=> $datas_inscricao,
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
		}
	} else {
		if( 'campeonatos' == $infos['tipo'] ){
			$meta = array();
			$new = array();
			$table = array();
			$meta = get_post_meta($post_id, 'user_subscribers', true);

			if( ! empty($meta) || is_null($meta) ){
				array_push($meta, $user_id);
				update_post_meta($post_id, 'user_subscribers', $meta );
			} else {
				$meta = array();
				array_push($meta, $user_id);
				update_post_meta($post_id, 'user_subscribers', $meta );
			}


			foreach($categorias as $term => $term_value ){
				if(is_array($term_value)){
						foreach($term_value as $item){

							if(isset($item['equipe']) && !isset($item['arma'])){
								$new[$term][] = array(
		                               'peso'  			=> $item['id'],
		                               'groups' 		=> $item['equipe'],
		                               'id_pagamento' 	=> $id_pagamento
		                        );

		                        $table[$term][] = array(
		                               'peso'  			=> $item['id'],
		                               'groups' 		=> $item['equipe'],
		                               'id_pagamento' 	=> $id_pagamento
		                        );
							} elseif(isset($item['arma']) && !isset($item['equipe'])){
								$new[$term][] = array(
		                               'peso'  			=> $item['id'],
		                               'arma' 			=> $item['arma'],
		                               'id_pagamento' 	=> $id_pagamento
		                        );

		                        $table[$term][] = array(
		                               'peso'  			=> $item['id'],
		                               'arma' 			=> $item['arma'],
		                               'id_pagamento' 	=> $id_pagamento
		                        );
							} else {
								$new[$term][] = array(
		                               'peso'  			=> $item['id'],
		                               'id_pagamento'	=> $id_pagamento
		                        );

		                        $table[$term][] = array(
		                               'peso'  			=> $item['id'],
		                               'id_pagamento'	=> $id_pagamento
		                        );
							}
						}
				} else {

                    $new[$term] = array(
                        'peso' 			=> $term_value,
                        'id_pagamento' 	=> $id_pagamento,
                    );

					$table[$term] = array(
                        'peso' 			=> $term_value,
                        'id_pagamento' 	=> $id_pagamento,
                    );
				}
			}

			$inscricoes[$post_id]['pagamento'][] = $pagamento;
            $pagamentos = $inscricoes[$post_id]['pagamento'];

            $save = array(
                $post_id => array(
                    'categorias' 		=> $categorias,
                    'pagamento' 		=> $pagamentos,
                    'data_inscricao' 	=> array($data_inscricao),
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

		} elseif ( 'eventos' == $infos['tipo'] ){

			$meta = array();
			$meta = get_post_meta($post_id, 'user_subscribers', true);

            if (is_array($meta) && !empty($meta) && !is_null($meta)) {
                array_push($meta, $user_id);

                update_post_meta($post_id, 'user_subscribers', $meta);

            } else {
                $meta = array();
                array_push($meta, $user_id);

                update_post_meta($post_id, 'user_subscribers', $meta);
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

		}
	}

	if('pag_seguro' == $infos['meio_pag']){
		$paymentRequest = new PagSeguroPaymentRequest();

        $paymentRequest->addItem($id_pagamento, get_the_title($post_id), 1, $infos['valor']);

        preg_match("/^([^ ]+)[^\\d]+(\\d*+[\\-]+\\d*)/", get_the_author_meta('phone', $user_id ), $m);

	    $ddd = str_replace(array('(', ')'), "", $m[1]);
	    $number = str_replace('-', "", $m[2]);

		   $paymentRequest->setSender(
		     get_the_author_meta('display_name', $user_id),
		     get_the_author_email($user_id),
		     $ddd,
		     $number
		   );
		   $paymentRequest->setCurrency('BRL');
		   $paymentRequest->setShippingType(3);
		   $paymentRequest->setShippingAddress(
		     get_the_author_meta('cep', $user_id ),
		     get_the_author_meta('address', $user_id ),
		     get_the_author_meta('addressnumber', $user_id ),
		     get_the_author_meta('addresscomplement', $user_id ),
		     get_the_author_meta('district', $user_id ),
		     get_the_author_meta('city', $user_id ),
		     get_the_author_meta('state', $user_id ),
		     'BRA'
		   );

		   $paymentRequest->setReference($id_pagamento);
		   $paymentRequest->setRedirectUrl(get_permalink($pages_ids['inscricoes']));

   		$credentials = new PagSeguroAccountCredentials(get_option('email-credentials'), get_option('token-credentials'));

	   try {
	       $url = $paymentRequest->register($credentials);
	   } catch (Exception $e) {
	       wp_die($e->getMessage());
	   }

	} elseif( 'deposito' == $infos['meio_pag'] ){
		$options = unserialize(get_option('deposito'));

	    $home = home_url();
	    $nome = get_the_author_meta('display_name', $user_id );
	    $titulo = get_the_title($post_id);
	    $tipo = (get_post_type($post_id) == 'campeonatos') ? 'Campeonato' : 'Evento';
	    $conta = sprintf('%s<br> %s<br>Agência: %s<br>Conta: %s', $options['banco'], $options['beneficiario'], $options['agencia'], $options['conta']);

	    $admin_email = get_option('admin_email');
	    $sexo = get_the_author_meta( 'sex', $user_id );
	    $fetaria = get_the_author_meta( 'fEtaria', $user_id );
	    $to =  get_the_author_meta('user_email', $user_id);

	    $subject = sprintf("Inscrição no %s da Skigawk", $tipo);

	    $tab = "<table class='modalidade'>";
	    $tab .= "<thead>
	            <th>Modalidade</th>
	  					<th>Peso ou Forma</th></thead>";
	    $tab .= "<tbody>";
	    foreach($table as $key => $value){
	      switch ($key) {
	        case 'formaslivres':
	        case 'formastradicionais':
	        case 'formasinternas':
	          if($key == 'formastradicionais'){
	            $valida_item = array(7,8,20,21);
	          } else if($key == 'formaslivres'){
	            $valida_item = array(8, 9, 12, 13);
	          } else if($key == 'formasinternas'){
	            $valida_item = array(7, 8);
	          }

	          $term = get_term_by( 'slug', $key, 'categoria');

	          $tab .= '<tr>';
	          $tab .= "<th>{$term->name}</th>";
	          $tab .= '<td>';
	          $tab .= '<ul class="modalidade">';
	          foreach ($value as $item){
	            $tab .= "<li>";
	              if(in_array($item, $valida_item)){
	                $g = (! empty($item['groups'])) ? implode(', ', array_filter($item['groups'])) : '';
	                $tab .= sprintf('%s (Equipe: %s)', get_weight($key, $item['peso'], $sexo, $fetaria), $g);
	              } else {
	                $tab .= get_weight($key, $item['peso'], $sexo, $fetaria);
	              }
	            $tab .= "</li>";
	          }
	          $tab .= '</ul>';
	          $tab .= '</td>';
	          $tab .= '</tr>';

	          break;
	        case 'formasolimpicas':
	          $term = get_term_by( 'slug', $key, 'categoria');
	          $tab .= '<tr>';
	          $tab .= "<th>{$term->name}</th>";
	          $tab .= '<td>';
	          $tab .= '<ul class="modalidade">';
	          foreach ($value as $item){
	            $tab .= "<li>";
	            $tab .= get_weight($key, $item['peso'], $sexo, $fetaria);
	            $tab .= "</li>";
	          }
	          $tab .= '</ul>';
	          $tab .= '</td>';
	          $tab .= '</tr>';
	          break;
	        case 'tree':
	        $term = get_term_by( 'slug', $key, 'categoria');
	          $tab .= '<tr>';
	          $tab .= "<th>{$term->name}</th>";
	          $tab .= '<td>';
	          $tab .= '<ul class="modalidade">';
	          foreach ($value as $item){
	            $tab .= "<li>";
	            $w = (! empty($item['arma'])) ? implode(', ', array_filter($item['arma'])) : '';
	            $tab .= sprintf('%s (Arma: %s)', get_weight($key, $item['peso'], $sexo, $fetaria), $w);
	            $tab .= "</li>";
	          }
	          $tab .= '</ul>';
	          $tab .= '</td>';
	          $tab .= '</tr>';
	          break;
	        default:
	        $term = get_term_by( 'slug', $key, 'categoria');
	          $tab .= '<tr>';
	          $tab .= "<th>{$term->name}</th>";
	          $tab .= '<td>';
	          $tab .= get_weight($key, $value['peso'], $sexo, $fetaria) . ' Kg';
	          $tab .= '</td>';
	          $tab .= '</tr>';
	          break;
	      }
	    }
	    $tab .= "</tbody>";
	    $tab .= "<tfoot>
	  					<tr>
	  						<th>&nbsp;</th>
	  						<td>&nbsp;</td>
	  					</tr>
	  					<tr>
	  						<th scope='row'>Total</th>
		  					<td class='total'>$ {$valor}</td>
	  					</tr>
	  				</tfoot>";
	    $tab .= '</table>';

	    $message = "<style type='text/css'>
			.full {
				width: 100%;
				height: 100%;
				margin: 0;
				padding: 0;
				background-color: #f5f5f5;
				font-family: 'Roboto', sans-serif;
			}

			.content {
				padding:10px;
			}

			header {
				width: auto;
				display: flex;
				background-color: #f1c40f;
			}

			header a {
				margin: 0 auto;
			}

			.modalidade {
				box-sizing: border-box;
				width: 100%;
				margin: 0 auto;
			}

			.modalidade th, .modalidade td  {
				border: 1px solid rgb(0,0,0);
				margin: 0;
				padding: 0;
			}

			.modalidade th {
				width: 200px;
			}

			.formas {
				margin: 0;
				list-style: none;
				padding: 0;
			}

			.total {
				text-align: right;
				background-color: red;
				color: rgb(255,255,255);
			}

			footer {
				font-size: 11px;
			}
		</style>
		<div class=full>
			<div class='content'>
				<header>
					<a href='{$home}'>

		  			<img src='http://skigawk.com.br/testes/wordpress/wp-content/uploads/2016/07/logo-home2.png' alt='SKIGAWK' title='Skigawk' />
		  			</a>
				</header>
				<div>

	  			<p>Olá, <b>{$nome}</b> sua inscrição para o <b>{$titulo}</b> foi realizada com sucesso.</p>

	        {$tab}

	  			<p>&nbsp;&nbsp;Para darmos continuidade ao processo de validação, por favor realize o pagamento para os dados abaixo: </p>

	  			<p class='conta'>

	  				{$conta}

	  			</p>

	  			<p>&nbsp;Ao realizar o pagamento, envie o seu <b>nome completo</b> e o <b>comprovante</b> para <a href='mailto:adriel@skigawk.com.br'>adriel@skigawk.com.br</a>.</p>



	  		</div>

		  		<footer>
		  			<p>
		  				<i>Se o e-mail não foi para você, desconsidere este e-mail e avise o administrador do sistema em <a href='mailto:{$admin_email}'>{$admin_email}</a>.</i>
		  			</p>
		  		</footer>
			</div>
	  	</div>";

	    $headers[] = "From: Skigawk <{$admin_email}>". "\r\n";

	    $headers[] = "MIME-Version: 1.0";

	    $headers[] = "Content-type:text/html;charset=UTF-8";

	    wp_mail( $to, $subject, $message, $headers);

	    $url = get_permalink($pages_ids['inscricoes']);
	} else {
		$url = get_permalink($pages_ids['inscricoes']);
	}

	if(isset($_POST['feedback']) && $_POST['feedback'] == 's'){
		$commentdata = array(
			'comment_post_ID' => $post_id, // to which post the comment will show up
			'comment_author' => get_the_author_meta('display_name', $user_id), //fixed value - can be dynamic
			'comment_author_email' => get_the_author_email($user_id), //fixed value - can be dynamic
			'comment_content' => $_POST['feedback_msg'], //fixed value - can be dynamic
			'user_id' => $user_id, //passing current user ID or any predefined as per the demand
		);

		//Insert new comment and get the comment ID
		wp_new_comment( $commentdata );
	}

	wp_redirect($url);
	exit;
}

function add_pagamento($user_id, $post_id, $valor, $meio_pag, $categorias ){
	global $wpdb;

	if(!is_serialized($categorias)){
		if(is_array($categorias)){
			$categorias = serialize($categorias);
		} else {
			$categorias = serialize(array($categorias));
		}
	}

	$table = $wpdb->prefix . 'payments';

	$wpdb->insert(
			$table,
			array(
				'user_id'		=> $user_id,
				'post_id'		=> $post_id,
				'valor'			=> $valor,
				'cat_inscricao'	=> $categorias,
				'meio_pag'		=> $meio_pag
			),
			array(
				'%d',
				'%d',
				'%s',
				'%s',
				'%s',
			)
	);

	return ($wpdb->insert_id) ? $wpdb->insert_id : false;
}
