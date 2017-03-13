<?php
header('Access-Control-Allow-Origin: *');
require '../../../../wp-blog-header.php';

extract($_POST);

$guardas = array(
    'feminino' => array(
        '1' => '00.1 - 20',
        '2' => '20.1 - 25',
        '3' => '25.1 - 30',
        '4' => '30.1 - 35',
        '5' => '35.1 - 40',
        '6' => '40.1 - 45',
        '7' => '45.1 - 50',
        '8' => '50.1 - 55',
        '9' => '55.1 - 60',
        '10' => '60.1 - 65',
        '11' => '65.1 - 70',
        '12' => '70.1 - 75',
        '13' => '75.1 - 999',
    ),
    'masculino' => array(
        '1' => '00.1 - 20',
        '2' => '20.1 - 25',
        '3' => '25.1 - 30',
        '4' => '30.1 - 35',
        '5' => '35.1 - 40',
        '6' => '40.1 - 45',
        '7' => '45.1 - 50',
        '8' => '50.1 - 55',
        '9' => '55.1 - 60',
        '10' => '60.1 - 65',
        '11' => '65.1 - 70',
        '12' => '70.1 - 75',
        '13' => '75.1 - 80',
        '14' => '80.1 - 85',
        '15' => '85.1 - 90',
        '16' => '90.1 - 999',
    ),
);
$cassetete = array(
    'masculino' => array(
        '1' => '00.1 - 20',
        '2' => '20.1 - 30',
        '3' => '30.1 - 40',
        '4' => '40.1 - 50',
        '5' => '50.1 - 60',
        '6' => '60.1 - 70',
        '7' => '70.1 - 80',
        '8' => '80.1 - 90',
        '9' => '90.1 - 999',
    ),
    'feminino' => array(
        '1' => '00.1 - 20',
        '2' => '20.1 - 26',
        '3' => '26.1 - 32',
        '4' => '32.1 - 38',
        '5' => '38.1 - 44',
        '6' => '44.1 - 50',
        '7' => '50.1 - 56',
        '8' => '56.1 - 62',
        '9' => '68.1 - 74',
        '10' => '74.1 - 999',
    ),
);
$semi = array(
    'feminino' => array(
        '1' => '00.1 - 20',
        '2' => '20.1 - 25',
        '3' => '25.1 - 30',
        '4' => '30.1 - 35',
        '5' => '35.1 - 40',
        '6' => '40.1 - 45',
        '7' => '45.1 - 50',
        '8' => '50.1 - 55',
        '9' => '55.1 - 60',
        '10' => '60.1 - 65',
        '11' => '65.1 - 70',
        '12' => '70.1 - 75',
        '13' => '75.1 - 999',
    ),
    'masculino' => array(
        '1' => '00.1 - 20',
        '2' => '20.1 - 27',
        '3' => '27.1 - 34',
        '4' => '34.1 - 41',
        '5' => '41.1 - 48',
        '6' => '48.1 - 55',
        '7' => '55.1 - 62',
        '8' => '62.1 - 69',
        '9' => '69.1 - 76',
        '10' => '76.1 - 83',
        '11' => '83.1 - 90',
        '12' => '90.1 - 97',
        '13' => '97.1 - 999',
    ),
);
$kuolight = array(
    'feminino' => array(
        '1' => '00.1 - 35',
        '2' => '35.1 - 40',
        '3' => '40.1 - 45',
        '4' => '45.1 - 50',
        '5' => '50.1 - 55',
        '6' => '55.1 - 60',
        '7' => '60.1 - 65',
        '8' => '65.1 - 70',
        '9' => '70.1 - 75',
        '10' => '75.1 - 80',
        '11' => '80.1 - 999',
    ),
    'masculino' => array(
        '1' => '00.1 - 34',
        '2' => '34.1 - 39',
        '3' => '39.1 - 44',
        '4' => '44.1 - 49',
        '5' => '49.1 - 54',
        '6' => '54.1 - 59',
        '7' => '59.1 - 64',
        '8' => '64.1 - 69',
        '9' => '69.1 - 74',
        '10' => '74.1 - 79',
        '11' => '79.1 - 84',
        '12' => '84.1 - 89',
        '13' => '89.1 - 94',
        '14' => '94.1 - 999',
    ),
);
$kuoleitai = array(
    'feminino' => array(
        '1' => '00.1 - 35',
        '2' => '35.1 - 40',
        '3' => '40.1 - 45',
        '4' => '45.1 - 50',
        '5' => '50.1 - 55',
        '6' => '55.1 - 60',
        '7' => '60.1 - 65',
        '8' => '65.1 - 70',
        '9' => '70.1 - 75',
        '10' => '75.1 - 80',
        '11' => '80.1 - 999',
    ),
    'masculino' => array(
        '1' => '00.1 - 35',
        '2' => '35.1 - 40',
        '3' => '40.1 - 45',
        '4' => '45.1 - 50',
        '5' => '40.1 - 55',
        '6' => '55.1 - 60',
        '7' => '60.1 - 65',
        '8' => '65.1 - 70',
        '9' => '70.1 - 75',
        '10' => '75.1 - 80',
        '11' => '80.1 - 85',
        '12' => '85.1 - 90',
        '13' => '90.1 - 95',
        '14' => '95.1 - 100',
        '15' => '100.1 - 999',
    ),
);
$wushu = array(
    'feminino' => array(
			'1' => '00.1 - 40',
			'2' => '40.1 - 45',
			'3' => '45.1 - 50',
			'4' => '50.1 - 55',
			'5' => '55.1 - 60',
			'6' => '60.1 - 65',
			'7' => '65.1 - 70',
			'8' => '70.1 - 75',
			'9' => '75.1 - 80',
			'10' => '80.1 - 85',
			'11' => '85.1 - 90',
			'12' => '90.1 - 999',
    ),
    'masculino' => array(
				'1' => '00.1 - 40',
				'2' => '40.1 - 45',
				'3' => '45.1 - 50',
        '4' => '50.1 - 55',
        '5' => '55.1 - 60',
        '6' => '60.1 - 65',
        '7' => '65.1 - 70',
        '8' => '70.1 - 75',
        '9' => '75.1 - 80',
        '10' => '80.1 - 85',
        '11' => '85.1 - 90',
        '12' => '90.1 - 999',
    ),
);
$shuai = array(
    'mirim' => array(
        'feminino' => array(
            '1' => '00.1 - 20',
            '2' => '20.1 - 24',
            '3' => '24.1 - 28',
            '4' => '28.1 - 32',
            '5' => '32.1 - 36',
            '6' => '36.1 - 40',
            '7' => '40.1 - 44',
            '8' => '44.1 - 48',
        ),
        'masculino' => array(
            '1' => '00.1 - 20',
            '2' => '20.1 - 24',
            '3' => '24.1 - 28',
            '4' => '28.1 - 32',
            '5' => '32.1 - 36',
            '6' => '36.1 - 40',
            '7' => '40.1 - 44',
            '8' => '44.1 - 48',
        ),
    ),
    'infantil' => array(
        'feminino' => array(
            '1' => '00.1 - 20',
            '2' => '20.1 - 24',
            '3' => '24.1 - 28',
            '4' => '28.1 - 32',
            '5' => '32.1 - 36',
            '6' => '36.1 - 40',
            '7' => '40.1 - 44',
            '8' => '44.1 - 48',
        ),
        'masculino' => array(
            '1' => '00.1 - 20',
            '2' => '20.1 - 24',
            '3' => '24.1 - 28',
            '4' => '28.1 - 32',
            '5' => '32.1 - 36',
            '6' => '36.1 - 40',
            '7' => '40.1 - 44',
            '8' => '44.1 - 48',
        ),
    ),
    'junior' => array(
        'feminino' => array(
            '1' => '00.1 - 20',
            '2' => '20.1 - 23',
            '3' => '23.1 - 26',
            '4' => '26.1 - 29',
            '5' => '29.1 - 32',
            '6' => '32.1 - 35',
            '7' => '35.1 - 38',
            '8' => '38.1 - 41',
            '9' => '41.1 - 999',
        ),
        'masculino' => array(
            '1' => '00.1 - 20',
            '2' => '20.1 - 23',
            '3' => '23.1 - 26',
            '4' => '26.1 - 29',
            '5' => '29.1 - 32',
            '6' => '32.1 - 35',
            '7' => '35.1 - 38',
            '8' => '38.1 - 41',
            '9' => '41.1 - 999',
        ),
    ),
    'infanto-juvenil' => array(
        'feminino' => array(
            '1' => '00.1 - 20',
            '2' => '20.1 - 23',
            '3' => '23.1 - 26',
            '4' => '26.1 - 29',
            '5' => '29.1 - 32',
            '6' => '32.1 - 35',
            '7' => '35.1 - 38',
            '8' => '38.1 - 41',
            '9' => '41.1 - 999',
        ),
        'masculino' => array(
            '1' => '00.1 - 20',
            '2' => '20.1 - 23',
            '3' => '23.1 - 26',
            '4' => '26.1 - 29',
            '5' => '29.1 - 32',
            '6' => '32.1 - 35',
            '7' => '35.1 - 38',
            '8' => '38.1 - 41',
            '9' => '41.1 - 999',
        ),
    ),
    'juvenil' => array(
        'feminino' => array(
            '1' => '00.1 - 44',
            '2' => '44.1 - 47',
            '3' => '47.1 - 50',
            '4' => '50.1 - 54',
            '5' => '54.1 - 58',
            '6' => '58.1 - 62',
            '7' => '62.1 - 66',
            '8' => '66.1 - 70',
            '9' => '70.1 - 74',
            '10' => '74.1 - 82',
            '11' => '82.1 - 999',
        ),
        'masculino' => array(
            '1' => '00.1 - 46',
            '2' => '46.1 - 49',
            '3' => '49.1 - 52',
            '4' => '52.1 - 56',
            '5' => '56.1 - 61',
            '6' => '61.1 - 66',
            '7' => '66.1 - 72',
            '8' => '72.1 - 80',
            '9' => '80.1 - 90',
            '10' => '90.1 - 999',
        ),
    ),
    'adulto' => array(
        'feminino' => array(
            '1' => '00.1 - 44',
            '2' => '44.1 - 47',
            '3' => '47.1 - 50',
            '4' => '50.1 - 54',
            '5' => '54.1 - 58',
            '6' => '58.1 - 62',
            '7' => '62.1 - 66',
            '8' => '66.1 - 70',
            '9' => '70.1 - 74',
            '10' => '74.1 - 82',
            '11' => '82.1 - 999',
        ),
        'masculino' => array(
            '1' => '00.1 - 48',
            '2' => '48.1 - 52',
            '3' => '52.1 - 57',
            '4' => '57.1 - 62',
            '5' => '62.1 - 68',
            '6' => '68.1 - 74',
            '7' => '74.1 - 82',
            '8' => '82.1 - 90',
            '9' => '90.1 - 100',
            '10' => '100.1 - 999',
        ),
    ),
);
$muaythai = array(
    'feminino' => array(
        '1' => '00.1 - 55',
        '2' => '55.1 - 59',
        '3' => '59.1 - 63',
        '4' => '63.1 - 67',
        '5' => '67.1 - 71',
        '6' => '71.1 - 75',
        '7' => '75.1 - 79',
        '8' => '79.1 - 83',
        '9' => '83.1 - 87',
        '10' => '87.1 - 91',
        '11' => '91.1 - 95',
        '12' => '95.1 - 999',
    ),
    'masculino' => array(
        '1' => '00.1 - 55',
        '2' => '55.1 - 59',
        '3' => '59.1 - 63',
        '4' => '63.1 - 67',
        '5' => '67.1 - 71',
        '6' => '71.1 - 75',
        '7' => '75.1 - 79',
        '8' => '79.1 - 83',
        '9' => '83.1 - 87',
        '10' => '87.1 - 91',
        '11' => '91.1 - 95',
        '12' => '95.1 - 999',
    ),
);
$mma = array(
    'feminino' => array(
        '1' => '00.1 - 55',
        '2' => '55.1 - 59',
        '3' => '59.1 - 63',
        '4' => '63.1 - 67',
        '5' => '67.1 - 71',
        '6' => '71.1 - 75',
        '7' => '75.1 - 79',
        '8' => '79.1 - 83',
        '9' => '83.1 - 87',
        '10' => '87.1 - 91',
        '11' => '91.1 - 95',
        '12' => '95.1 - 999',
    ),
    'masculino' => array(
        '1' => '00.1 - 55',
        '2' => '55.1 - 59',
        '3' => '59.1 - 63',
        '4' => '63.1 - 67',
        '5' => '67.1 - 71',
        '6' => '71.1 - 75',
        '7' => '75.1 - 79',
        '8' => '79.1 - 83',
        '9' => '83.1 - 87',
        '10' => '87.1 - 91',
        '11' => '91.1 - 95',
        '12' => '95.1 - 999',
    ),
);
$cmma = array(
    'feminino' => array(
        '1' => '00.1 - 55',
        '2' => '55.1 - 59',
        '3' => '59.1 - 63',
        '4' => '63.1 - 67',
        '5' => '67.1 - 71',
        '6' => '71.1 - 75',
        '7' => '75.1 - 79',
        '8' => '79.1 - 83',
        '9' => '83.1 - 87',
        '10' => '87.1 - 91',
        '11' => '91.1 - 95',
        '12' => '95.1 - 999',
    ),
    'masculino' => array(
        '1' => '00.1 - 55',
        '2' => '55.1 - 59',
        '3' => '59.1 - 63',
        '4' => '63.1 - 67',
        '5' => '67.1 - 71',
        '6' => '71.1 - 75',
        '7' => '75.1 - 79',
        '8' => '79.1 - 83',
        '9' => '83.1 - 87',
        '10' => '87.1 - 91',
        '11' => '91.1 - 95',
        '12' => '95.1 - 999',
    ),
);
$formaslivres = array(
    '1' => 'Não-tradicional Arma Articulada',
    '2' => 'Não-tradicional Arma Bastão',
    '3' => 'Não-tradicional Arma Espada',
    '4' => 'Não-tradicional Arma Especial',
    '5' => 'Não-tradicional Arma Facão',
    '6' => 'Não-tradicional Arma Lança',
    '7' => 'Não-tradicional Outras Armas',
    '8' => 'Não-tradicional Forma sincronizada armas',
    '9' => 'Não-tradicional Forma sincronizada mãos',
    '10' => 'Não-tradicional Mãos Norte',
    '11' => 'Não-tradicional Mãos Sul',
    '12' => 'Não-tradicional Toi Tcha de armas',
    '13' => 'Não-tradicional Toi Tcha de mãos',
);
$formasinternas = array(
    '1' => 'Tai Chi Chuan 16 movimentos',
    '2' => 'Tai Chi Chuan 24 movimentos',
    '3' => 'Tai Chi Chuan 8 movimentos',
    '4' => 'Tai Chi Chuan Estilo Chen',
    '5' => 'Tai Chi Chuan Estilo Outros',
    '6' => 'Tai Chi Chuan Estilo Yang',
    '7' => 'Tai Chi Chuan Forma conjunto armas',
    '8' => 'Tai Chi Chuan Forma conjunto mãos',
    '9' => 'Tai Chi Chuan Outras armas',
    '10' => 'Tai Chi Chuan Tai Ji Jian 32',
    '11' => 'Tai Chi Chuan Tai Ji Jian 42 olimpico',
    '12' => 'Tai Chi Chuan Forma 42 Espada olímpica',
);
$formastradicionais = array(
    '1' => 'Tradicional Arma Articulada',
    '2' => 'Tradicional Arma Bastão',
    '3' => 'Tradicional Arma Espada',
    '4' => 'Tradicional Arma Especial',
    '5' => 'Tradicional Arma Facão',
    '6' => 'Tradicional Arma Lança',
    '7' => 'Tradicional Forma sincronizada armas',
    '8' => 'Tradicional Forma sincronizada mãos',
    '9' => 'Tradicional Mãos Choy Lay Fut',
    '10' => 'Tradicional Mãos Fei Hok Phai',
    '11' => 'Tradicional Mãos Fu Xin Chuan',
    '12' => 'Tradicional Mãos Garra de Águia',
    '13' => 'Tradicional Mãos Kon-Li-Kuen',
    '14' => 'Tradicional Mãos Louva-a-Deus',
    '15' => 'Tradicional Mãos Norte',
    '16' => 'Tradicional Mãos Pam Pou Kiu',
    '17' => 'Tradicional Mãos Sul',
    '18' => 'Tradicional Mãos Tam Tuei',
    '19' => 'Tradicional Mãos Tchon-I-Tchen',
    '20' => 'Tradicional Toi Tcha de armas',
    '21' => 'Tradicional Toi Tcha de mãos',
);
$formasolimpicas = array(
    '1' => 'NAN QUAN PUNHO DO SUL',
    '2' => 'CHANG QUAN PUNHO DO NORTE',
    '3' => 'DAO SHU FACÃO DO NORTE',
    '4' => 'NAN DAO FACÃO DO SUL',
    '5' => 'BASTÃO DO SUL',
    '6' => 'BASTÃO DO NORTE',
    '7' => 'ESPADA',
    '8' => 'ESPADA DE TAI CHI FORMA 42',
    '9' => 'LANÇA',
    '10' => 'TAI CHI FORMA 42 MÃOS',
);
$tree = array(
    '1' => 'Arma Longa',
    '2' => 'Arma Média',
    '3' => 'Mãos Livres',
);
$submission = array(
  'feminino' => array(
      '1' => '00.1 - 20',
      '2' => '20.1 - 25',
      '3' => '25.1 - 30',
      '4' => '30.1 - 35',
      '5' => '35.1 - 40',
      '6' => '40.1 - 45',
      '7' => '45.1 - 50',
      '8' => '50.1 - 55',
      '9' => '55.1 - 60',
      '10' => '60.1 - 65',
      '11' => '65.1 - 70',
      '12' => '70.1 - 75',
      '13' => '75.1 - 999',
  ),
  'masculino' => array(
      '1' => '00.1 - 20',
      '2' => '20.1 - 25',
      '3' => '25.1 - 30',
      '4' => '30.1 - 35',
      '5' => '35.1 - 40',
      '6' => '40.1 - 45',
      '7' => '45.1 - 50',
      '8' => '50.1 - 55',
      '9' => '55.1 - 60',
      '10' => '60.1 - 65',
      '11' => '65.1 - 70',
      '12' => '70.1 - 75',
      '13' => '75.1 - 80',
      '14' => '80.1 - 85',
      '15' => '85.1 - 90',
      '16' => '90.1 - 999',
  ),
);

