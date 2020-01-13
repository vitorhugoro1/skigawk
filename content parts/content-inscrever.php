<?php
error_reporting(1);
$post_id = esc_attr($_POST['camp_id']);
$user = wp_get_current_user();
$pages_ids = pages_group_ids();
$category_url = get_template_directory_uri() . '/includes/category-encode.php';
$term_url = get_template_directory_uri() . '/includes/termo-responsabilidade.php?post_id=' . $post_id;
$fetaria = get_the_author_meta('fEtaria', $user->ID);
?>
<section class="tc-content <?php echo $_layout_class; ?>">
    <?php do_action('__before_content');?>
        <div class="entry-content">
            <div class="hentry">
                <form action="<?php echo get_permalink($pages_ids['save-inscrito']); ?>" class="inscrever" method="post">
                  <input type="hidden" id="term_url" value="<?php echo $term_url; ?>"/>
                  <input type="hidden" id="category_url" value="<?php echo $category_url; ?>"/>
                  <input type="hidden" id="sex" value="<?php echo get_the_author_meta('sex', $user->ID); ?>"/>
                  <input type="hidden" id="fetaria" value="<?php echo $fetaria; ?>"/>
                  <input type="hidden" id="user_id" value="<?php echo $user->ID; ?>"/>
                  <input type="hidden" id="post_id" value="<?php echo $post_id; ?>"/>

              <?php
                if (get_post_type($post_id) == 'campeonatos') {
                    require 'template-campeonatos.php';
                }

                if (get_post_type($post_id) == 'eventos') {
                    require 'template-eventos.php';
                }
                ?>
                </form>
            </div>
        </div>
    <?php do_action('__after_content');?>
</section>
