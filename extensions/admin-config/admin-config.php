<?php
function admin_config_inits(){
  $admin_email = get_option('admin_email');
  $define['titulo'] = 'Inscrição no site da SKIGAWK';
  $define['message'] = 'Você acaba de se cadastrar no site da SKIGAWK. Clique [redirect] para logar. <br> Se não foi você acesse [cancel] e nos informe para removermos seu e-mail e tomarmos as providências cabiveis.';
  $define['email-from'] = $admin_email;
  $define['redirect'] = 'aqui';
  $define['cancel'] = 'aqui';

  if( ! get_option('inscricao-email'))
    add_option( 'inscricao-email', serialize($define) );
}

add_action('init', 'admin_config_inits');

function admin_config_menus(){
  add_options_page('Configuração e-mail de respostas', 'Configuração e-mail de respostas', 'manage_options', 'admin_config', 'admin_config' );
}

add_action('admin_menu', 'admin_config_menus');

function admin_config(){
  $options = unserialize(get_option('inscricao-email'));

  if(isset($_POST['ficha'])){
    if($_POST['ficha'] == 'inscricao'){
      $options['titulo'] = (! empty($_POST['titulo'])) ? strip_tags($_POST['titulo']) : '';
      $options['redirect'] = (! empty($_POST['redirect'])) ? strip_tags($_POST['redirect']) : '';
      $options['cancel'] = (! empty($_POST['cancel'])) ? strip_tags($_POST['cancel']) : '';
      $options['message'] = (! empty($_POST['message'])) ? $_POST['message'] : '';
      $options['email-from'] = (! empty($_POST['email-from'])) ? sanitize_email($_POST['email-from']) : '';

      update_option('inscricao-email', serialize($options));
    }
  }
  ?>
  <div class="wrap">
    <h2><?php echo get_admin_page_title(); ?></h2>
    <form method="post">
      <input type="hidden" name="ficha" value="inscricao"/>
      <legend>Inscrição no site</legend>
      <table class="form-table">
        <tr>
          <th>
            <label for="titulo"> Título </label>
          </th>
          <td>
            <input type="text" name="titulo" id="titulo" class="regular-text" placeholder="Titulo do e-mail" value="<?php echo $options['titulo']; ?>" />
          </td>
        </tr>
        <tr>
          <th>
            <label for="message"> Mensagem </label>
          </th>
          <td>
            <textarea name="message" id="message" style="width:100%;"><?php echo $options['message']; ?></textarea>
            <p class="description">
              Aceita tags especiais de html, para adicionar os links de redirecionamento e de cancelamento faça: <br>
              <i>[redirect]</i> - Para mensagem de redirecionamento. <br>
              <i>[cancel]</i> - Para o link de cancelamento.
            </p>
          </td>
        </tr>
        <tr>
          <th>
            <label for="redirect">Texto redirect</label>
          </th>
          <td>
            <input type="text" name="redirect" id="redirect" class="regular-text" placeholder="Texto do redirect" value="<?php echo $options['redirect']; ?>" />
          </td>
        </tr>
        <tr>
          <th>
            <label for="cancel">Texto redirect</label>
          </th>
          <td>
            <input type="text" name="cancel" id="cancel" class="regular-text" placeholder="Texto do cancel" value="<?php echo $options['cancel']; ?>" />
          </td>
        </tr>
        <tr>
          <th>
            <label for="email-from">E-mail De: </label>
          </th>
          <td>
            <input type="email" name="email-from" id="email-from" class="regular-text" placeholder="email@email.com" value="<?php echo $options['email-from']; ?>"/>
          </td>
        </tr>
      </table>
      <?php submit_button( __('Save') ); ?>
    </form>
  </div>
  <?php
}

/**
 * Envia e-mail de confirmação de inscricão para o e-mail ($to)
 *
 * @param  string $to       E-mail para enviar.
 * @param  string $redirect URL para Logar.
 * @param  string $cancel   URL de cancelamento.
 */

function send_inscricao($to, $redirect, $cancel){
  $options = get_option('inscricao-email');

  $redirecionar = sprintf('<a href="%s">%s</a>',$redirect, $options['redirect']);
  $cancelar = sprintf('<a href="%s">%s</a>',$cancel, $options['cancel']);

  if( ! is_email( $to )) { return 'Erro: e-mail incorreto'; }

  $subject = $options['titulo'];
  $message = str_replace('[redirect]', $redirecionar, $options['message']);
  $message = str_replace('[cancel]', $cancelar, $options['message']);
  $headers = array(
    "From: Skigawk <{$options['email-from']}>". "\r\n",
    "MIME-Version: 1.0",
    "Content-type:text/html;charset=UTF-8"
  );

  $valid = wp_mail( $to, $subject, $message, $headers);

  if(is_wp_error( $valid ))
  {
    die($valid);
  }

}
