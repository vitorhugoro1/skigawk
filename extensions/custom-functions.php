<?php
if (file_exists(__DIR__ . '/admin-config/admin-pagamentos.php')) {
    require __DIR__ . '/admin-config/admin-pagamentos.php';
}

if (!function_exists('wp_handle_upload')) {
    require_once ABSPATH . 'wp-admin/includes/file.php';
}

include get_template_directory() . '/actions/action-editar-perfil.php';
include get_template_directory() . '/actions/action-cadastrar-usuario.php';
include get_template_directory() . '/actions/action-cadastrar-evento.php';
include get_template_directory() . '/actions/action-generate.php';
include get_template_directory() . '/actions/action-user-terms.php';

add_action('admin_enqueue_scripts', 'admin_functions');
function admin_functions()
{
    wp_enqueue_script('maskinput', get_template_directory_uri() . '/js/jquery.mask.min.js', array('jquery'), '', true);
    wp_enqueue_style('modcss', get_template_directory_uri() . '/css/mod.css');
    wp_enqueue_script('mods', get_template_directory_uri() . '/js/adminfunctions.js', array('jquery'), '', true);
}

add_action('wp_enqueue_scripts', 'enqueue_scripts_and_styles');
function enqueue_scripts_and_styles()
{
    wp_enqueue_script('jquery', get_template_directory_uri() . '/js/jquery-2.1.4.min.js', array('jquery'), '2.1.4', true);
    wp_enqueue_script('maskinput', get_template_directory_uri() . '/js/jquery.mask.min.js', array('jquery'), '', true);
    wp_enqueue_script('mods', get_template_directory_uri() . '/js/mod.js', array('jquery'), '', true);
    wp_enqueue_script('subscribe', get_template_directory_uri() . '/js/subscribe.js', array('jquery'), '', true);
    wp_enqueue_style('toogle-btn', get_template_directory_uri() . '/css/mdtoggle.min.css', '', true);
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
    if (file_exists(__DIR__ . '/cmb2/init.php')) {
        require_once __DIR__ . '/cmb2/init.php';
    } elseif (file_exists(__DIR__ . '/CMB2/init.php')) {
        require_once __DIR__ . '/CMB2/init.php';
    }
} else {
    /*
     * Get the bootstrap!
     */
    if (file_exists(ABSPATH . 'wp-content/themes/skigawk/extensions/cmb2/init.php')) {
        require_once ABSPATH . 'wp-content/themes/skigawk/extensions/cmb2/init.php';
    } elseif (file_exists(ABSPATH . 'wp-content/themes/skigawk/extensions/CMB2/init.php')) {
        require_once ABSPATH . 'wp-content/themes/skigawk/extensions/CMB2/init.php';
    }
}

