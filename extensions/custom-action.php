<?php

function save_dual_post(){
    global $post;
    global $wpdb;

    $prefix = $wpdb->prefix;
    $is_registered = $wpdb->get_results("SELECT ID FROM ".$prefix."posts WHERE post_title = '".$post->post_title."' AND post_type = 'inscritos'");
    if(empty($is_registered)){
          $new_post = wp_insert_post(
              array(
                  'post_type'         => 'inscritos',
                  'post_author'       => $post->post_author,
                  'post_date'         => $post->post_date,
                  'post_date_gmt'     => $post->post_date_gmt,
                  'post_title'        => $post->post_title,
                  'post_status'       => 'publish'
              )
          );
          update_post_meta($new_post, 'camp_id', $post->ID);
    }
}

// add_action('save_post', 'save_dual_post');
add_action('publish_eventos', 'save_dual_post');
add_action('publish_campeonatos', 'save_dual_post');
// add_action('draft_to_publish', 'save_dual_post', 10, 1);
// add_action('pending_to_publish', 'save_dual_post',10, 1);
