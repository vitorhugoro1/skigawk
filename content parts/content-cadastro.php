<?php
$pages_ids = pages_group_ids();
 if (!email_exists($_POST['id_mail']) && !username_exists($_POST['id_mail'])) {
     $user_id = wp_insert_user(array(
         'user_login' => $_POST['id_mail'],
         'user_pass' => $_POST['id_password'],
         'first_name' => $_POST['id_name'],
         'user_email' => $_POST['id_mail'],
       ));

     if (!is_wp_error($user_id)) {
         update_user_meta($user_id, 'birthday', date('d/m/Y', strtotime($_POST['id_datanasc'])));
         $birth = date_create_from_format('d/m/Y', get_the_author_meta('birthday', $user_id));
         $birth = get_etaria_user($birth);

         update_user_meta($user_id, 'fEtaria', $birth);
         if ($birth !== 'adulto') {
             update_user_meta($user_id, 'responsavel', $_POST['id_responsavel']);
         }
         update_user_meta($user_id, 'phone', $_POST['id_tel']);
         update_user_meta($user_id, 'cellphone', $_POST['id_cellphone']);
         update_user_meta($user_id, 'sex', $_POST['id_sex']);
         update_user_meta( $user_id, 'nacionalidade', $_POST['nacionalidade']);
         update_user_meta($user_id, 'cep', $_POST['id_cep']);
         update_user_meta($user_id, 'address', $_POST['id_end']);
         update_user_meta($user_id, 'addressnumber', $_POST['id_endnumber']);
         update_user_meta($user_id, 'addresscomplement', $_POST['id_endcomplement']);
         update_user_meta($user_id, 'district', $_POST['id_district']);
         update_user_meta($user_id, 'city', $_POST['id_city']);
         update_user_meta($user_id, 'state', $_POST['id_state']);
         if($_POST['id_assoc'] == 'other'){
             wp_insert_term( $_POST['assoc_other'], 'academia', array('slug' => sanitize_title($_POST['assoc_other'])));
             update_user_meta($user_id, 'assoc', sanitize_title($_POST['assoc_other']));
         } else {
             update_user_meta($user_id, 'assoc', $_POST['id_assoc']);
         }
         update_user_meta($user_id, 'estilo', $_POST['id_estilo']);
         update_user_meta($user_id, 'data-pratica', date('d/m/Y', strtotime($_POST['data-pratica'])));
         update_user_meta($user_id, 'exp', get_exp_user($_POST['data-pratica']));

         $modalidades = $_POST['id_modalidade'];
         foreach ($modalidades as $cat) {
             $periodo[$cat] = $_POST['data-'.$cat];
         }
         update_user_meta($user_id, 'modalidades', $periodo);
         update_user_option($user_id, 'show_admin_bar_front', false);

          $uploadedfile = $_FILES['avatar'];

          $uploadedfile = $_FILES['avatar'];

          if($uploadedfile['error'] == 0 ){
            $upload_overrides = array( 'test_form' => false );
            $movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
            if ( $movefile && ! isset( $movefile['error'] ) ) {
                // echo "File is valid, and was successfully uploaded.\n";
                // $filename should be the path to a file in the upload directory.
                $filename = $movefile['file'];

                // Check the type of file. We'll use this as the 'post_mime_type'.
                $filetype = $movefile['type'];

                // Get the path to the upload directory.
                $wp_upload_dir = wp_upload_dir();

                // Prepare an array of post data for the attachment.
                $attachment = array(
                  'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
                  'post_mime_type' => $filetype,
                  'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
                  'post_content'   => '',
                  'post_status'    => 'inherit'
                );

                // Insert the attachment.
                $attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );

                update_user_meta( $user->ID, 'avatar_id', $attach_id );
              } else {
                  /**
                   * Error generated by _wp_handle_upload()
                   * @see _wp_handle_upload() in wp-admin/includes/file.php
                   */
                  echo $movefile['error'];

                  return;
              }
          }

         $redirect = get_permalink($pages_ids['login']); // Link para a pagina de login
         $fale = get_permalink($pages_ids['fale-conosco']);

         /**
          * Envio de e-mail de confirmação da inscrição no campeonato ou evento
          */
         $to = sanitize_email($_POST['id_mail']);
         send_inscricao($to, $redirect, $fale);

         scriptRedirect($redirect);
     } else {
         $error = true;
     }
 }
