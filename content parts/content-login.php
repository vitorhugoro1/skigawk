<?php
$pages_ids = pages_group_ids();

$redirect = $_GET['redirect'];
if(!isset($redirect)){
  $redirect = get_permalink($pages_ids['perfil']);
}

$args = array(
    'echo'           => true,
    'redirect'       => $redirect,
    'form_id'        => 'loginform',
    'label_username' => __( 'Nome de Usu&aacute;rio' ),
    'label_password' => __( 'Senha' ),
    'label_remember' => __( 'Lembrar de Mim' ),
    'label_log_in'   => __( 'Logar' ),
    'id_username'    => 'user_login',
    'id_password'    => 'user_pass',
    'id_remember'    => 'rememberme',
    'id_submit'      => 'wp-submit',
    'remember'       => true,
    'value_username' => NULL,
    'value_remember' => false
);
?>

<!-- Corpo -->
<section class="tc-content <?php echo $_layout_class; ?>">
    <?php do_action( '__before_content' ); ?>
        <div class="entry-content">
            <div class="hentry">
                <b>Utilize seu e-mail para acessar</b>
            <?php
            if ( $_REQUEST['login'] == 'failed' )
                echo '<p style="color:red;">Usu&aacute;rio ou senha incorretos.</p>';
            ?>
                <?php wp_login_form( $args ); ?>
                <a href="<?php echo wp_lostpassword_url( get_permalink($pages_ids['login']) ); ?>">Esqueci a senha</a><br>
                Sem usuario? <a href="<?php echo get_permalink($pages_ids['cadastro']);?>" class="alert-link">Clique aqui</a> e faça agora mesmo o cadastro.
            </div>
        </div>
    <?php do_action( '__after_content' ); ?>
</section>
