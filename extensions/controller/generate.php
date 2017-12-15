<?php
require_once('Classes/PHPExcel.php');
require '../../../../../wp-blog-header.php';
error_reporting(0);
ini_set('display_errors', false);
ini_set('display_startup_errors', false);

if(!current_user_can('manage_options')){
    header('Location: '. home_url());
}

$post_id = $_REQUEST['post_id'];
$categoria = $_REQUEST['categoria'];

$users = array();

$list_ids = get_post_meta($post_id, 'user_subscribers', true);

$args = array(
    'include'   => $list_ids,
);

if(!empty($categoria)){
    $meta_value = esc_sql($categoria);
    $args['meta_query'] = array(
        array(
                'key'     => 'insiders',
                'value'   => $meta_value,
                'compare' => 'LIKE'
              ),
        'exp' => array(
            'key' => 'exp',
            'compare' => 'EXISTS'
        )
    );
}

$user_query = new WP_User_Query($args);

foreach($user_query->results as $user){
    $users[] = $user->data;
}

if('campeonatos' == get_post_type( $post_id )){
    foreach($users as $user){

      $sex = get_the_author_meta('sex', $user->ID);
      $fetaria = get_the_author_meta('fEtaria', $user->ID);
      $exp = (! empty(get_the_author_meta('exp', $user->ID))) ? get_the_author_meta('exp', $user->ID) : 'Não disponível';

      $inscricoes = get_the_author_meta('insiders', $user->ID);
      $items = $inscricoes[$post_id]['categorias'][$categoria];

      $term = get_term_by('slug', $categoria, 'categoria');
      $academia = get_term_by('slug', get_the_author_meta('assoc', $user->ID), 'academia');
      $academia = (! is_wp_error($academia) && ! empty($academia)) ? $academia->name : 'Não cadastrado';

      switch ($categoria) {
        case 'formaslivres':
        case 'formasinternas':
        case 'formastradicionais':
            if(!is_null($items)){
              foreach($items as $item){
	              if(! isset($item[0])) {
		              $id = (!isset($item['id'])) ? $item['peso'] : $item['id'];
		              $modalidade = (is_string($id)) ?
			              get_weight($categoria, $id, $sex, $fetaria) : 'usuario não cadastrou';
		              $equipe = (isset($item['groups'])) ? implode(", ", array_filter( $item['groups']) ) : $academia;
	              } else {
		              $id = (!isset($item[0]['id'])) ? $item[0]['peso'] : $item[0]['id'];

		              $modalidade = (is_string($id)) ?
			              get_weight($categoria, $id, $sex, $fetaria) : 'usuario não cadastrou';
		              $equipe = (isset($item[0]['groups'])) ? implode(", ", array_filter( $item[0]['groups']) ) : $academia;
	              }

                $relatorio[] = array(
                      'nome'              => $user->display_name,
                      'sexo'              => $sex,
                      'faixa-etaria'      => $fetaria,
                      'categoria'         => $term->name,
                      'modalidade'        => $modalidade,
                      'equipe'            => $equipe,
                      'experiencia'       => $exp,
                      'academia'          => $academia
                );
              }
            } else {
              $modalidade = 'usuario não cadastrou';
              if(! isset($item[0])){
                $equipe = (isset($item['groups'])) ? implode(", ", array_filter( $item['groups']) ) : $academia;
              } else {
                $equipe = (isset($item[0]['groups'])) ? implode(", ", array_filter( $item[0]['groups']) ) : $academia;
              }

              $relatorio[] = array(
                    'nome'              => $user->display_name,
                    'sexo'              => $sex,
                    'faixa-etaria'      => $fetaria,
                    'categoria'         => $term->name,
                    'modalidade'        => $modalidade,
                    'equipe'            => $equipe,
                    'experiencia'       => $exp,
                    'academia'          => $academia
                );
            }
          break;
        case 'formasolimpicas':
          if(!is_null($items)){
            foreach($items as $item){
              if(! isset($item[0])) {
	              $id = (!isset($item['id'])) ? $item['peso'] : $item['id'];

	              $modalidade = $modalidade = (is_string($id)) ?
		              get_weight($categoria, $item['id'], $sex, $fetaria) : 'usuario não cadastrou';
              } else {
	              $id = (!isset($item[0]['id'])) ? $item[0]['peso'] : $item[0]['id'];

	              $modalidade = (is_string($id)) ?
		              get_weight($categoria, $item[0]['peso'], $sex, $fetaria) : 'usuario não cadastrou';
              }


              $relatorio[] = array(
                    'nome'              => $user->display_name,
                    'sexo'              => $sex,
                    'faixa-etaria'      => $fetaria,
                    'categoria'         => $term->name,
                    'modalidade'        => $modalidade,
                    'experiencia'       => $exp,
                    'academia'          => $academia
              );
            }
          } else {
            $modalidade = 'usuario não cadastrou';

            $relatorio[] = array(
                  'nome'              => $user->display_name,
                  'sexo'              => $sex,
                  'faixa-etaria'      => $fetaria,
                  'categoria'         => $term->name,
                  'modalidade'        => $modalidade,
                  'experiencia'       => $exp,
                  'academia'          => $academia
              );
          }
          break;
		case 'tree':
		case 'desafio-bruce':
	      if(!is_null($items)){
	          if(! isset($items[0]) ) {
		          $id = (!isset($items['id'])) ? $items['peso'] : $items['id'];
		          $modalidade = (is_string($id)) ?
			          get_weight($categoria, $id, $sex, $fetaria) : 'usuario não cadastrou';
		          $arma = (isset($items['arma'])) ? $items['arma'] : 'vazio';

	          } else {
		          $id = (!isset($items[0]['id'])) ? $items[0]['peso'] : $items[0]['id'];
		          $modalidade = (is_string($id)) ?
			          get_weight($categoria, $id, $sex, $fetaria) : 'usuario não cadastrou';
		          $arma = (isset($items[0]['arma'])) ? $items[0]['arma'] : 'vazio';
	          }

	          $relatorio[] = array(
		          'nome'              => $user->display_name,
		          'sexo'              => $sex,
		          'faixa-etaria'      => $fetaria,
		          'categoria'         => $term->name,
		          'modalidade'        => $modalidade,
		          'arma'              => $arma,
		          'experiencia'       => $exp,
		          'academia'          => $academia
	          );
          } else {
            $modalidade = 'usuario não cadastrou';

            if(! isset($item[0])) {
              $arma = (isset($item['arma'])) ? $item['arma'] : 'vazio';
            } else {
              $arma = (isset($item[0]['arma'])) ? $item[0]['arma'] : 'vazio';
            }

            $relatorio[] = array(
                  'nome'              => $user->display_name,
                  'sexo'              => $sex,
                  'faixa-etaria'      => $fetaria,
                  'categoria'         => $term->name,
                  'modalidade'        => $modalidade,
                  'arma'              => $arma,
                  'experiencia'       => $exp,
                  'academia'          => $academia
              );
          }
          break;
        default:
          if(! isset($items[0]) ) {
            $id = (!isset($items['id'])) ? $items['peso'] : $items['id'];
            $modalidade = ((!empty($id) && ! $id !== '0') && is_string($id)) ?
	            get_weight($categoria, $id, $sex, $fetaria) : 'usuario não cadastrou';
          } else {
            $id = (!isset($items[0]['id'])) ? $items[0]['peso'] : $items[0]['id'];
            $modalidade = ((!empty($id) && ! $id !== '0') && is_string($id))
	            ? get_weight($categoria, $id, $sex, $fetaria) : 'usuario não cadastrou';
          }

          $relatorio[] = array(
              'nome'              => $user->display_name,
              'sexo'              => $sex,
              'faixa-etaria'      => $fetaria,
              'categoria'         => $term->name,
              'modalidade'        => $modalidade,
              'experiencia'       => $exp,
              'academia'          => $academia
          );
          break;
    }
  }

	$order = array('avancado', 'intermediario', 'novato');
	$data = array_orderby($relatorio, 'nome', SORT_DESC, 'modalidade', SORT_ASC, 'sexo', SORT_DESC, 'experiencia', SORT_DESC);
} else {
	foreach($users as $user){
		$relatorio[] = array(
		  'nome'          => $user->display_name,
		  'faixa-etaria'  => get_the_author_meta('fEtaria', $user->ID),
		  'categoria'     => ucfirst($categoria)
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
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

if ('campeonatos' === get_post_type($post_id)){
    switch ($categoria) {
      case 'formaslivres':
      case 'formasinternas':
      case 'formastradicionais':
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Nome')
            ->setCellValue('B1', 'Sexo')
            ->setCellValue('C1', 'Faixa Etária')
            ->setCellValue('D1', 'Categoria')
            ->setCellValue('E1', 'Forma')
            ->setCellValue('F1', 'Equipe')
            ->setCellValue('G1', 'Experiência')
            ->setCellValue('H1', 'Academia');

            foreach($data as $key => $value)
            {
                $line = intval($key)+2; // Definindo a linha
                $cat = $value['categoria'];
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$line, $value['nome']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $line, ($value['sexo'] == 'm') ? 'Masculino' : 'Feminino');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $line, ucfirst($value['faixa-etaria']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $line, $value['categoria']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $line, $value['modalidade']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $line, $value['equipe']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $line, $value['experiencia']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $line, $value['academia']);
            }
            break;
      case 'formasolimpicas':
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Nome')
            ->setCellValue('B1', 'Sexo')
            ->setCellValue('C1', 'Faixa Etária')
            ->setCellValue('D1', 'Categoria')
            ->setCellValue('E1', 'Forma')
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
            break;
		case 'tree':
		case 'desafio-bruce':
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Nome')
            ->setCellValue('B1', 'Sexo')
            ->setCellValue('C1', 'Faixa Etária')
            ->setCellValue('D1', 'Categoria')
            ->setCellValue('E1', 'Forma')
            ->setCellValue('F1', 'Arma')
            ->setCellValue('G1', 'Experiência')
            ->setCellValue('H1', 'Academia');

            foreach($data as $key => $value)
            {
                $line = intval($key)+2; // Definindo a linha
                $cat = $value['categoria'];
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$line, $value['nome']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1, $line, ($value['sexo'] == 'm') ? 'Masculino' : 'Feminino');
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $line, ucfirst($value['faixa-etaria']));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $line, $value['categoria']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4, $line, $value['modalidade']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5, $line, $value['arma']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6, $line, $value['experiencia']);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7, $line, $value['academia']);
            }
        break;
      default:
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
        break;
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
