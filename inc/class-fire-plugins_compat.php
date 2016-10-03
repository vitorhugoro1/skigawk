<?php
/**
* Handles various plugins compatibilty (Jetpack, Bbpress, Qtranslate, Woocommerce, The Event Calendar ...)
*
* @package      Customizr
* @subpackage   classes
* @since        3.3+
* @author       Nicolas GUILLAUME <nicolas@presscustomizr.com>
* @copyright    Copyright (c) 2013-2015, Nicolas GUILLAUME
* @link         http://presscustomizr.com/customizr
* @license      http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/
if ( ! class_exists( 'TC_plugins_compat' ) ) :
  class TC_plugins_compat {
    //Access any method or var of the class with classname::$instance -> var or method():
    static $instance;
    //credits @Srdjan
    public $default_language, $current_language;

    function __construct () {

      self::$instance =& $this;
      //add various plugins compatibilty (Jetpack, Bbpress, Qtranslate, Woocommerce, The Event Calendar ...)
      add_action ('after_setup_theme'          , array( $this , 'tc_set_plugins_supported'), 20 );
      add_action ('after_setup_theme'          , array( $this , 'tc_plugins_compatibility'), 30 );
      // remove qtranslateX theme options filter
      remove_filter('option_tc_theme_options', 'qtranxf_translate_option', 5);
    }//end of constructor



    /**
    * Set plugins supported ( before the plugin compat function is fired )
    * => allows to easily remove support by firing remove_theme_support() (with a priority < tc_plugins_compatibility) on hook 'after_setup_theme'
    * hook : after_setup_theme
    *
    * @package Customizr
    * @since Customizr 3.3+
    */
    function tc_set_plugins_supported() {
      //add support for plugins (added in v3.1+)
      add_theme_support( 'jetpack' );
      add_theme_support( 'bbpress' );
      add_theme_support( 'buddy-press' );
      add_theme_support( 'qtranslate-x' );
      add_theme_support( 'polylang' );
      add_theme_support( 'wpml' );
      add_theme_support( 'woocommerce' );
      add_theme_support( 'the-events-calendar' );
      add_theme_support( 'optimize-press' );
      add_theme_support( 'sensei' );
      add_theme_support( 'visual-composer' );//or js-composer as they call it
      add_theme_support( 'disqus' );
    }



    /**
    * This function handles the following plugins compatibility : Jetpack (for the carousel addon), Bbpress, Qtranslate, Woocommerce
    *
    * @package Customizr
    * @since Customizr 3.0.15
    */
    function tc_plugins_compatibility() {
      /* JETPACK */
      //adds compatibilty with the jetpack image carousel
      if ( current_theme_supports( 'jetpack' ) && $this -> tc_is_plugin_active('jetpack/jetpack.php') )
        add_filter( 'tc_gallery_bool', '__return_false' );

      /* BBPRESS */
      //if bbpress is installed and activated, we can check the existence of the contextual boolean function is_bbpress() to execute some code
      if ( current_theme_supports( 'bbpress' ) && $this -> tc_is_plugin_active('bbpress/bbpress.php') )
        $this -> tc_set_bbpress_compat();

      /* BUDDYPRESS */
      //if buddypress is installed and activated, we can check the existence of the contextual boolean function is_buddypress() to execute some code
      // we have to use buddy-press instead of buddypress as string for theme support as buddypress makes some checks on current_theme_supports('buddypress') which result in not using its templates
      if ( current_theme_supports( 'buddy-press' ) && $this -> tc_is_plugin_active('buddypress/bp-loader.php') )
        $this -> tc_set_buddypress_compat();

      /*
      * QTranslatex
      * Credits : @acub, http://websiter.ro
      */
      if ( current_theme_supports( 'qtranslate-x' ) && $this -> tc_is_plugin_active('qtranslate-x/qtranslate.php') )
        $this -> tc_set_qtranslatex_compat();

      /*
      * Polylang
      * Credits : Rocco Aliberti
      */
      if ( current_theme_supports( 'polylang' ) && $this -> tc_is_plugin_active('polylang/polylang.php') )
        $this -> tc_set_polylang_compat();

      /*
      * WPML
      */
      if ( current_theme_supports( 'wpml' ) && $this -> tc_is_plugin_active('sitepress-multilingual-cms/sitepress.php') )
        $this -> tc_set_wpml_compat();

      /* The Events Calendar */
      if ( current_theme_supports( 'the-events-calendar' ) && $this -> tc_is_plugin_active('the-events-calendar/the-events-calendar.php') )
        $this -> tc_set_the_events_calendar_compat();

      /* Optimize Press */
      if ( current_theme_supports( 'optimize-press' ) && $this -> tc_is_plugin_active('optimizePressPlugin/optimizepress.php') )
        $this -> tc_set_optimizepress_compat();

      /* Woocommerce */
      if ( current_theme_supports( 'woocommerce' ) && $this -> tc_is_plugin_active('woocommerce/woocommerce.php') )
        $this -> tc_set_woocomerce_compat();

      /* Sensei woocommerce addon */
      if ( current_theme_supports( 'sensei') && $this -> tc_is_plugin_active('woothemes-sensei/woothemes-sensei.php') )
        $this -> tc_set_sensei_compat();

      /* Visual Composer */
      if ( current_theme_supports( 'visual-composer') && $this -> tc_is_plugin_active('js_composer/js_composer.php') )
        $this -> tc_set_vc_compat();

      /* Disqus Comment System */
      if ( current_theme_supports( 'disqus') && $this -> tc_is_plugin_active('disqus-comment-system/disqus.php') )
        $this -> tc_set_disqus_compat();

    }//end of plugin compatibility function



    /**
    * BBPress compat hooks
    *
    * @package Customizr
    * @since Customizr 3.3+
    */
    private function tc_set_bbpress_compat() {
      // hide tax archive title
      add_filter( 'tc_show_tax_archive_title', 'tc_bbpress_disable_tax_archive_title');
      function tc_bbpress_disable_tax_archive_title( $bool ){
        return ( function_exists('is_bbpress') && is_bbpress() ) ? false : $bool;
      }

      //disables thumbnails and excerpt for post lists
      add_filter( 'tc_show_post_list_thumb', 'tc_bbpress_disable_thumbnail' );
      function tc_bbpress_disable_thumbnail($bool) {
         return ( function_exists('is_bbpress') && is_bbpress() ) ? false : $bool;
      }
      add_filter( 'tc_show_excerpt', 'tc_bbpress_disable_excerpt' );
      function tc_bbpress_disable_excerpt($bool) {
         return ( function_exists('is_bbpress') && is_bbpress() ) ? false : $bool;
      }

      //disables Customizr author infos on forums
      add_filter( 'tc_show_author_metas_in_post', 'tc_bbpress_disable_author_meta' );
      function tc_bbpress_disable_author_meta($bool) {
        return ( function_exists('is_bbpress') && is_bbpress() ) ? false : $bool;
      }

      //disables post navigation
      add_filter( 'tc_show_post_navigation', 'tc_bbpress_disable_post_navigation' );
      function tc_bbpress_disable_post_navigation($bool) {
         return ( function_exists('is_bbpress') && is_bbpress() ) ? false : $bool;
      }

      //disables post metas
      add_filter( 'tc_show_post_metas', 'tc_bbpress_disable_post_metas', 100);
      function tc_bbpress_disable_post_metas($bool) {
         return ( function_exists('is_bbpress') && is_bbpress() ) ? false : $bool;
      }

      //disable the grid
      add_filter( 'tc_set_grid_hooks' , 'tc_bbpress_disable_grid', 100 );
      function tc_bbpress_disable_grid($bool) {
         return ( function_exists('is_bbpress') && is_bbpress() ) ? false : $bool;
      }
    }

    /**
    * BuddyPress compat hooks
    *
    * @package Customizr
    * @since Customizr 3.3+
    */
    private function tc_set_buddypress_compat() {
      add_filter( 'tc_are_comments_enabled', 'tc_buddypress_disable_comments' );
      function tc_buddypress_disable_comments($bool){
        return ( is_page() && function_exists('is_buddypress') && is_buddypress() ) ? false : $bool;
      }
    }

    /**
    * QtranslateX compat hooks
    *
    * @package Customizr
    * @since Customizr 3.3+
    */
    private function tc_set_qtranslatex_compat() {
      function tc_url_lang($url) {
        return ( function_exists( 'qtrans_convertURL' ) ) ? qtrans_convertURL($url) : $url;
      }
      function tc_apply_qtranslate ($text) {
        return call_user_func(  '__' , $text );
      }
      function tc_remove_char_limit() {
        return 99999;
      }
      function tc_change_transport( $value , $set ) {
        return ('transport' == $set) ? 'refresh' : $value;
      }

      //outputs correct urls for current language : in logo, slider
      foreach ( array( 'tc_slide_link_url', 'tc_logo_link_url') as $filter )
        add_filter( $filter, 'tc_url_lang' );

      //outputs the qtranslate translation for slider
      foreach ( array( 'tc_slide_title', 'tc_slide_text', 'tc_slide_button_text', 'tc_slide_background_alt' ) as $filter )
        add_filter( $filter, 'tc_apply_qtranslate' );
      //sets no character limit for slider (title, lead text and button title) => allow users to use qtranslate tags for as many languages they wants ([:en]English text[:de]German text...and so on)
      foreach ( array( 'tc_slide_title_length', 'tc_slide_text_length', 'tc_slide_button_length' ) as $filter )
        add_filter( $filter  , 'tc_remove_char_limit');

      //outputs the qtranslate translation for archive titles;
      $tc_archive_titles = array( 'tag_archive', 'category_archive', 'author_archive', 'search_results');
      foreach ( $tc_archive_titles as $title )
        add_filter("tc_{$title}_title", 'tc_apply_qtranslate' , 20);

      // QtranslateX for FP when no FPC or FPU running
      if ( ! class_exists('TC_fpu') && ! class_exists('TC_fpc') ) {
        //outputs correct urls for current language : fp
        add_filter( 'tc_fp_link_url' , 'tc_url_lang');
        //outputs the qtranslate translation for featured pages
        add_filter( 'tc_fp_text', 'tc_apply_qtranslate' );
        add_filter( 'tc_fp_button_text', 'tc_apply_qtranslate' );

        /* The following is pretty useless at the momment since we should inhibit preview js code */
        //modify the customizer transport from post message to null for some options
        add_filter( 'tc_featured_page_button_text_customizer_set' , 'tc_change_transport', 20, 2);
        add_filter( 'tc_featured_text_one_customizer_set' , 'tc_change_transport', 20, 2);
        add_filter( 'tc_featured_text_two_customizer_set' , 'tc_change_transport', 20, 2);
        add_filter( 'tc_featured_text_three_customizer_set', 'tc_change_transport', 20, 2);
      }

      //posts slider (this filter is not fired in admin )
      add_filter('tc_posts_slider_pre_model', 'tc_posts_slider_qtranslate');
      function tc_posts_slider_qtranslate( $pre_slides ){
        if ( empty($pre_slides) )
          return $pre_slides;

        // remove useles q-translation of the slider view
        foreach ( array( 'tc_slide_title', 'tc_slide_text', 'tc_slide_button_text', 'tc_slide_background_alt' ) as $filter )
          remove_filter( $filter, 'tc_apply_qtranslate' );

        // allow q-translation pre trim/sanitize
        foreach ( array( 'tc_posts_slider_button_text_pre_trim', 'tc_post_title_pre_trim', 'tc_post_excerpt_pre_sanitize', 'tc_posts_slide_background' ) as $filter )
          add_filter( $filter, 'tc_apply_qtranslate' );

        //translate button text
        $pre_slides['common']['button_text'] = $pre_slides['common']['button_text'] ? TC_slider::$instance -> tc_get_post_slide_button_text( $pre_slides['common']['button_text'] ) : '';

        //translate title and excerpt if needed
        $_posts = &$pre_slides['posts'];

        foreach ($_posts as &$_post) {
          $ID = $_post['ID'];
          $_p = get_post( $ID );
          if ( ! $_p ) continue;

          $_post['title'] = $_post['title'] ? TC_slider::$instance -> tc_get_post_slide_title($_p, $ID) : '';
          $_post['text']  = $_post['text'] ? TC_slider::$instance -> tc_get_post_slide_excerpt($_p, $ID) : '';
        }
        return $pre_slides;
      }
    }


    /**
    * Polylang compat hooks
    *
    * @package Customizr
    * @since Customizr 3.3+
    */
    private function tc_set_polylang_compat() {
      // Disable posts slider transient caching
      add_filter('tc_posts_slider_use_transient', '__return_false');

      // If Polylang is active, hook function on the admin pages
      if ( function_exists( 'pll_register_string' ) )
        add_action( 'admin_init', 'tc_pll_strings_setup' );

      function tc_pll_strings_setup() {
        // grab theme options
        $tc_options = tc__f('__options');
        // grab settings map, useful for some options labels
        $tc_settings_map = TC_utils_settings_map::$instance -> tc_get_customizer_map( $get_default = true );
        $tc_controls_map = $tc_settings_map['add_setting_control'];
        // set $polylang_group;
        $polylang_group = 'customizr-pro' == TC___::$theme_name ? 'Customizr-Pro' : 'Customizr';

        //get options to translate
        $tc_translatable_raw_options = TC_plugins_compat::$instance -> tc_get_string_options_to_translate();
        $tc_pll_options              = array();

        //build array if option => array( label (gettext-ed), option )
        foreach ( $tc_translatable_raw_options as $tc_translatable_option )
          if ( isset( $tc_options[$tc_translatable_option] ) ) {
            switch ( $tc_translatable_option ) {
              case 'tc_front_slider'             : $label = __( 'Front page slider name', 'customizr' );
                                                   break;
              case 'tc_posts_slider_button_text' : $label = __( 'Posts slider button text', 'customizr' );
                                                   break;
              default:                             $label = $tc_controls_map[$tc_translatable_option]['label'];
                                                   break;
            }//endswitch
            $tc_pll_options[$tc_translatable_option]= array(
                'label'  => $label,
                'value'  => $tc_options[$tc_translatable_option]
            );
          }

        //register the strings to translate
        foreach ( $tc_pll_options as $tc_pll_option )
          pll_register_string( $tc_pll_option['label'], $tc_pll_option['value'], $polylang_group);
      }// end tc_pll_strings_setup function

      // Front
      // If Polylang is active, translate/swap featured page buttons/text/link and slider
      if ( function_exists( 'pll_get_post' ) && function_exists( 'pll__' ) && ! is_admin() ) {
        //strings translation
        //get the options to translate
        $tc_translatable_options = TC_plugins_compat::$instance -> tc_get_string_options_to_translate();
        //translate
        foreach ( $tc_translatable_options as $tc_translatable_option )
          add_filter("tc_opt_$tc_translatable_option", 'pll__');

        /**
        * Tax filtering (home/blog posts filtered by cat)
        * @param array of term ids
        */
        function tc_pll_translate_tax( $term_ids ){
          if ( ! ( is_array( $term_ids ) && ! empty( $term_ids ) ) )
            return $term_ids;

          $translated_terms = array();
          foreach ( $term_ids as $id ){
              $translated_term = pll_get_term( $id );
              $translated_terms[] = $translated_term ? $translated_term : $id;
          }
          return array_unique( $translated_terms );
        }

        //Translate category ids for the filtered posts in home/blog
        add_filter('tc_opt_tc_blog_restrict_by_cat', 'tc_pll_translate_tax');
        /*end tax filtering*/

        /* Slider of posts */
        if ( function_exists( 'pll_current_language') ) {
        // Filter the posts query for the current language
          add_filter( 'tc_query_posts_slider_join'      , 'pll_posts_slider_join' );
          add_filter( 'tc_query_posts_slider_join_where', 'pll_posts_slider_join' );
        }
        function pll_posts_slider_join( $join ) {
          global $wpdb;
          switch ( current_filter() ){
            case 'tc_query_posts_slider_join'        : $join .= " INNER JOIN $wpdb->term_relationships AS pll_tr";
                                                       break;
            case 'tc_query_posts_slider_join_where'  : $_join = $wpdb->prepare("pll_tr.object_id = posts.ID AND pll_tr.term_taxonomy_id=%d ",
                                                                                pll_current_language( 'term_taxonomy_id' )
                                                       );
                                                       $join .= $join ? 'AND ' . $_join : 'WHERE '. $_join;
                                                       break;
          }

          return $join;
        }
        /*end Slider of posts */

        //Featured pages ids "translation"
        // Substitute any page id with the equivalent page in current language (if found)
        add_filter( 'tc_fp_id', 'tc_pll_page_id', 20 );
        function tc_pll_page_id( $fp_page_id ) {
          return is_int( pll_get_post( $fp_page_id ) ) ? pll_get_post( $fp_page_id ) : $fp_page_id;
        }
      }//end Front
    }//end polylang compat


    /**
    * WPML compat hooks
    *
    * @package Customizr
    * @since Customizr 3.4+
    */
    private function tc_set_wpml_compat() {
      //credits : @Srdjan
      $this->default_language = apply_filters( 'wpml_default_language', null );
      $this->current_language = apply_filters( 'wpml_current_language', null );

      // Disable posts slider transient caching
      add_filter('tc_posts_slider_use_transient', '__return_false');
      //define the CONSTANT wpml context. This means that user have to set the translations again when switching from Customizr, to Customizr-Pro.
      //If we don't want to do this, let's go with 'Customizr-option' in any case.
      //Also I choose to use "-option" suffix to avoid confusions as with WPML you can also translate theme's strings ( gettexted -> __() ) and WPML by default assigns to theme the context 'customizr' (textdomain)
      define( 'TC_WPML_CONTEXT' ,  'customizr-option' );

      // We cannot use wpml-config.xml to translate theme options because we use to update the option even in front page after retrieved, so we have to act on
      // a different filter.
      // When registering and translate strings WPML requires a 'context' and a 'name' (in a readable format for translators) plus the string to translate
      // context will be concatenated to the name and md5 will run on the result. The new result will represent the KEY for the WPML translations cache array.
      // This means that
      // 1) We cannot use translated string for the "name" param (which actually they say should be in a readable format ..)
      // 2) We need a way to use the same "name" both when registering the string to translate and retrieving its translations
      function tc_wpml_get_options_names_config() {
        $_wp_cache_key     = 'tc_wpml_get_options_names_config';
        $option_name_assoc = wp_cache_get( $_wp_cache_key );

        if ( false === $option_name_assoc ) {
          $options_to_translate = TC_plugins_compat::$instance -> tc_get_string_options_to_translate();

          $option_name_assoc = apply_filters( 'tc_wpml_options_names_config', array(
 //           'tc_front_slider'              => 'Front page slider name', //Handled in a different way by Srdjan
            'tc_posts_slider_button_text'  => 'Posts slider button text',
            'tc_tag_title'                 => 'Tag pages title',
            'tc_cat_title'                 => 'Category pages title',
            'tc_author_title'              => 'Author pages title',
            'tc_search_title'              => 'Search pages title',
            'tc_social_in_sidebar_title'   => 'Social link title in sidebars',
            'tc_featured_page_button_text' => 'Featured button text',
            'tc_featured_text_one'         => 'Featured text one',
            'tc_featured_text_two'         => 'Featured text two',
            'tc_featured_text_three'       => 'Featured text three'
          ) );

          foreach ( $option_name_assoc as $key => $value ) {
            //use array_key_exists when and if options_to_translate will be an associative array
            if ( ! in_array( $key, $options_to_translate ) )
              unset( $option_name_assoc[$key] );
            else
              //md5 and html are stripped in wpml string table rendering, we add it for a better key
              $option_name_assoc[$key]    = $value . ' - ' . md5($key); //name
          }

          $option_name_assoc = apply_filters( 'tc_wpml_options_names_config_pre_cache', $option_name_assoc );
          //cache this 'cause is used several times in filter callbacks
          wp_cache_set( $_wp_cache_key, $option_name_assoc );
        }
        return apply_filters( 'tc_wpml_get_options_names_config', $option_name_assoc );
      }

      //Wras wpml_object_id in a more convenient function which recursevely translates array of values
      //$object can be an array or a single value
      function tc_wpml_object_id( $object_id, $type ) {
        if ( empty( $object_id ) )
          return $object_id;
        if ( is_array( $object_id ) )
          return array_map( 'tc_wpml_object_id', $object_id, array_fill( 0, sizeof( $object_id ), $type ) );
        return apply_filters( 'wpml_object_id', $object_id, $type, true );
      }

      //credits: @Srdjan -> filter the slides in the current language
      function sliders_filter( $sliders ) {
        if ( is_array( $sliders ) )
          foreach ( $sliders as $name => $slides ) {
            foreach ( $slides as $key => $attachment_id ) {
              // Get current slide language
              $slide_language = apply_filters( 'wpml_element_language_code',
                            null, array('element_id' => $attachment_id,
                                'element_type' => 'attachment') );
              if ( TC_plugins_compat::$instance->current_language != $slide_language ) {
                // Replace with translated slide
                $translated_slide_id = apply_filters( 'wpml_object_id',
                                $attachment_id, 'attachment', false );
                if ( $translated_slide_id )
                  $sliders[$name][$key] = $translated_slide_id;
              }
            }
            $sliders[$name] = array_unique( $sliders[$name] );
          }

        return $sliders;
      }
      //credits: @Srdjan,
      function add_theme_options_filter() {
        add_filter( 'option_tc_theme_options', 'theme_options_filter', 99 );
      }
      //credits: @Srdjan
      function theme_options_filter( $options ) {
        if ( isset( $options['tc_sliders'] ) ) {
            $options['tc_sliders'] = sliders_filter( $options['tc_sliders'] );
        }
        return $options;
      }
      //credits: @Srdjan
      function edit_attachment_action( $attachment_id ) {
        $languages = apply_filters( 'wpml_active_languages', array() );
        // TODO check which meta keys are a must
        $meta_data = get_post_custom( $attachment_id );
        foreach ( $languages as $language) {
            $translated_attachment_id = apply_filters( 'wpml_object_id',
                    $attachment_id, 'attachment', false, $language['code'] );
            // Update post meta
            foreach ( array('post_slider_key', 'slider_check_key') as $meta_key ) {
                if ( isset( $meta_data[$meta_key][0] ) ) {
                    update_post_meta( $translated_attachment_id, $meta_key, $meta_data[$meta_key][0] );
                }
            }
        }
      }

      function pre_update_option_filter( $options ) {
        if ( isset( $options['tc_sliders'] ) ) {
            // Force default language
            $current_language = TC_plugins_compat::$instance->current_language;
            TC_plugins_compat::$instance->current_language = TC_plugins_compat::$instance->default_language;
            $options['tc_sliders'] = sliders_filter( $options['tc_sliders'] );
            TC_plugins_compat::$instance->current_language = $current_language;
        }
        return $options;
      }

      add_action( 'admin_init', 'tc_wpml_admin_setup' );

      function tc_wpml_admin_setup() {
        // If wpml-string-translation is active perform admin pages translation
        if ( function_exists( 'icl_register_string' ) ) {
          $tc_wpml_option_name = tc_wpml_get_options_names_config();
          $tc_wpml_options     = array_keys($tc_wpml_option_name);

          // grab theme options
          $tc_options = tc__f('__options');

          // build array of options to translate
          foreach ( $tc_wpml_options as $tc_wpml_option )
            if ( isset( $tc_options[$tc_wpml_option] ) )
              icl_register_string( TC_WPML_CONTEXT,
                $tc_wpml_option_name[$tc_wpml_option],
                esc_attr($tc_options[$tc_wpml_option]) //value
            );
        }//end of string based admin translation
        //Taxonomies/Pages "transposing" in the Customizer
        //We actually could just do this instead of A) and B) in front, but we retrieve the options in front before the compat method is called (after_setup_theme with lower priority) and I prefer to keep front and back separated in this case. Different opinions are welcome, but not too much :P.
        //we have to filter the interesting options so they appear "translated" in the customizer too, 'cause wpml filters the pages/cats to choose (fp, cat pickers), and we kinda like this :), right (less memory)?
        //Side effect example for categories: TODO
        //In English we have set to filter blog posts for cat A,B and C.
        //In Italian we do not have cat C so there will be displayed transposed cats A and B
        //if we change this option in the Customizer with lang IT removing B, e.g., when we switch to EN we'll have that the array of cats contains just A, as it as been overwritten with the new setting
        if ( TC___::$instance -> tc_is_customize_left_panel() )
          add_filter( 'option_tc_theme_options', 'tc_wpml_customizer_options_transpose' );
        function tc_wpml_customizer_options_transpose( $options ) {
          $options_to_transpose = apply_filters ( 'tc_wpml_customizer_translate_options', array(
            'page'     => ( ! class_exists('TC_fpu') && ! class_exists('TC_fpc') ) ? array( 'tc_featured_page_one', 'tc_featured_page_two', 'tc_featured_page_three' ) : array(),
            'category' => array( 'tc_blog_restrict_by_cat' )
            )
          );
          foreach ( $options_to_transpose as $type => $option_to_transpose )
            foreach ( $option_to_transpose as $option )
              if ( isset( $options[$option] ) )
                $options[$option] = tc_wpml_object_id( $options[$option], $type);
          return $options;
        }

        //credits @Srdjan
        // Filter slides in admin screens
        add_action( '__attachment_slider_infos', 'add_theme_options_filter', 9 );
        add_action( '__post_slider_infos', 'add_theme_options_filter', 9 );
        // Update translated slide post meta
        add_action( 'edit_attachment', 'edit_attachment_action', 99 );
        // Pre-save hook
        add_filter( 'pre_update_option_tc_theme_options', 'pre_update_option_filter', 99 );

      }// end tc_wpml_admin_setup function

      // Front
      // If WPML string translator is active, translate/swap featured page buttons/text/link and slider
      if ( ! is_admin() ) {
        // String transaltion binders : requires wpml icl_t function
        if ( function_exists( 'icl_t') ) {
          /*** TC - WPML bind, wrap WPML string translator function into convenient tc functions ***/
          //define our icl_t wrapper for options filtered with tc_opt_{$option}
          if ( ! function_exists( 'tc_wpml_t_opt' ) ) {
            function tc_wpml_t_opt( $string ) {
              return tc_wpml_t( $string, str_replace('tc_opt_', '', current_filter() ) );
            }
          }
          //special function for the post slider button text pre trim filter
          if ( ! function_exists( 'tc_wpml_t_ps_button_text' ) ) {
            function tc_wpml_t_ps_button_text( $string ) {
              return tc_wpml_t( $string, 'tc_posts_slider_button_text' );
            }
          }
          //define our icl_t wrapper
          if ( ! function_exists( 'tc_wpml_t' ) ) {
            function tc_wpml_t( $string, $opt ) {
              $tc_wpml_options_names = tc_wpml_get_options_names_config();
              return icl_t( TC_WPML_CONTEXT, $tc_wpml_options_names[$opt], $string );
            }
          }
          /*** End TC - WPML bind ***/

          //get the options to translate
          $tc_wpml_options = array_keys( tc_wpml_get_options_names_config() );

          //strings translation
          foreach ( $tc_wpml_options as $tc_wpml_option )
            add_filter("tc_opt_$tc_wpml_option", 'tc_wpml_t_opt', 20 );

          //translates sliders? credits @Srdjan
          add_filter( 'tc_opt_tc_sliders', 'sliders_filter', 99 );

        }
        /*A) FP*/
        // Featured pages ids "translation"
        add_filter( 'tc_fp_id', 'tc_wpml_page_id', 20 );
        function tc_wpml_page_id( $fp_page_id ) {
          return tc_wpml_object_id( $fp_page_id, 'page');
        }

        /*B) Tax */
        /**
        * Cat filtering (home/blog posts filtered by cat)
        *
        * AFAIK wpml needs to exactly know which kind of tax we're looking for, category, tag ecc..
        * @param array of term ids
        */
        function tc_wpml_translate_cat( $cat_ids ){
          if ( ! ( is_array( $cat_ids ) && ! empty( $cat_ids ) ) )
            return $cat_ids;
          return array_unique( tc_wpml_object_id( $cat_ids, 'category' ) );
        }
        //Translate category ids for the filtered posts in home/blog
        add_filter('tc_opt_tc_blog_restrict_by_cat', 'tc_wpml_translate_cat');
        /*end tax filtering*/

        /* Slider of posts */
        if ( defined( 'ICL_LANGUAGE_CODE') ) {
        // Filter the posts query for the current language
          add_filter( 'tc_query_posts_slider_join'      , 'wpml_posts_slider_join' );
          add_filter( 'tc_query_posts_slider_join_where', 'wpml_posts_slider_join' );
        }
        function wpml_posts_slider_join( $join ) {
          global $wpdb;
          switch ( current_filter() ){
            case 'tc_query_posts_slider_join'        : $join .= " INNER JOIN {$wpdb->prefix}icl_translations AS wpml_tr";
                                                       break;
            case 'tc_query_posts_slider_join_where'  : $_join = $wpdb->prepare("wpml_tr.element_id = posts.ID AND wpml_tr.language_code=%s AND wpml_tr.element_type=%s",
                                                                    ICL_LANGUAGE_CODE,
                                                                    'post_post'
                                                       );
                                                       $join .= $join ? 'AND ' . $_join : 'WHERE '. $_join;
                                                       break;
          }

          return $join;
        }
        /*end Slider of posts */
        /*end Slider*/
      }//end Front
    }//end wpml compat




    /**
    * The Events Calendar compat hooks
    *
    * @package Customizr
    * @since Customizr 3.4+
    */
    private function tc_set_the_events_calendar_compat() {
      /*
      * Are we in the Events list context?
      */
      if ( ! ( function_exists( 'tc_is_tec_events_list' ) ) ) {
        function tc_is_tec_events_list() {
          return function_exists( 'tribe_is_event_query' ) && tribe_is_event_query() && is_post_type_archive();
        }
      }
      /*
      * Are we in single Event context?
      */
      if ( ! ( function_exists( 'tc_is_tec_single_event' ) ) ) {
        function tc_is_tec_single_event() {
          return function_exists( 'tribe_is_event_query' ) && tribe_is_event_query() && is_single();
        }
      }
      // hide tax archive title
      add_filter( 'tc_show_tax_archive_title', 'tc_tec_disable_tax_archive_title');
      function tc_tec_disable_tax_archive_title( $bool ) {
        return tc_is_tec_events_list() ? false : $bool;
      }

      // Events archive is displayed, wrongly, we our post lists classes, we have to prevent this
      add_filter( 'tc_post_list_controller', 'tc_tec_disable_post_list');
      add_filter( 'tc_is_grid_enabled', 'tc_tec_disable_post_list');
      function tc_tec_disable_post_list( $bool ) {
        return tc_is_tec_events_list() ? false : $bool;
      }

      // Now we have to display a post or page content
      add_filter( 'tc_show_single_post_content', 'tc_tec_show_content' );
      function tc_tec_show_content( $bool ) {
        //2 cases:
        //1 - in events lists - we force showing single post content
        //2 - in single events we have to prevent showing both page and post content
        if ( tc_is_tec_events_list() )
          return true;
        else if( tc_is_tec_single_event() )
          return false;
        return $bool;
      }

      // Force the tax name in the breadcrumb when list of events shown as 'Month'
      // The Events Calendar adds a filter on post_type_archive_title with __return_false callback
      // for their own reasons. This impacts on our breadcrumb 'cause we use the function post_type_archive_title() to build up the trail arg in posty_type_archives contexts.
      // What we do here is unhooking their callback before the breadcrumb is built and re-hook it after it has been displayed
      add_action( 'wp_head', 'tc_tec_allow_display_breadcrumb_in_month_view');
      function tc_tec_allow_display_breadcrumb_in_month_view() {
        if ( ! ( tc_is_tec_events_list() && function_exists( 'tribe_is_month' ) && tribe_is_month() ) )
          return;

        add_filter( 'tc_breadcrumb_trail_args', 'tc_tec_unhook_empty_post_type_archive_title');
        function tc_tec_unhook_empty_post_type_archive_title( $args = null ) {
          remove_filter( 'post_type_archive_title', '__return_false', 10 );
          return $args;
        }
        add_filter( 'tc_breadcrumb_trail_display', 'tc_tec_rehook_empty_post_type_archive_title', PHP_INT_MAX );
        function tc_tec_rehook_empty_post_type_archive_title( $breadcrumb = null ) {
          add_filter( 'post_type_archive_title', '__return_false', 10 );
          return $breadcrumb;
        }
      }
      //disables post navigation in single tec pages
      add_filter( 'tc_show_post_navigation', 'tc_tec_disable_post_navigation' );
      function tc_tec_disable_post_navigation($bool) {
        return ( tc_is_tec_single_event() ) ? false : $bool;
      }
    }//end the-events-calendar compat



    /**
    * OptimizePress compat hooks
    *
    * @package Customizr
    * @since Customizr 3.3+
    */
    private function tc_set_optimizepress_compat() {
      add_action('wp_print_scripts', 'tc_op_dequeue_fancybox_js');
      function tc_op_dequeue_fancybox_js(){
        if ( function_exists('is_le_page') ){
          /* Op Back End: Dequeue tc-scripts */
          if ( is_le_page() || defined('OP_LIVEEDITOR') ) {
            wp_dequeue_script('tc-scripts');
            wp_dequeue_script('tc-fancybox');
          }
          else {
            /* Front End: Dequeue Fancybox maybe already embedded in Customizr */
            wp_dequeue_script('tc-fancybox');
            //wp_dequeue_script(OP_SN.'-fancybox');
          }
        }
      }

      /* Remove fancybox loading icon*/
      add_action('wp_footer','tc_op_remove_fancyboxloading');
      function tc_op_remove_fancyboxloading(){
        echo "<script>
                if (typeof(opjq) !== 'undefined') {
                  opjq(document).ready(function(){
                    opjq('#fancybox-loading').remove();
                  });
                }
             </script>";
      }
    }//end optimizepress compat



    /**
    * Sensei compat hooks
    *
    * @package Customizr
    * @since Customizr 3.3+
    */
    private function tc_set_sensei_compat() {
      //unkooks the default sensei wrappers and add customizr's content wrapper and action hooks
      global $woothemes_sensei;
      remove_action( 'sensei_before_main_content', array( $woothemes_sensei->frontend, 'sensei_output_content_wrapper' ), 10 );
      remove_action( 'sensei_after_main_content', array( $woothemes_sensei->frontend, 'sensei_output_content_wrapper_end' ), 10 );

      add_action('sensei_before_main_content', 'tc_sensei_wrappers', 10);
      add_action('sensei_after_main_content', 'tc_sensei_wrappers', 10);


      function tc_sensei_wrappers() {
        switch ( current_filter() ) {
          case 'sensei_before_main_content': TC_plugins_compat::$instance -> tc_mainwrapper_start();
                                             break;

          case 'sensei_after_main_content' : TC_plugins_compat::$instance -> tc_mainwrapper_end();
                                             break;
        }//end of switch on hook
      }//end of nested function

      // hide tax archive title
      add_filter( 'tc_show_tax_archive_title', 'tc_sensei_disable_tax_archive_title');
      function tc_sensei_disable_tax_archive_title( $bool ){
        return ( function_exists('is_sensei') && is_sensei() ) ? false : $bool;
      }

      //disables post navigation
      add_filter( 'tc_show_post_navigation', 'tc_sensei_disable_post_navigation' );
      function tc_sensei_disable_post_navigation($bool) {
        return ( function_exists('is_sensei') && is_sensei() ) ? false : $bool;
      }
      //removes post comment action on after_loop hook
      add_filter( 'tc_are_comments_enabled', 'tc_sensei_disable_comments' );
      function tc_sensei_disable_comments($bool) {
        return ( function_exists('is_sensei') && is_sensei() ) ? false : $bool;
      }
    }//end sensei compat




    /**
    * Woocommerce compat hooks
    *
    * @package Customizr
    * @since Customizr 3.3+
    */
    private function tc_set_woocomerce_compat() {
      //unkooks the default woocommerce wrappersv and add customizr's content wrapper and action hooks
      remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
      remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
      add_action('woocommerce_before_main_content', 'tc_woocommerce_wrappers', 10);
      add_action('woocommerce_after_main_content', 'tc_woocommerce_wrappers', 10);

      //disable WooCommerce default breadcrumb
      if ( apply_filters( 'tc_disable_woocommerce_breadcrumb', true ) )
        remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

      function tc_woocommerce_wrappers() {
        switch ( current_filter() ) {
          case 'woocommerce_before_main_content': TC_plugins_compat::$instance -> tc_mainwrapper_start();
                                                  break;

          case 'woocommerce_after_main_content' : TC_plugins_compat::$instance -> tc_mainwrapper_end();
                                                  break;
        }//end of switch on hook
      }//end of nested function
      //Helper
      function tc_wc_is_checkout_cart() {
        return is_checkout() || is_cart() || defined('WOOCOMMERCE_CHECKOUT') || defined('WOOCOMMERCE_CART');
      }
      // use Customizr title
      // initially used to display the edit button
      add_filter( 'the_title', 'tc_woocommerce_the_title' );
      function tc_woocommerce_the_title( $_title ){
        if ( function_exists('is_woocommerce') && is_woocommerce() && ! is_page() )
            return apply_filters( 'tc_title_text', $_title );
        return $_title;
      }

      // hide tax archive title
      add_filter( 'tc_show_tax_archive_title', 'tc_woocommerce_disable_tax_archive_title');
      function tc_woocommerce_disable_tax_archive_title( $bool ){
        return ( function_exists('is_woocommerce') && is_woocommerce() ) ? false : $bool;
      }

      //allow slider in the woocommerce shop page
      add_filter('tc_show_slider', 'tc_woocommerce_enable_shop_slider');
      function tc_woocommerce_enable_shop_slider( $bool ){
        return ( function_exists('is_woocommerce') && is_woocommerce() && function_exists('is_shop') && is_shop() ) ? true : $bool;
      }
      //to allow the slider in the woocommerce shop page we need the shop page id
      add_filter('tc_slider_get_real_id', 'tc_woocommerce_shop_page_id');
      function tc_woocommerce_shop_page_id( $id ){
        return ( function_exists('is_woocommerce') && is_woocommerce() && function_exists('is_shop') && is_shop() && function_exists('wc_get_page_id') ) ? wc_get_page_id('shop') : $id;
      }

      //handles the woocomerce sidebar : removes action if sidebars not active
      if ( !is_active_sidebar( 'shop') ) {
        remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
      }


      //disables post navigation
      add_filter( 'tc_show_post_navigation', 'tc_woocommerce_disable_post_navigation' );
      function tc_woocommerce_disable_post_navigation($bool) {
         return ( function_exists('is_woocommerce') && is_woocommerce() ) ? false : $bool;
      }


      //removes post comment action on after_loop hook
      add_filter( 'tc_are_comments_enabled', 'tc_woocommerce_disable_comments' );
      function tc_woocommerce_disable_comments($bool) {
         return ( function_exists('is_woocommerce') && is_woocommerce() ) ? false : $bool;
      }

      //link smooth scroll: exclude woocommerce tabs
      add_filter( 'tc_anchor_smoothscroll_excl', 'tc_woocommerce_disable_link_scroll' );
      function tc_woocommerce_disable_link_scroll( $excl ){
        if ( false == esc_attr( TC_utils::$inst->tc_opt('tc_link_scroll') ) ) return $excl;

        if ( function_exists('is_woocommerce') && is_woocommerce() ) {
          if ( ! is_array( $excl ) )
            $excl = array();

          if ( ! is_array( $excl['deep'] ) )
            $excl['deep'] = array() ;

          if ( ! is_array( $excl['deep']['classes'] ) )
              $excl['deep']['classes'] = array();

          $excl['deep']['classes'][] = 'wc-tabs';
        }
        return $excl;
      }


      //changes customizr meta boxes priority (slider and layout not on top) if displaying woocommerce products in admin
      add_filter( 'tc_post_meta_boxes_priority', 'tc_woocommerce_change_meta_boxes_priority' , 2 , 10 );
      function tc_woocommerce_change_meta_boxes_priority($priority , $screen) {
         return ( 'product' == $screen ) ? 'default' : $priority ;
      }

      //woocommerce options in the Customizer
      //add new panel
      add_filter( 'tc_add_panel_map', 'tc_woocommerce_popul_panel_map' );
      function tc_woocommerce_popul_panel_map( $panel_map ) {
        $tc_woocommerce_panel_map = array(
            'tc-woocommerce-panel' => array(
                  'priority'       => 50,
                  'capability'     => 'edit_theme_options',
                  'title'          => __( 'WooCommerce options' , 'customizr' ),
                  'description'    => __( "WooCommerce settings for the Customizr theme." , 'customizr' )
            )
        );
        return array_merge( $panel_map, $tc_woocommerce_panel_map );
      }
      //add new section
      add_filter( 'tc_add_section_map', 'tc_woocommerce_popul_section_map' );
      function tc_woocommerce_popul_section_map( $_sections ) {
        $tc_woocommerce_section_map = array(
            'tc_woocommerce_sec' => array(
                  'title'          => __( 'Header Cart' , 'customizr' ),
                  'priority'       => 50,
                  'panel'          => 'tc-woocommerce-panel'
            )
        );
        return array_merge( $_sections, $tc_woocommerce_section_map );
      }
      //end woocommerce options in the Customizer

      /* rendering the cart icon in the header */
      //narrow the tagline
      add_filter( 'tc_tagline_class', 'tc_woocommerce_force_tagline_width', 100 );
      function tc_woocommerce_force_tagline_width( $_class ) {
        return 1 == esc_attr( TC_utils::$inst->tc_opt( 'tc_woocommerce_header_cart' ) ) ? 'span6' : $_class ;
      }

      //print the cart menu in the header
      add_action( '__navbar', 'tc_woocommerce_header_cart', is_rtl() ? 9 : 19 );
      function tc_woocommerce_header_cart() {
        if ( 1 != esc_attr( TC_utils::$inst->tc_opt( 'tc_woocommerce_header_cart' ) ) )
          return;

        $_main_item_class = '';
        $_cart_count      = WC()->cart->get_cart_contents_count();
        //highlight the cart icon when in the Cart or Ceckout page
        if ( tc_wc_is_checkout_cart() ) {
          $_main_item_class = 'current-menu-item';
        }

       ?>
       <div class="tc-wc-menu tc-open-on-hover span1">
         <ul class="tc-wc-header-cart nav tc-hover-menu">
           <li class="<?php echo esc_attr( $_main_item_class ); ?> menu-item">
             <a class="cart-contents" href="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" title="<?php _e( 'View your shopping cart', 'customizr' ); ?>">
             <?php if ( $_cart_count > 0 ) { //do not display cart count if there are no items 'cause atm wc doesn't update this with ajax (storefront), do we want to look into this??>
               <span class="count btn-link"><?php echo $_cart_count; ?></span>
             <?php } ?>
            </a>
            <?php
            ?>
            <?php if ( ! tc_wc_is_checkout_cart() ) : //do not display the dropdown in the cart or checkout page ?>
              <ul class="dropdown-menu">
               <li>
                 <?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
                </li>
              </ul>
            <?php endif; ?>
           </li>
          </ul>
        </div>
      <?php
      }

      add_filter('tc_user_options_style', 'tc_woocommerce_header_cart_css');
      function tc_woocommerce_header_cart_css( $_css ) {
        if ( 1 != esc_attr( TC_utils::$inst->tc_opt( 'tc_woocommerce_header_cart' ) ) )
          return;
        /* The only real decision I took here is the following:
        * I let the "count" number possibily overflow the parent (span1) width
        * so that as it grows it won't break on a new line. This is quite an hack to
        * keep the cart space as small as possible (span1) and do not hurt the tagline too much (from span7 to span6). Also nobody will, allegedly, have more than 10^3 products in its cart
        */
        $_header_layout  = esc_attr( TC_utils::$inst->tc_opt( 'tc_header_layout') );
        $_resp_pos_css   = 'right' == $_header_layout ? 'float: left;' : '';
        $_wc_t_align     = 'left';
        //dropdown top arrow, as we open the drodpdown on the right we have to move the top arrow accordingly
        $_dd_top_arrow   = '.navbar .tc-wc-menu .nav > li > .dropdown-menu:before { right: 9px; left: auto;} .navbar .tc-wc-menu .nav > li > .dropdown-menu:after { right: 10px; left: auto; }';

        //rtl custom css
        if ( is_rtl() ) {
          $_wc_t_align   = 'right';
          $_dd_top_arrow = '';
        }
        return sprintf( "%s\n%s",
              $_css,
              ".sticky-enabled .tc-header .tc-wc-menu { display: none; }
               .tc-header .tc-wc-menu .nav {
                 text-align: right;
               }
               $_dd_top_arrow
               .tc-header .tc-wc-menu .dropdown-menu {
                  right: 0; left: auto; width: 250px; padding: 2px;
               }
               .tc-header .tc-wc-menu {
                 float: right; clear:none; margin-top: 10px;
               }
               .tc-header .tc-wc-menu .nav > li {
                 float:none;
               }
               .tc-wc-menu ul.dropdown-menu .buttons a,
               .tc-wc-menu ul {
                 width: 100%;
                 -webkit-box-sizing: border-box;
                 -moz-box-sizing: border-box;
                 box-sizing: border-box;
               }
               .tc-wc-menu ul.dropdown-menu .buttons a {
                 margin: 10px 5px 0 0px; text-align: center;
               }
               .tc-wc-menu .nav > li > a:before {
                 content: '\\f447';
                 font-family: 'genericons';
                 speak:none; position:absolute;
                 top:-.1em; font-size:1.8em; left: 0;
               }
               .tc-header .tc-wc-menu .nav > li > a {
                 position: relative;
                 padding-right: 0 !important;
                 padding-left: 0 !important;
                 display:inline-block;
                 border-bottom: none;
                 text-align: right;
                 height: 1em;
                 min-width:1.8em;
               }
               .tc-wc-menu .count {
                 font-size: 0.7em;
                 margin-left: 2.1em;
                 pointer-events: none;
               }
               .tc-wc-menu .woocommerce.widget_shopping_cart li {
                 padding: 0.5em;
               }
               .tc-header .tc-wc-menu .woocommerce.widget_shopping_cart p,
               .tc-header .tc-wc-menu .woocommerce.widget_shopping_cart li {
                 padding-right: 1em;
                 padding-left: 1em;
                 text-align: $_wc_t_align;
                 font-size: inherit; font-family: inherit;
               }
               .tc-wc-menu .widget_shopping_cart .product_list_widget li a.remove {
                 position: relative; float: left; top: auto; margin-right: 0.2em;
               }
               /* hack for the first letter issue */
               .tc-wc-menu .count:before {
                 content: '';
               }
               .tc-wc-menu .widget_shopping_cart .product_list_widget {
                 max-height: 10em;
                 overflow-y: auto;
                 padding: 1em 0;
               }
               @media (max-width: 979px) {
                .tc-wc-menu[class*=span] { width: auto; margin:18px 0 0 0; $_resp_pos_css }
                .tc-wc-menu .dropdown-menu { display: none !important;}
               }
        ");
      }
      /*end rendering the cart icon in the header */
    }//end woocommerce compat


    /**
    * Visual Composer compat hooks
    *
    * @package Customizr
    * @since Customizr 3.4+
    */
    private function tc_set_vc_compat() {
      //link smooth scroll: exclude all anchor links inside vc wrappers (.vc_row)
      add_filter( 'tc_anchor_smoothscroll_excl', 'tc_vc_disable_link_scroll' );
      function tc_vc_disable_link_scroll( $excl ){
        if ( false == esc_attr( TC_utils::$inst->tc_opt('tc_link_scroll') ) ) return $excl;

        if ( ! is_array( $excl ) )
          $excl = array();

        if ( ! is_array( $excl['deep'] ) )
          $excl['deep'] = array() ;

        if ( ! is_array( $excl['deep']['classes'] ) )
            $excl['deep']['classes'] = array();

        $excl['deep']['classes'][] = 'vc_row';

        return $excl;
      }
    }//end woocommerce compat


    /**
    * Disqus Comment System compat hooks
    *
    * @package Customizr
    * @since Customizr 3.4+
    */
    private function tc_set_disqus_compat() {
      if ( ! function_exists( 'tc_disqus_comments_enabled' ) ) {
        function tc_disqus_comments_enabled() {
          return function_exists( 'dsq_is_installed' ) && function_exists( 'dsq_can_replace' )
                 && dsq_is_installed() && dsq_can_replace();
        }
      }
      //replace the default comment link anchor with a more descriptive disqus anchor
      add_filter( 'tc_bubble_comment_anchor', 'tc_disqus_bubble_comment_anchor' );
      function tc_disqus_bubble_comment_anchor( $anchor ) {
        return tc_disqus_comments_enabled() ? '#tc-disqus-comments' : $anchor;
      }
      //wrap disqus comments template in a convenient div
      add_action( 'tc_before_comments_template' , 'tc_disqus_comments_wrapper' );
      add_action( 'tc_after_comments_template'  , 'tc_disqus_comments_wrapper' );
      function tc_disqus_comments_wrapper() {
        if ( ! tc_disqus_comments_enabled() )
          return;

        switch ( current_filter() ) {
          case 'tc_before_comments_template' : echo '<div id="tc-disqus-comments">';
                                               break;
          case 'tc_after_comments_template'  : echo '</div>';
        }
      }
    }//end woocommerce compat

    /**
    * CUSTOMIZR WRAPPERS
    * print the customizr wrappers
    *
    * @since 3.3+
    *
    * originally used for woocommerce compatibility
    */
    function tc_mainwrapper_start() {
      ?>
      <div id="main-wrapper" class="<?php echo implode(' ', apply_filters( 'tc_main_wrapper_classes' , array('container') ) ) ?>">

        <?php do_action( '__before_main_container' ); ##hook of the featured page (priority 10) and breadcrumb (priority 20)...and whatever you need! ?>

        <div class="container" role="main">
          <div class="<?php echo implode(' ', apply_filters( 'tc_column_content_wrapper_classes' , array('row' ,'column-content-wrapper') ) ) ?>">

            <?php do_action( '__before_article_container'); ##hook of left sidebar?>

              <div id="content" class="<?php echo implode(' ', apply_filters( 'tc_article_container_class' , array( TC_utils::tc_get_layout( TC_utils::tc_id() , 'class' ) , 'article-container' ) ) ) ?>">

                <?php do_action ('__before_loop');##hooks the header of the list of post : archive, search... ?>
      <?php
    }

    function tc_mainwrapper_end() {
      ?>
                <?php do_action ('__after_loop');##hook of the comments and the posts navigation with priorities 10 and 20 ?>

              </div><!--.article-container -->

              <?php do_action( '__after_article_container'); ##hook of left sidebar?>

            </div><!--.row -->
        </div><!-- .container role: main -->

        <?php do_action( '__after_main_container' ); ?>

      </div><!--#main-wrapper"-->
      <?php
    }



    /**
    * HELPER
    * Check whether the plugin is active by checking the active_plugins list.
    * copy of is_plugin_active declared in wp-admin/includes/plugin.php
    *
    * @since 3.3+
    *
    * @param string $plugin Base plugin path from plugins directory.
    * @return bool True, if in the active plugins list. False, not in the list.
    */
    function tc_is_plugin_active( $plugin ) {
      return in_array( $plugin, (array) get_option( 'active_plugins', array() ) ) || $this -> tc_is_plugin_active_for_network( $plugin );
    }


    /**
    * HELPER
    * Check whether the plugin is active for the entire network.
    * copy of is_plugin_active_for_network declared in wp-admin/includes/plugin.php
    *
    * @since 3.3+
    *
    * @param string $plugin Base plugin path from plugins directory.
    * @return bool True, if active for the network, otherwise false.
    */
    function tc_is_plugin_active_for_network( $plugin ) {
      if ( ! is_multisite() )
        return false;

      $plugins = get_site_option( 'active_sitewide_plugins');
      if ( isset($plugins[$plugin]) )
        return true;

      return false;
    }

    public function tc_get_string_options_to_translate() {
      $string_options = array(
        'tc_front_slider',
        'tc_posts_slider_button_text',
        'tc_tag_title',
        'tc_cat_title',
        'tc_author_title',
        'tc_search_title',
        'tc_social_in_sidebar_title',
      );
      if ( ! class_exists('TC_fpu') && ! class_exists('TC_fpc') ) {
        $fp_areas = TC_init::$instance -> fp_ids;
        foreach ( $fp_areas as $fp_area )
          $string_options[] = 'tc_featured_text_' . $fp_area;

        $string_options[] = 'tc_featured_page_button_text';
      }
      return apply_filters( 'tc_get_string_options_to_translate', $string_options );
    }
  }//end of class
endif;
