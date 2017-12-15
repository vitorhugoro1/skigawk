<?php
if (file_exists(__DIR__.'/admin-config/admin-pagamentos.php')) {
    require __DIR__.'/admin-config/admin-pagamentos.php';
}

if (!function_exists('wp_handle_upload')) {
    require_once ABSPATH.'wp-admin/includes/file.php';
}

include get_template_directory() . '/actions/action-editar-perfil.php';
include get_template_directory() . '/actions/action-cadastrar-usuario.php';
include get_template_directory() . '/actions/action-cadastrar-evento.php';

add_action('admin_enqueue_scripts', 'admin_functions');
function admin_functions()
{
    wp_enqueue_script('maskinput', get_template_directory_uri().'/js/jquery.mask.min.js', array('jquery'), '', true);
    wp_enqueue_style('modcss', get_template_directory_uri() . '/css/mod.css');
    wp_enqueue_script('mods', get_template_directory_uri() . '/js/adminfunctions.js', array('jquery'), '', true);
}

add_action('wp_enqueue_scripts', 'enqueue_scripts_and_styles');
function enqueue_scripts_and_styles()
{
    wp_enqueue_script( 'jquery', get_template_directory_uri() . '/js/jquery-2.1.4.min.js', array('jquery'), '2.1.4', true);
    wp_enqueue_script('maskinput', get_template_directory_uri().'/js/jquery.mask.min.js', array('jquery'), '', true);
    wp_enqueue_script('mods', get_template_directory_uri() . '/js/mod.js', array('jquery'), '', true);
    wp_enqueue_style('toogle-btn', get_template_directory_uri().'/css/mdtoggle.min.css', '', true);
    wp_enqueue_style('modcss', get_template_directory_uri() . '/css/mod.css');
}

add_action('init', 'controller_init', 9999);
function controller_init()
{
    require_once 'controller/init.php';
}

if (!version_compare(phpversion(), '5.2.17', '<=')) {
    /*
     * Get the bootstrap!
     */
    if (file_exists(__DIR__.'/cmb2/init.php')) {
        require_once __DIR__.'/cmb2/init.php';
    } elseif (file_exists(__DIR__.'/CMB2/init.php')) {
        require_once __DIR__.'/CMB2/init.php';
    }
} else {
    /*
     * Get the bootstrap!
     */
    if (file_exists(ABSPATH.'wp-content/themes/skigawk/extensions/cmb2/init.php')) {
        require_once ABSPATH.'wp-content/themes/skigawk/extensions/cmb2/init.php';
    } elseif (file_exists(ABSPATH.'wp-content/themes/skigawk/extensions/CMB2/init.php')) {
        require_once ABSPATH.'wp-content/themes/skigawk/extensions/CMB2/init.php';
    }
}

function array_orderby()
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
            }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}


/**
 * [get_user_age description].
 *
 * @param [type] $user_id [description]
 *
 * @return [type] [description]
 */
function get_user_age($user_id)
{
    $change = get_the_author_meta('birthday', $user_id);
    $new = str_replace('/', '-', $change);
    $birthday = date('Y-m-d', strtotime($new));

    if (version_compare(phpversion(), '5.2.17', '<=')) {
        $birthday = new DateTime($birthday);
        $diff = diff($birthday);
    } else {
        $date = new DateTime($birthday);
        $diff = $date->diff(new DateTime());
    }

    return $diff->y;
}

/**
 * @param $birth
 *
 * @return string
 */
function get_etaria_user($birth)
{
    if ($birth instanceof DateTime) {
        $diff = $birth->diff(new DateTime());
    } else {
        $birthDate = new DateTime($birth); // Transforma a data de nascimento, para UTC
        $diff = $birthDate->diff(new DateTime()); // Verifica a diferença entre o dia atual e a data de nascimento
    }

    if ($diff->y <= 5 || $diff->y == 5  && $diff->m < 6) {
        $birth = 'mirim';
    }
    if ($diff->y >= 5 && $diff->y <= 8) {
        if ($diff->y == 8) {
            if ($diff->m <= 6) {
                $birth = 'infantil';
            }
        } elseif ($diff->y == 5) {
            if ($diff->m >= 7) {
                $birth = 'infantil';
            }
        } else {
            $birth = 'infantil';
        }
    }
    if ($diff->y >= 8 && $diff->y <= 11) {
        if ($diff->y == 8) {
            if ($diff->m >= 7) {
                $birth = 'junior';
            }
        } elseif ($diff->y == 11) {
            if ($diff->m <= 6) {
                $birth = 'junior';
            }
        } else {
            $birth = 'junior';
        }
    }
    if ($diff->y >= 11 && $diff->y <= 14) {
        if ($diff->y == 11) {
            if ($diff->m >= 7) {
                $birth = 'ijuvenil';
            }
        } elseif ($diff->y == 14) {
            if ($diff->m <= 6) {
                $birth = 'ijuvenil';
            }
        } else {
            $birth = 'ijuvenil';
        }
    }
    if ($diff->y >= 14 && $diff->y <= 17) {
        if ($diff->y == 14) {
            if ($diff->m >= 7) {
                $birth = 'juvenil';
            }
        } else {
            $birth = 'juvenil';
        }
    }
    if ($diff->y >= 18 && $diff->y <= 38) {
        if ($diff->y == 38) {
            if ($diff->m <= 6) {
                $birth = 'adulto';
            }
        } else {
            $birth = 'adulto';
        }
    }
    if ($diff->y >= 38) {
        $birth = 'senior';
    }

    return $birth;
}

