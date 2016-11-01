<?php

$user_list = new Usuarios();
$user_list->prepare_items();
$delete_nonce = wp_create_nonce('delete_user');
?>
<div class="wrap">
	<h2><?php echo get_admin_page_title(); ?></h2>
	<form method="post">
		<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
		<?php wp_nonce_field('delete_user'); ?>
		<?php
			wp_referer_field();
			$user_list->search_box(__('Search'), 'search_id');
			$user_list->display();
		 ?>
	</form>
</div>