switch ($slug) {

case 'cassetete':

    if ($sexo == 'm') {
        $data = $cassetete['masculino'];
    } elseif ($sexo == 'f') {
          $data = $cassetete['feminino'];
      }

    $array = $data;

    break;

case 'guardas':

    if ($sexo == 'm') {
        $data = $guardas['masculino'];
    } elseif ($sexo == 'f') {
          $data = $guardas['feminino'];
      }

    $array = $data;

    break;

case 'semi':

    if ($sexo == 'm') {
        $data = $semi['masculino'];
    } elseif ($sexo == 'f') {
          $data = $semi['feminino'];
      }

    $array = $data;

    break;

case 'kuolight':

    if ($sexo == 'm') {
        $data = $kuolight['masculino'];
    } elseif ($sexo == 'f') {
          $data = $kuolight['feminino'];
      }

    $array = $data;

    break;

case 'kuoleitai':

    if ($sexo == 'm') {
        $data = $kuoleitai['masculino'];
    } elseif ($sexo == 'f') {
          $data = $kuoleitai['feminino'];
      }

    $array = $data;

    break;

case 'guardas':

    if ($sexo == 'm') {
        $data = $guardas['masculino'];
    } elseif ($sexo == 'f') {
          $data = $guardas['feminino'];
      }

    $array = $data;

    break;

case 'wushu':
case 'sanda':

    if ($sexo == 'm') {
        $data = $wushu['masculino'];
    } elseif ($sexo == 'f') {
          $data = $wushu['feminino'];
      }

    $array = $data;

    break;

    case 'muaythai-a':
    case 'muaythai-p':

        if ($sexo == 'm') {
            $data = $muaythai['masculino'];
        } elseif ($sexo == 'f') {
            $data = $muaythai['feminino'];
        }
        $array = $data;
        break;

case 'shuai':

    switch ($fetaria) {
    case 'mirim':
    case 'infantil':
    case 'junior':
    case 'ijuvenil':

        if ($sexo == 'm') {
            $data = $shuai['infanto-juvenil']['masculino'];
        } elseif ($sexo == 'f') {
              $data = $shuai['infanto-juvenil']['feminino'];
          }
        break;
    case 'juvenil':

        if ($sexo == 'm') {
            $data = $shuai['juvenil']['masculino'];
        } elseif ($sexo == 'f') {
              $data = $shuai['juvenil']['feminino'];
          }

        break;

    case 'adulto':
    case 'senior':
      if ($sexo == 'm') {
          $data = $shuai['adulto']['masculino'];
      } elseif ($sexo == 'f') {
            $data = $shuai['adulto']['feminino'];
        }
      }
    $array = $data;
    break;
case 'mma':
    if ($sexo == 'm') {
        $data = $mma['masculino'];
    } elseif ($sexo == 'f') {
          $data = $mma['feminino'];
      }
    $array = $data;
    break;
case 'cmma':
    if ($sexo == 'm') {
        $data = $cmma['masculino'];
    } elseif ($sexo == 'f') {
          $data = $cmma['feminino'];
      }
    $array = $data;
    break;
case 'formaslivres':
    $array = $formaslivres;
    break;
case 'formasinternas':
    $array = $formasinternas;
    break;
case 'formastradicionais':
    $array = $formastradicionais;
    break;
case 'formasolimpicas':
    $array = $formasolimpicas;
    break;
case 'tree':
		$array = $tree;
		break;
case 'submission-infantil':
case 'submission-adulto':
    if ($sexo == 'm') {
        $data = $submission['masculino'];
    } elseif ($sexo == 'f') {
          $data = $submission['feminino'];
    }
    $array = $data;
    break;
}

