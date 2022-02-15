<?php

/**
 * The Header for Customizr.
 *
 * Displays all of the <head> section and everything up till <div id="main-wrapper">
 *
 * @package Customizr
 * @since Customizr 1.0
 */
?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<?php
//the '__before_body' hook is used by TC_header_main::$instance->tc_head_display()
do_action('__before_body');
?>

<body <?php body_class(); ?> <?php echo apply_filters('tc_body_attributes', 'itemscope itemtype="http://schema.org/WebPage"') ?>>
    <div class="modal">
        <!-- Place at bottom of page -->
    </div>
    <?php do_action('__before_page_wrapper');
    $user_role = get_current_user_role();
    $pages_ids = pages_group_ids();
    if (is_user_logged_in() && $user_role !== 'administrator') {
        $user = get_current_user_id();
        $user_name = get_the_author_meta('display_name', $user);
        $pos = strpos($user_name, " ");
        $user_name = (($pos == '') ? $user_name : substr($user_name, 0, $pos));
        $server = $_SERVER['SERVER_NAME'];
        $endereco = $_SERVER['REQUEST_URI'];
    ?>
        <div class="bar-top">
            <div>
                Olá, <?php echo $user_name; ?> | <a href="<?php echo get_permalink($pages_ids['perfil']); ?>">Perfil</a> | <a href="<?php echo get_permalink($pages_ids['inscricoes']); ?>">Inscrições</a> | <a href="<?php echo wp_logout_url("https://" . $server . $endereco); ?>">Sair</a>
            </div>
        </div>
    <?php } elseif (!is_user_logged_in()) { ?>
        <div class="bar-top">
            <div>
                <a href="<?php echo get_permalink($pages_ids['login']); ?>">Logar</a> | <a href="<?php echo get_permalink($pages_ids['cadastro']); ?>">Cadastre-se</a>
            </div>
        </div>
    <?php } ?>
    <div id="tc-page-wrap" class="<?php echo implode(" ", apply_filters('tc_page_wrap_class', array())) ?>">

        <?php do_action('__before_header'); ?>

        <header class="<?php echo implode(" ", apply_filters('tc_header_classes', array('tc-header', 'clearfix', 'row-fluid'))) ?>" role="banner">
            <?php
            // The '__header' hook is used with the following callback functions (ordered by priorities) :
            //TC_header_main::$instance->tc_logo_title_display(), TC_header_main::$instance->tc_tagline_display(), TC_header_main::$instance->tc_navbar_display()
            do_action('__header');
            ?>
        </header>

        <div id="tc-reset-margin-top" class="container-fluid"></div>