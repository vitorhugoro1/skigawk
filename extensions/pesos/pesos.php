<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);

require 'Classes/PHPExcel.php';
require 'Classes/PHPExcel/IOFactory.php';

if(!file_exists($_FILES['excel'])){
  exit("Archive not found");
}

$data = array(
  'guardas' => array(
		  'feminino' => array(
		    '1'  => '00.1 - 20',
		    '2'  => '20.1 - 25',
		    '3'  => '25.1 - 30',
		    '4'  => '30.1 - 35',
		    '5'  => '35.1 - 40',
		    '6'  => '40.1 - 45',
		    '7'  => '45.1 - 50',
		    '8'  => '50.1 - 55',
		    '9'  => '55.1 - 60',
		    '10'  => '60.1 - 65',
		    '11'  => '65.1 - 70',
		    '12'  => '70.1 - 75',
		    '13'  => '75.1 - 999',
		  ),
		  'masculino' => array(
		    '1'  => '00.1 - 20',
		    '2'  => '20.1 - 25',
		    '3'  => '25.1 - 30',
		    '4'  => '30.1 - 35',
		    '5'  => '35.1 - 40',
		    '6'  => '40.1 - 45',
		    '7'  => '45.1 - 50',
		    '8'  => '50.1 - 55',
		    '9'  => '55.1 - 60',
		    '10'  => '60.1 - 65',
		    '11'  => '65.1 - 70',
		    '12'  => '70.1 - 75',
		    '13'  => '75.1 - 80',
		    '14'  => '80.1 - 85',
		    '15'  => '85.1 - 90',
		    '16'  => '90.1 - 999'
		  )
		),
'cassetete' => array(
      'masculino' => array(
        '1'   => '00.1 - 20',
        '2'   => '20.1 - 30',
        '3'   => '30.1 - 40',
        '4'   => '40.1 - 50',
        '5'   => '50.1 - 60',
        '6'   => '60.1 - 70',
        '7'   => '70.1 - 80',
        '8'   => '80.1 - 90',
        '9'   => '90.1 - 999'
      ),
      'feminino' => array(
        '1'   => '00.1 - 20',
        '2'   => '20.1 - 26',
        '3'   => '26.1 - 32',
        '4'   => '32.1 - 38',
        '5'   => '38.1 - 44',
        '6'   => '44.1 - 50',
        '7'   => '50.1 - 56',
        '8'   => '56.1 - 62',
        '9'   => '68.1 - 74',
        '10'   => '74.1 - 999'
      )
    )
);

$sexo = $_REQUEST['sexo'];
$categoria = $_REQUEST['categoria'];
$objPHPExcel = PHPExcel_IOFactory::load('Pasta1.xlsx');

foreach($objPHPExcel->getWorksheetIterator() as $worksheet){
    $worksheetTitle     = $worksheet->getTitle();
    $highestRow         = $worksheet->getHighestRow(); // e.g. 10
    $highestColumn      = $worksheet->getHighestColumn(); // e.g 'F'
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
    $nrColumns = ord($highestColumn) - 64;
    for ($row = 1; $row <= $highestRow; ++ $row) {
        for ($col = 0; $col < 1; ++ $col) {
            $cell = $worksheet->getCellByColumnAndRow($col, $row);
            $val = $cell->getValue();
            $pos = $row - 1;
            $textParent = $worksheet->getCellByColumnAndRow($col+1, $row);
            $peso = $val . ' - ' .  $textParent;

            if(isset($data[$categoria][$sexo][$pos])){
              $data[$categoria][$sexo][$pos] = $peso;
            }
        }
    }
}
// $data[$categoria][$sexo] = $relatorio[$sexo];

var_dump(json_encode( $data[$categoria] ));
// var_dump($data[$categoria]);
?>
<table>
  <?php foreach($data[$categoria][$sexo] as $key => $peso): ?>
    <tr>
      <th>
        <?php echo $key ?>
      </th>
      <td>
        <?php echo $peso ?>
      </td>
    </tr>
  <?php endforeach; ?>
</table>