function array_orderby()
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row) {
                $tmp[$key] = $row[$field];
            }

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

    if ($diff->y <= 5 || $diff->y == 5 && $diff->m < 6) {
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
 * @return array|string|null Retorna um array ou uma string dependendo do $type escolhido
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
        $address = $street . ', ' . (($number == '') ? 's/n' : $number) . (($complement == '') ? '' : ' ' . $complement) . ', ' . $city . ' - ' . $state;
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

function get_bank_account_text($echo = false)
{
    $options = unserialize(get_option('deposito'));

    $text = sprintf(
        '%s<br> %s<br>Agência: %s<br>Conta: %s',
        $options['banco'],
        $options['beneficiario'],
        $options['agencia'],
        $options['conta']
    );

    if (!$echo) {
        return $text;
    }

    echo $text;
}

function get_user_post_type_subscribes($userId)
{
    /** @var array */
    $subscribes = get_the_author_meta('insiders', $userId);

    $list = array();

    $postIds = array_unique(array_keys($subscribes));

    $list = array_map(function ($postID) {
        return [
            'type' => get_post_type($postID),
            'id' => $postID,
        ];
    }, $postIds);

    sort($list);

    return $list;
}

function get_user_subscribes($echo = false)
{
    $user = wp_get_current_user();
    $gender = get_the_author_meta('sex', $user->ID);
    $ageing = get_the_author_meta('fEtaria', $user->ID);
    $subscribes = get_the_author_meta('insiders', $user->ID);
    $rules = form_style_rules();
    $pages_id = pages_group_ids();
    $text = "";

    // Se não houver inscrições
    if (empty($subscribes)) {
        $text .= "<p><b>Sem inscrições no momento.</b></p>";

        $text .= "<p>Clique em um dos itens abaixo para acessar as páginas de postagens e realizar inscrições.</p>";

        $text .= '<p class="aligncenter">';
        $text .= sprintf('<a class="btn" href="%s">Campeonatos</a>', get_permalink($pages_id['campeonatos']));
        $text .= sprintf('<a class="btn" href="%s">Eventos</a>', get_permalink($pages_id['eventos']));
        $text .= "</p>";

        if (!$echo) {
            return $text;
        }

        echo $text;
        return;
    }

    // Se tiver inscrições ativo
    $posts = get_user_post_type_subscribes($user->ID);

    $championships = array_filter($posts, function ($post) {
        return $post['type'] === 'campeonatos';
    });

    $events = array_filter($posts, function ($post) {
        return $post['type'] === 'eventos';
    });

    if (!empty($championships)) {
        $table = '<table class="table-bordered table-striped">';

        $table .= '<thead>
                        <tr>
                            <td class="text-center" colspan="3">
                                <b>Inscrições</b>
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td>
                                <b>Campeonatos</b>
                            </td>
                            <td>
                                <b>Categorias / Peso ou Forma(s)</b>
                            </td>
                            <td>
                                <b>Pagamentos</b>
                            </td>
                        </tr>
                    </thead>';

        $table .= "<tbody>";

        foreach ($championships as $championship) {
            $tbody = '<tr class="text-center">';

            // Link para visualizar outros inscritos
            $link = sprintf("%s?post_id=%s", home_url('/visualiza-inscrito'), $championship['id']);
            $tbody .= sprintf(
                '<td><a href="%s" target="_blank">%s</a></td>',
                $link,
                get_the_title($championship['id'])
            );

            // Mostra informações da inscrição
            // @TODO Alterar para página externa com detalhes
            $value = $subscribes[$championship['id']];
            $tbody .= '<td><ul class="list-inscrito">';
            foreach ($value['categorias'] as $cat_slug => $cat_data) {
                $category = get_term_by('slug', $cat_slug, 'categoria');
                $tbody .= '<li>';
                if (in_array($cat_slug, form_style_data())) {
                    $count = count($cat_data);
                    $c = 0;
                    $tbody .= sprintf('<b>%s</b>', $category->name);
                    $tbody .= '<ul>';

                    foreach ($cat_data as $item) {
                        $c++;
                        $forma = get_weight($cat_slug, $item['peso'], $gender, $ageing);
                        $tbody .= '<li>';
                        $tbody .= $forma;

                        if (array_key_exists($cat_slug, $rules['withGroup'])) {
                            if (in_array($item['peso'], $rules['withGroup'][$cat_slug])) {
                                if (isset($item['groups'])) {
                                    $tbody .= ' <i>' . implode(", ", array_filter($item['groups'])) . '</i>';
                                }
                            }
                        }

                        $tbody .= (empty($forma)) ? '' : (($c == $count) ? '.' : ', ');
                        $tbody .= '</li>';
                    }

                    $tbody .= '</ul>';
                }

                if (!in_array($cat_slug, form_style_data())) {
                    $cat = (isset($cat_data[0])) ? $cat_data[0] : $cat_data;

                    if (in_array($cat_slug, $rules['withWeapon'])) {
                        $tbody .= sprintf(
                            '<b>%s</b> / %s <b>Arma:</b> %s',
                            $category->name,
                            get_weight($cat_slug, $cat['peso'], $gender, $ageing),
                            $cat['arma']
                        );
                    }

                    // @todo Adicionar os custom teams
                    if (!in_array($cat_slug, $rules['withWeapon'])) {
                        $tbody .= sprintf(
                            '<b>%s</b> / %s Kg',
                            $category->name,
                            get_weight($cat_slug, $cat['peso'], $gender, $ageing)
                        );
                    }
                }

                $tbody .= '</li>';
            }
            $tbody .= "</ul></td>";

            // Mostra informações sobre o pagamento
            $tbody .= '<td><ul class="list-inscrito">';

            foreach ($value['categorias'] as $slug => $data) {
                $term = get_term_by('slug', $slug, 'categoria');

                if (in_array($slug, form_style_data())) {
                    if (!empty($data)) {
                        foreach ($data as $item) {
                            $pagamento = (isset($data['id_pagamento'])) ? $item['id_pagamento'] : $item[0]['id_pagamento'];
                            $id_pag[] = $pagamento;
                        }

                        $unique = array_unique($id_pag);
                        $ids = array_filter($unique);
                        $string_ids = implode(', ', $ids);
                        $tbody .= '<li>';
                        $tbody .= sprintf('<b>%s</b> - %s', $term->name, $string_ids);
                        $tbody .= '</li>';
                    }
                }

                if (!in_array($slug, form_style_data())) {
                    $pagamento = (isset($data['id_pagamento'])) ? $data['id_pagamento'] : $data[0]['id_pagamento'];
                    $tbody .= '<li>';
                    $tbody .= sprintf('<b>%s</b> - %s', $term->name, $pagamento);
                    $tbody .= '</li>';
                }
            }

            $tbody .= "</ul></td>";

            $tbody .= "</tr>";

            $table .= $tbody;
        }

        $table .= "</tbody>";
        $table .= '<caption>Campeonatos</caption></table>';

        $text .= $table;
    }

    if (!empty($events)) {
        $table = "";

        $table .= '<table class="table-bordered table-striped">';

        $table .= '<thead>
                    <tr>
                      <td class="text-center" colspan="2">
                        <b>Inscrições</b>
                      </td>
                    </tr>
                    <tr class="text-center">
                      <td>
                        <b>Eventos</b>
                      </td>
                      <td>
                        <b>Modalidades</b>
                      </td>
                    </tr>
                </thead>';

        $tbody = "<tbody>";

        foreach ($events as $event) {
            $tbody .= "<tr>";
            $value = $subscribes[$event['id']];

            // Visualiza o link do evento
            $tbody .= sprintf(
                '<td><a href="%s" target="_blank">%s</a></td>',
                get_permalink($event['id']),
                get_the_title($event['id'])
            );

            $paymentText = '';

            foreach ($value as $key => $data) {
                if ($key === 'pagamento') {
                    $category = ucfirst($data['category']);
                    $valor = (isset($data['valor'])) ? $data['valor'] : 'N/A';
                    $paymentText .= sprintf('%s - R$%s', $category, $valor);
                }
            }

            $tbody .= sprintf("<td>%s</td>", $paymentText);
            $tbody .= "</tr>";
        }

        $tbody .= '</tbody>';
        $table .= $tbody;
        $table .= '<caption>Eventos</caption></table>';
        $text .= $table;
    }

    if (!$echo) {
        return $text;
    }

    echo $text;
}

function get_event_price_text($echo = true)
{
    $isBilled = get_post_meta($_POST['camp_id'], '_vhr_price_option', true);
    $response = "";

    if ($isBilled === 's') {
        $response .= sprintf(
            'Valor da inscrição para o primeiro <b>Estilo</b>: <b>R$ %s </b><br>',
            get_post_meta($_POST['camp_id'], '_vhr_price', true)
        );

        if (get_post_meta($_POST['camp_id'], '_vhr_price_extra', true) !== '0.00') {
            $response .= sprintf(
                'Valor da inscrição para cada <b>Estilo</b> adicional: <b>R$ %s </b>',
                get_post_meta($_POST['camp_id'], '_vhr_price_extra', true)
            );
        }

        $response .= '<div class="text-bold mt-10">O valor total será mostrado na página seguinte</div>';
    }

    if ($isBilled !== 's') {
        $response .= '<div class="text-bold mt-10">Campeonato Gratuito</div>';
    }

    if (!$echo) {
        return $response;
    }

    echo $response;
}

function children_ageing()
{
    return [
        'mirim',
        'infantil',
        'ijuvenil',
        'junior',
    ];
}

function children_autorization_file_text($echo = true)
{
    $postData = $_POST;
    $user = wp_get_current_user();
    $ageing = get_the_author_meta('fEtaria', $user->ID);

    if (!in_array($ageing, children_ageing())) {
        return;
    }

    $file = get_post_meta($postData['camp_id'], '_vhr_autorizacao_file_id');

    if (empty($file)) {
        return;
    }

    $parsed = parse_url(wp_get_attachment_url($file));
    $url = dirname($parsed['path']) . '/' . rawurlencode(basename($parsed['path']));

    $text = "<p>";
    $text .= "Autorização paulista para atleta menor de idade:";
    $text .= sprintf('<a href="%s" target="_blank"> Autorização para Atleta Menor de Idade</a>', $url);
    $text .= "</p>";

    if (!$echo) {
        return $text;
    }

    echo $text;
}

function show_modalities_rules_text($echo = true)
{
    $postData = $_POST;
    $user = wp_get_current_user();
    $text = '';
    $text .= "<p>";
    $text .= "Regras por estilo (Arquivo para Download de acordo com os estilos disponiveis)";

    $text .= "<ul>";

    $list = wp_get_post_terms($postData['camp_id'], 'categoria', array('fields' => 'all'));
    foreach ($list as $term) {
        $in = get_the_author_meta('insiders', $user->ID);

        if (empty($in) || !array_key_exists($postData['camp_id'], $in)) {
            $text .= '<li>';
            $text .= '<a href="' . get_modalidade_file($term->slug) . '">' . $term->name . '</a>';
            $text .= '</li>';

            continue;
        }

        foreach ($in[$postData['camp_id']] as $k => $i) {
            if ($k === 'categorias') {
                if (!array_key_exists($term->slug, $i)) {
                    $text .= '<li>';
                    $text .= '<a href="' . get_modalidade_file($term->slug) . '">' . $term->name . '</a>';
                    $text .= '</li>';
                }
            }
        }
    }

    $text .= "</ul>";

    $text .= "</p>";

    if (!$echo) {
        return $text;
    }

    echo $text;
}

function weight_data()
{
    return [
        'judo' => [
            'feminino' => [
                '1' => '00.1 - 22',
                '2' => '22.1 - 28',
                '3' => '28.1 - 32',
                '4' => '32.1 - 37',
                '5' => '37.1 - 42',
                '6' => '42.1 - 47',
                '7' => '47.1 - 52',
                '8' => '52.1 - 55',
                '9' => '55.1 - 60',
                '10' => '60.1 - 65',
                '11' => '65.1 - 72',
                '12' => '72.1 - 80',
                '13' => '80.1 - 90',
                '14' => '90.1 - 100',
                '15' => '100.1 - 999',
            ],
            'masculino' => [
                '1' => '00.1 - 22',
                '2' => '22.1 - 28',
                '3' => '28.1 - 32',
                '4' => '32.1 - 37',
                '5' => '37.1 - 42',
                '6' => '42.1 - 47',
                '7' => '47.1 - 52',
                '8' => '52.1 - 55',
                '9' => '55.1 - 60',
                '10' => '60.1 - 65',
                '11' => '65.1 - 72',
                '12' => '72.1 - 80',
                '13' => '80.1 - 90',
                '14' => '90.1 - 100',
                '15' => '100.1 - 999',
            ],
        ],
        'boxe-amador' => [
            'feminino' => [
                '1' => '00.1 - 22',
                '2' => '22.1 - 27',
                '3' => '27.1 - 32',
                '4' => '32.1 - 37',
                '5' => '37.1 - 42',
                '6' => '42.1 - 46.27',
                '7' => '46.3 - 47.63',
                '8' => '47.7 - 48.99',
                '9' => '50 - 50.35',
                '10' => '50.4 - 51.71',
                '11' => '51.72 - 53.52',
                '12' => '53.53 - 55.34',
                '13' => '55.35 - 57.15',
                '14' => '57.16 - 58.97',
                '15' => '58.98 - 61.23',
                '16' => '61.24 - 63.5',
                '17' => '63.51 - 66.68',
                '18' => '66.69 - 69.85',
                '19' => '69.86 - 73.3',
                '20' => '73.31 - 76.2',
                '21' => '76.21 - 79.38',
                '22' => '79.39 - 90.72',
                '23' => '90.73 - 999',
            ],
            'masculino' => [
                '1' => '00.1 - 22',
                '2' => '22.1 - 27',
                '3' => '27.1 - 32',
                '4' => '32.1 - 37',
                '5' => '37.1 - 42',
                '6' => '42.1 - 46.27',
                '7' => '46.3 - 47.63',
                '8' => '47.7 - 48.99',
                '9' => '50 - 50.35',
                '10' => '50.4 - 51.71',
                '11' => '51.72 - 53.52',
                '12' => '53.53 - 55.34',
                '13' => '55.35 - 57.15',
                '14' => '57.16 - 58.97',
                '15' => '58.98 - 61.23',
                '16' => '61.24 - 63.5',
                '17' => '63.51 - 66.68',
                '18' => '66.69 - 69.85',
                '19' => '69.86 - 73.3',
                '20' => '73.31 - 76.2',
                '21' => '76.21 - 79.38',
                '22' => '79.39 - 90.72',
                '23' => '90.73 - 999',
            ],
        ],
        'boxe-profissional' => [
            'feminino' => [
                '1' => '00.1 - 22',
                '2' => '22.1 - 27',
                '3' => '27.1 - 32',
                '4' => '32.1 - 37',
                '5' => '37.1 - 42',
                '6' => '42.1 - 46.27',
                '7' => '46.3 - 47.63',
                '8' => '47.7 - 48.99',
                '9' => '50 - 50.35',
                '10' => '50.4 - 51.71',
                '11' => '51.72 - 53.52',
                '12' => '53.53 - 55.34',
                '13' => '55.35 - 57.15',
                '14' => '57.16 - 58.97',
                '15' => '58.98 - 61.23',
                '16' => '61.24 - 63.5',
                '17' => '63.51 - 66.68',
                '18' => '66.69 - 69.85',
                '19' => '69.86 - 73.3',
                '20' => '73.31 - 76.2',
                '21' => '76.21 - 79.38',
                '22' => '79.39 - 90.72',
                '23' => '90.73 - 999',
            ],
            'masculino' => [
                '1' => '00.1 - 22',
                '2' => '22.1 - 27',
                '3' => '27.1 - 32',
                '4' => '32.1 - 37',
                '5' => '37.1 - 42',
                '6' => '42.1 - 46.27',
                '7' => '46.3 - 47.63',
                '8' => '47.7 - 48.99',
                '9' => '50 - 50.35',
                '10' => '50.4 - 51.71',
                '11' => '51.72 - 53.52',
                '12' => '53.53 - 55.34',
                '13' => '55.35 - 57.15',
                '14' => '57.16 - 58.97',
                '15' => '58.98 - 61.23',
                '16' => '61.24 - 63.5',
                '17' => '63.51 - 66.68',
                '18' => '66.69 - 69.85',
                '19' => '69.86 - 73.3',
                '20' => '73.31 - 76.2',
                '21' => '76.21 - 79.38',
                '22' => '79.39 - 90.72',
                '23' => '90.73 - 999',
            ],
        ],
        'kickboxing' => [
            'feminino' => [
                '1' => '00.1 - 22',
                '2' => '22.1 - 27',
                '3' => '27.1 - 32',
                '4' => '32.1 - 37',
                '5' => '37.1 - 42',
                '6' => '42.1 - 47',
                '7' => '47.1 - 52',
                '8' => '52.1 - 57',
                '9' => '57.1 - 63',
                '10' => '63.1 - 69',
                '11' => '69.1 - 75',
                '12' => '75.1 - 79',
                '13' => '79.1 - 84',
                '14' => '84.1 - 90',
                '15' => '90.1 - 999',
            ],
            'masculino' => [
                '1' => '00.1 - 22',
                '2' => '22.1 - 27',
                '3' => '27.1 - 32',
                '4' => '32.1 - 37',
                '5' => '37.1 - 42',
                '6' => '42.1 - 47',
                '7' => '47.1 - 52',
                '8' => '52.1 - 57',
                '9' => '57.1 - 63',
                '10' => '63.1 - 69',
                '11' => '69.1 - 75',
                '12' => '75.1 - 79',
                '13' => '79.1 - 84',
                '14' => '84.1 - 90',
                '15' => '90.1 - 999',
            ],
        ],
        'point-fighter' => [
            'feminino' => [
                '1' => '00.1 - 22',
                '2' => '22.1 - 27',
                '3' => '27.1 - 32',
                '4' => '32.1 - 37',
                '5' => '37.1 - 42',
                '6' => '42.1 - 47',
                '7' => '47.1 - 52',
                '8' => '52.1 - 57',
                '9' => '57.1 - 63',
                '10' => '63.1 - 69',
                '11' => '69.1 - 75',
                '12' => '75.1 - 79',
                '13' => '79.1 - 84',
                '14' => '84.1 - 90',
                '15' => '90.1 - 999',
            ],
            'masculino' => [
                '1' => '00.1 - 22',
                '2' => '22.1 - 27',
                '3' => '27.1 - 32',
                '4' => '32.1 - 37',
                '5' => '37.1 - 42',
                '6' => '42.1 - 47',
                '7' => '47.1 - 52',
                '8' => '52.1 - 57',
                '9' => '57.1 - 63',
                '10' => '63.1 - 69',
                '11' => '69.1 - 75',
                '12' => '75.1 - 79',
                '13' => '79.1 - 84',
                '14' => '84.1 - 90',
                '15' => '90.1 - 999',
            ],
        ],
        'kicklight' => [
            'feminino' => [
                '1' => '00.1 - 22',
                '2' => '22.1 - 27',
                '3' => '27.1 - 32',
                '4' => '32.1 - 37',
                '5' => '37.1 - 42',
                '6' => '42.1 - 47',
                '7' => '47.1 - 52',
                '8' => '52.1 - 57',
                '9' => '57.1 - 63',
                '10' => '63.1 - 69',
                '11' => '69.1 - 75',
                '12' => '75.1 - 79',
                '13' => '79.1 - 84',
                '14' => '84.1 - 90',
                '15' => '90.1 - 999',
            ],
            'masculino' => [
                '1' => '00.1 - 22',
                '2' => '22.1 - 27',
                '3' => '27.1 - 32',
                '4' => '32.1 - 37',
                '5' => '37.1 - 42',
                '6' => '42.1 - 47',
                '7' => '47.1 - 52',
                '8' => '52.1 - 57',
                '9' => '57.1 - 63',
                '10' => '63.1 - 69',
                '11' => '69.1 - 75',
                '12' => '75.1 - 79',
                '13' => '79.1 - 84',
                '14' => '84.1 - 90',
                '15' => '90.1 - 999',
            ],
        ],
        'k1-ruler' => [
            'feminino' => [
                '1' => '00.1 - 22',
                '2' => '22.1 - 27',
                '3' => '27.1 - 32',
                '4' => '32.1 - 37',
                '5' => '37.1 - 42',
                '6' => '42.1 - 47',
                '7' => '47.1 - 52',
                '8' => '52.1 - 57',
                '9' => '57.1 - 63',
                '10' => '63.1 - 69',
                '11' => '69.1 - 75',
                '12' => '75.1 - 79',
                '13' => '79.1 - 84',
                '14' => '84.1 - 90',
                '15' => '90.1 - 999',
            ],
            'masculino' => [
                '1' => '00.1 - 22',
                '2' => '22.1 - 27',
                '3' => '27.1 - 32',
                '4' => '32.1 - 37',
                '5' => '37.1 - 42',
                '6' => '42.1 - 47',
                '7' => '47.1 - 52',
                '8' => '52.1 - 57',
                '9' => '57.1 - 63',
                '10' => '63.1 - 69',
                '11' => '69.1 - 75',
                '12' => '75.1 - 79',
                '13' => '79.1 - 84',
                '14' => '84.1 - 90',
                '15' => '90.1 - 999',
            ],
        ],
        'k1' => [
            'feminino' => [
                '1' => '00.1 - 22',
                '2' => '22.1 - 27',
                '3' => '27.1 - 32',
                '4' => '32.1 - 37',
                '5' => '37.1 - 42',
                '6' => '42.1 - 47',
                '7' => '47.1 - 52',
                '8' => '52.1 - 57',
                '9' => '57.1 - 63',
                '10' => '63.1 - 69',
                '11' => '69.1 - 75',
                '12' => '75.1 - 79',
                '13' => '79.1 - 84',
                '14' => '84.1 - 90',
                '15' => '90.1 - 999',
            ],
            'masculino' => [
                '1' => '00.1 - 22',
                '2' => '22.1 - 27',
                '3' => '27.1 - 32',
                '4' => '32.1 - 37',
                '5' => '37.1 - 42',
                '6' => '42.1 - 47',
                '7' => '47.1 - 52',
                '8' => '52.1 - 57',
                '9' => '57.1 - 63',
                '10' => '63.1 - 69',
                '11' => '69.1 - 75',
                '12' => '75.1 - 79',
                '13' => '79.1 - 84',
                '14' => '84.1 - 90',
                '15' => '90.1 - 999',
            ],
        ],
        'guardas' => array(
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
        ),
        'cassetete' => array(
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
        ),
        'semi' => array(
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
        ),
        'kuolight' => array(
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
                '13' => '75.1 - 80',
                '14' => '80.1 - 999',
            ),
            'masculino' => array(
                '1' => '00.1 - 20',
                '2' => '20.1 - 27',
                '3' => '27.1 - 34',
                '4' => '34.1 - 41',
                '5' => '34.1 - 39',
                '6' => '39.1 - 44',
                '7' => '44.1 - 49',
                '8' => '49.1 - 54',
                '9' => '54.1 - 59',
                '10' => '59.1 - 64',
                '11' => '64.1 - 69',
                '12' => '69.1 - 74',
                '13' => '74.1 - 79',
                '14' => '79.1 - 84',
                '15' => '84.1 - 89',
                '16' => '89.1 - 94',
                '17' => '94.1 - 999',
            ),
        ),
        'kuoleitai' => array(
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
                '13' => '75.1 - 80',
                '14' => '80.1 - 999',
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
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 100',
                '20' => '100.1 - 999',
            ),
        ),
        'wushu' => array(
            'feminino' => array(
                '1' => '00.1 - 20',
                '2' => '20.1 - 25',
                '3' => '25.1 - 30',
                '4' => '30.1 - 35',
                '5' => '35.1 - 40',
                '6' => '40.1 - 45',
                '7' => '45.1 - 50',
                '8' => '50.1 - 55',
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 999',
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
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 999',
            ),
        ),
        'jiu-jitsu' => array(
            'feminino' => array(
                '1' => '00.1 - 20',
                '2' => '20.1 - 25',
                '3' => '25.1 - 30',
                '4' => '30.1 - 35',
                '5' => '35.1 - 40',
                '6' => '40.1 - 45',
                '7' => '45.1 - 50',
                '8' => '50.1 - 55',
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 100',
                '20' => '100.1 - 999',
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
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '19' => '95.1 - 100',
                '20' => '100.1 - 999',
            ),
        ),
        'thay-boxing' => array(
            'feminino' => array(
                '1' => '00.1 - 20',
                '2' => '20.1 - 25',
                '3' => '25.1 - 30',
                '4' => '30.1 - 35',
                '5' => '35.1 - 40',
                '6' => '40.1 - 45',
                '7' => '45.1 - 50',
                '8' => '50.1 - 55',
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 100',
                '20' => '100.1 - 999',
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
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '19' => '95.1 - 100',
                '20' => '100.1 - 999',
            ),
        ),
        'low-kick' => array(
            'feminino' => array(
                '1' => '00.1 - 20',
                '2' => '20.1 - 25',
                '3' => '25.1 - 30',
                '4' => '30.1 - 35',
                '5' => '35.1 - 40',
                '6' => '40.1 - 45',
                '7' => '45.1 - 50',
                '8' => '50.1 - 55',
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 100',
                '20' => '100.1 - 999',
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
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '19' => '95.1 - 100',
                '20' => '100.1 - 999',
            ),
        ),
        'full-contact' => array(
            'feminino' => array(
                '1' => '00.1 - 20',
                '2' => '20.1 - 25',
                '3' => '25.1 - 30',
                '4' => '30.1 - 35',
                '5' => '35.1 - 40',
                '6' => '40.1 - 45',
                '7' => '45.1 - 50',
                '8' => '50.1 - 55',
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 100',
                '20' => '100.1 - 999',
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
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '19' => '95.1 - 100',
                '20' => '100.1 - 999',
            ),
        ),
        'light-contact' => array(
            'feminino' => array(
                '1' => '00.1 - 20',
                '2' => '20.1 - 25',
                '3' => '25.1 - 30',
                '4' => '30.1 - 35',
                '5' => '35.1 - 40',
                '6' => '40.1 - 45',
                '7' => '45.1 - 50',
                '8' => '50.1 - 55',
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 100',
                '20' => '100.1 - 999',
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
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '19' => '95.1 - 100',
                '20' => '100.1 - 999',
            ),
        ),
        'sanda' => array(
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
                '13' => '75.1 - 80',
                '14' => '80.1 - 85',
                '15' => '85.1 - 90',
                '16' => '90.1 - 999',
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
        ),
        'sansou' => array(
            'feminino' => array(
                '1' => '00.1 - 20',
                '2' => '20.1 - 25',
                '3' => '25.1 - 30',
                '4' => '30.1 - 35',
                '5' => '35.1 - 40',
                '6' => '40.1 - 45',
                '7' => '45.1 - 50',
                '8' => '50.1 - 55',
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 999',
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
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 999',
            ),
        ),
        'shuai' => array(
            'mirim' => array(
                'feminino' => array(
                    '1' => '00.1 - 20',
                    '2' => '20.1 - 24',
                    '3' => '24.1 - 28',
                    '4' => '28.1 - 32',
                    '5' => '32.1 - 36',
                    '6' => '36.1 - 40',
                    '7' => '40.1 - 44',
                    '8' => '44.1 - 48',
                ),
                'masculino' => array(
                    '1' => '00.1 - 20',
                    '2' => '20.1 - 24',
                    '3' => '24.1 - 28',
                    '4' => '28.1 - 32',
                    '5' => '32.1 - 36',
                    '6' => '36.1 - 40',
                    '7' => '40.1 - 44',
                    '8' => '44.1 - 48',
                ),
            ),
            'infantil' => array(
                'feminino' => array(
                    '1' => '00.1 - 20',
                    '2' => '20.1 - 24',
                    '3' => '24.1 - 28',
                    '4' => '28.1 - 32',
                    '5' => '32.1 - 36',
                    '6' => '36.1 - 40',
                    '7' => '40.1 - 44',
                    '8' => '44.1 - 48',
                ),
                'masculino' => array(
                    '1' => '00.1 - 20',
                    '2' => '20.1 - 24',
                    '3' => '24.1 - 28',
                    '4' => '28.1 - 32',
                    '5' => '32.1 - 36',
                    '6' => '36.1 - 40',
                    '7' => '40.1 - 44',
                    '8' => '44.1 - 48',
                ),
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
                    '1' => '00.1 - 20',
                    '2' => '20.1 - 24',
                    '3' => '24.1 - 28',
                    '4' => '28.1 - 32',
                    '5' => '32.1 - 36',
                    '6' => '36.1 - 40',
                    '7' => '40.1 - 44',
                    '8' => '44.1 - 47',
                    '9' => '47.1 - 50',
                    '10' => '50.1 - 54',
                    '11' => '54.1 - 58',
                    '12' => '58.1 - 62',
                    '13' => '62.1 - 66',
                    '14' => '66.1 - 70',
                    '15' => '70.1 - 74',
                    '16' => '74.1 - 82',
                    '17' => '82.1 - 999',
                ),
                'masculino' => array(
                    '1' => '00.1 - 20',
                    '2' => '20.1 - 24',
                    '3' => '24.1 - 28',
                    '4' => '28.1 - 32',
                    '5' => '32.1 - 36',
                    '6' => '36.1 - 40',
                    '7' => '40.1 - 44',
                    '8' => '44.1 - 48',
                    '9' => '48.1 - 52',
                    '10' => '52.1 - 57',
                    '11' => '57.1 - 62',
                    '12' => '62.1 - 68',
                    '13' => '68.1 - 74',
                    '14' => '74.1 - 82',
                    '15' => '82.1 - 90',
                    '16' => '90.1 - 100',
                    '17' => '100.1 - 999',
                ),
            ),
            'adulto' => array(
                'feminino' => array(
                    '1' => '00.1 - 20',
                    '2' => '20.1 - 24',
                    '3' => '24.1 - 28',
                    '4' => '28.1 - 32',
                    '5' => '32.1 - 36',
                    '6' => '36.1 - 40',
                    '7' => '40.1 - 44',
                    '8' => '44.1 - 47',
                    '9' => '47.1 - 50',
                    '10' => '50.1 - 54',
                    '11' => '54.1 - 58',
                    '12' => '58.1 - 62',
                    '13' => '62.1 - 66',
                    '14' => '66.1 - 70',
                    '15' => '70.1 - 74',
                    '16' => '74.1 - 82',
                    '17' => '82.1 - 999',
                ),
                'masculino' => array(
                    '1' => '00.1 - 20',
                    '2' => '20.1 - 24',
                    '3' => '24.1 - 28',
                    '4' => '28.1 - 32',
                    '5' => '32.1 - 36',
                    '6' => '36.1 - 40',
                    '7' => '40.1 - 44',
                    '8' => '44.1 - 48',
                    '9' => '48.1 - 52',
                    '10' => '52.1 - 57',
                    '11' => '57.1 - 62',
                    '12' => '62.1 - 68',
                    '13' => '68.1 - 74',
                    '14' => '74.1 - 82',
                    '15' => '82.1 - 90',
                    '16' => '90.1 - 100',
                    '17' => '100.1 - 999',
                ),
            ),
            'senior' => array(
                'feminino' => array(
                    '1' => '00.1 - 20',
                    '2' => '20.1 - 24',
                    '3' => '24.1 - 28',
                    '4' => '28.1 - 32',
                    '5' => '32.1 - 36',
                    '6' => '36.1 - 40',
                    '7' => '40.1 - 44',
                    '8' => '44.1 - 47',
                    '9' => '47.1 - 50',
                    '10' => '50.1 - 54',
                    '11' => '54.1 - 58',
                    '12' => '58.1 - 62',
                    '13' => '62.1 - 66',
                    '14' => '66.1 - 70',
                    '15' => '70.1 - 74',
                    '16' => '74.1 - 82',
                    '17' => '82.1 - 999',
                ),
                'masculino' => array(
                    '1' => '00.1 - 20',
                    '2' => '20.1 - 24',
                    '3' => '24.1 - 28',
                    '4' => '28.1 - 32',
                    '5' => '32.1 - 36',
                    '6' => '36.1 - 40',
                    '7' => '40.1 - 44',
                    '8' => '44.1 - 48',
                    '9' => '48.1 - 52',
                    '10' => '52.1 - 57',
                    '11' => '57.1 - 62',
                    '12' => '62.1 - 68',
                    '13' => '68.1 - 74',
                    '14' => '74.1 - 82',
                    '15' => '82.1 - 90',
                    '16' => '90.1 - 100',
                    '17' => '100.1 - 999',
                ),
            ),
        ),
        'muaythai-a' => array(
            'feminino' => array(
                '1' => '00.1 - 20',
                '2' => '20.1 - 25',
                '3' => '25.1 - 30',
                '4' => '30.1 - 35',
                '5' => '35.1 - 40',
                '6' => '40.1 - 45',
                '7' => '45.1 - 50',
                '8' => '50.1 - 55',
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 999',
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
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 999',
            ),
        ),
        'muaythai-p' => array(
            'feminino' => array(
                '1' => '00.1 - 20',
                '2' => '20.1 - 25',
                '3' => '25.1 - 30',
                '4' => '30.1 - 35',
                '5' => '35.1 - 40',
                '6' => '40.1 - 45',
                '7' => '45.1 - 50',
                '8' => '50.1 - 55',
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 999',
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
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 999',
            ),
        ),
        'mma' => array(
            'feminino' => array(
                '1' => '00.1 - 20',
                '2' => '20.1 - 25',
                '3' => '25.1 - 30',
                '4' => '30.1 - 35',
                '5' => '35.1 - 40',
                '6' => '40.1 - 45',
                '7' => '45.1 - 50',
                '8' => '50.1 - 55',
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 999',
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
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 999',
            ),
        ),
        'cmma' => array(
            'feminino' => array(
                '1' => '00.1 - 20',
                '2' => '20.1 - 25',
                '3' => '25.1 - 30',
                '4' => '30.1 - 35',
                '5' => '35.1 - 40',
                '6' => '40.1 - 45',
                '7' => '45.1 - 50',
                '8' => '50.1 - 55',
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 999',
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
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 999',
            ),
        ),
        'taekwondo-kyorugui' => array(
            'feminino' => array(
                '1' => '00.1 - 20',
                '2' => '20.1 - 25',
                '3' => '25.1 - 30',
                '4' => '30.1 - 35',
                '5' => '35.1 - 40',
                '6' => '40.1 - 45',
                '7' => '45.1 - 50',
                '8' => '50.1 - 55',
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 999',
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
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 999',
            ),
        ),
        'taekwondo-kyorugui-dupla' => array(
            'feminino' => array(
                '1' => '00.1 - 20',
                '2' => '20.1 - 25',
                '3' => '25.1 - 30',
                '4' => '30.1 - 35',
                '5' => '35.1 - 40',
                '6' => '40.1 - 45',
                '7' => '45.1 - 50',
                '8' => '50.1 - 55',
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 999',
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
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 999',
            ),
        ),
        'karate-kumite' => array(
            'feminino' => array(
                '1' => '00.1 - 20',
                '2' => '20.1 - 25',
                '3' => '25.1 - 30',
                '4' => '30.1 - 35',
                '5' => '35.1 - 40',
                '6' => '40.1 - 45',
                '7' => '45.1 - 50',
                '8' => '50.1 - 55',
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 999',
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
                '9' => '55.1 - 59',
                '10' => '59.1 - 63',
                '11' => '63.1 - 67',
                '12' => '67.1 - 71',
                '13' => '71.1 - 75',
                '14' => '75.1 - 79',
                '15' => '79.1 - 83',
                '16' => '83.1 - 87',
                '17' => '87.1 - 91',
                '18' => '91.1 - 95',
                '19' => '95.1 - 999',
            ),
        ),
        'submission-adulto' => array(
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
        ),
        'submission-infantil' => array(
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
        ),
        'formaslivres' => array(
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
        ),
        'formasinternas' => array(
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
        ),
        'formastradicionais' => array(
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
        ),
        'formasolimpicas' => array(
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
        ),
        'tree' => array(
            '1' => 'Arma Longa',
            '2' => 'Arma Média',
            '3' => 'Mãos Livres',
        ),
        'desafio-bruce' => array(
            '0' => 'Desafio Bruce Lee',
        ),
        'taekwondo-poomsae' => [
            '1' => "Kibon Dong Jak",
            '2' => "Poomsae (formas)",
            '3' => "Poomsae (formas em conjunto)", // Participantes
            '4' => "Poomsae (musical formas)", // Participantes
            '5' => "Kyukpa (quebramentos)",
            '6' => "Ho Sin Sul (defesas e torções)", // Participantes
        ],
        'karate-kata' => [
            '1' => 'Goju Ryu',
            '2' => 'Shito Ryu',
            '3' => 'Shorin Ryu',
            '4' => 'Shotokan',
            '5' => 'Wado Ryu',
            '6' => 'Uechi Ryu',
            '7' => 'Kenyu Ryu',
            '8' => 'Kyokushin Kai',
            '9' => 'Goju Ryu (Conjunto)',
            '10' => 'Shito Ryu (Conjunto)',
            '11' => 'Shorin Ryu (Conjunto)',
            '12' => 'Shotokan (Conjunto)',
            '13' => 'Wado Ryu (Conjunto)',
            '14' => 'Uechi Ryu (Conjunto)',
            '15' => 'Kenyu Ryu (Conjunto)',
            '16' => 'Kyokushin Kai (Conjunto)',
        ],
    ];
}

