<?php


/**
 * @author Vitor Hugo (vitorhugo.ro10@gmail.com)

 * @param Modificador de Estilos com jQuery
 */
add_action('admin_footer', 'modify_editor');

function modify_editor()
{
    global $post_type;

    switch ($post_type) {

        case 'campeonatos': ?>

        <script type="text/javascript">

        jQuery(document).ready(function(){

          var option = jQuery("input[name=_vhr_price_option]:checked").val();

          if(option == 'n'){

            jQuery(".cmb_id__vhr_price").hide();

            jQuery(".cmb_id__vhr_price_extra").hide();

          }

          jQuery("#field_user_subscribers").hide();

            jQuery("#categoriadiv").remove();

            jQuery("#postexcerpt h3 span").text("Descrição do Campeonato");

            jQuery("#postexcerpt .inside p").text("Uma pequena descrição e/ou apresentação do campeonato para o usúario");

            jQuery("#postimagediv h3 span").text("Banner da Competição");

            //jQuery("#postimagediv #set-post-thumbnail").text("Definir banner");

            jQuery("#data-comp").mask("00/00/0000", {placeholder: "__/__/____"});

            jQuery("#data-aberto").mask("00/00/0000", {placeholder: "__/__/____"});

            jQuery("#data-fechamento").mask("00/00/0000", {placeholder: "__/__/____"});

            jQuery("#_vhr_cep").mask("00000-000");

            jQuery('#_vhr_price').mask('000.000.000.000.000,00', {reverse: true});

            jQuery('#_vhr_price_extra').mask('000.000.000.000.000,00', {reverse: true});

        });



        jQuery("input[name=_vhr_price_option]").change(function(){

              var option = jQuery(this).val();

              if(option == 'n'){

                jQuery(".cmb_id__vhr_price").hide();

                jQuery(".cmb_id__vhr_price_extra").hide();

              } else {

                jQuery(".cmb_id__vhr_price").show();

                jQuery(".cmb_id__vhr_price_extra").show();

              }

            });



        jQuery("#_vhr_cep").change(function(){

                //alert(jQuery(this).val());

                var cep_code = jQuery(this).val();

                if(cep_code.length <= 0 ) return;

                jQuery.get("http://apps.widenet.com.br/busca-cep/api/cep.json", {code: cep_code},

                    function(result){

                        if(result.status!=1){

                            alert(result.message || "Houve um erro desconhecido");

                            return;

                        }

                        jQuery("input#_vhr_cep").val(result.code);

                        jQuery("input#_vhr_state").val(result.state);

                        jQuery("input#_vhr_city").val(result.city);

                        jQuery("input#_vhr_district").val(result.district);

                        jQuery("input#_vhr_street").val(result.address);

                    });

        });

        </script>

<?php

        break;

        case 'eventos': ?>

        <script type="text/javascript">

        jQuery(document).ready(function(){

          var option = jQuery("input[name=_vhr_price_option]:checked").val();

          if(option == 'n'){

            jQuery(".cmb_id__vhr_price").hide();

            jQuery(".cmb_id__vhr_price_extra").hide();

          }

            jQuery("#field_user_subscribers").hide();

            jQuery("#_vhr_cep").mask("00000-000");

            jQuery('#_vhr_price').mask('000.000.000.000.000,00', {reverse: true});

        });



        jQuery("input[name=_vhr_price_option]").change(function(){

              var option = jQuery(this).val();

              if(option == 'n'){

                jQuery(".cmb_id__vhr_price").hide();

                jQuery(".cmb_id__vhr_price_extra").hide();

              } else {

                jQuery(".cmb_id__vhr_price").show();

                jQuery(".cmb_id__vhr_price_extra").show();

              }

            });



        jQuery("#_vhr_cep").change(function(){

                var cep_code = jQuery(this).val();

                if(cep_code.length <= 0 ) return;

                jQuery.get("http://apps.widenet.com.br/busca-cep/api/cep.json", {code: cep_code},

                    function(result){

                        if(result.status!=1){

                            alert(result.message || "Houve um erro desconhecido");

                            return;

                        }

                        jQuery("input#_vhr_cep").val(result.code);

                        jQuery("input#_vhr_state").val(result.state);

                        jQuery("input#_vhr_city").val(result.city);

                        jQuery("input#_vhr_district").val(result.district);

                        jQuery("input#_vhr_street").val(result.address);

                    });

        });

        </script>

<?php

        break;

    }
}

//Modificador de ordem das colunas dos campeonatos

add_filter('manage_campeonatos_posts_columns', 'campeonatos_column');

function campeonatos_column($columns)
{
    $colunas = array(

        'cb' => '<input type="checkbox" />',

        'title' => 'Título',

        'date_open' => 'Inscrições',

        'date_camp' => 'Data da competição',

        'price' => 'Valor da inscrição',

        'insiders' => 'Inscritos',

        'date' => 'Data cadastro',

    );

    return $colunas;
}

