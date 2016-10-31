<?php
require('../../../../wp-blog-header.php');
$post_id = $_GET['post_id'];
if(empty($post_id)) return;
$termo = get_post_meta($post_id,'_vhr_termo', true);
echo wpautop($termo);
?>
<style media="screen">
  body {
    font-family: serif;
    text-align: justify;
  }
</style>