/**
 * Automatiza os campos dos itens do Campeonato.
 *
 * @param int    $post_id   ID do post que quer o campo
 * @param string $fieldName Qual o nome do campo
 * @param string $type      Seta o tipo do retorno da função
 *
 * @return array||string Retorna um array ou uma string dependendo do $type escolhido
 */
function fieldCampeonato($post_id, $fieldName, $type)
{
    $category = get_post_meta($post_id, $fieldName, true);
    switch ($type) {
        case 'array':
            return $category;
        break;

        case 'dado':
            switch ($fieldName) {
            case '_vhr_nivel_luta':
                foreach ($category as $dado) {
                    if ($dado == 'avancado') {
                        $dado = 'avançado';
                    }
                    if ($dado == 'intermediario') {
                        $dado = 'intermediário';
                    }
                    $returnDado[] = ucfirst($dado);
                }
            break;
            case '_vhr_faixa_etaria':
                foreach ($category as $dado) {
                    if ($dado == 'ijuvenil') {
                        $dado = 'Infanto Juvenil';
                    }
                    if ($dado == 'senior') {
                        $dado = 'sênior';
                    }
                    $returnDado[] = ucfirst($dado);
                }
            break;
            }

            return implode(', ', $returnDado);
        break;
    }
}

/**
 * [addressCampeonato description].
 *
 * @param [type] $post_id [description]
 *
 * @return [type] [description]
 */
function addressCampeonato($post_id)
{
    $street = get_post_meta($post_id, '_vhr_street', true);
    $number = get_post_meta($post_id, '_vhr_street_number', true);
    $complement = get_post_meta($post_id, '_vhr_complement', true);
    $city = get_post_meta($post_id, '_vhr_city', true);
    $state = get_post_meta($post_id, '_vhr_state', true);

    if ($street !== '') {
        $address = $street.', '.(($number == '') ? 's/n' : $number).(($complement == '') ? '' : ' '.$complement).', '.$city.' - '.$state;
    } else {
        $address = 'Endereço não disponivel';
    }

    return $address;
}

/**
 * Verifica se o usuario está inscrito no campeonato.
 *
 * @param [INT] $user_id ID do usuario atual
 * @param [INT] $post_id ID do campeonato atual
 *
 * @return [BOLLEAN] Retorna se true se o usuario tiver inscrito e false se não tiver
 */
