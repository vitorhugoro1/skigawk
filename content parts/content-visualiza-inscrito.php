<?php

$post_id = esc_attr($_GET['post_id']);
$pages_ids = pages_group_ids();
$user = wp_get_current_user();
$check = (isset($_GET['listar'])) ? esc_attr($_GET['listar']) : 's';

$inscritos = get_post_meta($post_id, 'user_subscribers', true); // Todos os inscritos no campeonato atual
$key = array_search(esc_attr($user->ID), $inscritos); // Remove o usuario atual da lista
if($key!==false && is_array($inscritos)) {
    unset( $inscritos[ $key ] );
}
$inscricoes = get_the_author_meta('insiders', $user->ID);
$modalidades = array_keys($inscricoes[$post_id]['categorias']); // Todas as modalidades do usuario atual
?>
<section class="tc-content <?php echo $_layout_class; ?>">
    <?php do_action( '__before_content' ); ?>
    <div class="entry-content">
        <div class="hentry">
            <h3><?php echo get_the_title($post_id); ?> - Lista de Inscritos</h3>
            <span class="alignright vizu-title">Listar pela modalidade</span>
            <label class="input-toggle alignright">
                <input type="checkbox" name="listar" id="listar" <?php checked($check, 's');?>>
                <span></span>
            </label>
            <?php
                if($check == 's'){
                    require 'visualizar-categoria.php';
                } else {
                    require 'visualizar-all.php';
                }
            ?>
            <a href="<?php echo get_permalink($pages_ids['inscricoes']);?>" class="btn fp-button">Voltar</a>
        </div>
    </div>
    <?php do_action( '__after_content' ); ?>
</section>
<script>
    jQuery(document).ready(function($) {
      $('#listar').on('change', function () {
          var checked = $(this).prop('checked');
          if(checked){
              document.location.href = "<?php echo home_url('/visualiza-inscrito/'); ?>?post_id=<?php echo $post_id; ?>&listar=s";
          } else {
              document.location.href = "<?php echo home_url('/visualiza-inscrito/'); ?>?post_id=<?php echo $post_id; ?>&listar=n";
          }
      });
    });

</script>
