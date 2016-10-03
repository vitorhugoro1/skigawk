<?php
require_once('Classes/PHPExcel.php');
require '../../../../../wp-blog-header.php';
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

if(!current_user_can('manage_options')){
    header('Location: '. home_url());
}

$post_id = $_REQUEST['post_id'];

$data = array();

$list_ids = get_post_meta($post_id, 'user_subscribers', true);

$args = array(
    'include'   => $list_ids,
);

if(!empty($_REQUEST['categoria'])){
    $meta_value = esc_sql($_REQUEST['categoria']);
    $args['meta_query'] = array(
        array(
                'key'     => 'insiders',
                'value'   => $meta_value,
                'compare' => 'LIKE'
            )
    );
}

$user_query = new WP_User_Query($args);

foreach($user_query->results as $user){
    $data[] = $user->data;
}

if('campeonatos' == get_post_type( $post_id )){
    foreach($data as $user){
    $sex = get_the_author_meta('sex', $user->ID);
    $fetaria = get_the_author_meta('fEtaria', $user->ID);
    $inscricoes = get_the_author_meta('insiders', $user->ID);
    $items = $inscricoes[$_REQUEST['post_id']]['categorias'][$_REQUEST['categoria']];
    $term = get_term_by('slug', $_REQUEST['categoria'], 'categoria');
    $academia = get_term_by('slug', get_the_author_meta('assoc', $user->ID), 'academia');

    if($_GET['categoria'] == 'formaslivres' || $_GET['categoria'] == 'formasinternas' || $_GET['categoria'] == 'formastradicionais' || $_GET['categoria'] == 'formasolimpicas'){
        if(empty($items) || is_null($items)){
          $modalidade = 'usuario não cadastrou';
          $relatorio[] = array(
                'nome'              => $user->display_name,
                'sexo'              => $sex,
                'faixa-etaria'      => get_the_author_meta('fEtaria', $user->ID),
                'categoria'         => $term->name,
                'modalidade'        => $modalidade,
                'experiencia'       => get_the_author_meta('exp', $user->ID),
                'academia'          => $academia->name
            );
        } else {
          foreach ($items as $key => $value) {

              if(!empty($value) || !is_null($items)){
                $modalidade = get_weight($_REQUEST['categoria'],$value['peso'],$sex, $fetaria);
              } else {
                $modalidade = 'usuario não cadastrou';
              }

              $relatorio[] = array(
                    'nome'              => $user->display_name,
                    'sexo'              => $sex,
                    'faixa-etaria'      => get_the_author_meta('fEtaria', $user->ID),
                    'categoria'         => $term->name,
                    'modalidade'        => $modalidade,
                    'experiencia'       => get_the_author_meta('exp', $user->ID),
                    'academia'          => $academia->name
                );
            }
        }

    } else {
        $modalidade = get_weight($_REQUEST['categoria'],$items['peso'],$sex, $fetaria);

        $relatorio[] = array(
            'nome'              => $user->display_name,
            'sexo'              => $sex,
            'faixa-etaria'      => get_the_author_meta('fEtaria', $user->ID),
            'categoria'         => $term->name,
            'modalidade'        => $modalidade,
            'experiencia'       => get_the_author_meta('exp', $user->ID),
            'academia'          => $academia->name
        );
    }
  }
} else {
      foreach($data as $user){
        $relatorio[] = array(
          'nome'          => $user->display_name,
          'faixa-etaria'  => get_the_author_meta('fEtaria', $user->ID),
          'categoria'     => ucfirst($_REQUEST['categoria'])
        );
      }
}
var_dump($relatorio);

$data = $relatorio;

/*
 * Configurações para a classe PHPExcel
 */

$locale = 'pt_br';
$validLocale = PHPExcel_Settings::setLocale($locale);
$objPHPExcel = new PHPExcel();

/*
 * Adicionando estilo ao relatorio
 */

$objPHPExcel->getActiveSheet()->getStyle('1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

if ('campeonatos' == get_post_type($post_id)){
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Nome')
        ->setCellValue('B1', 'Sexo')
        ->setCellValue('C1', 'Faixa Etária')
        ->setCellValue('D1', 'Categoria')
        ->setCellValue('E1', 'Peso (em KG)')
        ->setCellValue('F1', 'Experiência')
        ->setCellValue('G1', 'Academia');

    foreach($data as $key => $value)
    {
        $line = intval($key)+2; // Definindo a linha
        $cat = $value['categoria'];
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$line, $value['nome']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $line, ($value['sexo'] == 'm') ? 'Masculino' : 'Feminino');
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $line, ucfirst($value['faixa-etaria']));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $line, $value['categoria']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $line, $value['modalidade']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $line, $value['experiencia']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $line, $value['academia']);
    }
} else {
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'Nome')
        ->setCellValue('B1', 'Faixa Etária')
        ->setCellValue('C1', 'Categoria');

    foreach($data as $key => $value)
    {
        $line = intval($key)+2; // Definindo a linha
        $cat = $value['categoria'];
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $line, $value['nome']);
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $line, ucfirst($value['faixa-etaria']));
        $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $line, ucfirst($value['categoria']));
    }
}

$archive_name = sprintf('%s - %s.xlsx', get_the_title($post_id), $cat);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
ob_end_clean();
header('Cache-Control: no-cache');
header('Pragma: no-cache');
header("Content-Disposition:attachment; filename='{$archive_name}'");
ob_start();
$objWriter->save('php://output');
exit;
