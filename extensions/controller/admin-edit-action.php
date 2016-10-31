<?php
function vhr_edit_user()
{
    if (!current_user_can('manage_options')) {
        wp_die('Não tem permissões para realizar está ação');
    }

    if (!isset($_POST['user_id'])) {
        wp_die('Não é possível atualizar sem usuário.');
    }

    check_admin_referer('vhr_edit_user');

    $user_id = $_POST['user_id'];

    update_user_age_group($user_id);

    $args = array(
        'ID' => $user_id,
        'user_email' => sanitize_email($_POST['mail']),
        'user_login' => sanitize_email($_POST['mail']),
        'display_name' => esc_attr($_POST['nome']),
    );

    $user_id = wp_update_user($args);

    if (is_wp_error($user_id)) {
        $url = wp_get_referer().'&m=0';
        wp_redirect($url);
    } else {
        update_user_meta($user_id, 'birthday', $_POST['idade']);
        update_user_age_group($user_id);

        if (!empty($_POST['responsavel'])) {
            update_user_meta($user_id, 'responsavel', $_POST['responsavel']);
        }

        update_user_meta($user_id, 'phone', $_POST['phone']);
        update_user_meta($user_id, 'cellphone', $_POST['cellphone']);
        update_user_meta($user_id, 'nacionalidade', $_POST['nacionalidade']);
        update_user_meta($user_id, 'cep', $_POST['cep']);
        update_user_meta($user_id, 'address', $_POST['address']);
        update_user_meta($user_id, 'addressnumber', $_POST['endnumber']);
        update_user_meta($user_id, 'addresscomplement', $_POST['endcomplement']);
        update_user_meta($user_id, 'district', $_POST['district']);
        update_user_meta($user_id, 'city', $_POST['city']);
        update_user_meta($user_id, 'state', $_POST['state']);
        update_user_meta($user_id, 'assoc', $_POST['assoc']);
        update_user_meta($user_id, 'estilo', $_POST['estilo']);
        update_user_meta($user_id, 'data-pratica', date('d/m/Y', strtotime($_POST['data-pratica'])));
        update_user_meta($user_id, 'exp', get_exp_user($_POST['data-pratica']));

        if (!empty($_POST['modalidade'])) {
            $modalidades = $_POST['modalidade'];

            foreach ($modalidades as $cat) {
                $periodo[$cat] = $_POST['data-'.$cat];
            }

            update_user_meta($user_id, 'modalidades', $periodo);
        }

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
            'guid' => $wp_upload_dir['url'].'/'.basename($filename),
            'post_mime_type' => $filetype,
            'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
            'post_content' => '',
            'post_status' => 'inherit',
          );

          // Insert the attachment.
          $attach_id = wp_insert_attachment($attachment, $filename, $parent_post_id);

                update_user_meta($user_id, 'avatar_id', $attach_id);
            }
        }
    }

    $url = wp_get_referer().'&m=1';
    wp_redirect($url);
    exit;
}

function update_user_age_group($user_id)
{
    $birth = date_create_from_format('d/m/Y', get_the_author_meta('birthday', $user_id));
    $birth = get_etaria_user($birth);

    $update = update_user_meta($user_id, 'fEtaria', $birth);

    return $update;
}
