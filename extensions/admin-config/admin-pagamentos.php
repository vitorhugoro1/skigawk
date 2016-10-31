<?php

if(file_exists( __DIR__ . 'PagSeguroLibrary/PagSeguroLibrary.php')){
	require __DIR__ . 'PagSeguroLibrary/PagSeguroLibrary.php';
}

function init_pagamentos(){
  global $wpdb;

  $tableName = $wpdb->prefix.'payments';
  $wpdb->query("
          CREATE TABLE IF NOT EXISTS $tableName (
        `id` INT NOT NULL AUTO_INCREMENT,
        `user_id` BIGINT(20) NOT NULL,
        `post_id` BIGINT(20) NOT NULL,
        `valor` DOUBLE NOT NULL,
        `cat_inscricao` LONGTEXT NOT NULL,
        `meio_pag` VARCHAR(45) NOT NULL,
        `data_pag` DATE NOT NULL,
        PRIMARY KEY (`id`));"
      );

  $pagseguro = array(
    'nome'  => 'Vitor Hugo Rodrigues',
    'email' => 'vitorhugo.ro10@gmail.com',
    'token' => '0303FFE9CD2041AFB9251B194A45984F'
  );

  if( ! get_option('pagseguro')){
    add_option('pagseguro', serialize($pagseguro));
  }

  $deposito = array(
    'banco'   => 'Caixa Econômica',
    'agencia' => '123456-5',
    'conta'   => '32165-5',
    'beneficiario'  => 'Vitor Hugo R Merencio'
  );

  if( ! get_option('deposito')){
    add_option('deposito', serialize($deposito));
  }


}

add_action('init', 'init_pagamentos');

function pagamentos_menus(){
  add_menu_page( "Configurações de Pagamento", "Configurações de Pagamento", 'manage_options', 'payments-general', 'payments_general','dashicons-cart' );
  add_submenu_page( 'payments-general', 'Depositos', 'Depositos', 'manage_options', 'payments-deposito', 'payments_deposito' );
  add_submenu_page( 'payments-general', 'PagSeguro', 'PagSeguro', 'manage_options', 'payments-pagseguro', 'payments_pagseguro' );
  add_submenu_page( 'payments-general', 'Sobre', 'Sobre', 'manage_options', 'payments-about', 'payments_about' );
}

add_action( 'admin_menu', 'pagamentos_menus' );

function payments_general(){
  $pagseguro = unserialize(get_option('pagseguro'));
  $deposito = unserialize(get_option( 'deposito' ));
  ?>
  <div class="wrap">
    <h2><?php echo get_admin_page_title() ?></h2>
    <h2 class="nav-tab-wrapper">
      <a href="<?php echo admin_url('admin.php?page=payments-general'); ?>" class="nav-tab nav-tab-active"><?php echo __('General'); ?></a>
      <a href="<?php echo admin_url('admin.php?page=payments-deposito'); ?>" class="nav-tab"><?php echo __('Deposito'); ?></a>
      <a href="<?php echo admin_url('admin.php?page=payments-pagseguro'); ?>" class="nav-tab"><?php echo __('PagSeguro'); ?></a>
      <a href="<?php echo admin_url('admin.php?page=payments-about'); ?>" class="nav-tab"><?php echo __('About'); ?></a>
    </h2>
    <div class="wrap">
        <table class="form-table">
          <caption><b>PagSeguro</b></caption>
          <tbody>
            <tr>
              <th scope="row">
                <label for="nome">Nome para Exibição</label>
              </th>
              <td>
                <?php echo $pagseguro['nome'] ?>
              </td>
            </tr>
            <tr>
              <th scope="row">
                <label for="email">E-mail</label>
              </th>
              <td>
                <?php echo $pagseguro['email'] ?>
              </td>
            </tr>
            <tr>
              <th scope="row">
                <label for="token">Token</label>
              </th>
              <td>
                <?php echo $pagseguro['token'] ?>
              </td>
            </tr>
          </tbody>
        </table>
        <table class="form-table">
          <caption><b>Deposito</b></caption>
          <tbody>
            <tr>
              <th scope="row">
                <label for="beneficiario">Beneficiario</label>
              </th>
              <td>
                <?php echo $deposito['beneficiario'] ?>
              </td>
            </tr>
            <tr>
              <th scope="row">
                <label for="banco">Banco</label>
              </th>
              <td>
                <?php echo $deposito['banco'] ?>
              </td>
            </tr>
            <tr>
              <th scope="row">
                <label for="agencia">Agência</label>
              </th>
              <td>
                <?php echo $deposito['agencia'] ?>
              </td>
            </tr>
            <tr>
              <th scope="row">
                <label for="conta">Conta</label>
              </th>
              <td>
                <?php echo $deposito['conta'] ?>
              </td>
            </tr>
          </tbody>
        </table>
    </div>
  </div>
  <?php
}

