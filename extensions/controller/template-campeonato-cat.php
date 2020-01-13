<?php

$formas = [
    'formaslivres',
    'formasinternas',
    'formastradicionais',
    'formasolimpicas',
    'tree',
];

$lutas = [
    'aged' => [
        'shuai',
    ],
    'not-aged' => [
        'cassetete',
        'guardas',
        'semi',
        'kuolight',
        'kuoleitai',
        'guardas',
        'muaythai',
        'wushu',
        'mma',
        'cmma',
        'sansou',
        'jiu-jitsu',
        'thay-boxing',
        'low-kick',
        'full-contact',
        'light-contact',
    ],
];

foreach ($inscricao as $key => $info) {
    $term = get_term_by('slug', $key, 'categoria');
    $selected = $info['peso'];

    echo sprintf('<tr><th><label for="%s">%s</label></th><td>', [$term->slug, $term->name]);

    echo sprintf('<select id="%s" name="categoria-%s[]" multiple>', [$term->slug, $term->slug]);

    if (in_array($term->slug, $formas)) {
        foreach ($info as $item) {
            $selected[] = $item['peso'];
        }

        foreach ($category[$term->slug] as $chave => $cat) {
            $isSelected = in_array($chave, $selected) ? 'selected' : '';

            echo sprintf('<option value="%s" %s>%s</option>', [$chave, $isSelected, $cat]);
        }
    }

    if (in_array($term->slug, $lutas['not-aged']) || in_array($term->slug, $lutas['aged'])) {
        if (in_array($term->slug, $lutas['not-aged'])) {
            if ($sexo === 'm') {
                foreach ($category[$term->slug]['masculino'] as $chave => $cat) {
                    $isSelected = selected($selected, $chave, false);
                    echo sprintf('<option value="%s" %s>%s</option>', [$chave, $isSelected, $cat]);
                }
            }

            if ($sexo === 'f') {
                foreach ($category[$term->slug]['feminino'] as $chave => $cat) {
                    $isSelected = selected($selected, $chave, false);
                    echo sprintf('<option value="%s" %s>%s</option>', [$chave, $isSelected, $cat]);
                }
            }
        }

        if (in_array($term->slug, $lutas['aged'])) {
            if ($sexo === 'm') {
                foreach ($category[$term->slug][$fetaria]['masculino'] as $chave => $cat) {
                    $isSelected = selected($selected, $chave, false);
                    echo sprintf('<option value="%s" %s>%s</option>', [$chave, $isSelected, $cat]);
                }
            }

            if ($sexo === 'f') {
                foreach ($category[$term->slug][$fetaria]['feminino'] as $chave => $cat) {
                    $isSelected = selected($selected, $chave, false);
                    echo sprintf('<option value="%s" %s>%s</option>', [$chave, $isSelected, $cat]);

                }
            }
        }
    }

    if (in_array($term->slug, ['desafio-bruce'])) {
        foreach ($category[$term->slug] as $chave => $cat) {
            $isSelected = selected($selected, $chave, false);

            echo sprintf('<option value="%s" %s>%s</option>', [$chave, $isSelected, $cat]);
        }
    }

    echo '</select>';
    echo sprintf('<input type="checkbox" name="delete[]" value="%s"/> Excluir?', [$term->slug]);
    echo '</td></tr>';
}
