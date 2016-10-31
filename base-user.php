<?php
// [ID CAMPEONATO]
'1' => array(
    'categorias'  => array( // * ['SLUG CATEGORIA'] => ['ID DO PESO']
      'mma'     => array(
          'peso'  => '11',
          'id_pagamento'  => '4523'
        ),
      'cmma'    => array(
          'peso'  => '11',
          'id_pagamento'  => '4524'
        )
    ),
    'pagamento'   => array(
      '1' => array(
          'id_pagamento'  => '4523', // *
          'data_venc'     => '10/10/2016', // * DD/MM/AAAA
          'valor'         => '60,50', // *
          'status'        => 'p' // * , 'p' => pago, 'v' => à verificar
        ),
      '2' => array(
          'id_pagamento'  => '4524', // *
          'data_venc'     => '11/10/2016', // * DD/MM/AAAA
          'valor'         => '60,50', // *
          'status'        => 'v' // * , 'p' => pago, 'v' => à verificar
        )
    ),
    'data_inscricao'  => array('05/09/2016', '06/09/2016'), // * DD/MM/AAAA
);
// [ID EVENTO]
'1' => array(
    'pagamento'   => array(
      '1' => array(
          'id_pagamento'  => '4523',
          'category'      => 'premium', // *
          'data_venc'     => '10/10/2016', // * DD/MM/AAAA
          'valor'         => '60,50', // *
          'status'        => 'p' // * , 'p' => pago, 'v' => à verificar
        ),
      '2' => array(
          'id_pagamento'  => '4524',
          'category'      => 'premium', // *
          'data_venc'     => '11/10/2016', // * DD/MM/AAAA
          'valor'         => '60,50', // *
          'status'        => 'v' // * , 'p' => pago, 'v' => à verificar
        )
    ),
    'data_inscricao'  => array('05/09/2016', '06/09/2016'), // * DD/MM/AAAA
);

?>
