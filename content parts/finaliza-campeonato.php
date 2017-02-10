<?php
$arr_sub_adulto = array('adulto', 'senior');
?>
<div class="relation-post">
                <h3><?php echo get_the_title($post_id); ?></h3>
                <div class="">
                  <?php echo get_excerpt($post_id); ?><br>
                  <?php if($type == 'campeonatos') { ?>
                  <span>Modalidades disponiveis: </span>
                  <ul>
                     <?php
                         $list = wp_get_post_terms( $_POST['camp_id'], 'categoria', array('fields' => 'all') );
                         foreach($list as $term){
                             $in = get_the_author_meta('insiders', $_POST['user_id']);
                             $fetaria = get_the_author_meta('fEtaria', $_POST['user_id']);

                             if($term->slug == 'submission-adulto' && ! in_array($fetaria, $arr_sub_adulto) ){
                               continue;
                             } else if($term->slug == 'submission-infantil' && in_array($fetaria, $arr_sub_adulto)) {
                               continue;
                             }

                             if(empty($in)){
                              echo '<li>'.$term->name.'</li>';
                             } else {
                              foreach($in[$_POST['camp_id']] as $k => $i){
                                 if($k == 'categorias'){
                                   if(!array_key_exists($term->slug, $i)){
                                     echo '<li>'.$term->name.'</li>';
                                   }
                                 }
                               }
                             }
                         }
                      ?>
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
                    foreach ($peso as $cat => $value) { ?>
                      <tr>
                        <td>
                          <?php echo get_term_by( 'slug', $cat, 'categoria')->name; ?>
                        </td>
                        <td>
                          <?php if($type == 'campeonatos'){
                            switch ($cat) {
                              case 'formaslivres':
                                $group = array(8, 9, 12, 13);
                                break;
                              case 'formastradicionais':
                                $group = array(7,8,20,21);
                                break;
                              case 'formasinternas':
                                $group = array(7, 8);
                                break;
                            }

                            if ($cat == 'formaslivres' || $cat == 'formasinternas' ||
                             $cat == 'formastradicionais' || $cat == 'formasolimpicas'){
                                foreach($value as $item){
                                    echo get_weight($cat, $item, $sexo, $fetaria).'<br>';
                                    if(in_array($item, $group)){
                                      echo '<b>Equipe: </b>'.implode(', ', array_filter($groups[$cat][$item])).'<br>';
                                    }
                                }
                            } elseif ( $cat == 'tree') {
                              foreach($value as $item){
                                  echo get_weight($cat, $item, $sexo, $fetaria);
                                  echo sprintf('  <b>Arma:</b> %s<br>', array_filter($arma[$item]));
                              }
                            } else {
                              echo get_weight($cat, $value, $sexo, $fetaria). ' Kg';
                            }

                          } else {

                          } ?>
                        </td>
                      </tr>
                  <?php  }
                    ?>
                </tbody>
              </table>
                  <?php } else { ?>
                      <br>
<?php                  } ?>
              <div class="">
                <?php
                 if($type == 'campeonatos') {
                   echo (count($categoria) > 1) ? '&nbsp;&nbsp;Todas as <b>Modalidades</b> selecionadas foram' : '&nbsp;&nbsp;A <b>Modalidade</b> selecionada foi';
                   if($priceOption == 's') {
                     echo ' no total de <b>R$ '.number_format($subscriberPrice, 2, ',', '').'</b>';
                   } else {
                     echo ' <b>Gratuita</b>';
                   }
                 }
                   ?>
                conforme descrito na página anterior em referência aos valores pedidos em relação a cada modalidade, e se houver, o valor diferencial em relação a cada modalidade
                nova selecionada.<br>
              </div>
                <p>
                    O pagamento do valor acima deve ser realizado para nos dados da conta descritos abaixo:
                </p>
                <p>
                  <?php
                    $options = unserialize(get_option('deposito'));

                    echo sprintf('%s<br> %s<br>Agência: %s<br>Conta: %s', $options['banco'], $options['beneficiario'], $options['agencia'], $options['conta']);
                   ?>
                </p>
                <p>
                    Ao realizar o pagamento enviar o comprovante para o e-mail <a href="mailto:adriel@skigawk.com.br">adriel@skigawk.com.br</a>.
                </p>
              <form action="<?php echo admin_url('admin-post.php'); ?>" method="post" class="form-inline">
                <input type="hidden" name="action" value="vhr_cadastrar_evento"/>
                <?php wp_nonce_field('vhr_cadastrar_evento') ?>
                <input type="hidden" name="info[post_id]" value="<?php echo $_POST['camp_id']; ?>"/>
                <input type="hidden" name="info[tipo]" value="<?php echo $type; ?>"/>
                <input type="hidden" name="info[valor]" value="<?php echo $subscriberPrice; ?>"/>
                <input type="hidden" name="info[inscrito]" value="<?php echo ($insider) ? 's' : 'n'; ?>"/>
                <input type="hidden" name="info[meio_pag]" id="meio_pag_input" value="deposito"/>
                <?php foreach($peso as $cat => $value){
                    if(is_array($value)){
                      foreach($value as $k => $item){
                        echo sprintf('<input type="hidden" name="categorias[%s][%d][id]" value="%s" />', $cat, $k , $item );

                        if(isset($armas[$item])){
                          echo sprintf('<input type="hidden" name="categorias[%s][%d][arma]" value="%s" />', $cat, $k,$armas[$item]);
                        } elseif (isset(array_filter($groups)[$item])) {
                          echo sprintf('<input type="hidden" name="categorias[%s][%d][equipe][nome]" value="%s" />', $cat,$k, $groups[$item]['nome'] );
                          foreach (array_filter($groups) as $key => $vlx) {
                            echo sprintf('<input type="hidden" name="categorias[%s][%d][equipe][elementos][%d][nome]" value="%s" />', $cat,$k, $key, $vlw['nome'] );
                            echo sprintf('<input type="hidden" name="categorias[%s][%d][equipe][elementos][%d][email]" value="%s" />', $cat,$k, $key, $vlw['email'] );
                          }
                        }
                      }
                    } else {
                      echo sprintf('<input type="hidden" name="categorias[%s][id]" value="%s" />', $cat , $value );
                    }
                  } ?>
                  <div class="row-fluid form-actions">
                    <label for="feedback"><input type="checkbox" id="feedback" name="feedback" value="s"> Adicionar uma mensagem para o organizador?</label>
                    <textarea name="feedback_msg" id="feedback_msg" style="display:none;" class="span8" placeholder="Mensagem"></textarea>
                  </div>
                  <input type="submit" class="btn btn-primary fp-button" value="Avançar"/>
              </form>
              <script type="text/javascript">
                jQuery(document).ready(function($) {
                  $("#feedback").on('click', function(){
                    if($(this).is(':checked')){
                      $("#feedback_msg").show().attr('required', 'required');
                    } else {
                      $("#feedback_msg").hide().attr('required', false);
                    }
                  });
                });
              </script>
