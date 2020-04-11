<?php
$arr_sub_adulto = array('adulto', 'senior');
$formas = form_style_data();
$rules = form_style_rules();
$group = [];
?>
<div class="relation-post">
  <h3><?php echo get_the_title($post_id); ?></h3>
  <div>
    <?php echo get_excerpt($post_id); ?><br>
    <?php if ($type === 'campeonatos') {?>
    <span>Modalidades disponiveis: </span>
    <ul>
        <?php allowed_subscribe_categories();?>
    </ul>
  </div>
</div>

<table>
    <thead>
        <tr>
            <td colspan="2" class="text-center">
                <b>Inscrições</b>
            </td>
        </tr>
        <tr>
            <td>
                <b>Categoria</b>
            </td>
            <td>
                <b>Forma ou Peso (Kg)</b>
            </td>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($peso as $cat => $value) {
            ?>
            <tr>
                <td>
                    <?php echo get_term_by('slug', $cat, 'categoria')->name; ?>
                </td>
                <td>
                <?php
                if ($type == 'campeonatos') {
                    if (array_key_exists($cat, $rules['withGroup'])) {
                        $group = $rules['withGroup'][$cat];
                    }

                    if (array_key_exists($cat, $rules['withCustomTeam'])) {
                        $group = $rules['withCustomTeam'][$cat];
                    }

                    if (in_array($cat, $formas)) {
                        if (is_array($value)) {
                            foreach ($value as $item) {
                                echo get_weight($cat, $item, $sexo, $fetaria) . '<br>';

                                if (in_array($item, $group)) {
                                    echo '<b>Equipe: </b>' . implode(', ', array_filter($groups[$cat][$item])) . '<br>';
                                }
                            }
                        }

                        if (is_string($value)) {
                            echo get_weight($cat, $value, $sexo, $fetaria) . '<br>';

                            if (in_array($value, $group)) {
                                echo '<b>Equipe: </b>' . implode(', ', array_filter($groups[$cat][$value])) . '<br>';
                            }
                        }
                    }

                    if ($cat === 'tree') {
                        echo get_weight($cat, $value, $sexo, $fetaria);
                        echo sprintf('  <b>Arma:</b> %s<br>', $arma[$value]);
                    }

                    if ($cat === 'desafio-bruce') {
                        echo get_weight($cat, $value, $sexo, $fetaria);
                        echo sprintf('  <b>Arma:</b> %s<br>', $desafio);
                    }

                    if (!in_array($cat, array_merge($formas, ['desafio-bruce', 'tree']))) {
                        echo get_weight($cat, $value, $sexo, $fetaria) . ' Kg';

                        if (!empty($group)) {
                            echo '<br><b>Equipe: </b>' . implode(', ', array_filter($groups[$cat][$value])) . '<br>';
                        }
                    }
                }
                ?>
                </td>
            </tr>
            <?php
        }
        ?>
    </tbody>
</table>
    <?php } else { ?>
        <br>
    <?php } ?>
<div class="">
    <?php
    if ($type == 'campeonatos') {
        echo (count($categoria) > 1) ? '&nbsp;&nbsp;Todas as <b>Modalidades</b> selecionadas foram' : '&nbsp;&nbsp;A <b>Modalidade</b> selecionada foi';
        if ($priceOption == 's') {
            echo ' no total de <b>R$ ' . number_format($subscriberPrice, 2, ',', '') . '</b>';
        } else {
            echo ' <b>Gratuita</b>';
        }
    }
    ?> conforme descrito na página anterior em referência aos valores pedidos em relação a cada modalidade, e se houver, o valor diferencial em relação a cada modalidade nova selecionada.<br>
</div>
<p>
    O pagamento do valor acima deve ser realizado para nos dados da conta descritos abaixo:
</p>
<p>
<?php
$options = unserialize(get_option('deposito'));
$banco = get_post_meta($_POST['camp_id'], '_vhr_banco', true);
$beneficiario = get_post_meta($_POST['camp_id'], '_vhr_beneficiario', true);
$agencia = get_post_meta($_POST['camp_id'], '_vhr_agencia', true);
$conta = get_post_meta($_POST['camp_id'], '_vhr_conta', true);

