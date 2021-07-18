<?php

$niveis = get_post_meta($post_id, 'category_insider_group', true);

?>

<div>
    <p>Níveis de inscrição disponiveis:</p>

    <ul id="seleciona">
        <?php
        if (!empty($niveis)) {
            foreach ((array) $niveis as $tkey => $tentry) {
        ?>
                <li>
                    <label for="<?php echo sanitize_title($tentry['name']); ?>">
                        <input type="hidden" name="option-<?php echo sanitize_title($tentry['name']); ?>" value="<?php echo sanitize_title($tentry['name']); ?>" />
                        <input type="hidden" name="priceOption-<?php echo sanitize_title($tentry['name']); ?>" value="<?php echo $tentry['price_option'] ?>" />
                        <input type="radio" id="<?php echo sanitize_title($tentry['name']); ?>" name="price" value="<?php echo number_format($tentry['price'], 2, '.', ''); ?>" /> <?php echo $tentry['name'] . ' - R$' . number_format($tentry['price'], 2, '.', ''); ?>
                    </label>
                    <div class="info">
                        <?= wpautop($tentry['description']) ?>
                    </div>
                </li>
        <?php
            }
        }
        ?>
    </ul>

</div>
<div>
    <b>Termo de Responsabilidade</b>
    <iframe id="frame" src="<?= sprintf("%s?action=%s&post_id=%s", admin_url('admin-post.php'), 'vhr_event_user_term', $_POST['camp_id']) ?>" width="100%" height="400px"></iframe>
    <div>
        <input type="checkbox" id="accept" value="true" /> Eu concordo com o <strong>Termo de Responsabilidade</strong>
    </div>
</div>
<br>
<input type="hidden" name="user_id" value="<?php echo $user->ID; ?>">
<input type="hidden" name="camp_id" value="<?php echo $post_id; ?>">
<button type="submit" class="btn btn-primary fp-button mt-10">
    Avançar
</button>