function form_style_data()
{
    return [
        'formaslivres',
        'formasinternas',
        'formastradicionais',
        'formasolimpicas',
        'taekwondo-poomsae',
        'karate-kata',
    ];
}

function form_style_rules()
{
    return [
        'withGroup' => [
            'formastradicionais' => [7, 8, 20, 21],
            'formasinternas' => [7, 8],
            'formaslivres' => [8, 9, 12, 13],
            'taekwondo-poomsae' => [3, 4, 6],
            'karate-kata' => [9, 10, 11, 12, 13, 14, 15, 16],
        ],
        'withWeapon' => [
            'tree',
            'desafio-bruce',
        ],
        'withCustomTeam' => [
            'taekwondo-kyorugui-dupla' => [
                'size' => 1,
                'min' => 1,
                'max' => 1,
            ],
        ],
    ];
}

function groups_from_request()
{
    $postBody = $_POST;
    $groups = [];

    foreach ($postBody as $key => $body) {
        if (strpos($key, 'group-') !== false) {
            $category = str_replace('group-', '', $key);
            $groups[$category] = $body;
        }
    }

    return $groups;
}

function allowed_subscribe_categories()
{
    $request = $_POST;
    $categories = wp_get_post_terms($request['camp_id'], 'categoria', array('fields' => 'all'));
    $oldAged = ['adulto', 'senior'];
    $isAged = ['submission-adulto', 'submission-infantil'];
    $in = get_the_author_meta('insiders', $request['user_id']);
    $fetaria = get_the_author_meta('fEtaria', $request['user_id']);

    foreach ($categories as $term) {
        if ((!in_array($fetaria, $oldAged) && $term->slug === 'submission-adulto') ||
            (in_array($fetaria, $oldAged) && $term->slug === 'submission-infantil')) {
            continue;
        }

        if (empty($in)) {
            echo sprintf(
                '<li>%s</li>',
                $term->name
            );
        }

        if (!empty($in)) {
            foreach ($in[$request['camp_id']] as $k => $i) {
                if ($k === 'categorias') {
                    if (!array_key_exists($term->slug, $i)) {
                        echo sprintf(
                            '<li>%s</li>',
                            $term->name
                        );
                    }
                }
            }
        }
    }
}