// Modificador da Pagina dos Eventos

add_filter('manage_eventos_posts_columns', 'eventos_column');

function eventos_column($columns)
{
    $colunas = array(

        'cb' => '<input type="checkbox" />',

        'title' => 'Título',

        'date_open' => 'Inscrições',

        'date_camp' => 'Data do Evento',

        'price' => 'Valor da inscrição',

        'insiders' => 'Inscritos',

        'date' => 'Data cadastro',

    );

    return $colunas;
}

add_action('manage_campeonatos_posts_custom_column', 'column_content', 10, 2);

add_action('manage_eventos_posts_custom_column', 'column_content', 10, 2);

function column_content($column_name, $post_id)
{
    global $post_type;

    switch ($column_name) {

        case 'date_open':

            $abertura = date('d/m/Y',get_post_meta($post_id, '_vhr_abertura', true));
            $fechamento = date('d/m/Y',get_post_meta($post_id, '_vhr_fechamento', true));

            echo empty($abertura) ? 'Sem cadastro' : $abertura. ' à ' . $fechamento;

        break;

        case 'date_camp':

            $competicao = date('d/m/Y',get_post_meta($post_id, '_vhr_dia', true));

            echo empty($competicao) ? 'Sem cadastro' : $competicao;
        break;

        case 'insiders':

            $i = get_post_meta($post_id, 'user_subscribers', true);

            if (!empty($i)) {
                if (get_post_type($post_id) == 'campeonatos') {
                    ?>

                  <input type="button" name="insiders" class="button" onclick="document.location.href='<?php echo get_admin_url();
                    ?>edit.php?post_type=campeonatos&page=gerenciar_camp&post_id=<?php echo $post_id;
                    ?>'" value="Ver inscritos">

                  <?php

                } else {
                    ?>

                  <input type="button" name="insiders" class="button" onclick="document.location.href='<?php echo get_admin_url();
                    ?>edit.php?post_type=eventos&page=gerenciar_event&post_id=<?php echo $post_id;
                    ?>'" value="Ver inscritos">

                  <?php

                }
            } else {
                echo '<b>Sem inscritos</b>';
            }

        break;

        case 'price':

            if(get_post_type($post_id) == 'campeonatos'){
                $priceCheck = get_post_meta($post_id, '_vhr_price_option', true);
                echo ($priceCheck == 's') ? 'R$ '.get_post_meta($post_id, '_vhr_price', true) : 'Gratuito';
            } elseif(get_post_type($post_id) == 'eventos') {
                $niveis = get_post_meta( $post_id, 'category_insider_group', true );
                if(! empty($niveis)){
                    foreach( (array) $niveis as $tkey => $tentry){
                            $priceCheck = ($tentry['price_option'] == 's') ? true : false;
                            
                            if($priceCheck){
                                if($price <= $tentry['price']){
                                    $price = $tentry['price'];
                                }
                            }
                    }
                }

                echo empty($price) ? 'Gratuito' : 'R$'.$price;
            }

            

        break;

    }
}

function order_custom_post_types( $query ) {
    // exit out if it's the admin or it isn't the main query
    if ( ! $query->is_main_query() ) {
        return;
    }
    // order category archives by title in ascending order
    if ( is_category() || is_admin() ) {
        $query->set( 'order' , 'desc' );
        $query->set( 'orderby', 'date');
        return;
    }
}
add_action( 'pre_get_posts', 'order_custom_post_types', 1 );

add_filter('manage_edit-academia_columns', 'academia_columns');

function academia_columns($academia_columns){
    $new_columns = array(
        'cb' => '<input type="checkbox" />',
        'name'  => __('Name'),
        'users'      => __('Users')
    );
    return $new_columns;
}

add_action('admin_footer-edit-tags.php', 'wp_tags_academia');

function wp_tags_academia(){
    global $current_screen;
    switch ($current_screen->id)
    {
        case 'edit-academia':
            ?>
            <script>
                jQuery(document).ready(function () {
                    jQuery('#tag-slug').parent().remove();
                    jQuery('#parent').parent().remove();
                    jQuery('#tag-description').parent().remove();
                });
            </script>
            <?php
            break;
    }
}

// Add to admin_init function
add_filter("manage_academia_custom_column", 'manage_theme_columns', 10, 3);

function manage_theme_columns($out, $column_name, $term_id) {
        $term = get_term_by('id', $term_id, 'academia');
    switch ($column_name) {
        case 'users':
            $args = array(
                'meta_query'    => array(
                    array(
                        'key'       => 'assoc',
                        'value'     => $term->slug,
                        'compare'   => '='
                    )
                )
            );

            $user_query = new WP_User_Query($args);
            $result = ($user_query->get_total() > 0) ? $user_query->get_total().' '.(($user_query->get_total() == 1) ? 'usuario' : 'usuarios') : 'Sem usuarios';
                echo $result;
            break;
        default:
            break;
    }
    return $out;
}