<?php

function vhr_event_user_term()
{
    if (!is_user_logged_in() || empty($_GET['post_id'])) {
        wp_die('Não tem permissão para realizar isto');
    }

    check_admin_referer('vhr_event_user_term'); ?>
    <style media="screen">
        body {
            font-family: serif;
            text-align: justify;
        }
    </style>
<?php
    echo wpautop(get_post_meta($_GET['post_id'], '_vhr_termo', true));
}

add_action('admin_post_vhr_event_user_term', 'vhr_event_user_term');