function payments_about(){
  ?>
    <div class="wrap">
      <h2><?php echo get_admin_page_title() ?></h2>
      <h2 class="nav-tab-wrapper">
  	    <a href="<?php echo admin_url('admin.php?page=payments-general'); ?>" class="nav-tab"><?php echo __('General'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=payments-deposito'); ?>" class="nav-tab"><?php echo __('Deposito'); ?></a>
        <a href="<?php echo admin_url('admin.php?page=payments-pagseguro'); ?>" class="nav-tab"><?php echo __('PagSeguro'); ?></a>
  	    <a href="<?php echo admin_url('admin.php?page=payments-about'); ?>" class="nav-tab nav-tab-active"><?php echo __('About'); ?></a>
    	</h2>

    	<p class="wrap">
    		Plugin desenvolvido por <a href="https://github.com/vitorhugoro1/" target="_blank">Vitor Hugo Rodrigues Merencio</a> para Skigawk.
    	</p>
    </div>
  <?php
}

function payments_deposito(){
  $deposito = unserialize(get_option('deposito'));

  if(isset($_POST['beneficiario']) && isset($_POST['banco']) && isset($_POST['agencia']) && isset($_POST['conta'])){
    $deposito['beneficiario'] = strip_tags($_POST['beneficiario']);
    $deposito['banco'] = strip_tags($_POST['banco']);
    $deposito['agencia'] = strip_tags($_POST['agencia']);
    $deposito['conta'] = strip_tags($_POST['conta']);

    update_option( 'deposito', serialize($deposito) );
  }
  ?>
  <div class="wrap">
    <h2><?php echo get_admin_page_title(); ?></h2>

    <h2 class="nav-tab-wrapper">
      <a href="<?php echo admin_url('admin.php?page=payments-general'); ?>" class="nav-tab"><?php echo __('General'); ?></a>
      <a href="<?php echo admin_url('admin.php?page=payments-deposito'); ?>" class="nav-tab nav-tab-active"><?php echo __('Deposito'); ?></a>
      <a href="<?php echo admin_url('admin.php?page=payments-pagseguro'); ?>" class="nav-tab"><?php echo __('PagSeguro'); ?></a>
      <a href="<?php echo admin_url('admin.php?page=payments-about'); ?>" class="nav-tab"><?php echo __('About'); ?></a>
    </h2>
    <form method="post">
      <table class="form-table">
        <tbody>
          <tr>
            <th scope="row">
              <label for="beneficiario">Beneficiario</label>
            </th>
            <td>
              <?php echo sprintf('<input type="text" name="beneficiario" id="beneficiario" class="regular-text" value="%s">', $deposito['beneficiario']) ?>
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="banco">Banco</label>
            </th>
            <td>
              <?php echo sprintf('<input type="text" name="banco" id="banco" class="regular-text" value="%s">', $deposito['banco']) ?>
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="agencia">Agência</label>
            </th>
            <td>
              <?php echo sprintf('<input type="text" name="agencia" id="agencia" class="regular-text" value="%s">', $deposito['agencia']) ?>
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="conta">Conta</label>
            </th>
            <td>
              <?php echo sprintf('<input type="text" name="conta" id="conta" class="regular-text" value="%s">', $deposito['conta']) ?>
            </td>
          </tr>
        </tbody>
      </table>
      <?php submit_button( __('Save') ); ?>
    </form>
  </div>
  <?php
}

function payments_pagseguro(){
  $pagseguro = unserialize(get_option('pagseguro'));

  if(isset($_POST['nome']) && isset($_POST['email']) && isset($_POST['token'])){
    $pagseguro['nome'] = strip_tags($_POST['nome']);
    $pagseguro['email'] = sanitize_email( $_POST['email'] );
    $pagseguro['token'] = strip_tags($_POST['token']);

    update_option( 'pagseguro', serialize($pagseguro) );
  }
  ?>
  <div class="wrap">
    <h2><?php echo get_admin_page_title(); ?></h2>

    <h2 class="nav-tab-wrapper">
      <a href="<?php echo admin_url('admin.php?page=payments-general'); ?>" class="nav-tab"><?php echo __('General'); ?></a>
      <a href="<?php echo admin_url('admin.php?page=payments-deposito'); ?>" class="nav-tab"><?php echo __('Deposito'); ?></a>
      <a href="<?php echo admin_url('admin.php?page=payments-pagseguro'); ?>" class="nav-tab  nav-tab-active"><?php echo __('PagSeguro'); ?></a>
      <a href="<?php echo admin_url('admin.php?page=payments-about'); ?>" class="nav-tab"><?php echo __('About'); ?></a>
    </h2>
    <form method="post">
      <table class="form-table">
        <tbody>
          <tr>
            <th scope="row">
              <label for="nome">Nome para Exibição</label>
            </th>
            <td>
              <?php echo sprintf('<input type="text" name="nome" id="nome" class="regular-text" value="%s">', $pagseguro['nome']) ?>
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="email">E-mail</label>
            </th>
            <td>
              <?php echo sprintf('<input type="email" name="email" id="email" class="regular-text" value="%s">', $pagseguro['email']) ?>
            </td>
          </tr>
          <tr>
            <th scope="row">
              <label for="token">Token</label>
            </th>
            <td>
              <?php echo sprintf('<input type="text" name="token" id="token" class="regular-text" value="%s">', $pagseguro['token']) ?>
            </td>
          </tr>
        </tbody>
      </table>
      <?php submit_button( __('Save') ); ?>
    </form>
  </div>
  <?php
}
