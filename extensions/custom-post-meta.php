<?php

function evento_date()
{
    $prefix = '_vhr_';

    $cmb = new_cmb2_box(array(
        'id' => 'dates',
        'title' => __('Date'),
        'object_types' => array('eventos', 'campeonatos'),
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true,
        // 'cmb_styles'  => false
    ));

    $cmb->add_field(array(
        'name' => 'Abertura das inscrições',
        'id' => $prefix . 'abertura',
        'type' => 'text_date_timestamp',
        'date_format' => 'd/m/Y',
    ));

    $cmb->add_field(array(
        'name' => 'Fechamento das inscrições',
        'id' => $prefix . 'fechamento',
        'type' => 'text_date_timestamp',
        'date_format' => 'd/m/Y',
    ));

    $cmb->add_field(array(
        'name' => 'Dia do evento',
        'id' => $prefix . 'dia',
        'type' => 'text_date_timestamp',
        'date_format' => 'd/m/Y',
    ));

    $conta = new_cmb2_box(array(
        'id' => $prefix . 'contas',
        'title' => __('Conta de Pagamento'),
        'object_types' => array('eventos', 'campeonatos'),
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true,
    ));

    $conta->add_field(array(
        'name' => 'Beneficiario',
        'id' => $prefix . 'beneficiario',
        'type' => 'text',
    ));

    $conta->add_field(array(
        'name' => 'Banco',
        'id' => $prefix . 'banco',
        'type' => 'text',
    ));

    $conta->add_field(array(
        'name' => 'Agência',
        'id' => $prefix . 'agencia',
        'type' => 'text',
    ));

    $conta->add_field(array(
        'name' => 'Conta',
        'id' => $prefix . 'conta',
        'type' => 'text',
    ));
}

function evento_local()
{
    $prefix = '_vhr_';

    $cmb = new_cmb2_box(array(
        'id' => 'local',
        'title' => __('Local'),
        'object_types' => array('eventos', 'campeonatos'),
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true,
    ));

    $cmb->add_field(array(
        'name' => 'CEP',
        'id' => $prefix . 'cep',
        'type' => 'text_small',
    ));

    $cmb->add_field(array(
        'name' => 'Endereço',
        'id' => $prefix . 'street',
        'type' => 'text',
    ));
    $cmb->add_field(array(
        'name' => 'Número',
        'id' => $prefix . 'street_number',
        'type' => 'text_small',
    ));
    $cmb->add_field(array(
        'name' => 'Complemento',
        'id' => $prefix . 'complement',
        'type' => 'text_medium',
    ));
    $cmb->add_field(array(
        'name' => 'Bairro',
        'id' => $prefix . 'district',
        'type' => 'text_medium',
    ));
    $cmb->add_field(array(
        'name' => 'Cidade',
        'id' => $prefix . 'city',
        'type' => 'text_medium',
    ));
    $cmb->add_field(array(
        'name' => 'Estado',
        'id' => $prefix . 'state',
        'type' => 'text_medium',
    ));
}

function evento_termo()
{
    $prefix = '_vhr_';

    $cmb = new_cmb2_box(array(
        'id' => 'termo',
        'title' => 'Termo de Responsabilidade',
        'object_types' => array('eventos', 'campeonatos'),
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => false,
    ));

    $cmb->add_field(array(
        'name' => 'Termo de Responsabilidade',
        'id' => $prefix . 'termo',
        'type' => 'wysiwyg',
        'options' => array(),
    ));
}

function price()
{
    $prefix = '_vhr_';

    $cmb = new_cmb2_box(array(
        'id' => 'price',
        'title' => 'Valor da Inscrição',
        'object_types' => array('campeonatos'),
        'context' => 'side',
        'priority' => 'high',
    ));

    $cmb->add_field(array(
        'name' => 'Vai ser pago?',
        'id' => $prefix . 'price_option',
        'type' => 'radio_inline',
        'options' => array(
            's' => 'Sim',
            'n' => 'Não',
        ),
        'default' => 'n',
    ));

    $cmb->add_field(array(
        'name' => 'Valor da Inscrição',
        'id' => $prefix . 'price',
        'type' => 'text_money',
        'before' => 'R',
    ));

    $cmb->add_field(array(
        'name' => 'Valor da Inscrição Adicional',
        'id' => $prefix . 'price_extra',
        'type' => 'text_money',
        'before' => 'R',
        'show_on_cb' => 'show_extra_price',
        'default' => '000',
    ));
}

function autorizacao_file()
{
    $prefix = '_vhr_';

    $cmb = new_cmb2_box(array(
        'id' => 'autorizacao_file',
        'title' => 'Arquivo de Autorização de Menor de Idade',
        'object_types' => array('campeonatos'),
        'context' => 'side',
        'priority' => 'default',
    ));

    $cmb->add_field(array(
        'id' => $prefix . 'autorizacao_file',
        'type' => 'file',
        'desc' => 'Arquivo de autorização para menor de idade',
    ));
}

