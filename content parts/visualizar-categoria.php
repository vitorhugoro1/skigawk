<?php
foreach($modalidades as $cat){
    $term = get_term_by('slug', $cat,'categoria');
    ?>
    <table class="table-bordered table-striped">
        <thead>
        <tr class="text-center strong">
            <td>
                Nome
            </td>
            <td>
                Faixa Etária
            </td>
            <td>
                Peso ou Forma(s)
            </td>
        </tr>
        </thead>
        <tbody>
        <?php
        if(! empty($inscritos)){
            foreach($inscritos as $user_id){
                $user_data = get_the_author_meta('insiders', $user_id);
                $sexo = get_the_author_meta('sex', $user_id);
                $fetaria = get_the_author_meta('fEtaria', $user_id);

                if(isset($user_data[$post_id]['categorias'][$cat])){
                    $user_cat = array_keys($user_data[$post_id]['categorias']);
                    $user_cat_data = $user_data[$post_id]['categorias'][$cat];
                } else {
                    $user_cat = false;
                }
                ?>
                <tr class="text-center">
                    <td>
                        <?php
                        echo get_the_author_meta('display_name', $user_id);
                        ?>
                    </td>
                    <td>
                        <?php
                        echo ucfirst($fetaria);
                        ?>
                    </td>
                    <td>
                        <?php
                        if($cat == 'formaslivres' || $cat == 'formasinternas' || $cat == 'formastradicionais' || $cat == 'formasolimpicas'){
                            if($user_cat !== false){
                                  $count = count($user_cat_data);
                                  $c = 0;
                                  foreach ($user_cat_data as $item){
                                     $c++;
                                     echo get_weight($cat, $item['peso'], $sexo, $fetaria);
                                     echo ($c == $count) ? '.' : ', ';
                                  }
                            } else {
                                ?>
                                <b>Não inscrito</b>
                                <?php
                            }
                        } else {
                            if($user_cat !== false){
                                echo get_weight($cat, $user_cat_data['peso'], $sexo, $fetaria).' Kg';
                            }
                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
        } else {
        ?>
            <tr class="text-center">
                <td colspan="3">
                    Sem inscritos adicionais à modalidade
                </td>
            </tr>
        <?php
        }
        ?>
        </tbody>
        <caption class="small">Inscritos - <?php echo $term->name; ?></caption>
    </table>
    <?php
}
?>