?>
<section class="tc-content <?php echo $_layout_class; ?>">
    <?php do_action('__before_content'); ?>
        <div class="entry-content">
          <div class="hentry">
              <?php the_content( $more_link_text = null, $strip_teaser = false ); ?>
              <form action="<?php echo get_permalink($pages_ids['cadastro']);?>" method="post" enctype="multipart/form-data" encoding="multipart/form-data">
                  <div class="clear">
                  <label for="id_name"><b>Nome completo</b> <span class="red">*</span><br>
                      <input type="text" name="id_name" id="id_name" placeholder="Nome Completo" value="" required>
                  </label>
                  <label for="id_mail"><b>Email</b><span class="red">*</span><br>
                      <input type="email" name="id_mail" id="id_mail" placeholder="email@email.com" value="" required>
                  </label>
                  <label for="id_password"><b>Senha Nova</b><br>
                      <input type="password" id="id_password" name="id_password" placeholder="Senha" value="" required>
                  </label>
                  <label for="confirm_password"><b>Confirmar Senha Nova</b><br>
                      <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirme a senha" value="" required>
                      <div id="confirm" class="hide"></div>
                  </label>
                  <label for="dateNasc" class="alignleft"><b>Data de nascimento</b><span class="red">*</span><br>
                    <input type="text" class="input-medium" id="dateNasc" name="id_datanasc" required>
                  </label>
                  <label for="id_sex" class="alignleft label-mod margin-10"><b>Sexo</b> <span class="red">*</span><br>
                      <input type="radio" name="id_sex" id="id_masc" value="m" required> <label for="id_masc">Masculino</label>
                      <input type="radio" name="id_sex" id="id_fem" value="f"> <label for="id_fem"> Feminino</label>
                  </label><br><br>
                  <div class="clearfix"></div>
                  <label id="responsavel" for="id_responsavel"><b>Responsavel</b><span class="red">*</span><br>
                      <input type="text" name="id_responsavel" disabled value="">
                  </label>
                  <div class="row-fluid">
                    <label for="nacionalidade" class="alignleft"><b>Nacionalidade</b><br>
                      <select class="" name="nacionalidade" id="nacionalidade">
                        <option value="">Selecione um País</option>
                        <option value="br">Brasil</option>
                        <option value="py">Paraguai</option>
                        <option value="ra">Argentina</option>
                      </select>
                    </label>
                    <label for="cep" class="alignleft margin-10"><b>CEP/Zip Code</b><br>
                        <input type="text" name="id_cep" id="cep" class="input-medium" value="" required>
                    </label>
                  </div>
                  <label for="address"><b>Endereço</b><br>
                      <input type="text" id="address" name="id_end" readonly>
                  </label>
                  <label for="id_endnumber"><b>Número</b><br>
                      <input type="text" id="id_endnumber" name="id_endnumber" value="" required>
                  </label>
                  <label for="id_endcomplement"><b>Complemento</b><br>
                    <input type="text" name="id_endcomplement" id="id_endcomplement" value="">
                  </label>
                  <label for="id_district"><b>Bairro</b><br>
                      <input type="text" id="district" name="id_district" readonly>
                  </label>
                  <label for="id_city"><b>Cidade</b><br>
                      <input type="text" id="city" name="id_city" readonly>
                  </label>
                  <label for="id_state"><b>Estado</b><br>
                      <input type="text" id="state" name="id_state" readonly>
                  </label>
                  <label for="phone"><b>Telefone</b><br>
                      <input type="text" id="phone" name="id_tel" value="">
                  </label>
                  <label for="cellphone"><b>Celular</b><br>
                      <input type="text" id="cellphone" name="id_cellphone" value="">
                  </label>
                  <label for="avatar"><b>Avatar</b><br>
                    <input type="file" name="avatar" id="avatar" />
                  </label>
                  <label for="id_assoc"><b>Nome da Associação</b><br>
                      <select name="id_assoc" class="input-xxlarge" id="id_assoc" required>
                          <option value="">Selecione uma associação</option>
                          <?php
                          $assoc = get_terms('academia', array('fields'   => 'all', 'hide_empty' => false));
                          foreach($assoc as $term){
                              ?>
                              <option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
                              <?php
                          }
                          ?>
                              <option value="other">Outra</option>
                      </select>
                  </label>
                  <label for="id_estilo"><b>Estilo Principal</b><br>
                      <input type="text" id="id_estilo" name="id_estilo" value="" required>
                  </label>
                  <label for="inicio-pratica">
                      <b>Data que começou a praticar</b>
                      <input type="date" name="data-pratica" id="inicio-pratica" class="input-medium" max="<?php echo date('Y-m-d'); ?>" required/>
                  </label>
                  <label for="id_modalidade">
                    <b>Qual(is) modalidade(s) é praticante?</b>
                    <ul class="modalidade">
                      <?php
                           $list = get_terms('categoria', array('fields' => 'all', 'hide_empty' => false));
                           foreach ($list as $term) {
                               ?>
                               <li>
                               <input type="checkbox" id="<?php echo $term->slug; ?>" name="id_modalidade[]" value="<?php echo $term->slug; ?>" />
                               <?php echo $term->name; ?>
                               <div id="<?php echo $term->slug; ?>">
                                  <ul id="categoria" class="categoria">

                                    <li><input type="radio" name="data-<?php echo $term->slug;?>" value="novato">Novato (até 01 ano)</li>

                                    <li><input type="radio" name="data-<?php echo $term->slug;?>" value="iniciante">Iniciante (até 2 anos)</li>

                                    <li><input type="radio" name="data-<?php echo $term->slug;?>" value="intermediario">Intermediário (até 3 anos)</li>

                                    <li><input type="radio" name="data-<?php echo $term->slug;?>" value="avancado">Avançado (acima de 4 anos)</li>

                                  </ul>
                                </div>
                               </li>
                      <?php

                           }
                       ?>
                    </ul>
                  </label>
                  <br>
                  </div>
                  <div class="input-submit-fix">
                      <input type="submit" class="btn btn-primary fp-button" value="Cadastrar">
                  </div>
            </form>
        </div>
                </div>
    <?php do_action('__after_content'); ?>
</section>
