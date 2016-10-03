<?php

/**
*
*/
class Usuarios extends WP_List_Table
{

	public function __construct()
	{
		parent::__construct(array(
			'singular'	=> 'cad_user',
			'plural'	=> 'cad_users',
			'ajax'		=> false
			));
	}

	/**
	 * [user_data description]
	 * @return [type] [description]
	 */
	public function user_data()
	{
		$args = array(
			'role'	=> 'Subscriber'
		);

		$args['orderby'] = !empty($_REQUEST['orderby']) ? esc_attr($_REQUEST['orderby']) : 'display_name';
		$args['order']	= !empty($_REQUEST['order']) ? esc_attr($_REQUEST['order']) : 'ASC';

		if(!empty($_REQUEST['s'])){
			$args['search'] = '*'.esc_attr($_REQUEST['s']).'*';
			$args['search_columns'] = array('user_nicename','display_name', 'user_email');
		}

		$query = new WP_User_Query($args);

		foreach($query->results as $user){
			$data[] = $user->data;
		}

		return $data;
	}

	/**
	 * [get_columns description]
	 * @return [type] [description]
	 */
	public function get_columns()
	{
		$columns = array(
				'cb'	=> '<input type="checkbox" />',
				'avatar'	=> 'Avatar',
				'display_name'	=> 'Nome',
				'email'	=> 'E-mail',
				'idade'	=> 'Idade',
				'sex'	=> 'Sexo',
				'insiders'	=> 'Inscrições'
			);

		return $columns;
	}

	/**
	 * [no_items description]
	 * @return [type] [description]
	 */
	public function no_items()
	{
		_e('Nenhum usuário.');
	}

	/**
	 * [get_bulk_actions description]
	 * @return [type] [description]
	 */
	public function get_bulk_actions()
	{
		$actions = [
			'bulk-delete'	=> __('Delete')
		];

		return $actions;
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

	/**
	 * [column_display_name description]
	 * @param  [type] $item [description]
	 * @return [type]       [description]
	 */
	public function column_display_name($item)
	{
		$delete_nonce = wp_create_nonce('delete_user');

		$title = sprintf('<a href="?page=%s&user=%s">%s</a>', 'edit_cad', absint($item->ID), esc_attr($item->display_name));

		$actions = [
			'edit'		=> sprintf('<a href="?page=%s&user=%s">%s</a>', 'edit_cad', absint($item->ID), __('Edit')),
			'delete'	=> sprintf('<a href="?page=%s&action=%s&user=%s&_wpnonce=%s&_wp_http_referer=%s">%s</a>', esc_attr($_REQUEST['page']), 'delete', absint($item->ID), $delete_nonce,wp_get_referer(), __('Delete'))
		];

		return $title.$this->row_actions($actions);
	}

	/**
	 * [column_default description]
	 * @param  [type] $item        [description]
	 * @param  [type] $column_name [description]
	 * @return [type]              [description]
	 */
	public function column_default($item, $column_name)
	{
		switch($column_name){
			case 'avatar':
					$avatar_url = wp_get_attachment_url(get_the_author_meta( 'avatar_id', $item->ID));
					if(empty($avatar_url)) {
						return get_avatar($item->ID, 100);
					 }
				return sprintf('<img src="%s" width="%s" alt="Avatar" />', $avatar_url, '100px');
			case 'display_name':
				return $item->$column_name;
			case 'email':
				return get_the_author_meta('email', $item->ID);
			case 'idade':
				return get_user_age($item->ID);
			case 'insiders':
				return !empty(get_the_author_meta('insiders' , $item->ID, true)) ? count(get_the_author_meta('insiders' , $item->ID, true)) : __('Nenhuma');
			case 'sex':
				return (get_the_author_meta('sex', $item->ID) == 'm') ? 'Masculino' : 'Feminino';
			default:
				return print_r($item, true);
		}
	}

	/**
	 * [get_sortable_columns description]
	 * @return [type] [description]
	 */
	public function get_sortable_columns()
	{
		$sortable_columns = array(
			'display_name' => array('display_name', true)
		);

		return $sortable_columns;
	}

	/**
	 * [prepare_items description]
	 * @return [type] [description]
	 */
	public function prepare_items()
	{
		$this->process_bulk_action();
		$columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $data = $this->user_data();
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
		if('delete' === $this->current_action())
		{
			$nonce = esc_attr($_REQUEST['_wpnonce']);

			if(!wp_verify_nonce( $nonce, 'delete_user'))
			{
				die('Go get a life script kiddies');
			}
			else
			{
				wp_delete_user(absint($_GET['user']));
				$url = esc_url(wp_get_referer());
				?>
				<script type="text/javascript">
					<!--
					window.location= <?php echo "'" . htmlspecialchars_decode($url) . "'"; ?>;
					//-->
				</script>
				<?php
				exit();
			}
		}

		if((isset($_POST['action']) && $_POST['action'] == 'bulk-delete') || (isset($_POST['action2']) && $_POST['action2'] == 'bulk-delete'))
		{
			$delete_ids = $_POST['bulk-delete'];

			if(!empty($delete_ids)){
				foreach($delete_ids as $id){
//					wp_delete_user(absint($id));
				}
			}
			$url = esc_url($_POST['_wp_http_referer']);
			?>
			<script type="text/javascript">
				<!--
				window.location= <?php echo "'" . htmlspecialchars_decode($url) . "'"; ?>;
				//-->
			</script>
			<?php
			exit();
		}
	}
}
