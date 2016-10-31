<?php

$wp_list_table = new Inscritos();
$wp_list_table->prepare_items();

?>
<div class="wrap">

  <h2>Gerenciar Inscritos do <b><?php echo ucfirst(get_the_title($_REQUEST['post_id'])); ?></b> </h2>

  <form method="post">
            <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
            <input type="hidden" name="post_type" value="<?php echo $_REQUEST['post_type'] ?>" />
            <input type="hidden" name="post_id" value="<?php echo $_REQUEST['post_id'] ?>" />
            <?php
              $wp_list_table->search_box(__('Search'), 'search_id');
              $wp_list_table->display();
             ?>
  </form>
</div>
