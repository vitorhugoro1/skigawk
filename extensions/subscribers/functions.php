<?php

function create_subscription($tournamentID, $category)
{
    // Verifica se tem inscrições feitas para a categoria
    $subscriptions = get_posts([
        'author' => get_current_user_id(),
        'category_name' => $category,
        'meta_query' => [
            [
                'key' => 'tournament_id',
                'value' => $tournamentID,
                'compare' => '=',
            ],
        ],
    ]);

    // Se possuir
    if (!empty($subscriptions)) {
        // Mas for Formas continuar
        // Cadastrar nova inscrição
        // Mas for Lutas
        // Mensagem de erro
    }

    if (empty($subscriptions)) {
        // $title = "{$author->display_name} - {$tounament->title}";
        $title = '';

        wp_insert_post([
            'post_type' => 'subscribers',
            'post_content' => '',
            'post_title' => $title,
            'post_status' => 'publish',
            'post_author' => get_current_user_id(),
            'tax_input' => [
                'categoria' => [$category],
            ],
            'meta_input' => [
                'tournament_id' => $tournamentID,
                'status' => 'verify',
                'weight' => $category['weight'],
                'human_weight' => $category['human_weight'],
                'subscribed_at' => date('Y-m-d'),
                'payment_id' => $payment->id,
                'payment_method' => $payment->meio_pag,
                'payment_date' => date('Y-m-d'),
                'payment_value' => $payment->valor,
                'payment_old_data' => serialize($payment->cat_inscricao),
            ],
        ]);
    }
}
