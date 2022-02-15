<?php

require_once __DIR__ . '/../extensions/controller/Classes/PHPExcel.php';

add_action('admin_post_vhr_generate_export', 'vhr_generate_export');

function user_experience_export($user)
{
    if (empty(get_the_author_meta('exp', $user->ID))) {
        return 'Não disponível';
    }

    return get_the_author_meta('exp', $user->ID);
}

function vhr_generate_export()
{
    if (!current_user_can('manage_options')) {
        header('Location: '. home_url());
    }

    $post_id = $_REQUEST['post_id'];
    $categoria = $_REQUEST['categoria'];
    $formas = form_style_data();
    $rules = form_style_rules();
    $groups = array_keys($rules['withGroup']);
    $weapons = array_merge($rules['withWeapon'], ['desafio-bruce']);
    $customTeam = array_keys($rules['withCustomTeam']);
    $users = array();

    $list_ids = get_post_meta($post_id, 'user_subscribers', true);

    $args = array(
        'include' => $list_ids,
    );

    if (!empty($categoria)) {
        $meta_value = esc_sql($categoria);
        $args['meta_query'] = array(
            array(
                'key' => 'insiders',
                'value' => $meta_value,
                'compare' => 'LIKE',
            ),
            'exp' => array(
                'key' => 'exp',
                'compare' => 'EXISTS',
            ),
        );
    }

    $user_query = new WP_User_Query($args);

    foreach ($user_query->results as $user) {
        $users[] = $user->data;
    }


    if ('campeonatos' == get_post_type($post_id)) {
        foreach ($users as $user) {
            $sex = get_the_author_meta('sex', $user->ID);
            $fetaria = get_the_author_meta('fEtaria', $user->ID);
            $exp = user_experience_export($user);

            $inscricoes = get_the_author_meta('insiders', $user->ID);
            /** @var array $items */
            $items = $inscricoes[$post_id]['categorias'][$categoria];

            $term = get_term_by('slug', $categoria, 'categoria');
            $academia = get_term_by('slug', get_the_author_meta('assoc', $user->ID), 'academia');
            $academia = (!is_wp_error($academia) && !empty($academia)) ? $academia->name : 'Não cadastrado';

            // Preenche se for uma forma sem armas
            if (in_array($categoria, $formas) && !in_array($categoria, $rules['withWeapon'])) {
                if (is_null($items)) {
                    $modalidade = 'usuario não cadastrou';
                    $equipe = $academia;

                    if (in_array($categoria, $groups)) {
                        if (isset($item[0]['groups'])) {
                            $equipe = implode(", ", array_filter($item[0]['groups']));
                        }

                        if (isset($item['groups'])) {
                            $equipe = implode(", ", array_filter($item['groups']));
                        }
                    }

                    if (in_array($categoria, $groups)) {
                        $relatorio[] = array(
                            'nome' => $user->display_name,
                            'sexo' => $sex,
                            'faixa-etaria' => $fetaria,
                            'categoria' => $term->name,
                            'modalidade' => $modalidade,
                            'equipe' => $equipe,
                            'experiencia' => $exp,
                            'academia' => $academia,
                        );
                    }

                    if (!in_array($categoria, $groups)) {
                        $relatorio[] = array(
                            'nome' => $user->display_name,
                            'sexo' => $sex,
                            'faixa-etaria' => $fetaria,
                            'categoria' => $term->name,
                            'modalidade' => $modalidade,
                            'experiencia' => $exp,
                            'academia' => $academia,
                        );
                    }
                }

                if (!is_null($items)) {
                    foreach ($items as $item) {
                        $equipe = $academia;

                        // Define a equipe
                        if (in_array($categoria, $groups)) {
                            if (isset($item[0]['groups'])) {
                                $equipe = implode(", ", array_filter($item[0]['groups']));
                            }

                            if (isset($item['groups'])) {
                                $equipe = implode(", ", array_filter($item['groups']));
                            }
                        }

                        if (!isset($item[0])) {
                            $id = (!isset($item['id'])) ? $item['peso'] : $item['id'];
                            $modalidade = (is_string($id)) ?
                                get_weight($categoria, $id, $sex, $fetaria) : 'usuario não cadastrou';
                        }

                        if (isset($item[0])) {
                            $id = (!isset($item[0]['id'])) ? $item[0]['peso'] : $item[0]['id'];

                            $modalidade = (is_string($id)) ?
                                get_weight($categoria, $id, $sex, $fetaria) : 'usuario não cadastrou';
                        }

                        if (in_array($categoria, $groups)) {
                            $relatorio[] = array(
                                'nome' => $user->display_name,
                                'sexo' => $sex,
                                'faixa-etaria' => $fetaria,
                                'categoria' => $term->name,
                                'modalidade' => $modalidade,
                                'equipe' => $equipe,
                                'experiencia' => $exp,
                                'academia' => $academia,
                            );
                        }

                        if (!in_array($categoria, $groups)) {
                            $relatorio[] = array(
                                'nome' => $user->display_name,
                                'sexo' => $sex,
                                'faixa-etaria' => $fetaria,
                                'categoria' => $term->name,
                                'modalidade' => $modalidade,
                                'experiencia' => $exp,
                                'academia' => $academia,
                            );
                        }
                    }
                }
            }

            // Preenche se tiver arma
            if (in_array($categoria, $weapons)) {
                $arma = 'vazio';

                if (!is_null($items)) {
                    if (!empty($items['arma'])) {
                        $arma = $items['arma'];
                    }

                    if (!empty($items[0]['arma'])) {
                        $arma = $items[0]['arma'];
                    }

                    if (!isset($items[0])) {
                        $id = (!isset($items['id'])) ? $items['peso'] : $items['id'];
                        $modalidade = (is_string($id)) ?
                            get_weight($categoria, $id, $sex, $fetaria) : 'usuario não cadastrou';
                    }

                    if (isset($items[0])) {
                        $id = (!isset($items[0]['id'])) ? $items[0]['peso'] : $items[0]['id'];
                        $modalidade = (is_string($id)) ?
                            get_weight($categoria, $id, $sex, $fetaria) : 'usuario não cadastrou';
                    }

                    $relatorio[] = array(
                        'nome' => $user->display_name,
                        'sexo' => $sex,
                        'faixa-etaria' => $fetaria,
                        'categoria' => $term->name,
                        'modalidade' => $modalidade,
                        'arma' => $arma,
                        'experiencia' => $exp,
                        'academia' => $academia,
                    );
                }

                if (is_null($items)) {
                    $modalidade = 'usuario não cadastrou';

                    if (!empty($items['arma'])) {
                        $arma = $items['arma'];
                    }

                    if (!empty($items[0]['arma'])) {
                        $arma = $items[0]['arma'];
                    }

                    $relatorio[] = array(
                        'nome' => $user->display_name,
                        'sexo' => $sex,
                        'faixa-etaria' => $fetaria,
                        'categoria' => $term->name,
                        'modalidade' => $modalidade,
                        'arma' => $arma,
                        'experiencia' => $exp,
                        'academia' => $academia,
                    );
                }
            }

            // Preenche se não for um dos outros casos
            if (!in_array($categoria, $formas) && !in_array($categoria, $weapons)) {
                $equipe = $academia;
                if (in_array($categoria, $customTeam)) {
                    if (isset($items[0]['groups'])) {
                        $equipe = implode(", ", array_filter($items[0]['groups']));
                    }

                    if (isset($items['groups'])) {
                        $equipe = implode(", ", array_filter($items['groups']));
                    }
                }

                if (!isset($items[0])) {
                    $id = (!isset($items['id'])) ? $items['peso'] : $items['id'];
                    $modalidade = (is_string($id)) ?
                        get_weight($categoria, $id, $sex, $fetaria) : 'usuario não cadastrou';
                }

                if (isset($items[0])) {
                    $id = (!isset($items[0]['id'])) ? $items[0]['peso'] : $items[0]['id'];
                    $modalidade = (is_string($id)) ?
                        get_weight($categoria, $id, $sex, $fetaria) : 'usuario não cadastrou';
                }

                if (in_array($categoria, $customTeam)) {
                    $relatorio[] = array(
                        'nome' => $user->display_name,
                        'sexo' => $sex,
                        'faixa-etaria' => $fetaria,
                        'categoria' => $term->name,
                        'modalidade' => $modalidade,
                        'equipe' => $equipe,
                        'experiencia' => $exp,
                        'academia' => $academia,
                    );
                }

                if (!in_array($categoria, $customTeam)) {
                    $relatorio[] = array(
                        'nome' => $user->display_name,
                        'sexo' => $sex,
                        'faixa-etaria' => $fetaria,
                        'categoria' => $term->name,
                        'modalidade' => $modalidade,
                        'experiencia' => $exp,
                        'academia' => $academia,
                    );
                }
            }
        }

        $order = array('avancado', 'intermediario', 'novato');
        $data = array_orderby(
            $relatorio,
            'nome',
            SORT_DESC,
            'modalidade',
            SORT_ASC,
            'sexo',
            SORT_DESC,
            'experiencia',
            SORT_DESC
        );
    } else {
        foreach ($users as $user) {
            $relatorio[] = array(
                'nome' => $user->display_name,
                'faixa-etaria' => get_the_author_meta('fEtaria', $user->ID),
                'categoria' => ucfirst($categoria),
            );
        }

        $data = $relatorio;
    }

    /*
 * Configurações para a classe PHPExcel
 */

    $locale = 'pt_br';
    $validLocale = PHPExcel_Settings::setLocale($locale);
    $objPHPExcel = new PHPExcel();

    // /*
    //  * Adicionando estilo ao relatorio
    //  */

    $objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

    if ('campeonatos' === get_post_type($post_id)) {
        if (in_array($categoria, $formas) && !in_array($categoria, $groups)) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Nome')
                ->setCellValue('B1', 'Sexo')
                ->setCellValue('C1', 'Faixa Etária')
                ->setCellValue('D1', 'Categoria')
                ->setCellValue('E1', 'Forma')
                ->setCellValue('F1', 'Experiência')
                ->setCellValue('G1', 'Academia');

            foreach ($data as $key => $value) {
                $line = intval($key) + 2; // Definindo a linha
                $cat = $value['categoria'];
                $gender = ($value['sexo'] === 'm') ? 'Masculino' : 'Feminino';

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $line, $value['nome']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $line, $gender);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $line, ucfirst($value['faixa-etaria']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $line, $value['categoria']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $line, $value['modalidade']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $line, $value['experiencia']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $line, $value['academia']);
            }
        }

        if (in_array($categoria, $formas) && in_array($categoria, $groups)) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Nome')
                ->setCellValue('B1', 'Sexo')
                ->setCellValue('C1', 'Faixa Etária')
                ->setCellValue('D1', 'Categoria')
                ->setCellValue('E1', 'Forma')
                ->setCellValue('F1', 'Experiência')
                ->setCellValue('G1', 'Academia');

            foreach ($data as $key => $value) {
                $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Nome')
                    ->setCellValue('B1', 'Sexo')
                    ->setCellValue('C1', 'Faixa Etária')
                    ->setCellValue('D1', 'Categoria')
                    ->setCellValue('E1', 'Forma')
                    ->setCellValue('F1', 'Equipe')
                    ->setCellValue('G1', 'Experiência')
                    ->setCellValue('H1', 'Academia');

                foreach ($data as $key => $value) {
                    $line = intval($key) + 2; // Definindo a linha
                    $cat = $value['categoria'];
                    $gender = ($value['sexo'] === 'm') ? 'Masculino' : 'Feminino';

                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $line, $value['nome']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $line, $gender);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(
                        2,
                        $line,
                        ucfirst($value['faixa-etaria'])
                    );
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $line, $value['categoria']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $line, $value['modalidade']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $line, $value['equipe']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $line, $value['experiencia']);
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $line, $value['academia']);
                }
            }
        }

        if (in_array($categoria, $weapons)) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Nome')
                ->setCellValue('B1', 'Sexo')
                ->setCellValue('C1', 'Faixa Etária')
                ->setCellValue('D1', 'Categoria')
                ->setCellValue('E1', 'Forma')
                ->setCellValue('F1', 'Arma')
                ->setCellValue('G1', 'Experiência')
                ->setCellValue('H1', 'Academia');

            foreach ($data as $key => $value) {
                $line = intval($key) + 2; // Definindo a linha
                $cat = $value['categoria'];
                $gender = ($value['sexo'] === 'm') ? 'Masculino' : 'Feminino';

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $line, $value['nome']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $line, $gender);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $line, ucfirst($value['faixa-etaria']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $line, $value['categoria']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $line, $value['modalidade']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $line, $value['arma']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $line, $value['experiencia']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $line, $value['academia']);
            }
        }

        if (!in_array($categoria, $formas) && !in_array($categoria, $weapons)) {
            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Nome')
                ->setCellValue('B1', 'Sexo')
                ->setCellValue('C1', 'Faixa Etária')
                ->setCellValue('D1', 'Categoria')
                ->setCellValue('E1', 'Peso (em KG)')
                ->setCellValue('F1', 'Experiência')
                ->setCellValue('G1', 'Academia');

            foreach ($data as $key => $value) {
                $line = intval($key) + 2; // Definindo a linha
                $cat = $value['categoria'];
                $gender = ($value['sexo'] === 'm') ? 'Masculino' : 'Feminino';

                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $line, $value['nome']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $line, $gender);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $line, ucfirst($value['faixa-etaria']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $line, $value['categoria']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $line, $value['modalidade']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $line, $value['experiencia']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $line, $value['academia']);
            }
        }
    } else {
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Nome')
            ->setCellValue('B1', 'Faixa Etária')
            ->setCellValue('C1', 'Categoria');

        foreach ($data as $key => $value) {
            $line = intval($key) + 2; // Definindo a linha
            $cat = $value['categoria'];
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $line, $value['nome']);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $line, ucfirst($value['faixa-etaria']));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $line, ucfirst($value['categoria']));
        }
    }

    $archive_name = sprintf('%s - %s.xlsx', html_entity_decode(get_the_title($post_id)), $cat);

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    ob_end_clean();
    header('Cache-Control: no-cache');
    header('Pragma: no-cache');
    header("Content-Disposition:attachment; filename={$archive_name}");
    ob_start();
    $objWriter->save('php://output');
    exit;
}