function nivel_luta()
{
    $prefix = '_vhr_';

    $cmb = new_cmb2_box(array(
        'id' => 'nivel_luta',
        'title' => 'Categoria de Níveis de Luta',
        'object_types' => array('campeonatos'),
        'context' => 'normal',
        'priority' => 'default',
    ));

    $cmb->add_field(array(
        'name' => 'Categorias',
        'id' => $prefix . 'categorias',
        'type' => 'taxonomy_multicheck',
        'taxonomy' => 'categoria',
    ));

    $cmb->add_field(array(
        'name' => 'Níveis de Luta',
        'id' => $prefix . 'nivel_luta',
        'type' => 'multicheck',
        'options' => array(
            'novato' => 'Novato (até 01 ano)',
            'iniciante' => 'Iniciante (até 2 anos)',
            'intermediario' => 'Intermediário (até 3 anos)',
            'avancado' => 'Avançado (acima de 4 anos)',
        ),
    ));

    $cmb->add_field(array(
        'name' => 'Faixa Etária',
        'id' => $prefix . 'faixa_etaria',
        'type' => 'multicheck',
        'options' => array(
            'mirim' => 'Mirim (até 5 anos e meio)',
            'infantil' => 'Infantil (de 6 anos à 8 e meio)',
            'junior' => 'Junior (de 9 anos à 11 e meio) ',
            'ijuvenil' => 'Infanto Juvenil (de 12 anos à 14 e meio) ',
            'juvenil' => 'Juvenil (de 15 anos à 17 e meio)',
            'adulto' => 'Adulto (de 18 anos à 36)',
            'senior' => 'Sênior (acima de 36 anos e meio)',
        ),
    ));
}

function pfxr_inscritos_meta()
{
    add_meta_box("user_subscribers", 'Usuários', 'pfxr_callback_meta', array('campeonatos', 'eventos'));
}
add_action('add_meta_boxes', 'pfxr_inscritos_meta');

function pfxr_callback_meta($post)
{
    $meta = get_post_meta($post->ID, 'user_subscribers', true);

    $text = '';

    $text .= '<table class="form-table">';
    $text .= '<tbody>';
    $text .= '<tr>';
    $text .= '<th scope="row">
    <label>Quantidade de Inscritos</label>
    </th>';
    $count = count($meta);
    $countText = $count > 1 ? 'usuários' : 'usuário';
    $text .= "<td>{$count} {$countText}</td>";
    $text .= '</tr>';
    $text .= '</tbody>';
    $text .= '</table>';

    echo $text;
}

/**
 * Define the metabox and field configurations.
 */
function cmb2_sample_metaboxes()
{

    // Start with an underscore to hide fields from custom fields list
    // $prefix = '_yourprefix_';

    /**
     * Initiate the metabox
     */
    $cmb = new_cmb2_box(array(
        'id' => 'group_cadastros',
        'title' => __('Categorias de inscrição', 'cmb2'),
        'object_types' => array('eventos'), // Post type
        'context' => 'side',
        'priority' => 'core',
        'show_names' => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ));

    $group_field_id = $cmb->add_field(array(
        'id' => 'category_insider_group',
        'type' => 'group',
        'options' => array(
            'group_title' => __('Categoria {#}', 'cmb2'), // since version 1.1.4, {#} gets replaced by row number
            'add_button' => __('Add Outra Categoria', 'cmb2'),
            'remove_button' => __('Remove', 'cmb2'),
            'sortable' => true, // beta
            // 'closed'     => true, // true to have the groups closed by default
        ),
    ));

    // Id's for group's fields only need to be unique for the group. Prefix is not needed.
    $cmb->add_group_field($group_field_id, array(
        'name' => 'Nome',
        'id' => 'name',
        'type' => 'text',
        // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
    ));

    $cmb->add_group_field($group_field_id, array(
        'name' => __('Description', 'cmb2'),
        'description' => __('Write a short description for this entry', 'cmb2'),
        'id' => 'description',
        'type' => 'textarea_small',
    ));

    $cmb->add_group_field($group_field_id, array(
        'name' => __('Vai ser paga?', 'cmb2'),
        // 'description' => __('Write a short description for this entry', 'cmb2'),
        'id' => 'price_option',
        'type' => 'radio_inline',
        'default' => 'n',
        'options' => array(
            's' => __('Sim', 'cmb2'),
            'n' => __('Não', 'cmb2'),
        ),
    ));

    $cmb->add_group_field($group_field_id, array(
        'name' => __('Valor', 'cmb2'),
        // 'description' => __('Write a short description for this entry', 'cmb2'),
        'id' => 'price',
        'default' => '0,00',
        'type' => 'text_money',
        'before_field' => 'R$',
    ));
}

function show_extra_price($field)
{
    return 'campeonatos' === get_post_type();
}

add_action('cmb2_admin_init', 'nivel_luta');
add_action('cmb2_admin_init', 'autorizacao_file');
add_action('cmb2_admin_init', 'price');
add_action('cmb2_admin_init', 'evento_termo');
add_action('cmb2_admin_init', 'evento_local');
add_action('cmb2_admin_init', 'evento_date');
add_action('cmb2_admin_init', 'cmb2_sample_metaboxes');
