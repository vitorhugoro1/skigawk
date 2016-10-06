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
                             $in = get_the_author_meta('insiders', $_POST['user_id'], true);
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
              <form action="<?php echo get_template_directory_uri() . '/includes/cadastrar.php' ?>" method="post" class="form-inline">
                <?php if($priceOption == 's') { ?>
                  <input type="hidden" name="priceTotal" value="<?php echo $subscriberPrice; ?>"/>
              <?php  } else { ?>
                  <input type="hidden" name="priceTotal" value="0"/>
              <?php  } ?>
                <input type="hidden" name="post_id" value="<?php echo $_POST['camp_id']; ?>"/>
                <input type="hidden" name="user_id" value="<?php echo $_POST['user_id']; ?>"/>
                <?php if($type == 'campeonatos') { ?>
                <input type="hidden" name="peso" value='<?php echo htmlspecialchars(json_encode($peso)); ?>'/>
                <input type="hidden" name="groups" value='<?php echo htmlspecialchars(json_encode($groups)); ?>'/>
                <input type="hidden" name="armas" value='<?php echo htmlspecialchars(json_encode($arma)); ?>'/>
                <?php } ?>
                <input type="hidden" name="insider" value="<?php echo ($insider) ? 's' : 'n'; ?>"/>
                <input type="submit" class="btn btn-primary fp-button" value="Finalizar"/>
                <a href="javascript:history.back()" class="btn fp-button">Voltar</a>
              </form>
