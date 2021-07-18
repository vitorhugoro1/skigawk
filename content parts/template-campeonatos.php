<p>Selecione o estilo que vai participar</p>
<div id="estilo">
    <ul>
        <?php echo template_modalities(); ?>
    </ul>
</div>

<?php echo get_event_price_text(false); ?>

<?php echo children_autorization_file_text(false); ?>

<div>
    <h3>Termo de Responsabilidade</h3>
    <iframe id="frame" src="<?= sprintf("%s?action=%s&post_id=%s", admin_url('admin-post.php'), 'vhr_event_user_term', $_POST['camp_id']) ?>" width="100%" height="400px"></iframe>
    <div>
        <label for="accept" aria-label="Eu concordo com o Termo de Responsabilidade">
            <input type="checkbox" id="accept" value="true" />
            <span>Eu concordo com o <strong>Termo de Responsabilidade</strong></span>
        </label>
    </div>
</div>
<div>
    <input type="hidden" name="user_id" value="<?php echo $user->ID; ?>">
    <input type="hidden" name="camp_id" value="<?php echo $_POST['camp_id']; ?>">
    <button type="submit" class="btn btn-primary fp-button mt-10">
        Avançar
    </button>
</div>