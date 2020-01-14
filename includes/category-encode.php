<?php
header("Access-Control-Allow-Origin: *");
require '../../../../wp-blog-header.php';

extract($_POST);

$categories = weight_data();
$formas = form_style_data();
$rules = form_style_rules();
$subscribed = [];
$data = null;

if (!in_array($slug, ['shuai', 'desafio-bruce']) && array_key_exists($slug, $categories)) {
    if ($sexo === 'm') {
        $data = $categories[$slug]['masculino'];
    }

    if ($sexo === 'f') {
        $data = $categories[$slug]['feminino'];
    }
}

if (array_key_exists($slug, $categories) && $slug === 'shuai') {
    $nested = $categories[$slug];
    $fetaria = $fetaria === 'ijuvenil' ? 'infanto-juvenil' : $fetaria;

    if (array_key_exists($fetaria, $nested)) {
        if ($sexo === 'm') {
            $data = $nested[$fetaria]['masculino'];
        }

        if ($sexo === 'f') {
            $data = $nested[$fetaria]['feminino'];
        }
    }
}

if (array_key_exists($slug, $categories) && in_array($slug, $formas) || array_key_exists($slug, $categories) && $slug === 'tree') {
    $data = $categories[$slug];
}

if (empty($data) && $slug !== 'desafio-bruce') {
    echo '<ul><li>sem dados</li></ul>';

    return;
}

$in = get_the_author_meta('insiders', $user_id);

if (!empty($in) && array_key_exists($post_id, $in)) {
    foreach ($in[$post_id]['categorias'][$slug] as $item) {
        $subscribed[] = $item['peso'];
    }
}

if (in_array($slug, $formas)) {
    echo '<ul>';
    foreach ($data as $category => $internal) {
        if (in_array($category, $subscribed)) {
            continue;
        }

        echo '<li>';

        echo sprintf(
            '<input type="checkbox" name="data-%s[]" value="%s">&nbsp;%s',
            $slug,
            $category,
            $internal
        );

        if (array_key_exists($slug, $rules['withGroup']) && in_array($category, $rules['withGroup'][$slug])) {
            echo sprintf(
                '<ul id="group-%s" class="groups">',
                $category
            );

            foreach (range(1, 5) as $group) {
                echo sprintf(
                    '<li><input type="text" name="group-%s[%s][]" placeholder="Nome do integrante"/></li>',
                    $slug,
                    $category
                );
            }

            echo sprintf(
                '<li>
                    <input type="button" class="btn add-member" data-name="group-%s[%s][]" value="Adicionar membro"/>
                    <a href="javascript:void(0);" class="btn btn-warning remove-member">Remover membro</a>
                </li>',
                $slug,
                $category
            );

            echo '</ul>';
        }

        echo '</li>';
    }
    echo '</ul>';
}

if ($slug === 'desafio-bruce') {
    echo '<ul>
            <li>
                <input type="text" name="desafio-bruce-arma" placeholder="Nome da arma"/>
            </li>
        </ul>';
}

if (!in_array($slug, $formas) && $slug !== 'desafio-bruce') {
    $isFirst = $slug !== 'tree';
    echo '<ul>';

    foreach ($data as $category => $option) {
        echo '<li>';
        echo sprintf(
            '<input type="radio" name="data-%s" value="%s" %s>&nbsp;%s %s',
            $slug,
            $category,
            $isFirst ? 'required' : '',
            $option,
            $slug === 'tree' ? '' : ' Kg'
        );

        $isFirst = false;

        if (in_array($slug, $rules['withWeapon'])) {
            echo sprintf(
                '<ul id="groups-%s" class="groups">
                    <li>
                        <input type="text" name="tree-arma-%s[%s]" placeholder="Nome da arma"/>
                    </li>
                </ul>',
                $category,
                $slug,
                $category
            );
        }

        echo '</li>';
    }

    echo '</ul>';
}
