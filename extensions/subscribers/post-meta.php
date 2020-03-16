<?php

function subscribers_show_data()
{
    add_meta_box(
        "subscribers_show_data",
        'Informações da Inscrição',
        'subscribers_show_data_box',
        ['subscribers'],
        'normal'
    );
}

add_action('add_meta_boxes', 'subscribers_show_data');

function subscribers_show_data_box($post)
{
    include get_template_directory() . '/extensions/subscribers/template/show-data-template.php';
}

function subscribers_payment_data()
{
    add_meta_box(
        "subscribers_payment_data",
        'Informações do Pagamento',
        'subscribers_payment_data_box',
        ['subscribers']
    );
}

add_action('add_meta_boxes', 'subscribers_payment_data');

function subscribers_payment_data_box($post)
{
    include get_template_directory() . '/extensions/subscribers/template/payment-data-template.php';
}

function weight_by_author($postID, $key)
{
    $categories = get_the_terms($postID, 'categoria');

    if (empty($categories)) {
        return '';
    }

    $category = $categories[0];

    $meta = get_post_meta($postID, $key, true);
    $author = get_post_field('post_author', $postID);
    $gender = get_the_author_meta('sex', $author);
    $ageing = get_the_author_meta('fEtaria', $author);

    $isFight = !in_array($category->slug, form_style_data());

    return get_weight($category->slug, $meta, $gender, $ageing) . ($isFight ? ' <b>KG</b>' : '');
}

function category_meta($postID)
{
    $categories = get_the_terms($postID, 'categoria');

    if (empty($categories)) {
        return '';
    }

    return $categories[0]->name;
}

function post_title_meta($postID, $key)
{
    $meta = get_post_meta($postID, $key, true);

    return get_the_title($meta);
}

/**
 * @param int $postID
 * @param string $key
 * @param string $from_date
 * @param string $to_date
 *
 * @return string
 */
function intl_date_meta($postID, $key, $from_date = 'Y-m-d', $to_date = 'd/m/Y')
{
    $meta = get_post_meta($postID, $key, true);
    $date = date_create_from_format($from_date, $meta);

    return date_format($date, $to_date);
}
