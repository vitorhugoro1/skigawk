<?php
$user = wp_get_current_user();
$sexo = get_the_author_meta('sex', $user->ID);
$fetaria = get_the_author_meta('fEtaria', $user->ID);
$inscricoes = get_the_author_meta('insiders', $user->ID);
$pages_id = pages_group_ids();

?>
<section class="tc-content <?php echo $_layout_class; ?>">
    <?php do_action('__before_content');?>
        <div class="entry-content">
            <div class="hentry inscricoes">
              <?php echo get_user_subscribes(); ?>
                <p>
                    O pagamento deve ser realizado para os dados da conta descritos abaixo:
                </p>
                <p>
                    <?php echo get_bank_account_text(); ?>
                </p>
                <p>
                    Ao realizar o pagamento enviar o comprovante para o e-mail
                    <a href="mailto:adriel@skigawk.com.br">adriel@skigawk.com.br</a>.
                </p>
              <div class="alignright">
                  <button class="btn btn-primary btn-large" onclick="document.location.href='<?php echo get_permalink($pages_id['fale']); ?>'">Fale Conosco</button>
              </div>
            </div>
        </div>
    <?php do_action('__after_content');?>
</section>
