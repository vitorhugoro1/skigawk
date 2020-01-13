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

if ($fetaria == 'mirim' || $fetaria == 'infantil' || $fetaria == 'ijuvenil' || $fetaria == 'junior') {
    $file = get_post_meta($_POST['camp_id'], '_vhr_autorizacao_file_id');
    $parsed = parse_url(wp_get_attachment_url($file));
    $url = dirname($parsed['path']) . '/' . rawurlencode(basename($parsed['path']));
    ?>
    <p>
        Autorização paulista para atleta menor de idade:

        <a href="<?php echo $url; ?>" target="_blank"> Autorização para Atleta Menor de Idade</a>
    </p>
<?php
}
?>
<p>
    Regras por estilo (Arquivo para Download de acordo com os estilos disponiveis)
    <ul>
        <?php
$list = wp_get_post_terms($_POST['camp_id'], 'categoria', array('fields' => 'all'));
foreach ($list as $term) {
    $in = get_the_author_meta('insiders', $user->ID);
    if (empty($in) || !array_key_exists($_POST['camp_id'], $in)) {
        echo '<li>';
        echo '<a href="' . get_modalidade_file($term->slug) . '">' . $term->name . '</a>';
        echo '</li>';
    } else {
        foreach ($in[$_POST['camp_id']] as $k => $i) {
            if ($k == 'categorias') {
                if (!array_key_exists($term->slug, $i)) {
                    echo '<li>';
                    echo '<a href="' . get_modalidade_file($term->slug) . '">' . $term->name . '</a>';
                    echo '</li>';
                }
            }
        }
    }
}

?>
    </ul>
</p>

<b>Termo de Responsabilidade</b>
<iframe id="frame" src="" width="100%" height="400px"></iframe>
<div>
<input type="checkbox" id="accept" value="true"/> Eu concordo com o <strong>Termo de Responsabilidade</strong>
</div>
<br>
<input type="hidden" name="user_id" value="<?php echo $user->ID; ?>">
<input type="hidden" name="camp_id" value="<?php echo $_POST['camp_id']; ?>">
<input type="submit" class="btn btn-primary fp-button" disabled value="Avançar">
