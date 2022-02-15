<?php

// error_reporting(1);

if (is_user_logged_in()) {
    $user = wp_get_current_user();
    $birth = date_create_from_format('d/m/Y', get_the_author_meta('birthday', $user->ID));
    $birth = get_etaria_user($birth);
    update_user_meta($user->ID, 'fEtaria', $birth);
}
$pages_ids = pages_group_ids();
$categoria = fieldCampeonato($post->ID, '_vhr_nivel_luta', 'dado');
$faixaEtaria = fieldCampeonato($post->ID, '_vhr_faixa_etaria', 'dado');
$faixaEtariaP = fieldCampeonato($post->ID, '_vhr_faixa_etaria', 'array');
$categoriaP = fieldCampeonato($post->ID, '_vhr_nivel_luta', 'array');
$address = addressCampeonato($post->ID);
$price = get_post_meta($post->ID, '_vhr_price', true);
$priceOption = get_post_meta($post->ID, '_vhr_price_option', true);

$dateCompeticao = date('Y-m-d', get_post_meta($post->ID, '_vhr_dia', true));
$dataInscricao = date('Y-m-d', get_post_meta($post->ID, '_vhr_fechamento', true));

$Competicao = date_create($dateCompeticao);
$Inscricao = date_create($dataInscricao);

$now = date('Y-m-d');

if (strtotime($dataInscricao) >= strtotime($now)) :

?>

    <article <?php tc__f('__article_selectors') ?>>

        <?php do_action('__before_content'); ?>

        <section class="tc-content <?php echo $_layout_class; ?>">

            <div class="entry-content">

                <div class="clear">

                    <?php the_content(); ?>

                    <div class="flex-table">
                        <div class="flex-row">
                            <div class="flex-head">Local da competição:</div>
                            <div><?= addressCampeonato($post->ID) ?></div>
                        </div>
                        <div class="flex-row">
                            <div class="flex-head">Faixa etária <?php echo count($faixaEtariaP) > 1 ? 'disponíveis' : 'disponível' ?>:</div>
                            <div><?= $faixaEtaria ?></div>
                        </div>
                        <div class="flex-row">
                            <div class="flex-head">Categorias <?php echo count($categoriaP) > 1 ? 'disponíveis' : 'disponível' ?>:</div>
                            <div><?= $categoria ?></div>
                        </div>
                        <div class="flex-row">
                            <div class="flex-head">
                                Valor da inscrição:
                            </div>
                            <div>
                            <?php
                            if ($priceOption === 's') {
                                echo $price;
                            }

                            if ($priceOption !== 's') {
                                echo sprintf("<b>Campeonato Gratuito</b>");
                            }
                            ?>
                            </div>
                        </div>
                        <div class="flex-row">
                            <div class="flex-head">Data da competição:</div>
                            <div class="text-bold"><?=date_format($Competicao, 'd/m/Y')?></div>
                        </div>
                        <div class="flex-row">
                            <div class="flex-head">Data de fechamento inscrições:</div>
                            <div class="text-bold"><?=date_format($Inscricao, 'd/m/Y')?></div>
                        </div>
                    </div>
                </div>

                <?php

                if (is_user_logged_in()) {
                    echo subscriberButton($user->ID, $post->ID, get_post_type());
                } else {
                ?>
                    <a class="btn" href="<?= get_permalink($pages_ids['login']) ?>?redirect=<?= get_permalink() ?>">Logar</a>
                <?php  } ?>
            </div>

        </section>

        <?php do_action('__after_content'); ?>

    </article>

<?php else : ?>

    <article <?php tc__f('__article_selectors') ?>>

        <?php do_action('__before_content'); ?>

        <section class="tc-content <?php echo $_layout_class; ?>">

            <div class="entry-content">

                <div class="clear">

                    <?php the_content(); ?>

                    <div class="flex-table">
                        <div class="flex-row">
                            <div class="flex-head">Local da competição:</div>
                            <div><?= addressCampeonato($post->ID) ?></div>
                        </div>
                        <div class="flex-row">
                            <div class="flex-head">Faixa etária <?php echo count($faixaEtariaP) > 1 ? 'disponíveis' : 'disponível' ?>:</div>
                            <div><?= $faixaEtaria ?></div>
                        </div>
                        <div class="flex-row">
                            <div class="flex-head">Categorias <?php echo count($categoriaP) > 1 ? 'disponíveis' : 'disponível' ?>:</div>
                            <div><?= $categoria ?></div>
                        </div>
                        <div class="flex-row">
                            <div class="flex-head">
                                Valor da inscrição:
                            </div>
                            <div>
                            <?php
                            if ($priceOption === 's') {
                                echo $price;
                            }

                            if ($priceOption !== 's') {
                                echo sprintf("<b>Campeonato Gratuito</b>");
                            }
                            ?>
                            </div>
                        </div>
                        <div class="flex-row">
                            <div class="flex-head">Data da competição:</div>
                            <div class="text-bold"><?=date_format($Competicao, 'd/m/Y')?></div>
                        </div>
                        <div class="flex-row">
                            <div class="flex-head">Data de fechamento inscrições:</div>
                            <div class="text-bold"><?=date_format($Inscricao, 'd/m/Y')?></div>
                        </div>
                    </div>
                </div>

                <div>
                    <?php echo strtoupper(__('inscri&#199;&#213;es encerradas', 'twentyfifteen')); ?>
                </div>
            </div>

            </div>

        </section>

        <?php do_action('__after_content'); ?>

    </article>

<?php endif; ?>