function userInsider($user_id, $post_id)
{
    $userList = get_post_meta($post_id, 'user_subscribers', true);

    if (!empty($userList)) {
        if (in_array($user_id, $userList)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * [get_id_subs_page description].
 *
 * @param [type] $post_id [description]
 *
 * @return [type] [description]
 */
function get_id_subs_page($post_id)
{
    $args = array(
            'posts_per_page' => 1,
            'post_type' => 'inscritos',
            'post_status' => 'publish',
            'meta_key' => 'camp_id',
            'meta_value' => $post_id,
    );

    $page = get_posts($args);

    return $page[0]->ID;
}

/**
 * [insidersPage description].
 *
 * @param [type] $user_id [description]
 * @param [type] $post_id [description]
 *
 * @return [type] [description]
 */
function insidersPage($user_id, $post_id)
{
    $page_id = get_id_subs_page($post_id);
    $old_meta = get_post_meta($post_id, 'user_subscribers', true);

    if (empty($old_meta)) {
        update_post_meta($post_id, 'user_subscribers', $user_id);
    } else {
        $new_meta = array_push($old_meta, $user_id);
        update_post_meta($post_id, 'user_subscribers', $new_meta);
    }
}

/**
 * Pegar o nível de permissão do usuário atual.
 *
 * @return string Retorna uma string com o nível do usuário
 */
function get_current_user_role()
{
    global $wp_roles;
    $current_user = wp_get_current_user();
    $roles = $current_user->roles;
    $role = array_shift($roles);

    return $role;
}

/**
 * [register_new_page description].
 *
 * @param [type] $new_page_title    [description]
 * @param [type] $new_page_content  [description]
 * @param [type] $new_page_template [description]
 *
 * @return [type] [description]
 */
function register_new_page($new_page_title, $new_page_content, $new_page_template)
{
    $new_page_id = null;

    $page_check = get_page_by_title($new_page_title);
    $new_page = array(
            'post_type' => 'page',
            'post_title' => $new_page_title,
            'post_content' => $new_page_content,
            'post_status' => 'publish',
            'post_author' => 1,
    );
    if (!isset($page_check->ID)) {
        $new_page_id = wp_insert_post($new_page);
        if (!empty($new_page_template)) {
            update_post_meta($new_page_id, '_wp_page_template', $new_page_template);
        }
    }

    return $new_page_id;
}

/**
 * [get_taxonomies_post_list description].
 *
 * @param [type] $post_id [description]
 *
 * @return [type] [description]
 */
function get_taxonomies_post_list($post_id)
{
    $post = get_post($post_id);

    $taxonomys_names = get_object_taxonomies($post, 'objects');

    foreach ($taxonomys_names as $taxonomy) {
        $return[] = $taxonomy->labels->name;
    }

    return $return;
}

/**
 * [pages_group_ids description].
 *
 * @return [type] [description]
 */
function pages_group_ids()
{
    $inscrever = get_page_by_title('Inscrever');
    $editarPerfil = get_page_by_title('Editar Perfil');
    $cadastro = get_page_by_title('Cadastro');
    $saveInscrito = get_page_by_title('Finalizar Cadastro');
    $campeonatos = get_page_by_title('Campeonatos');
    $eventos = get_page_by_title('Eventos');
    $login = get_page_by_title('Login');
    $perfil = get_page_by_title('Perfil');
    $inscricoes = get_page_by_title('Inscrições');
    $faq = get_page_by_title('FAQ');
    $fale = get_page_by_title('Fale Conosco');

    $pages = array(
        'inscrever' => $inscrever->ID,
        'editar-perfil' => $editarPerfil->ID,
        'cadastro' => $cadastro->ID,
        'save-inscrito' => $saveInscrito->ID,
        'campeonatos' => $campeonatos->ID,
        'login' => $login->ID,
        'perfil' => $perfil->ID,
        'eventos' => $eventos->ID,
        'inscricoes' => $inscricoes->ID,
        'fale' => $fale->ID,
        'faq' => $faq->ID,
    );

    return $pages;
}

function subscriberButton($user_id, $post_id, $typeEvent)
{
    $userAge = get_the_author_meta('fEtaria', $user_id);
    $postAge = fieldCampeonato($post_id, '_vhr_faixa_etaria', 'array');
    $userInsider = userInsider($user_id, $post_id);
    $pages_ids = pages_group_ids();

    if (get_current_user_role() === 'administrator') {
	    ob_start();
        ?>
			<div>
				<b>Acesse com uma conta não administradora</b>
			</div>
		<?php
	    return;
    }

    switch ($typeEvent) {
        case 'campeonatos':
	        ob_start();
            if (in_array($userAge, $postAge)) { // Verifica se a faixa etária está disponivel
                ?>
				<form action="<?=get_permalink($pages_ids['inscrever'])?>" method="post">
					<input type="hidden" name="camp_id" value="<?=$post_id?>">
					<input type="submit" class="btn btn-primary fp-button" value="Inscrever-se">
				</form>
				<?php
            } else { // Se a faixa etária dele não estiver disponivel
                ?>
				<div class="">
					<b>Faixa Etária não disponivel</b>
				</div>
		<?php
            }

	        return;
            break;
        case 'eventos':
            if ($userInsider) { // Se ele tiver inscrito, botão para realizar um nova inscrição
                ?>
				<b>Já está inscrito</b>
                <?php
            } else { // Se não tiver inscrito, botão para se inscrever pela primeira vez
                ?>
				<form action="<?=get_permalink($pages_ids['inscrever'])?>" method="post">
                    <input type="hidden" name="camp_id" value="<?=$post_id?>">
                    <input type="submit" class="btn btn-primary fp-button" value="Inscrever-se">
				</form>
                <?php
            }
            return;
	        break;
    }
}

function get_weight($modalidade, $id_peso, $sexo, $fetaria)
{
    $guardas = array(
      'feminino' => array(
        '1' => '00.1 - 20',
        '2' => '20.1 - 25',
        '3' => '25.1 - 30',
        '4' => '30.1 - 35',
        '5' => '35.1 - 40',
        '6' => '40.1 - 45',
        '7' => '45.1 - 50',
        '8' => '50.1 - 55',
        '9' => '55.1 - 60',
        '10' => '60.1 - 65',
        '11' => '65.1 - 70',
        '12' => '70.1 - 75',
        '13' => '75.1 - 999',
      ),
      'masculino' => array(
        '1' => '00.1 - 20',
        '2' => '20.1 - 25',
        '3' => '25.1 - 30',
        '4' => '30.1 - 35',
        '5' => '35.1 - 40',
        '6' => '40.1 - 45',
        '7' => '45.1 - 50',
        '8' => '50.1 - 55',
        '9' => '55.1 - 60',
        '10' => '60.1 - 65',
        '11' => '65.1 - 70',
        '12' => '70.1 - 75',
        '13' => '75.1 - 80',
        '14' => '80.1 - 85',
        '15' => '85.1 - 90',
        '16' => '90.1 - 999',
      ),
    );
    $cassetete = array(
      'masculino' => array(
        '1' => '00.1 - 20',
        '2' => '20.1 - 30',
        '3' => '30.1 - 40',
        '4' => '40.1 - 50',
        '5' => '50.1 - 60',
        '6' => '60.1 - 70',
        '7' => '70.1 - 80',
        '8' => '80.1 - 90',
        '9' => '90.1 - 999',
      ),
      'feminino' => array(
        '1' => '00.1 - 20',
        '2' => '20.1 - 26',
        '3' => '26.1 - 32',
        '4' => '32.1 - 38',
        '5' => '38.1 - 44',
        '6' => '44.1 - 50',
        '7' => '50.1 - 56',
        '8' => '56.1 - 62',
        '9' => '68.1 - 74',
        '10' => '74.1 - 999',
      ),
    );
    $semi = array(
      'feminino' => array(
        '1' => '00.1 - 20',
        '2' => '20.1 - 25',
        '3' => '25.1 - 30',
        '4' => '30.1 - 35',
        '5' => '35.1 - 40',
        '6' => '40.1 - 45',
        '7' => '45.1 - 50',
        '8' => '50.1 - 55',
        '9' => '55.1 - 60',
        '10' => '60.1 - 65',
        '11' => '65.1 - 70',
        '12' => '70.1 - 75',
        '13' => '75.1 - 999',
      ),
      'masculino' => array(
        '1' => '00.1 - 20',
        '2' => '20.1 - 27',
        '3' => '27.1 - 34',
        '4' => '34.1 - 41',
        '5' => '41.1 - 48',
        '6' => '48.1 - 55',
        '7' => '55.1 - 62',
        '8' => '62.1 - 69',
        '9' => '69.1 - 76',
        '10' => '76.1 - 83',
        '11' => '83.1 - 90',
        '12' => '90.1 - 97',
        '13' => '97.1 - 999',
      ),
    );
    $kuolight = array(
      'feminino' => array(
        '1' => '00.1 - 35',
        '2' => '35.1 - 40',
        '3' => '40.1 - 45',
        '4' => '45.1 - 50',
        '5' => '50.1 - 55',
        '6' => '55.1 - 60',
        '7' => '60.1 - 65',
        '8' => '65.1 - 70',
        '9' => '70.1 - 75',
        '10' => '75.1 - 80',
        '11' => '80.1 - 999',
      ),
      'masculino' => array(
        '1' => '00.1 - 34',
        '2' => '34.1 - 39',
        '3' => '39.1 - 44',
        '4' => '44.1 - 49',
        '5' => '49.1 - 54',
        '6' => '54.1 - 59',
        '7' => '59.1 - 64',
        '8' => '64.1 - 69',
        '9' => '69.1 - 74',
        '10' => '74.1 - 79',
        '11' => '79.1 - 84',
        '12' => '84.1 - 89',
        '13' => '89.1 - 94',
        '14' => '94.1 - 999',
      ),
    );
    $kuoleitai = array(
      'feminino' => array(
        '1' => '00.1 - 35',
        '2' => '35.1 - 40',
        '3' => '40.1 - 45',
        '4' => '45.1 - 50',
        '5' => '50.1 - 55',
        '6' => '55.1 - 60',
        '7' => '60.1 - 65',
        '8' => '65.1 - 70',
        '9' => '70.1 - 75',
        '10' => '75.1 - 80',
        '11' => '80.1 - 999',
      ),
      'masculino' => array(
        '1' => '00.1 - 35',
    		'2' => '35.1 - 40',
    		'3' => '40.1 - 45',
    		'4' => '45.1 - 50',
    		'5' => '40.1 - 55',
    		'6' => '55.1 - 60',
    		'7' => '60.1 - 65',
    		'8' => '65.1 - 70',
    		'9' => '70.1 - 75',
    		'10' => '75.1 - 80',
    		'11' => '80.1 - 85',
    		'12' => '85.1 - 90',
    		'13' => '90.1 - 95',
    		'14' => '95.1 - 100',
    		'15' => '100.1 - 999'
      ),
    );
    $wushu = array(
      'feminino' => array(
        '1' => '00.1 - 40',
				'2' => '40.1 - 45',
				'3' => '45.1 - 50',
        '4' => '50.1 - 55',
        '5' => '55.1 - 60',
        '6' => '60.1 - 65',
        '7' => '65.1 - 70',
        '8' => '70.1 - 75',
        '9' => '75.1 - 80',
        '10' => '80.1 - 85',
        '11' => '85.1 - 90',
        '12' => '90.1 - 999',
      ),
      'masculino' => array(
        '1' => '00.1 - 40',
				'2' => '40.1 - 45',
				'3' => '45.1 - 50',
        '4' => '50.1 - 55',
        '5' => '55.1 - 60',
        '6' => '60.1 - 65',
        '7' => '65.1 - 70',
        '8' => '70.1 - 75',
        '9' => '75.1 - 80',
        '10' => '80.1 - 85',
        '11' => '85.1 - 90',
        '12' => '90.1 - 999',
      ),
    );
    $shuai = array(
      'mirim' => array(
    		'feminino'	=> array(
    			'1'	=> '00.1 - 20',
    			'2'	=> '20.1 - 24',
    			'3'	=> '24.1 - 28',
    			'4'	=> '28.1 - 32',
    			'5'	=> '32.1 - 36',
    			'6'	=> '36.1 - 40',
    			'7'	=> '40.1 - 44',
    			'8'	=> '44.1 - 48'
    		),
    		'masculino'	=> array(
    			'1'	=> '00.1 - 20',
    			'2'	=> '20.1 - 24',
    			'3'	=> '24.1 - 28',
    			'4'	=> '28.1 - 32',
    			'5'	=> '32.1 - 36',
    			'6'	=> '36.1 - 40',
    			'7'	=> '40.1 - 44',
    			'8'	=> '44.1 - 48'
    		)
    	),
    	'infantil' => array(
    		'feminino'	=> array(
    			'1'	=> '00.1 - 20',
    			'2'	=> '20.1 - 24',
    			'3'	=> '24.1 - 28',
    			'4'	=> '28.1 - 32',
    			'5'	=> '32.1 - 36',
    			'6'	=> '36.1 - 40',
    			'7'	=> '40.1 - 44',
    			'8'	=> '44.1 - 48'
    		),
    		'masculino'	=> array(
    			'1'	=> '00.1 - 20',
    			'2'	=> '20.1 - 24',
    			'3'	=> '24.1 - 28',
    			'4'	=> '28.1 - 32',
    			'5'	=> '32.1 - 36',
    			'6'	=> '36.1 - 40',
    			'7'	=> '40.1 - 44',
    			'8'	=> '44.1 - 48'
    		)
    	),
      'junior' => array(
    		'feminino' => array(
    			'1' => '00.1 - 20',
    			'2' => '20.1 - 23',
    			'3' => '23.1 - 26',
    			'4' => '26.1 - 29',
    			'5' => '29.1 - 32',
    			'6' => '32.1 - 35',
    			'7' => '35.1 - 38',
    			'8' => '38.1 - 41',
    			'9' => '41.1 - 999'
    		) ,
    		'masculino' => array(
    			'1' => '00.1 - 20',
    			'2' => '20.1 - 23',
    			'3' => '23.1 - 26',
    			'4' => '26.1 - 29',
    			'5' => '29.1 - 32',
    			'6' => '32.1 - 35',
    			'7' => '35.1 - 38',
    			'8' => '38.1 - 41',
    			'9' => '41.1 - 999'
    		)
    	),
      'infanto-juvenil' => array(
        'feminino' => array(
          '1' => '00.1 - 20',
          '2' => '20.1 - 23',
          '3' => '23.1 - 26',
          '4' => '26.1 - 29',
          '5' => '29.1 - 32',
          '6' => '32.1 - 35',
          '7' => '35.1 - 38',
          '8' => '38.1 - 41',
          '9' => '41.1 - 999',
        ),
        'masculino' => array(
          '1' => '00.1 - 20',
          '2' => '20.1 - 23',
          '3' => '23.1 - 26',
          '4' => '26.1 - 29',
          '5' => '29.1 - 32',
          '6' => '32.1 - 35',
          '7' => '35.1 - 38',
          '8' => '38.1 - 41',
          '9' => '41.1 - 999',
        ),
      ),
      'juvenil' => array(
        'feminino' => array(
          '1' => '00.1 - 44',
          '2' => '44.1 - 47',
          '3' => '47.1 - 50',
          '4' => '50.1 - 54',
          '5' => '54.1 - 58',
          '6' => '58.1 - 62',
          '7' => '62.1 - 66',
          '8' => '66.1 - 70',
          '9' => '70.1 - 74',
          '10' => '74.1 - 82',
          '11' => '82.1 - 999',
        ),
        'masculino' => array(
          '1' => '00.1 - 46',
          '2' => '46.1 - 49',
          '3' => '49.1 - 52',
          '4' => '52.1 - 56',
          '5' => '56.1 - 61',
          '6' => '61.1 - 66',
          '7' => '66.1 - 72',
          '8' => '72.1 - 80',
          '9' => '80.1 - 90',
          '10' => '90.1 - 999',
        ),
      ),
      'adulto' => array(
        'feminino' => array(
          '1' => '00.1 - 44',
          '2' => '44.1 - 47',
          '3' => '47.1 - 50',
          '4' => '50.1 - 54',
          '5' => '54.1 - 58',
          '6' => '58.1 - 62',
          '7' => '62.1 - 66',
          '8' => '66.1 - 70',
          '9' => '70.1 - 74',
          '10' => '74.1 - 82',
          '11' => '82.1 - 999',
        ),
        'masculino' => array(
          '1' => '00.1 - 48',
          '2' => '48.1 - 52',
          '3' => '52.1 - 57',
          '4' => '57.1 - 62',
          '5' => '62.1 - 68',
          '6' => '68.1 - 74',
          '7' => '74.1 - 82',
          '8' => '82.1 - 90',
          '9' => '90.1 - 100',
          '10' => '100.1 - 999',
        ),
      ),
    );
    $muaythai = array(
      'feminino' => array(
        '1' => '00.1 - 55',
        '2' => '55.1 - 59',
        '3' => '59.1 - 63',
        '4' => '63.1 - 67',
        '5' => '67.1 - 71',
        '6' => '71.1 - 75',
        '7' => '75.1 - 79',
        '8' => '79.1 - 83',
        '9' => '83.1 - 87',
        '10' => '87.1 - 91',
        '11' => '91.1 - 95',
        '12' => '95.1 - 999',
      ),
      'masculino' => array(
        '1' => '00.1 - 55',
        '2' => '55.1 - 59',
        '3' => '59.1 - 63',
        '4' => '63.1 - 67',
        '5' => '67.1 - 71',
        '6' => '71.1 - 75',
        '7' => '75.1 - 79',
        '8' => '79.1 - 83',
        '9' => '83.1 - 87',
        '10' => '87.1 - 91',
        '11' => '91.1 - 95',
        '12' => '95.1 - 999',
      ),
    );
    $mma = array(
      'feminino' => array(
        '1' => '00.1 - 55',
        '2' => '55.1 - 59',
        '3' => '59.1 - 63',
        '4' => '63.1 - 67',
        '5' => '67.1 - 71',
        '6' => '71.1 - 75',
        '7' => '75.1 - 79',
        '8' => '79.1 - 83',
        '9' => '83.1 - 87',
        '10' => '87.1 - 91',
        '11' => '91.1 - 95',
        '12' => '95.1 - 999',
      ),
      'masculino' => array(
        '1' => '00.1 - 55',
        '2' => '55.1 - 59',
        '3' => '59.1 - 63',
        '4' => '63.1 - 67',
        '5' => '67.1 - 71',
        '6' => '71.1 - 75',
        '7' => '75.1 - 79',
        '8' => '79.1 - 83',
        '9' => '83.1 - 87',
        '10' => '87.1 - 91',
        '11' => '91.1 - 95',
        '12' => '95.1 - 999',
      ),
    );
    $cmma = array(
      'feminino' => array(
        '1' => '00.1 - 55',
        '2' => '55.1 - 59',
        '3' => '59.1 - 63',
        '4' => '63.1 - 67',
        '5' => '67.1 - 71',
        '6' => '71.1 - 75',
        '7' => '75.1 - 79',
        '8' => '79.1 - 83',
        '9' => '83.1 - 87',
        '10' => '87.1 - 91',
        '11' => '91.1 - 95',
        '12' => '95.1 - 999',
      ),
      'masculino' => array(
        '1' => '00.1 - 55',
        '2' => '55.1 - 59',
        '3' => '59.1 - 63',
        '4' => '63.1 - 67',
        '5' => '67.1 - 71',
        '6' => '71.1 - 75',
        '7' => '75.1 - 79',
        '8' => '79.1 - 83',
        '9' => '83.1 - 87',
        '10' => '87.1 - 91',
        '11' => '91.1 - 95',
        '12' => '95.1 - 999',
      ),
    );
    $formaslivres = array(
      '1' => 'Não-tradicional Arma Articulada',
      '2' => 'Não-tradicional Arma Bastão',
      '3' => 'Não-tradicional Arma Espada',
      '4' => 'Não-tradicional Arma Especial',
      '5' => 'Não-tradicional Arma Facão',
      '6' => 'Não-tradicional Arma Lança',
      '7' => 'Não-tradicional Outras Armas',
      '8' => 'Não-tradicional Forma sincronizada armas',
      '9' => 'Não-tradicional Forma sincronizada mãos',
      '10' => 'Não-tradicional Mãos Norte',
      '11' => 'Não-tradicional Mãos Sul',
      '12' => 'Não-tradicional Toi Tcha de armas',
      '13' => 'Não-tradicional Toi Tcha de mãos',
    );
    $formasinternas = array(
      '1' => 'Tai Chi Chuan 16 movimentos',
      '2' => 'Tai Chi Chuan 24 movimentos',
      '3' => 'Tai Chi Chuan 8 movimentos',
      '4' => 'Tai Chi Chuan Estilo Chen',
      '5' => 'Tai Chi Chuan Estilo Outros',
      '6' => 'Tai Chi Chuan Estilo Yang',
      '7' => 'Tai Chi Chuan Forma conjunto armas',
      '8' => 'Tai Chi Chuan Forma conjunto mãos',
      '9' => 'Tai Chi Chuan Outras armas',
      '10' => 'Tai Chi Chuan Tai Ji Jian 32',
      '11' => 'Tai Chi Chuan Tai Ji Jian 42 olimpico',
      '12' => 'Tai Chi Chuan Forma 42 Espada olímpica',
    );
    $formastradicionais = array(
      '1' => 'Tradicional Arma Articulada',
      '2' => 'Tradicional Arma Bastão',
      '3' => 'Tradicional Arma Espada',
      '4' => 'Tradicional Arma Especial',
      '5' => 'Tradicional Arma Facão',
      '6' => 'Tradicional Arma Lança',
      '7' => 'Tradicional Forma sincronizada armas',
      '8' => 'Tradicional Forma sincronizada mãos',
      '9' => 'Tradicional Mãos Choy Lay Fut',
      '10' => 'Tradicional Mãos Fei Hok Phai',
      '11' => 'Tradicional Mãos Fu Xin Chuan',
      '12' => 'Tradicional Mãos Garra de Águia',
      '13' => 'Tradicional Mãos Kon-Li-Kuen',
      '14' => 'Tradicional Mãos Louva-a-Deus',
      '15' => 'Tradicional Mãos Norte',
      '16' => 'Tradicional Mãos Pam Pou Kiu',
      '17' => 'Tradicional Mãos Sul',
      '18' => 'Tradicional Mãos Tam Tuei',
        '19' => 'Tradicional Mãos Tchon-I-Tchen',
      '20' => 'Tradicional Toi Tcha de armas',
      '21' => 'Tradicional Toi Tcha de mãos',
    );
    $formasolimpicas = array(
      '1' => 'NAN QUAN PUNHO DO SUL',
      '2' => 'CHANG QUAN PUNHO DO NORTE',
      '3' => 'DAO SHU FACÃO DO NORTE',
      '4' => 'NAN DAO FACÃO DO SUL',
      '5' => 'BASTÃO DO SUL',
      '6' => 'BASTÃO DO NORTE',
      '7' => 'ESPADA',
      '8' => 'ESPADA DE TAI CHI FORMA 42',
      '9' => 'LANÇA',
      '10' => 'TAI CHI FORMA 42 MÃOS',
    );
    $tree = array(
        '1' => 'Arma Longa',
        '2' => 'Arma Média',
        '3' => 'Mãos Livres',
    );
    $submission = array(
      'feminino' => array(
          '1' => '00.1 - 20',
          '2' => '20.1 - 25',
          '3' => '25.1 - 30',
          '4' => '30.1 - 35',
          '5' => '35.1 - 40',
          '6' => '40.1 - 45',
          '7' => '45.1 - 50',
          '8' => '50.1 - 55',
          '9' => '55.1 - 60',
          '10' => '60.1 - 65',
          '11' => '65.1 - 70',
          '12' => '70.1 - 75',
          '13' => '75.1 - 999',
      ),
      'masculino' => array(
          '1' => '00.1 - 20',
          '2' => '20.1 - 25',
          '3' => '25.1 - 30',
          '4' => '30.1 - 35',
          '5' => '35.1 - 40',
          '6' => '40.1 - 45',
          '7' => '45.1 - 50',
          '8' => '50.1 - 55',
          '9' => '55.1 - 60',
          '10' => '60.1 - 65',
          '11' => '65.1 - 70',
          '12' => '70.1 - 75',
          '13' => '75.1 - 80',
          '14' => '80.1 - 85',
          '15' => '85.1 - 90',
          '16' => '90.1 - 999',
      ),
    );

	$desafio = array(
		'0'   => 'Desafio Bruce Lee'
	);

	switch ($modalidade) {
      case 'cassetete':
        if ($sexo == 'm') {
            $data = $cassetete['masculino'][$id_peso];
        } elseif ($sexo == 'f') {
            $data = $cassetete['feminino'][$id_peso];
        }
        $array = $data;
      break;
      case 'guardas':
        if ($sexo == 'm') {
            $data = $guardas['masculino'][$id_peso];
        } elseif ($sexo == 'f') {
            $data = $guardas['feminino'][$id_peso];
        }
        $array = $data;
      break;
      case 'semi':
        if ($sexo == 'm') {
            $data = $semi['masculino'][$id_peso];
        } elseif ($sexo == 'f') {
            $data = $semi['feminino'][$id_peso];
        }
        $array = $data;
      break;
      case 'kuolight':
        if ($sexo == 'm') {
            $data = $kuolight['masculino'][$id_peso];
        } elseif ($sexo == 'f') {
            $data = $kuolight['feminino'][$id_peso];
        }
        $array = $data;
      break;
      case 'kuoleitai':
        if ($sexo == 'm') {
            $data = $kuoleitai['masculino'][$id_peso];
        } elseif ($sexo == 'f') {
            $data = $kuoleitai['feminino'][$id_peso];
        }
        $array = $data;
      break;
      case 'guardas':
        if ($sexo == 'm') {
            $data = $guardas['masculino'][$id_peso];
        } elseif ($sexo == 'f') {
            $data = $guardas['feminino'][$id_peso];
        }
        $array = $data;
      break;
      case 'wushu':
      case 'sanda':
        if ($sexo == 'm') {
            $data = $wushu['masculino'][$id_peso];
        } elseif ($sexo == 'f') {
            $data = $wushu['feminino'][$id_peso];
        }
        $array = $data;
      break;
      case 'muaythai-a':
      case 'muaythai-p':
            if ($sexo == 'm') {
                $data = $muaythai['masculino'][$id_peso];
            } elseif ($sexo == 'f') {
                $data = $muaythai['feminino'][$id_peso];
            }
            $array = $data;
            break;
      case 'shuai':
        switch ($fetaria) {
          case 'mirim':
            if ($sexo == 'm') {
                $data = $shuai[$fetaria]['masculino'][$id_peso];
            } elseif ($sexo == 'f') {
                $data = $shuai[$fetaria]['feminino'][$id_peso];
            }
            break;
          case 'infantil':
            if ($sexo == 'm') {
                $data = $shuai[$fetaria]['masculino'][$id_peso];
            } elseif ($sexo == 'f') {
                $data = $shuai[$fetaria]['feminino'][$id_peso];
            }
            break;
          case 'junior':
            if ($sexo == 'm') {
                $data = $shuai[$fetaria]['masculino'][$id_peso];
            } elseif ($sexo == 'f') {
                $data = $shuai[$fetaria]['feminino'][$id_peso];
            }
            break;
          case 'ijuvenil':
            if ($sexo == 'm') {
                $data = $shuai['infanto-juvenil']['masculino'][$id_peso];
            } elseif ($sexo == 'f') {
                $data = $shuai['infanto-juvenil']['feminino'][$id_peso];
            }
          break;
          case 'juvenil':
            if ($sexo == 'm') {
                $data = $shuai['juvenil']['masculino'][$id_peso];
            } elseif ($sexo == 'f') {
                $data = $shuai['juvenil']['feminino'][$id_peso];
            }
          break;
          case 'senior':
          case 'adulto':
            if ($sexo == 'm') {
                $data = $shuai['adulto']['masculino'][$id_peso];
            } elseif ($sexo == 'f') {
                $data = $shuai['adulto']['feminino'][$id_peso];
            }
          break;
        }
        $array = $data;
      break;
      case 'mma':
        if ($sexo == 'm') {
            $data = $mma['masculino'][$id_peso];
        } elseif ($sexo == 'f') {
            $data = $mma['feminino'][$id_peso];
        }
        $array = $data;
      break;
      case 'cmma':
        if ($sexo == 'm') {
            $data = $cmma['masculino'][$id_peso];
        } elseif ($sexo == 'f') {
            $data = $cmma['feminino'][$id_peso];
        }
        $array = $data;
      break;
        case 'formaslivres':
            $array = $formaslivres[$id_peso];
        break;
        case 'formasinternas':
            $array = $formasinternas[$id_peso];
        break;
        case 'formastradicionais':
            $array = $formastradicionais[$id_peso];
        break;
        case 'formasolimpicas':
            $array = $formasolimpicas[$id_peso];
        break;
        case 'tree':
        		$array = $tree[$id_peso];
    		break;
        case 'submission-infantil':
        case 'submission-adulto':
            if ($sexo == 'm') {
                $data = $submission['masculino'][$id_peso];
            } elseif ($sexo == 'f') {
                  $data = $submission['feminino'][$id_peso];
            }
            $array = $data;
            break;
        case 'desafio-bruce':
            $array = $desafio[$id_peso];
            break;
    }

    return $array;
}

/**
 * [get_excerpt description].
 *
 * @param [type] $post_id [description]
 *
 * @return [type] [description]
 */
function get_excerpt($post_id)
{
    global $post;
    $save_post = $post;
    $post = get_post($post_id);
    $output = get_the_excerpt();
    $post = $save_post;

    return $output;
}

function removeAcentos($string, $slug = false)
{
    $string = strtolower($string);
  // Código ASCII das vogais
  $ascii['a'] = range(224, 230);
    $ascii['e'] = range(232, 235);
    $ascii['i'] = range(236, 239);
    $ascii['o'] = array_merge(range(242, 246), array(240, 248));
    $ascii['u'] = range(249, 252);
  // Código ASCII dos outros caracteres
  $ascii['b'] = array(223);
    $ascii['c'] = array(231);
    $ascii['d'] = array(208);
    $ascii['n'] = array(241);
    $ascii['y'] = array(253, 255);
    foreach ($ascii as $key => $item) {
        $acentos = '';
        foreach ($item as $codigo) {
            $acentos .= chr($codigo);
        }
        $troca[$key] = '/['.$acentos.']/i';
    }
    $string = preg_replace(array_values($troca), array_keys($troca), $string);
  // Slug?
  if ($slug) {
      // Troca tudo que não for letra ou número por um caractere ($slug)
    $string = preg_replace('/[^a-z0-9]/i', $slug, $string);
    // Tira os caracteres ($slug) repetidos
    $string = preg_replace('/'.$slug.'{2,}/i', $slug, $string);
      $string = trim($string, $slug);
  }

    return $string;
}

/**
 * @param $date
 *
 * @return DateTime|string
 */
function get_exp_user($date)
{
    $exp = DateTime::createFromFormat('d/m/Y', $date); // Transforma a data de nascimento, para UTC
    $diff = $exp->diff(new DateTime());

    if ($diff->y < 1) {
        $exp = 'novato';
    } elseif ($diff->y == 1 || $diff->y <= 2 && $diff->d == 0 && $diff->m == 0) {
        $exp = 'iniciante';
    } elseif ($diff->y == 2 || $diff->y <= 3 && $diff->d < 31 && $diff->m <= 12) {
        $exp = 'intermediario';
    } elseif ($diff->y >= 4 && $diff->d >= 0) {
        $exp = 'avancado';
    }

    return $exp;
}

/**
 * @param $image_url
 *
 * @return mixed
 */
function pippin_get_image_id($image_url)
{
    global $wpdb;
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url));

    return $attachment[0];
}

/**
 * @param $name
 *
 * @return string
 */
function get_modalidade_file($name)
{
    switch ($name) {    case 'cassetete':
        case 'guardas':
             $url = 'http://www.fpsckf.com.br/wp-content/uploads/2016/05/05-Regras-Cassetete-e-Guardas-e-Esquivas.docx';
            break;
        case 'semi':
            break;
        case 'kuolight':
            $url = 'http://www.skigawk.com.br/wp-content/uploads/2015/11/06-REGRAS-PARA-LUTA-LEI-TAI-LIGHT.pdf';
            break;
        case 'kuoleitai':
            $url = 'http://www.fpsckf.com.br/wp-content/uploads/2016/05/10-Informativo-Lei-Tai.pdf';
            break;
        case 'wushu':
            $url = 'http://www.skigawk.com.br/wp-content/uploads/2015/11/11-REGRAS-PARA-LUTA-SAN-SOU.pdf';
            break;
        case 'muaythai' :
            break;
        case 'shuai':
            $url = 'http://www.fpsckf.com.br/wp-content/uploads/2016/05/13-Regras-de-shuai-jiao.docx';
            break;
        case 'mma':
            break;
        case 'cmma':
            $url = 'http://www.fpsckf.com.br/wp-content/uploads/2016/05/07-REGRAS-CMMA.docx';
            break;
        case 'formaslivres':
        case 'formasinternas':
        case 'formastradicionais':
        case 'formasolimpicas':
            $url = 'http://www.skigawk.com.br/wp-content/uploads/2015/11/03-REGRAS-PARA-FORMAS.pdf';
            break;
    }

    return $url;
}

/**
 * @param $url
 */
function scriptRedirect($url)
{
    ?>
	<script>
		jQuery(document).ready(function(){
			window.location= <?php echo "'".htmlspecialchars_decode($url)."'";
    ?>;
		});
	</script>
<?php

}


function send_inscricao($user_id){
  $name = get_the_author_meta( 'display_name', $user_id );
  $mail = get_the_author_meta('user_email', $user_id);
  $admin_email = get_option('admin_email');
  $home = esc_url(home_url());
  $perfil = esc_url(home_url( '/login' ));

  $subject = "Cadastro na Skigawk";
  $message = "
  <div>
    <header style='width: auto; display: flex; background-color: #f1c40f;'>
      <a href='{$home}' style='margin: 0 auto;'>
        <img src='http://skigawk.com.br/testes/wordpress/wp-content/uploads/2016/07/logo-home2.png' alt='SKIGAWK' title='Skigawk' />
      </a>
    </header>
    <main>
      <p>
        Olá <b>{$name}</b>, você acaba de realizar o cadastro no site da <b>Skigawk</b> para visualizar seu perfil <a href='{$perfil}' alt='Perfil'>clique aqui</a>.
      </p>
    </main>
    <footer>
      <i>Se não foi você que realizou este cadastro, por favor entre contato com <a href='mailto:adriel@skigawk.com.br'>adriel@skigawk.com.br</a>.</i><br>
      <b><i>Mensagem gerada automaticamente, não responsa este e-mail.</i></b>
    </footer>
  </div>
  ";

  $headers[] = "From: Skigawk <{$admin_email}>". "\r\n";
  $headers[] = "MIME-Version: 1.0";
  $headers[] = "Content-type:text/html;charset=UTF-8";

  wp_mail( $mail, $subject, $message, $headers);
}
