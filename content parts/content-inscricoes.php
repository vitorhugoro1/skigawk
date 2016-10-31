<?php
$user = wp_get_current_user();
$sexo = get_the_author_meta('sex' , $user->ID );
$fetaria = get_the_author_meta('fEtaria' , $user->ID );
$inscricoes = get_the_author_meta('insiders' , $user->ID );
$pages_id = pages_group_ids();

?>
<section class="tc-content <?php echo $_layout_class; ?>">
    <?php do_action( '__before_content' ); ?>
        <div class="entry-content">
            <div class="hentry inscricoes">
              <?php
              if(!empty($inscricoes)) {
                $list = array();
                foreach($inscricoes as $data_key => $data_value) {
                  if(!in_array(get_post_type($data_key), $list)){
                    $list[] = get_post_type($data_key);
                  }
                }
                sort($list);
                foreach ($list as $post_type) {
                  if($post_type == 'campeonatos') {
                ?>
              <table class="table-bordered table-striped">
                <thead>
                    <tr>
                      <td class="text-center" colspan="3">
                        <b>Inscrições</b>
                      </td>
                    </tr>
                    <tr class="text-center">
                      <td>
                        <b>Campeonatos</b>
                      </td>
                      <td>
                        <b>Categorias / Peso ou Forma(s)</b>
                      </td>
                      <td>
                        <b>Pagamentos</b>
                      </td>
                    </tr>
                </thead>
                <tbody>
                  <?php
                  foreach($inscricoes as $data => $value) {
                      if($post_type == get_post_type($data)){
                    ?>
                    <tr class="text-center">
                      <td>
                       <a href="<?php echo home_url('/visualiza-inscrito').'?post_id='.$data; ?>" target="_blank"><?php echo get_the_title($data); ?></a>
                      </td>
                      <td>
                        <ul class="list-inscrito">
                        <?php
                        $formaGrupo['formaslivres'] = array(7,8, 12, 13);
                        $formaGrupo['formasinternas'] = array(8,9);
                        $formaGrupo['formastradicionais'] = array(7,8,20,21);
                         foreach($value['categorias'] as $cat_slug => $cat_data){
                             $category = get_term_by( 'slug', $cat_slug, 'categoria');
                             echo '<li>';
                             if($cat_slug == 'formaslivres' || $cat_slug == 'formasinternas' || $cat_slug == 'formastradicionais' || $cat_slug == 'formasolimpicas' || $cat_slug == 'tree'){
                               $count = count($cat_data);
                               $c = 0;
                               ?>
                               <?php echo sprintf('<b>%s</b>', $category->name ); ?>
                                <ul>
                                  <?php
                                  foreach ($cat_data as $item){
                                      $c++;
                                      $forma = get_weight($cat_slug, $item['peso'], $sexo, $fetaria);
                                      echo '<li>';
                                      echo $forma;
                                      if($cat_slug == 'formaslivres' ||
                                       $cat_slug == 'formasinternas' ||
                                       $cat_slug == 'formastradicionais'){
                                         if(in_array($item['peso'], $formaGrupo[$cat_slug])){
                                           if(isset($item['groups'])) {
                                             echo ' <i>' . implode(", ", array_filter( $item['groups']) ) . '</i>';
                                           }
                                         }
                                       } else if($cat_slug == 'tree'){
                                         if(isset($item['arma'])){
                                           echo ' - ' . $item['arma'];
                                         } else {
                                           echo (empty($forma)) ? '' : ' - sem cadastro';
                                         }
                                       }

                                      echo (empty($forma)) ? '' : (($c == $count) ? '.' : ', ');
                                      echo '</li>';
                                  }
                                   ?>
                                </ul>
                               <?php
                             } else {
                               echo sprintf('<b>%s</b> / %s Kg', $category->name, get_weight($cat_slug, $cat_data['peso'], $sexo, $fetaria) );
                             }
                           echo '</li>';
                         }
                         ?>
                       </ul>
                      </td>
                      <td>
	                      <ul class="list-inscrito">
	                      	<?php
                        	foreach($value['categorias'] as $cat_slug => $cat_data){
                            $id_pag = '';
                        		$category = get_term_by( 'slug', $cat_slug, 'categoria');
                                echo '<li>';
                                if($cat_slug == 'formaslivres' || $cat_slug == 'formasinternas' || $cat_slug == 'formastradicionais' || $cat_slug == 'formasolimpicas' || $cat_slug == 'tree'){
                                    foreach($cat_data as $item){
                                        $id_pag[] = $item['id_pagamento'];
                                    }
                                    $unique = array_unique($id_pag);

                                    echo '<b>'.$category->name.'</b> - '.implode(', ', array_filter($unique));

                                } else {
                                    $query = "SELECT id FROM `wp_payments` WHERE id = ".esc_sql($cat_data['id_pagamento']);
                                    $results = ($wpdb->get_var($query) != '') ? $wpdb->get_var($query) : 'N/A';

                                    echo sprintf('<b>%s</b> - %s', $category->name, $results );
                                }
                        		echo '</li>';
                        	}
                         ?>
	                      </ul>
                      </td>
                    </tr>
<?php
              }
                  }

                   ?>

                </tbody>
                <caption class="">Campeonatos</caption>
              </table>
              <?php
              } else if($post_type == 'eventos'){
                ?>
              <table class="table-bordered table-striped">
                <thead>
                    <tr>
                      <td class="text-center" colspan="2">
                        <b>Inscrições</b>
                      </td>
                    </tr>
                    <tr class="text-center">
                      <td>
                        <b>Eventos</b>
                      </td>
                      <td>
                        <b>Modalidades</b>
                      </td>
                    </tr>
                </thead>
                <tbody>
                  <?php
                  foreach($inscricoes as $data => $value) {
                      if($post_type == get_post_type($data)){
                    ?>
                    <tr class="text-center">
                      <td>
                       <a href="<?php echo get_permalink($data); ?>" target="_blank"><?php echo get_the_title($data); ?></a>
                      </td>
                      <td>
                        <?php
                          foreach($value as $keyp => $pag){
                            switch($keyp){
                              case 'pagamento' :
                                $category = ucfirst($pag['category']);
                                $valor = (isset($pag['valor'])) ? $pag['valor'] : 'N/A';
                                echo sprintf('%s - R$%s', $category, $valor);
                              break;
                            }
                          }
                         ?>
                      </td>
                    </tr>
  <?php
              }
            }

                   ?>

                </tbody>
                <caption>Eventos</caption>
              </table>
              <?php
              }
            }
             } else {
                ?>
                <p>
                    <b>Sem inscrições no momento.</b>
                </p>
                  <p>
                      Clique em um dos itens abaixo para acessar as páginas de postagens e realizar inscrições.
                  </p>
              <p class="aligncenter">
                  <a class="btn" href="<?php echo get_permalink($pages_id['campeonatos']); ?>">Campeonatos</a>
                  <a class="btn" href="<?php echo get_permalink($pages_id['eventos']); ?>">Eventos</a>
              </p>
                <?php
              } ?>
              <p>
                    O pagamento deve ser realizado para os dados da conta descritos abaixo:
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
              <div class="alignright">
                  <button class="btn btn-primary btn-large" onclick="document.location.href='<?php echo get_permalink($pages_id['fale']);?>'">Fale Conosco</button>
              </div>
            </div>
        </div>
    <?php do_action( '__after_content' ); ?>
</section>
