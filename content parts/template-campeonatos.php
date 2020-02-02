Selecione o estilo que vai participar
<div id="estilo">
    <ul>
       <?php echo template_modalities(); ?>
    </ul>
</div>

<?php echo get_event_price_text(false); ?>

<?php echo children_autorization_file_text(false); ?>

<?php // show_modalities_rules_text(); ?>

<b>Termo de Responsabilidade</b>
<iframe id="frame" src="" width="100%" height="400px"></iframe>
<div>
<input type="checkbox" id="accept" value="true"/> Eu concordo com o <strong>Termo de Responsabilidade</strong>
</div>
<br>
<input type="hidden" name="user_id" value="<?php echo $user->ID; ?>">
<input type="hidden" name="camp_id" value="<?php echo $_POST['camp_id']; ?>">
<input type="submit" class="btn btn-primary fp-button" disabled value="Avançar">
