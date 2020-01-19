<?php

$post_id = esc_attr($_POST['camp_id']);
$user_id = esc_attr($_POST['user_id']);
$insider = userInsider($user_id, $post_id); // Carrega se o usuario está inscrito já
$type = get_post_type($post_id);
$extra = 0;

switch ($type) {
    case 'campeonatos':
        $categoria = $_POST['categoria'];
        $value = count($categoria);

        // Carrega os pesos
        foreach ($categoria as $cat) {
            $peso[$cat] = $_POST['data-' . $cat];
            if ($cat == 'formaslivres' || $cat == 'formasinternas' || $cat == 'formastradicionais' || $cat == 'formasolimpicas') {
                $value -= 1;
                if (isset($_POST['data-' . $cat])) {
                    foreach ($_POST['data-' . $cat] as $item) {
                        $extra = $extra + 1;
                    }
                }
            }
        }

        $groups = groups_from_request();
        $arma = $_POST['tree-arma-tree'];
        $desafio = $_POST['desafio-bruce-arma'];
        // fim Carrega pesos
        $arr = array(7, 8, 20, 21);
        $value += $extra;
        $sexo = get_the_author_meta('sex', $user_id);
        $fetaria = get_the_author_meta('fEtaria', $user_id);

        $price = get_post_meta($post_id, '_vhr_price', true); // Carrrega o valor da inscrição principal
        $priceExtra = get_post_meta($post_id, '_vhr_price_extra', true); // Carrrega o valor da inscrição adicional
        $priceOption = get_post_meta($post_id, '_vhr_price_option', true);
        $subscriberPrice = ($priceExtra !== '0.00') ? $price + ($priceExtra * ($value - 1)) : $price * $value;

        break;
    case 'eventos':
        $priceOption = $_POST['priceOption'];
        $subscriberPrice = $_POST['price'];
        break;
}

?>
<!-- Corpo -->
<section class="tc-content <?php echo $_layout_class; ?>">
    <?php do_action('__before_content');?>
    <style media="screen">
      .relation-post {
        margin: 0 0 10px 0;
      }
      .method label {
        margin:0 10px 0 0;
      }
    </style>
        <div class="entry-content">
            <div class="hentry">
              <?php
                if (get_post_type($post_id) === 'campeonatos') {
                    require 'finaliza-campeonato.php';
                } else {
                    require 'finaliza-evento.php';
                }
                ?>
            </div>
        </div>
    <?php do_action('__after_content');?>
</section>