if ($banco == '' || $beneficiario == '' || $agencia == '' || $conta == '') {
    echo sprintf(
        '%s<br> %s<br>Agência: %s<br>Conta: %s',
        $options['banco'],
        $options['beneficiario'],
        $options['agencia'],
        $options['conta']
    );
} else {
    echo sprintf('%s<br> %s<br>Agência: %s<br>Conta: %s', $banco, $beneficiario, $agencia, $conta);
}
?>
</p>
<p>
Ao realizar o pagamento enviar o comprovante para o e-mail <a href="mailto:adriel@skigawk.com.br">adriel@skigawk.com.br</a>.
</p>
<form action="https://improved-enigma.test/subscription-event" method="POST" class="form-inline">
    <input type="hidden" name="info[post_id]" value="<?php echo $_POST['camp_id']; ?>"/>
    <input type="hidden" name="info[redirectUrl]" value="<?php echo home_url('/inscricoes'); ?>"/>
    <input type="hidden" name="info[user_id]" value="<?php echo get_current_user_id(); ?>"/>
    <input type="hidden" name="info[tipo]" value="<?php echo $type; ?>"/>
    <input type="hidden" name="info[valor]" value="<?php echo $subscriberPrice; ?>"/>
    <input type="hidden" name="info[inscrito]" value="<?php echo ($insider) ? 's' : 'n'; ?>"/>
    <?php
    foreach ($peso as $cat => $value) {
        if (is_array($value)) {
            foreach ($value as $k => $item) {
                echo sprintf('<input type="hidden" name="categorias[%s][%d][id]" value="%s" />', $cat, $k, $item);
                if (isset($groups[$cat][$item])) {
                    foreach (array_filter($groups[$cat][$item]) as $key => $vlx) {
                        echo sprintf('<input type="hidden" name="categorias[%s][%d][equipe][%d]" value="%s" />', $cat, $k, $key, $vlx);
                    }
                }
            }
        } else {
            if ($cat === 'desafio-bruce') {
                echo sprintf('<input type="hidden" name="categorias[%s][id]" value="%s" />', $cat, '0');
                echo sprintf('<input type="hidden" name="desafio-bruce-arma" value="%s" />', $desafio);
                continue;
            }

            if ($cat === 'tree') {
                echo sprintf('<input type="hidden" name="categorias[%s][id]" value="%s" />', $cat, $value);
                echo sprintf('<input type="hidden" name="categorias[%s][arma]" value="%s" />', $cat, $arma[$value]);
                continue;
            }

            echo sprintf('<input type="hidden" name="categorias[%s][id]" value="%s" />', $cat, $value);

            if (array_key_exists($cat, $rules['withCustomTeam'])) {
                foreach (array_filter($groups[$cat][$value]) as $key => $vlx) {
                    echo sprintf(
                        '<input type="hidden" name="categorias[%s][equipe][%d]" value="%s" />',
                        $cat,
                        $key,
                        $vlx
                    );
                }
            }
        }
    }
    ?>
    <div class="row-fluid">
        <label>
            <input type="radio" name="info[meio_pag]" value="pag_seguro"> PagSeguro
        </label>
        <label>
            <input type="radio" name="info[meio_pag]" value="deposito"> Deposito
        </label>
    </div>
    <div class="row-fluid form-actions">
        <label for="feedback">
            <input type="checkbox" id="feedback" name="feedback" value="s"> Adicionar uma mensagem para o organizador?
        </label>
        <textarea name="feedback_msg" id="feedback_msg" style="display:none;" class="span8" placeholder="Mensagem"></textarea>
    </div>
    <input type="submit" class="btn btn-primary fp-button" value="Finalizar"/>
</form>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $("#feedback").on('click', function(){
        if($(this).is(':checked')){
            $("#feedback_msg").show().attr('required', 'required');

            return;
        }

        $("#feedback_msg").hide().attr('required', false);
    });
});
</script>