function get_weight($modalidade, $id_peso, $sexo, $fetaria)
{
    $data = weight_data();
    $notWeight = array_merge(form_style_data(), ['shuai', 'desafio-bruce', 'tree']);

    if (!in_array($modalidade, $notWeight) && array_key_exists($modalidade, $data)) {
        if ($sexo === 'm') {
            return $data[$modalidade]['masculino'][$id_peso];
        }

        if ($sexo === 'f') {
            return $data[$modalidade]['feminino'][$id_peso];
        }
    }

    if (array_key_exists($modalidade, $data) && $modalidade === 'shuai') {
        $nested = $data[$modalidade];
        $fetaria = $fetaria === 'ijuvenil' ? 'infanto-juvenil' : $fetaria;

        if (array_key_exists($fetaria, $nested)) {
            if ($sexo === 'm') {
                return $nested[$fetaria]['masculino'][$id_peso];
            }

            if ($sexo === 'f') {
                return $nested[$fetaria]['feminino'][$id_peso];
            }
        }
    }

    if (array_key_exists($modalidade, $data) || in_array($modalidade, ['desafio-bruce'])) {
        return $data[$modalidade][$id_peso];
    }

    return '';
}

function template_modalities($echo = true)
{
    $request = $_POST;
    $user = wp_get_current_user();
    $showOrder = array(
        'combate' => array(
            'guardas',
            'cassetete',
            'semi',
            'submission-adulto',
            'submission-infantil',
            'shuai',
            'kuolight',
            'kuoleitai',
            'wushu',
            'sanda',
            'muaythai-a',
            'muaythai-p',
            'cmma',
            'mma',
            'sansou',
            'jiu-jitsu',
            'thay-boxing',
            'low-kick',
            'full-contact',
            'light-contact',
            'taekwondo-kyorugui',
            'taekwondo-kyorugui-dupla',
            'karate-kumite',
            'kickboxing',
            'point-fighter',
            'kicklight',
            'k1-ruler',
            'k1',
            'boxe-amador',
            'boxe-profissional',
            'judo',
        ),
        'formas' => array(
            'formastradicionais',
            'formasinternas',
            'formasolimpicas',
            'formaslivres',
            'tree',
            'desafio-bruce',
            'taekwondo-poomsae',
            'karate-kata',
        ),
    );

    $oldAged = array('adulto', 'senior');
    $isAged = ['submission-adulto', 'submission-infantil'];
    $modalities = wp_get_object_terms($request['camp_id'], 'categoria', array('fields' => 'slugs'));
    $fetaria = get_the_author_meta('fEtaria', $user->ID);
    $formas = form_style_data();
    $in = get_the_author_meta('insiders', $user->ID);
    if (!$echo) {
        ob_start();
    }

    foreach ($showOrder as $type => $groupModality) {
        if ($type === 'combate') {
            if (count(array_intersect($groupModality, $modalities))) {
                echo sprintf('<h4>%s</h4>', 'Modalidades de Combate');
            }
        }

        if ($type === 'formas') {
            if (count(array_intersect($groupModality, $modalities))) {
                echo sprintf('<h4>%s</h4>', 'Modalidades de Formas Artísticas');
            }
        }

        foreach ($groupModality as $modality) {
            if (!in_array($modality, $modalities)) {
                continue;
            }

            if ((!in_array($fetaria, $oldAged) && $modality === 'submission-adulto') ||
                (in_array($fetaria, $oldAged) && $modality === 'submission-infantil')) {
                continue;
            }

            $term = get_term_by('slug', $modality, 'categoria');

            if (empty($in) || is_array($in) && !array_key_exists($request['camp_id'], $in) ||
                !empty($in[$request['camp_id']]['categorias']) && !array_key_exists($term->slug, $in[$request['camp_id']]['categorias'])) {
                echo sprintf(
                    '<li><input type="checkbox" id="%s" name="categoria[]" value="%s"/> %s<div id="%s"></div></li>',
                    $term->slug,
                    $term->slug,
                    $term->name,
                    $term->slug
                );
            }

            if (!empty($in) && is_array($in) && array_key_exists($request['camp_id'], $in) && $type === 'formas') {
                if (is_array($in[$request['camp_id']]['categorias'])) {
                    foreach ($in[$request['camp_id']]['categorias'] as $key => $subscribed) {
                        if (in_array($key, $formas) && $key === $term->slug) {
                            echo sprintf(
                                '<li><input type="checkbox" id="%s" name="categoria[]" value="%s"/> %s<div id="%s"></div></li>',
                                $term->slug,
                                $term->slug,
                                $term->name,
                                $term->slug
                            );
                        }
                    }
                }
            }
        }
    }

    if (!$echo) {
        return ob_end_flush();
    }
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
        $troca[$key] = '/[' . $acentos . ']/i';
    }
    $string = preg_replace(array_values($troca), array_keys($troca), $string);
    // Slug?
    if ($slug) {
        // Troca tudo que não for letra ou número por um caractere ($slug)
        $string = preg_replace('/[^a-z0-9]/i', $slug, $string);
        // Tira os caracteres ($slug) repetidos
        $string = preg_replace('/' . $slug . '{2,}/i', $slug, $string);
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
    switch ($name) {
        case 'cassetete':
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
        case 'sansou':
            $url = 'http://www.skigawk.com.br/wp-content/uploads/2015/11/11-REGRAS-PARA-LUTA-SAN-SOU.pdf';
            break;
        case 'muaythai':
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
			window.location= <?php echo "'" . htmlspecialchars_decode($url) . "'";
    ?>;
		});
	</script>
<?php

}

function send_inscricao($user_id)
{
    $name = get_the_author_meta('display_name', $user_id);
    $mail = get_the_author_meta('user_email', $user_id);
    $admin_email = get_option('admin_email');
    $home = esc_url(home_url());
    $perfil = esc_url(home_url('/login'));

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

    $headers[] = "From: Skigawk <{$admin_email}>" . "\r\n";
    $headers[] = "MIME-Version: 1.0";
    $headers[] = "Content-type:text/html;charset=UTF-8";

    wp_mail($mail, $subject, $message, $headers);
}
