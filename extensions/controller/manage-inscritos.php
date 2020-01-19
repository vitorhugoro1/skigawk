<?php
/*
Plugin Name: Inscritos
 */

error_reporting(1);

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 *
 */

class Inscritos extends WP_List_Table
{
    public function __construct()
    {
        parent::__construct(array(

            'singular' => 'inscrito', //Singular label
            'plural' => 'inscritos', //plural label, also this well be one of the table css class
            'ajax' => false,

            // We won't support Ajax for this table
        ));

        add_action('admin_head', array(&$this, 'admin_header'));
    }

    /**
     * [table_data description].
     *
     * @return [type] [description]
     */

    public function table_data()
    {
        $post_id = $_REQUEST['post_id'];

        $data = array();

        $list_ids = get_post_meta($post_id, 'user_subscribers', true);

        $args = array(
            'include' => $list_ids,
        );

        $args['orderby'] = !empty($_REQUEST['orderby']) ? esc_attr($_REQUEST['orderby']) : 'display_name';
        $args['order'] = !empty($_REQUEST['order']) ? esc_attr($_REQUEST['order']) : 'ASC';

        if (!empty($_REQUEST['s'])) {
            $args['search'] = '*' . esc_attr($_REQUEST['s']) . '*';
            $args['search_columns'] = array('user_nicename', 'user_email', 'display_name');
        }

        if (!empty($_REQUEST['categoria'])) {
            $meta_value = esc_sql($_REQUEST['categoria']);
            $args['meta_query'] = array(
                array(
                    'key' => 'insiders',
                    'value' => $meta_value,
                    'compare' => 'LIKE',
                ),
            );
        }

        $user_query = new WP_User_Query($args);

        foreach ($user_query->results as $user) {
            $data[] = $user->data;
        }

        return $data;
    }

