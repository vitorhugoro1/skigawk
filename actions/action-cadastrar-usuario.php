<?php

function vhr_cadastrar_usuario()
{
    $url = wp_get_referer();

    if (is_user_logged_in()) {
        wp_die('Não pode realizar está ação');
    }

    if (email_exists($_POST['mail']) || username_exists($_POST['mail'])) {
        wp_redirect($url);
    }

    check_admin_referer('vhr_cadastrar_usuario');

    $user_id = wp_insert_user(array(
        'user_login' => $_POST['mail'],
        'user_pass' => $_POST['password'],
        'first_name' => $_POST['name'],
        'user_email' => $_POST['mail'],
    ));

    if (is_wp_error($user_id)) {
        wp_redirect($url);
    } else {
        update_user_meta($user_id, 'birthday', $_POST['idade']);
        update_user_age_group($user_id);

        if (!empty($_POST['responsavel'])) {
            update_user_meta($user_id, 'responsavel', $_POST['responsavel']);
        }

        update_user_meta($user_id, 'phone', $_POST['phone']);
        update_user_meta($user_id, 'cellphone', $_POST['cellphone']);
        update_user_meta($user_id, 'sex', $_POST['sex']);
        update_user_meta($user_id, 'nacionalidade', $_POST['nacionalidade']);
        update_user_meta($user_id, 'cep', $_POST['cep']);
        update_user_meta($user_id, 'address', $_POST['address']);
        update_user_meta($user_id, 'addressnumber', $_POST['addressnumber']);
        update_user_meta($user_id, 'addresscomplement', $_POST['addresscomplement']);
        update_user_meta($user_id, 'district', $_POST['district']);
        update_user_meta($user_id, 'city', $_POST['city']);
        update_user_meta($user_id, 'state', $_POST['state']);

        if ($_POST['assoc'] == 'other') {
            wp_insert_term($_POST['assoc_other'], 'academia', array('slug' => sanitize_title($_POST['assoc_other'])));
            update_user_meta($user_id, 'assoc', sanitize_title($_POST['assoc_other']));
        } else {
            update_user_meta($user_id, 'assoc', $_POST['assoc']);
        }
        update_user_meta($user_id, 'estilo', $_POST['estilo']);
        update_user_meta($user_id, 'data-pratica', $_POST['data-pratica']);
        update_user_meta($user_id, 'exp', get_exp_user($_POST['data-pratica']));

        $modalidades = $_POST['modalidade'];
        foreach ($modalidades as $cat) {
            $periodo[$cat] = $_POST['data-' . $cat];
        }
        update_user_meta($user_id, 'modalidades', $periodo);
        update_user_option($user_id, 'show_admin_bar_front', false);

        $uploadedfile = $_FILES['avatar'];

        if ($uploadedfile['error'] == 0) {
            $upload_overrides = array('test_form' => false);
            $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
            if ($movefile && !isset($movefile['error'])) {
                // echo "File is valid, and was successfully uploaded.\n";
                // $filename should be the path to a file in the upload directory.
                $filename = $movefile['file'];

                // Check the type of file. We'll use this as the 'post_mime_type'.
                $filetype = $movefile['type'];

                // Get the path to the upload directory.
                $wp_upload_dir = wp_upload_dir();

                // Prepare an array of post data for the attachment.
                $attachment = array(
                    'guid'           => $wp_upload_dir['url'] . '/' . basename($filename),
                    'post_mime_type' => $filetype,
                    'post_title'     => preg_replace('/\.[^.]+$/', '', basename($filename)),
                    'post_content'   => '',
                    'post_status'    => 'inherit'
                );

                // Insert the attachment.
                $attach_id = wp_insert_attachment($attachment, $filename, $parent_post_id);

                update_user_meta($user_id, 'avatar_id', $attach_id);
            } else {
                /**
                 * Error generated by _wp_handle_upload()
                 * @see _wp_handle_upload() in wp-admin/includes/file.php
                 */
                echo $movefile['error'];

                return;
            }
        }

        $redirect = esc_url(home_url('/login')); // Link para a pagina de login

        send_inscricao($user_id);

        wp_redirect($redirect);
    }

    exit;
}

add_action('admin_post_vhr_cadastrar_usuario', 'vhr_cadastrar_usuario');
add_action('admin_post_nopriv_vhr_cadastrar_usuario', 'vhr_cadastrar_usuario');
