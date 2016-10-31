<?php
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
            Modalidade / Peso ou Forma(s)
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
                $user_cat = $user_data[$post_id]['categorias'];
                ?>
                <tr class="text-center">
                    <td>
                        <?php echo get_the_author_meta('display_name', $user_id); ?>
                    </td>
                    <td>
                        <?php echo ucfirst($fetaria); ?>
                    </td>
                    <td>
                        <ul class="list-inscrito">
                            <?php
                            foreach($user_cat as $cat_slug => $cat_data){
                                $category = get_term_by( 'slug', $cat_slug, 'categoria');
                                echo '<li>';
                                if($cat_slug == 'formaslivres' || $cat_slug == 'formasinternas' || $cat_slug == 'formastradicionais' || $cat_slug == 'formasolimpicas'){
                                    echo '<b>'.$category->name.'</b> / ';
                                    $count = count($cat_data);
                                    //                                 var_dump($cat_data);
                                    $c = 0;
                                    foreach ($cat_data as $item){
                                        $c++;
                                        echo get_weight($cat_slug, $item['peso'], $sexo, $fetaria);
                                        echo ($c == $count) ? '.' : ', ';
                                    }
                                } else {
                                    echo '<b>'.$category->name.'</b> / '.get_weight($cat_slug, $cat_data['peso'], $sexo, $fetaria).' Kg';
                                }
                                echo '</li>';
                            }
                            ?>
                        </ul>
                    </td>
                </tr>
                <?php
            }
        } else {
            ?>
            <tr class="text-center">
                <td colspan="3">
                    Sem inscritos adicionais ao campeonato
                </td>
            </tr>
            <?php
        }

        ?>
    </tbody>
    <caption class="small">Todos os inscritos</caption>
</table>