    public function admin_header()
    {
        $page = (isset($_REQUEST['page'])) ? esc_attr($_REQUEST['page']) : false;
        if ('gerenciar_camp' != $page) {return;}

        echo '<script type="text/javascript">';
        echo "jQuery('.ewc-filter-ativo').live('change', function(){
                var activeFilter = jQuery(this).val();
                //alert(activeFilter);
                //document.location.href = 'edit.php?post_type=" . esc_attr($_REQUEST['post_type']) . "&page=" . esc_attr($_REQUEST['page']) . "&post_id=" . esc_attr($_REQUEST['post_id']) . "'+activeFilter;
            });";
        echo '</script>';
    }

    /**
     * @return array
     */
    public function get_bulk_actions()
    {
        $actions = [
            'bulk-delete' => __('Delete'),
        ];

        return $actions;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table.
     *
     * @return array
     */

    public function get_columns()
    {
        $type = get_post_type($_REQUEST['post_id']);

        if ($type == 'campeonatos') {
            $columns = array(
                'cb' => '<input type="checkbox" />',
                'display_name' => 'Nome',
                'fetaria' => 'Faixa Etária',
                'categoria' => 'Categoria',
                'academia' => 'Academia',
            );
        }

        if ($type == 'eventos') {
            $columns = array(
                'cb' => '<input type="checkbox" />',
                'display_name' => 'Nome',
                'fetaria' => 'Faixa Etária',
                'category' => 'Categoria',
            );
        }

        return $columns;
    }

    /**
     * [column_cb description]
     * @param  [type] $item [description]
     * @return [type]       [description]
     */
    public function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="bulk-delete[]" value="%s" />', $item->ID);
    }

    public function column_display_name($item)
    {
        $delete_nonce = wp_create_nonce('delete_insider');
        $editpage = ($_REQUEST['post_type'] == 'campeonatos') ? 'edit_icamp_cat' : 'edit_ievent_pag';

        $title = sprintf('<a href="?post_type=%s&page=%s&post_id=%s&user=%s">%s</a>', esc_attr($_REQUEST['post_type']), $editpage, esc_attr($_REQUEST['post_id']), absint($item->ID), $item->display_name);

        $actions = [
            'edit' => sprintf('<a href="?post_type=%s&page=%s&post_id=%s&user=%s">%s</a>', esc_attr($_REQUEST['post_type']), $editpage, esc_attr($_REQUEST['post_id']), absint($item->ID), __('Edit')),
            'delete' => sprintf('<a href="?post_type=%s&page=%s&post_id=%s&action=%s&user=%s&_wpnonce=%s">%s</a>', esc_attr($_REQUEST['post_type']), esc_attr($_REQUEST['page']), esc_attr($_REQUEST['post_id']), 'delete', absint($item->ID), $delete_nonce, __('Delete')),
        ];

        return $title . $this->row_actions($actions);
    }

    /**
     * Define what data to show on each column of the table.
     *
     * @param array  $item        Data
     * @param string $column_name - Current column name
     *
     * @return mixed
     */

    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'display_name':
                return $item->$column_name;
            case 'fetaria':
                return ucfirst(get_the_author_meta('fEtaria', $item->ID));
            case 'category':
                $inscricoes = get_the_author_meta('insiders', $item->ID);

                $items = $inscricoes[$_REQUEST['post_id']]['pagamento'];

                return ucfirst($items[$column_name]);
            case 'academia':
                $academia = get_the_author_meta('assoc', $item->ID);
                $term = get_term_by('slug', $academia, 'academia');
                return $term->name;
            default:
                return print_r($item, true);
        }
    }

    public function column_categoria($item)
    {
        if (isset($_REQUEST['categoria'])) {
            $sex = get_the_author_meta('sex', $item->ID);
            $fetaria = get_the_author_meta('fEtaria', $item->ID);
            $inscricoes = get_the_author_meta('insiders', $item->ID);
            $items = $inscricoes[$_REQUEST['post_id']]['categorias'][$_REQUEST['categoria']];
            $term = get_term_by('slug', $_REQUEST['categoria'], 'categoria');

            switch ($_REQUEST['categoria']) {
                case 'formaslivres':
                case 'formasinternas':
                case 'formastradicionais':
                case 'formasolimpicas':
                    array_filter($items);
                    $mods = array();

                    foreach ($items as $key => $value) {
                        $id = (!isset($items['id'])) ? $value['peso'] : $value['id'];
                        if (in_array($id, $mods)) {
                            continue;
                        }

                        $cadastros[] = array(
                            'modalidade' => get_weight($_REQUEST['categoria'], $id, $sex, $fetaria),
                            'equipe' => ((isset($value['groups'])) ? implode(',', array_filter($value['groups'])) : ''),
                        );

                        $mods[] = $id;
                    }

                    $items = $term->name . " <ul> ";
                    foreach (array_filter($cadastros) as $cadastro) {
                        if (is_null($cadastro['modalidade'])) {
                            continue;
                        }

                        if ($cadastro['equipe'] == '') {
                            $items .= sprintf('<li> %s </li>', $cadastro['modalidade']);
                        } else {
                            $items .= sprintf('<li> %s - %s </li>', $cadastro['modalidade'], $cadastro['equipe']);
                        }
                    }
                    $items .= "</ul>";

                    break;
                case 'tree':
                    $id = (!isset($items['id'])) ? $items['peso'] : $items['id'];
                    $items = $term->name . ' - <b>' . get_weight($_REQUEST['categoria'], $id, $sex, $fetaria) . ' / ' . $items['arma'] . '</b>';
                    break;
                case 'desafio-bruce':
                    $id = (!isset($items['id'])) ? $items['peso'] : $items['id'];
                    $items = $term->name . ' - <b>' . get_weight($_REQUEST['categoria'], $id, $sex, $fetaria) . ' / ' . $items['arma'] . '</b>';
                    break;
                default:
                    $id = (!isset($items['id'])) ? $items['peso'] : $items['id'];
                    $items = $term->name . ' - <b>' . get_weight($_REQUEST['categoria'], $id, $sex, $fetaria) . ' Kg </b>';
                    break;
            }
        } else {
            $inscricoes = get_the_author_meta('insiders', $item->ID);
            $items = $inscricoes[$_REQUEST['post_id']]['categorias'];
            $extra = 0;
            $items = array_keys($items);
            $value = count($items);
            foreach ($items as $key => $item) {
                if ($key == 'formaslivres' || $key == 'formasinternas' || $key == 'formastradicionais' || $key == 'formasolimpicas' || $key == 'tree') {
                    $value--;
                    $extra += count($item);
                }
            }
            $items = $value + $extra;
        }

        return print_r($items, true);
    }

    public function extra_tablenav($which)
    {
        global $wpdb;
        if ($_REQUEST['post_type'] == 'campeonatos') {
            if ($which == "top") {
                echo '<script type="text/javascript">';
                echo "jQuery('.ewc-filter-ativo').live('change', function(){
                        var activeFilter = jQuery(this).val();
                        //alert(activeFilter);
                        document.location.href = 'edit.php?post_type=" . esc_attr($_REQUEST['post_type']) . "&page=" . esc_attr($_REQUEST['page']) . "&post_id=" . esc_attr($_REQUEST['post_id']) . "'+activeFilter;
                    });";
                echo "jQuery()";
                echo '</script>';
                ?>
             <div class="alignleft actions bulkactions">
               <label for="bulk-action-ativo" class="screen-reader-text">Categoria</label>
               <select class="ewc-filter-ativo" name="ativo-filter">
                    <option value="">Categoria</option>
                    <?php
                    $list = wp_get_post_terms($_REQUEST['post_id'], 'categoria', array('fields' => 'all'));
                    foreach ($list as $term) {
                        ?>
                        <option value="<?php echo sprintf('&categoria=%s', esc_attr($term->slug)); ?>"
                        <?php selected($_REQUEST['categoria'], $term->slug);?>>
                            <?php echo $term->name; ?>
                        </option>
                        <?php
                    }
                    ?>
               </select>
             </div>
                <?php
                if (isset($_GET['categoria'])) {
                    ?>
                    <div class="alignleft actions bulkactions">
                    <label for="button-exportar" class="screen-reader-text">Gerar</label>
                    <a href="<?php echo get_stylesheet_directory_uri() . '/extensions/controller/generate.php?post_id=' . esc_attr($_GET['post_id']) . '&categoria=' . esc_attr($_GET['categoria']); ?>" id="button-exportar" class="button">Gerar Relatório</a>
                    </div>
                    <?php
                }
            }
            } else {
            if ($which == "top") {
                echo '<script type="text/javascript">';
                echo "jQuery('.ewc-filter-ativo').live('change', function(){
                        var activeFilter = jQuery(this).val();
                        //alert(activeFilter);
                        document.location.href = 'edit.php?post_type=" . esc_attr($_REQUEST['post_type']) . "&page=" . esc_attr($_REQUEST['page']) . "&post_id=" . esc_attr($_REQUEST['post_id']) . "'+activeFilter;
                    });";
                echo '</script>';

                ?>
                 <div class="alignleft actions bulkactions">
                   <label for="bulk-action-ativo" class="screen-reader-text">Categoria</label>
                   <select class="ewc-filter-ativo" name="ativo-filter">
                        <option value="">Categoria</option>
                        <?php
