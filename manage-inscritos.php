<?php
/*
Plugin Name: My List Table Example
*/

error_reporting(1);

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH.'wp-admin/includes/class-wp-list-table.php';
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
    }

    /**
     * [table_data description].
     *
     * @return [type] [description]
     */

    private function table_data()
    {
        $post_id = $_REQUEST['post_id'];

        $data = array();

        $list_ids = get_post_meta($post_id, 'user_subscribers', true);

        foreach ($list_ids as $user_id) {
            $sexo = get_the_author_meta('sex', $user_id, true);

            $fetaria = get_the_author_meta('fEtaria', $user_id, true);

            $inscricoes = get_the_author_meta('insiders', $user_id, true);

            $name = get_the_author_meta('display_name', $user_id, true);

            if (get_post_type($post_id) == 'campeonatos') {
                foreach ($inscricoes[$post_id] as $key => $value) {
                    switch ($key) {

                    case 'categorias':

                        foreach ($value as $cat_slug => $cat_value) {
                            $arrCat[] = $cat_slug;

                            $arrPeso[] = array(

                                'categoria' => $cat_slug,

                                'peso' => $cat_value['peso'],

                            );
                        }

                        break;

                        }
                }

                $data[] = array(

                    'id' => $user_id,

                    'nome' => $name,

                    'fetaria' => $fetaria,

                    'fpeso' => $arrPeso,

                    'categoria' => $arrCat,

                );
            } else {
                $data[] = array(

                    'id' => $user_id,

                    'nome' => $name,

                    'fetaria' => $fetaria,

                );
            }
        }

        $sortArray = array();

        foreach ($data as $person) {
            foreach ($person as $key => $value) {
                if (!isset($sortArray[$key])) {
                    $sortArray[$key] = array();
                }

                $sortArray[$key][] = $value;
            }
        }

        $orderby = 'id'; //change this to whatever key you want from the array
        array_multisort($sortArray[$orderby], SORT_DESC, $data);

        return $data;
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

                'id' => 'ID',

                'nome' => 'Nome',

                'fetaria' => 'Faixa Etária',

                'fpeso' => 'Categoria - Peso',

                // 'categoria'    => 'Categoria',

            );
        } elseif ($type == 'eventos') {
            $columns = array(

                'cb' => '<input type="checkbox" />',

                'id' => 'ID',

                'nome' => 'Nome',

                'fetaria' => 'Faixa Etária',

            );
        }

        return $columns;
    }

    public function column_cb($item)
    {
        return sprintf('<input type="checkbox" name="book[]" value="%s" />', $item['id']);
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

        case 'id':

        case 'nome':

            return $item[$column_name];

        case 'fetaria':

            return ucfirst($item[$column_name]);

        case 'fpeso':

            $sexo = get_the_author_meta('sex', $item['id'], true);

            $fetaria = get_the_author_meta('fEtaria', $item['id'], true);

            $retorno = '<ul>';

            foreach ($item[$column_name] as $value) {
                $category = get_term_by('slug', $value['categoria'], 'categoria');

                $retorno .= '<li><b>'.$category->name.'</b> - '.get_weight($value['categoria'], $value['peso'], $sexo, $fetaria);

                $retorno .= ($value['categoria'] == 'formaslivres' || $value['categoria'] == 'formasinternas' || $value['categoria'] == 'formastradicionais' || $value['categoria'] == 'formasolimpicas') ? '</li>' : ' Kg</li>';
            }

            $retorno .= '</ul>';

            return $retorno;

        case 'categoria':

            $retorno = '<ul>';

            foreach ($item[$column_name] as $value) {
                $category = get_term_by('slug', $value, 'categoria');

                $retorno .= '<li>'.$category->name.'</li>';
            }

            $retorno .= '</ul>';

            return $retorno;

        default:

            return print_r($item, true);

            }
    }

    /**
     * Prepare the items for the table to process.
     */

    public function prepare_items()
    {
        $columns = $this->get_columns();

        $hidden = $this->get_hidden_columns();

        $sortable = $this->get_sortable_columns();

        $data = $this->table_data();

        usort($data, array(&$this,

            'sort_data',

        ));

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
}
