<?php

add_action('init', 'register_campeonatos');

function register_campeonatos(){
    $singular_label = 'campeonato';
    $labels = array(
        'name'					=> __('Campeonatos'),
    		'singular_name'			=> __($singular_label),
    		'add_new'				=> __('Adicionar novo'),
    		'add_new_item'			=> __('Adicionar novo').' '.$singular_label,
    		'edit_item'				=> __('Editar').' '.$singular_label,
    		'new_item'				=> __('Novo').' '.$singular_label,
    		'view_item'				=> __('Ver').' '.$singular_label,
    		'search_items'			=> __('Procurar').' '.$singular_label,
    		'not_found'				=> __('Nada encontrado'),
    		'not_found_in_trash'	=> __('Nada encontrado no lixo')
    );

    $args = array(
        'labels'                => $labels,
        'public'				=> true,
        'publicly_queryable'	=> true,
        'show_ui'				=> true,
        'query_var'				=> true,
        'capability_type'		=> 'post',
        'hierarchical'			=> true,
        'menu_position'			=> 7,
        'menu_icon'				=> 'dashicons-awards',
        'has_archive'			=> true,
        'exclude_from_search'	=> true,
        'supports'				=> array('title', 'thumbnail', 'editor')
    );

    register_post_type( 'campeonatos', $args);

    $label = array(
        'name'              => 'Categorias',
        'singular_name'     => 'Categoria',
    );

    $arg = array(
        'rewrite'               => true,
        'hierarchical'          => true,
        'show_admin_column'     => true,
        'show_in_menu'          => false,
        'labels'                => $label
    );

    register_taxonomy('categoria', 'campeonatos', $arg);
}

add_action('init', 'register_eventos');

function register_eventos(){
  $singular_label = 'evento';
  $labels = array(
      'name'					=> __('Eventos'),
      'singular_name'			=> __($singular_label),
      'add_new'				=> __('Adicionar novo'),
      'add_new_item'			=> __('Adicionar novo').' '.$singular_label,
      'edit_item'				=> __('Editar').' '.$singular_label,
      'new_item'				=> __('Novo').' '.$singular_label,
      'view_item'				=> __('Ver').' '.$singular_label,
      'search_items'			=> __('Procurar').' '.$singular_label,
      'not_found'				=> __('Nada encontrado'),
      'not_found_in_trash'	=> __('Nada encontrado no lixo')
  );

  $args = array(
      'labels'                => $labels,
      'public'				        => true,
      'publicly_queryable'	  => true,
      'show_ui'			        	=> true,
      'query_var'				      => true,
      'capability_type'		    => 'post',
      'hierarchical'		    	=> true,
      'menu_position'			    => 7,
      'menu_icon'				      => 'dashicons-tickets-alt',
      'has_archive'			      => true,
      'exclude_from_search'	  => true,
      'supports'				      => array('title', 'thumbnail', 'editor')
  );

  register_post_type('eventos', $args);
}

add_action('init', 'register_faq');

function register_faq(){
  $singular_label = 'faq';
  $singular_name = "FAQ";
  $labels = array(
    'name'    => 'FAQ',
    'singular_name'   => $singular_name,
    'add_new'       => __('Adicionar novo'),
    'add_new_item'      => __('Adicionar novo').' '.$singular_label,
    'edit_item'       => __('Editar').' '.$singular_label,
    'new_item'        => __('Novo').' '.$singular_label,
    'view_item'       => __('Ver').' '.$singular_label,
    'search_items'      => __('Procurar').' '.$singular_label,
    'not_found'       => __('Nada encontrado'),
    'not_found_in_trash'  => __('Nada encontrado no lixo')
    );

  $args = array(
      'labels'                => $labels,
      'public'                => true,
      'publicly_queryable'    => true,
      'show_ui'               => true,
      'query_var'             => true,
      'capability_type'       => 'post',
      'hierarchical'          => true,
      'menu_position'         => 7,
      'menu_icon'             => 'dashicons-format-status',
      'has_archive'           => true,
      'exclude_from_search'   => true,
      'supports'              => array('title', 'editor')
    );
  register_post_type( 'faq', $args );
  flush_rewrite_rules();
}