if (empty($array)) {
    echo '<ul>';

    echo '<li>sem dados</li>';

    echo '</ul>';

    return;
}

$in = get_the_author_meta('insiders', $user_id);
foreach ($in[$post_id]['categorias'][$slug] as $item) {
    $peso[] = $item['peso'];
}

if ($slug == 'formaslivres' || $slug == 'formasinternas' || $slug == 'formastradicionais' || $slug == 'formasolimpicas' || $slug == 'tree') {
    echo '<ul>';
    foreach ($array as $dado => $value) {
        if (!in_array($dado, $peso)) {
            $group_tradicional = array(7, 8, 20, 21);
            $group_interno = array(7, 8);
            $group_livre = array(8, 9, 12, 13);
            if (
            in_array($dado, $group_tradicional) && $slug == 'formastradicionais' ||
            in_array($dado, $group_interno) && $slug == 'formasinternas' ||
            in_array($dado, $group_livre) && $slug == 'formaslivres') {
                echo '<li>';
                echo '<input type="checkbox" name="data-'.$slug.'[]" value="'.$dado.'">&nbsp;'.$value; ?>
          					<ul id="group-<?php echo $dado; ?>" class="groups">
          						<li>
          							<input type="text" name="group-<?php echo $slug.'['.$dado.'][]'; ?>" placeholder="Nome do integrante"/>
          						</li>
          						<li>
          							<input type="text" name="group-<?php echo $slug.'['.$dado.'][]'; ?>" placeholder="Nome do integrante"/>
          						</li>
          						<li>
          							<input type="text" name="group-<?php echo $slug.'['.$dado.'][]'; ?>" placeholder="Nome do integrante"/>
          						</li>
          						<li>
          							<input type="text" name="group-<?php echo $slug.'['.$dado.'][]'; ?>" placeholder="Nome do integrante"/>
          						</li>
          						<li>
          							<input type="text" name="group-<?php echo $slug.'['.$dado.'][]'; ?>" placeholder="Nome do integrante"/>
          						</li>
          						<li>
          							<input type="button" class="btn add-member" data-name="group-<?php echo $slug.'['.$dado.'][]'; ?>" value="Adicionar membro"/>
          							<a href="javascript:void(0);" class="btn btn-warning remove-member">Remover membro</a>
          						</li>
          					</ul>
          				<?php
                echo '</li>';
            } else if($slug == 'tree') {
              echo '<li>';
              echo '<input type="checkbox" name="data-'.$slug.'[]" value="'.$dado.'">&nbsp;'.$value; ?>
                      <ul id="groups-<?php echo $dado; ?>" class="groups">
                        <li>
                          <input type="text" name="tree-arma-<?php echo $slug.'['.$dado.']'; ?>" placeholder="Nome da arma"/>
                        </li>
                      </ul>
                    <?php
              echo '</li>';
            } else {
                echo '<li>';
                echo '<input type="checkbox" name="data-'.$slug.'[]" value="'.$dado.'">&nbsp;'.$value;
                echo '</li>';
            }
        }
    }
    echo '</ul>';
} else {
    echo '<ul>';
    $c = 0;
    foreach ($array as $dado => $value) {
        ++$c;
        echo '<li>';
        echo '<input type="radio" name="data-'.$slug.'" value="'.$dado.'"'.(($c == 1) ? 'required' : '').'>&nbsp;'.$value.' Kg';
        echo '</li>';
    }
    echo '</ul>';
}