$niveis = get_post_meta($_REQUEST['post_id'], 'category_insider_group', true);

                if (!empty($niveis)) {
                    foreach ((array) $niveis as $tkey => $tentry) {
                        ?>
                                            <option value="<?php echo sprintf('&categoria=%s', sanitize_title($tentry['name'])); ?>" <?php selected($_REQUEST['categoria'], sanitize_title($tentry['name']));?>><?php echo $tentry['name']; ?></option>
                                        <?php
}
                }
                ?>
                   </select>
                 </div>
                <?php
if (isset($_GET['categoria'])) {
                    ?>
                    <div class="alignleft actions bulkactions">
                        <label for="button-exportar" class="screen-reader-text">Gerar</label>
                        <a href="<?php echo get_stylesheet_directory_uri() . '/extensions/controller/generate.php?post_id=' . esc_attr($_GET['post_id']) . '&categoria=' . esc_attr($_GET['categoria']); ?>" id="button-exportar" class="button">Gerar Relatório</a>
                    </div>
                    <?php
}
            }
        }
    }

    public function no_items()
    {
        _e('Nenhum usúario foi encontrado');
    }

    /**
     * [get_sortable_columns description]
     * @return [type] [description]
     */
    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'display_name' => array('display_name', true),
        );

        return $sortable_columns;
    }

    /**
     * Prepare the items for the table to process.
     */

    public function prepare_items()
    {
        $this->process_bulk_action();

        $columns = $this->get_columns();

        $hidden = $this->get_hidden_columns();

        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();

        $perPage = 20;

        $currentPage = $this->get_pagenum();

        $totalItems = count($data);

        $this->set_pagination_args(array(

            'total_items' => $totalItems,

            'per_page' => $perPage,

        ));

        $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);

        $this->_column_headers = array(

            $columns,

            $hidden,

            $sortable,

        );

        $this->items = $data;
    }

    public function process_bulk_action()
    {
        if ('delete' === $this->current_action()) {
            $nonce = esc_attr($_REQUEST['_wpnonce']);

            if (!wp_verify_nonce($nonce, 'delete_insider')) {
                die('Go get a life script kiddies');
            } else {
                if ($_REQUEST['post_type'] === 'campeonatos') {
                    $subscribers = get_post_meta($_GET['post_id'], 'user_subscribers', true);
                    $inside = get_the_author_meta('insiders', $_GET['user']);
                    $key = array_search(esc_attr($_GET['user']), $subscribers);

                    if ($key !== false) {
                        unset($subscribers[$key]);
                    }

                    if (is_array($inside)) {
                        unset($inside[$_GET['post_id']]);

                        update_user_meta($_GET['user'], 'insiders', $inside);
                    }

                    update_post_meta($_GET['post_id'], 'user_subscribers', $subscribers);
                } else {
                    $subscribers = get_post_meta($_GET['post_id'], 'user_subscribers', true);
                    $inside = get_the_author_meta('insiders', $_GET['user']);
                    $key = array_search(esc_attr($_GET['user']), $subscribers);
                    if ($key !== false) {
                        unset($subscribers[$key]);
                    }
                    unset($inside[$_GET['post_id']]);
                    update_post_meta($_GET['post_id'], 'user_subscribers', $subscribers);
                    update_user_meta($_GET['user'], 'insiders', $inside);
                }
                $query = array('post_type' => $_GET['post_type'], 'page' => $_GET['page'], 'post_id' => $_GET['post_id']);
                $url = esc_url(add_query_arg($query, admin_url('edit.php')));
                scriptRedirect($url);
                exit;
            }
        }

        if ((isset($_POST['action']) && $_POST['action'] == 'bulk-delete') || (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete')) {
            $delete_ids = esc_sql($_POST['bulk-delete']);
            if ($_REQUEST['post_type'] == 'campeonatos') {
                foreach ($delete_ids as $ids) {
                    $subscribers = get_post_meta($_GET['post_id'], 'user_subscribers', true);
                    $inside = get_the_author_meta('insiders', $ids);
                    $key = array_search(esc_attr($ids), $subscribers);
                    if ($key !== false) {
                        unset($subscribers[$key]);
                    }
                    unset($inside[$_GET['post_id']]);
                    update_post_meta($_GET['post_id'], 'user_subscribers', $subscribers);
                    update_user_meta($ids, 'insiders', $inside);
                }
            } else {
                foreach ($delete_ids as $ids) {
                    $subscribers = get_post_meta($_GET['post_id'], 'user_subscribers', true);
                    $inside = get_the_author_meta('insiders', $ids);
                    $key = array_search(esc_attr($ids), $subscribers);
                    if ($key !== false) {
                        unset($subscribers[$key]);
                    }
                    unset($inside[$_GET['post_id']]);
                    update_post_meta($_GET['post_id'], 'user_subscribers', $subscribers);
                    update_user_meta($ids, 'insiders', $inside);
                }
            }
            $query = array('post_type' => $_GET['post_type'], 'page' => $_GET['page'], 'post_id' => $_GET['post_id']);
            $url = esc_url(add_query_arg($query, admin_url('edit.php')));
            scriptRedirect($url);
            exit;
        }
    }
}
