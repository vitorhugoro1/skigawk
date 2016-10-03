<?php

require get_template_directory() . '/extensions/controller/manage-inscritos.php';
require get_template_directory() . '/extensions/controller/manage-users.php';

add_action('admin_menu', 'menus' );

function menus()
{

    add_submenu_page( 'edit.php?post_type=campeonatos', 'Gerenciar inscritos', 'Gerenciar Inscritos', 'manage_options', 'gerenciar_camp', 'gerenciar_screen' );

    add_submenu_page( 'edit.php?post_type=eventos', 'Gerenciar inscritos', 'Gerenciar Inscritos', 'manage_options', 'gerenciar_event', 'gerenciar_screen' );

    add_submenu_page( 'edit.php?post_type=campeonatos', 'Editar Inscrito', 'Editar Inscrito', 'manage_options', 'edit_icamp_cat', 'editar_insider' );

    add_submenu_page( 'edit.php?post_type=campeonatos', 'Editar Inscrito', 'Editar Inscrito', 'manage_options', 'edit_icamp_pag', 'editar_insider' );

    add_submenu_page( 'edit.php?post_type=eventos', 'Editar Inscrito', 'Editar Inscrito', 'manage_options', 'edit_ievent_pag', 'editar_insider' );

    add_users_page( 'Gerenciar Cadastrados', 'Gerenciar Cadastrados', 'manage_options', 'gerenciar_cad', 'gerenciar_cadastrados' );

    add_users_page( 'Editar Usuário', 'Editar Usuário', 'manage_options', 'edit_cad', 'editar_cad');

}


function gerenciar_cadastrados(){
  require('tela-users.php');
}

function gerenciar_screen(){
  require('tela-gerenciar.php');
}

function editar_cad(){
  require('tela-edit-user.php');
}

function editar_insider(){
  require('tela-edit-insider.php');
}

add_action( 'admin_menu', 'adjust_the_wp_menu', 999 );

function adjust_the_wp_menu() {

  $page = remove_submenu_page( 'edit.php?post_type=eventos', 'gerenciar_event' );

  remove_submenu_page( 'edit.php?post_type=campeonatos', 'gerenciar_camp' );

  remove_submenu_page( 'edit.php?post_type=eventos', 'edit_ievent_pag' );

  remove_submenu_page( 'edit.php?post_type=campeonatos', 'edit_icamp_cat' );

  remove_submenu_page( 'edit.php?post_type=campeonatos', 'edit_icamp_pag' );

  remove_submenu_page( 'users.php', 'edit_cad' );
  
  // $page[0] is the menu title

  // $page[1] is the minimum level or capability required

  // $page[2] is the URL to the item's file

}
