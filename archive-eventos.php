<?php
/*
Template Name: Eventos
*/
?>
<?php do_action( '__before_main_wrapper' ); ##hook of the header with get_header ?>
<div id="main-wrapper" class="<?php echo implode(' ', apply_filters( 'tc_main_wrapper_classes' , array('container') ) ) ?>">

    <?php do_action( '__before_main_container' ); ##hook of the featured page (priority 10) and breadcrumb (priority 20)...and whatever you need! ?>

    <div class="container" role="main" style="display: flex;">
        <div class="<?php echo implode(' ', apply_filters( 'tc_column_content_wrapper_classes' , array('row' ,'column-content-wrapper') ) ) ?>">

            <?php do_action( '__before_article_container' ); ##hook of left sidebar?>

                <div id="content" class="<?php echo implode(' ', apply_filters( 'tc_article_container_class' , array( TC_utils::tc_get_layout(  TC_utils::tc_id() , 'class' ) , 'article-container' ) ) ) ?>">

                    <?php do_action( '__before_loop' );##hooks the header of the list of post : archive, search... ?>

                        <?php
                        $page = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        $args = array(
                            'post_type'     => 'eventos',
                            'post_status'   => 'publish',
                            'paged' => $page
                        );

                        query_posts($args);

                        if ( have_posts() ) : ?>

                            <?php while ( have_posts() ) : ##all other cases for single and lists: post, custom post type, page, archives, search, 404 ?>

                                <?php the_post(); ?>
                                    <?php do_action( '__before_article' ) ?>
                                        <?php get_template_part('content parts/content', 'eventos'); ?>
                                    <?php do_action( '__after_article' ) ?>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <b>Nenhuma postagem encontrada.</b>
                        <?php endif; ##end if have posts ?>

                    <?php do_action( '__after_loop' );##hook of the comments and the posts navigation with priorities 10 and 20 ?>

                </div><!--.article-container -->

           <?php do_action( '__after_article_container' ); ##hook of left sidebar ?>

        </div><!--.row -->
    </div><!-- .container role: main -->

    <?php do_action( '__after_main_container' ); ?>

</div><!--#main-wrapper"-->

<?php do_action( '__after_main_wrapper' );##hook of the footer with get_get_footer ?>
