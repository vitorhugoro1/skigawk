Selecione o estilo que vai participar
<div id="estilo">
    <ul>
       <?php echo template_modalities(); ?>
    </ul>
</div>

<?php if (get_post_meta($_POST['camp_id'], '_vhr_price_option', true) == 's') {
    echo sprintf(
        'Valor da inscrição para o primeiro <b>Estilo</b>: <b>R$ %s </b><br>',
        get_post_meta($post_id, '_vhr_price', true)
    );

    if (get_post_meta($_POST['camp_id'], '_vhr_price_extra', true) !== '0.00') {
        echo sprintf(
            'Valor da inscrição para cada <b>Estilo</b> adicional: <b>R$ %s </b>',
            get_post_meta($post_id, '_vhr_price_extra', true)
        );
    }

    echo '<b>O valor total será mostrado na página seguinte</b><br>';
} else {?>
      <b>Campeonato Gratuito</b><br>
  <?php }

children_autorization_file_text();
?>

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