add_action('init', 'register_pages');

function register_pages(){
        register_new_page('Campeonatos', '', 'archive-campeonatos.php');
        register_new_page('Editar Perfil', '','page-editar-perfil.php');
        register_new_page('Perfil', '', 'page-perfil.php');
        register_new_page('Login', '', 'page-login.php');
        register_new_page('Cadastro', '', 'page-cadastro.php');
        register_new_page('Inscrever', '', 'page-inscrever.php');
        register_new_page('Inscrições', '', 'page-inscricoes.php');
        register_new_page('Finalizar Cadastro', '', 'save-inscrito.php');
        register_new_page('Eventos', '', 'archive-eventos.php');
        register_new_page('FAQ', '', 'archive-faq.php');
        register_new_page('Fale Conosco', '', 'page-fale-conosco.php');
        register_new_page('Visualiza Inscrito', '', 'page-visualiza-inscrito.php');
        register_new_page('Retorno', '', 'retorno.php');
}

add_action('admin_init', 'register_terms');

function register_terms(){
    wp_insert_term( 'Guardas Esquivas', 'categoria', array('slug' => 'guardas'));
    wp_insert_term( 'Cassetete', 'categoria', array('slug' => 'cassetete'));
    wp_insert_term( 'Semi Contato', 'categoria', array('slug' => 'semi'));
    wp_insert_term( 'Kuo Shu Light', 'categoria', array('slug' => 'kuolight'));
    wp_insert_term( 'Kuo Shu Lei Tai', 'categoria', array('slug' => 'kuoleitai'));
    wp_insert_term( 'Wushu Sansou', 'categoria', array('slug' => 'wushu'));
    wp_insert_term( 'Sanda Profissional', 'categoria', array('slug' => 'sanda'));
    wp_insert_term( 'Shuai Jiao', 'categoria', array('slug' => 'shuai'));
    wp_insert_term( 'Muay Thai Amador', 'categoria', array('slug' => 'muaythai-a'));
    wp_insert_term( 'Muay Thai Profissional', 'categoria', array('slug' => 'muaythai-p'));
    wp_insert_term( 'MMA', 'categoria', array('slug' => 'mma'));
    wp_insert_term( 'CMMA', 'categoria', array('slug' => 'cmma'));
    wp_insert_term( 'Formas Livres', 'categoria', array('slug' => 'formaslivres'));
    wp_insert_term( 'Formas Internas', 'categoria', array('slug' => 'formasinternas'));
    wp_insert_term( 'Formas Tradicionais', 'categoria', array('slug' => 'formastradicionais'));
    wp_insert_term( 'Formas Olimpicas', 'categoria', array('slug' => 'formasolimpicas'));
    wp_insert_term( 'Tree Star Formas Profissional', 'categoria', array('slug' => 'tree'));
    wp_insert_term( 'Submission Infantil', 'categoria', array('slug' => 'submission-infantil'));
    wp_insert_term( 'Submission Adulto', 'categoria', array('slug' => 'submission-adulto'));
}

function register_academias(){
    $singular_label = 'academia';
    $label = array(
        'name'              => 'Academias',
        'singular_name'     => 'Academia',
        'add_new'       => __('Adicionar nova'),
        'add_new_item'      => __('Adicionar nova').' '.$singular_label,
        'edit_item'       => __('Editar').' '.$singular_label,
        'new_item'        => __('Nova').' '.$singular_label,
        'view_item'       => __('Ver').' '.$singular_label,
        'search_items'      => __('Procurar').' '.$singular_label,
    );

    $arg = array(
        'rewrite'               => true,
        'hierarchical'          => true,
        'show_admin_column'     => false,
        'show_ui'               => true,
        'labels'                => $label
    );

    register_taxonomy('academia', 'post', $arg);
}

add_action('init', 'register_academias');
