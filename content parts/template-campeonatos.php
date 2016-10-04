<?php
$ordem_apresentar = array(
  'combate' => array('guardas', 'cassetete', 'semi', 'shuai', 'kuolight', 'kuoleitai', 'wushu', 'sanda', 'muaythai-a', 'muaythai-p', 'cmma', 'mma'),
  'formas'  => array('formastradicionais', 'formasinternas', 'formasolimpicas', 'formaslivres', 'tree')
);


if($fetaria == 'mirim' || $fetaria ==  'infantil' || $fetaria == 'ijuvenil' || $fetaria == 'junior'){
    $file = get_post_meta($_POST['camp_id'], '_vhr_autorizacao_file_id');
    $parsed = parse_url( wp_get_attachment_url( $file) );
    $url    = dirname( $parsed [ 'path' ] ) . '/' . rawurlencode( basename( $parsed[ 'path' ] ) );
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
        $list = wp_get_post_terms( $_POST['camp_id'], 'categoria', array('fields' => 'all') );
        foreach($list as $term){
            $in = get_the_author_meta('insiders', $user->ID);
            if(empty($in) || ! array_key_exists($_POST['camp_id'], $in)){
                echo '<li>';
                echo '<a href="'.get_modalidade_file($term->slug).'">'.$term->name.'</a>';
                echo '</li>';
            } else {
                foreach($in[$_POST['camp_id']] as $k => $i){
                    if($k == 'categorias'){
                        if(!array_key_exists($term->slug, $i)){
                            echo '<li>';
                            echo '<a href="'.get_modalidade_file($term->slug).'">'.$term->name.'</a>';
                            echo '</li>';
                        }
                    }
                }
            }
        }

        ?>
    </ul>
</p>


Selecione o estilo que vai participar
<div id="estilo">
    <ul>
       <?php
            $list = wp_get_post_terms( $_POST['camp_id'], 'categoria', array('fields' => 'all') );
            foreach($ordem_apresentar as $key => $modalidade){
              if('combate' == $key){
                echo sprintf('<h4>%s</h4>', 'Modalidades de Combate');
                foreach($modalidade as $term_slug){
                  $term = get_term_by('slug', $term_slug, 'categoria');
                  $in = get_the_author_meta('insiders', $user->ID, true);
                  if(empty($in) || ! array_key_exists($_POST['camp_id'], $in)){
                    echo '<li>';
                    echo '<input type="checkbox" id="'.$term->slug.'" name="categoria[]" value="'.$term->slug.'" />  '.$term->name;
                    echo '<div id="'.$term->slug.'"></div>';
                    echo '</li>';
                  } else {
                    foreach($in[$_POST['camp_id']] as $k => $i){
                      if($k == 'categorias'){
                        if($term->slug == 'formaslivres' || $term->slug == 'formasinternas' || $term->slug == 'formastradicionais' || $term->slug == 'formasolimpicas'){
                          echo '<li>';
                          echo '<input type="checkbox" id="'.$term->slug.'" name="categoria[]" value="'.$term->slug.'" />'.$term->name;
                          echo '<div id="'.$term->slug.'"></div>';
                          echo '</li>';
                        } else {
                          if(!array_key_exists($term->slug, $i)){
                            echo '<li>';
                            echo '<input type="checkbox" id="'.$term->slug.'" name="categoria[]" value="'.$term->slug.'" />'.$term->name;
                            echo '<div id="'.$term->slug.'"></div>';
                            echo '</li>';
                          }
                        }

                      }
                    }
                  }
                }
              } else if('formas' == $key){
                echo sprintf('<h4>%s</h4>', 'Modalidades de Formas Artísticas');
                foreach($modalidade as $term_slug){
                  $term = get_term_by('slug', $term_slug, 'categoria');
                  $in = get_the_author_meta('insiders', $user->ID, true);
                  if(empty($in) || ! array_key_exists($_POST['camp_id'], $in)){
                    echo '<li>';
                    echo '<input type="checkbox" id="'.$term->slug.'" name="categoria[]" value="'.$term->slug.'" />  '.$term->name;
                    echo '<div id="'.$term->slug.'"></div>';
                    echo '</li>';
                  } else {
                    foreach($in[$_POST['camp_id']] as $k => $i){
                      if($k == 'categorias'){
                        if($term->slug == 'formaslivres' || $term->slug == 'formasinternas' || $term->slug == 'formastradicionais' || $term->slug == 'formasolimpicas'){
                          echo '<li>';
                          echo '<input type="checkbox" id="'.$term->slug.'" name="categoria[]" value="'.$term->slug.'" />'.$term->name;
                          echo '<div id="'.$term->slug.'"></div>';
                          echo '</li>';
                        } else {
                          if(!array_key_exists($term->slug, $i)){
                            echo '<li>';
                            echo '<input type="checkbox" id="'.$term->slug.'" name="categoria[]" value="'.$term->slug.'" />'.$term->name;
                            echo '<div id="'.$term->slug.'"></div>';
                            echo '</li>';
                          }
                        }

                      }
                    }
                  }
                }
              }
            }
        ?>
    </ul>
</div>

<?php if(get_post_meta($_POST['camp_id'], '_vhr_price_option', true) == 's'){
    echo sprintf('Valor da inscrição para o primeiro <b>Estilo</b>: <b>R$ %s </b><br>', get_post_meta($post_id, '_vhr_price', true));
    if(get_post_meta($_POST['camp_id'], '_vhr_price_extra', true) !== '0.00'){
      echo sprintf('Valor da inscrição para cada <b>Estilo</b> adicional: <b>R$ %s </b>', get_post_meta($post_id, '_vhr_price_extra', true));
    }
    echo '<b>O valor total será mostrado na página seguinte</b><br>';
    } else { ?>
      <b>Campeonato Gratuito</b><br>
  <?php    } ?>
<b>Termo de Responsabilidade</b>
<iframe id="frame" src="" width="100%" height="400px"></iframe>
<div>
<input type="checkbox" id="accept" value="true"/> Eu concordo com o <strong>Termo de Responsabilidade</strong>
</div>
<br>
<input type="hidden" name="user_id" value="<?php echo $user->ID; ?>">
<input type="hidden" name="camp_id" value="<?php echo $_POST['camp_id']; ?>">
<input type="submit" class="btn btn-primary fp-button" disabled value="Inscrever-se">
