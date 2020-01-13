<?php
$nonce = wp_create_nonce('editar_insider');
$user_id = esc_attr($_REQUEST['user']);
$post_id = esc_attr($_REQUEST['post_id']);
$inscricoes = get_the_author_meta('insiders', $user_id);

if (get_post_type($post_id) == 'campeonatos') {
    $link1 = 'edit.php?post_type=campeonatos&page=edit_icamp_cat&post_id=' . $post_id . '&user=' . $user_id;
    $link2 = 'edit.php?post_type=campeonatos&page=edit_icamp_pag&post_id=' . $post_id . '&user=' . $user_id;
    $fetaria = get_the_author_meta('fEtaria', $user_id);
    $sexo = get_the_author_meta('sex', $user_id);
    $inscricao = $inscricoes[$post_id]['categorias'];
    $file = get_template_directory() . '/extensions/controller/category.json';
    $category = file_get_contents($file);
    $category = json_decode($category, true);
} elseif (get_post_type($post_id) == 'eventos') {
    $inscricao = $inscricoes[$post_id]['pagamento'];
}

?>
<div class="wrap">
    <h2>Editar Inscrito - <b><?php echo get_the_author_meta('display_name', $user_id); ?></b></h2>
<?php
if (get_post_type($post_id) === 'campeonatos') {
    ?>
    <h2 class="nav-tab-wrapper">
        <a href="<?php echo admin_url($link1); ?>" class="nav-tab <?php echo ($_REQUEST['page'] == 'edit_icamp_cat') ? 'nav-tab-active' : ''; ?>">
            <?php echo __('Modalidades'); ?>
        </a>
        <a href="<?php echo admin_url($link2); ?>" class="nav-tab <?php echo ($_REQUEST['page'] == 'edit_icamp_pag') ? 'nav-tab-active' : ''; ?>">
            <?php echo __('Pagamentos'); ?>
        </a>
    </h2>
    <?php
}
?>

    <form method="post">
        <input type="hidden" name="_wpnonce" value="<?php echo $nonce; ?>" />
        <table class="form-table">
        <?php
        if (get_post_type($post_id) == 'campeonatos') {
            if ($_REQUEST['page'] == 'edit_icamp_cat') {
                if (isset($_POST['delete'])) {
                    if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'editar_insider')) {
                        global $wpdb;
                        $table = $wpdb->prefix . 'payments';
                        $delete_slugs = $_POST['delete'];
                        foreach ($delete_slugs as $slugs) {
                            $id_pay = $inscricoes[$_GET['post_id']]['categorias'][$slugs]['id_pagamento'];
                            $results = $wpdb->get_results("SELECT cat_inscricao FROM $table WHERE user_id = $user_id AND post_id = $post_id AND id = $id_pay");
                            foreach ($results as $cat_slug) {
                                $arr = unserialize($cat_slug->cat_inscricao);
                                $arr_size = count($arr);
                                if ($arr_size > 1) {
                                    unset($arr[$slugs]);
                                    $wpdb->update($table, array('cat_inscricao' => serialize($arr)), array('id' => $id_pay), array('%s'));
                                    var_dump($retorno);
                                } else {
                                    $wpdb->delete($table, array('id' => $id_pay), array('%d'));
                                }
                            }
                            unset($inscricoes[$_GET['post_id']]['categorias'][$slugs]);
                            $new_meta = $inscricoes;
                            update_user_meta($user_id, 'insiders', $new_meta);
                            $inscricoes = get_the_author_meta('insiders', $user_id, true);
                        }

                        foreach ($inscricao as $key => $info) {
                            $term = get_term_by('slug', $key, 'categoria');
                            if (array_key_exists($term->slug, $inscricoes[$post_id]['categorias'])) {
                                $inscricoes[$post_id]['categorias'][$term->slug]['peso'] = esc_attr($_POST['categoria-' . $term->slug]);
                            }
                        }

                        update_user_meta($user_id, 'insiders', $inscricoes);
                        $inscricao = $inscricoes[$post_id]['categorias'];
                    }
                } else {
                    if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'editar_insider')) {
                        foreach ($inscricao as $key => $info) {
                            $term = get_term_by('slug', $key, 'categoria');
                            $inscricoes[$post_id]['categorias'][$term->slug]['peso'] = esc_attr($_POST['categoria-' . $term->slug]);
                        }

                        update_user_meta($user_id, 'insiders', $inscricoes);

                        $inscricao = $inscricoes[$post_id]['categorias'];
                        $message = 'Success';
                    } else {
                        if (isset($_POST['_wpnonce'])) {
                            $message = 'Error';
                        }
                    }
                }
                require 'template-campeonato-cat.php';
            } else {
                require 'template-campeonato-pag.php';
            }
        } elseif (get_post_type($post_id) == 'eventos') {
            if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'editar_insider')) {

                $inscricoes[$post_id]['pagamento']['category'] = esc_attr($_POST['category']);
                $inscricoes[$post_id]['pagamento']['status'] = esc_attr($_POST['status']);

                update_user_meta($user_id, 'insiders', $inscricoes);

                $inscricao = $inscricoes[$post_id]['pagamento'];
                $message = 'success';
            } else {
                if (isset($_POST['_wpnonce'])) {
                    $message = 'error';
                }
            }

            if (isset($message) && $message == 'success') {
                ?>
                <div id="message" class="updated fade"><p><strong>Dados atualizados com sucesso</strong></p></div>
                <?php
            } elseif (isset($message) && $message == 'error') {
                    ?>
                                    <div id="message" class="error fade"><p><strong>Erro ao atualizar os dados</strong></p></div>
                                    <?php
            }

            require 'template-evento.php';
        }
        ?>
    </table>
    <p class="submit"><?php submit_button(__('Change'), 'primary');?></p>
</form>
</div>
