<?php 
foreach($inscricao as $key => $info){
	if($key == 'id_pagamento'){ $id_pagamento = $info; }
	switch ($key) {
		case 'category':
				$current = $info;
				?>
					<tr>
						<th>
							<label for="<?php echo $key; ?>">Modalidade</label>
						</th>
						<td>
							<select id="<?php echo $key; ?>" name="<?php echo $key; ?>">
								<?php
									$niveis = get_post_meta( $post_id, 'category_insider_group', true );

					                if(! empty($niveis)){
					                    foreach( (array) $niveis as $tkey => $tentry){
					                            ?>
					                            	<option value="<?php echo sanitize_title($tentry['name']); ?>" <?php selected(sanitize_title($tentry['name']) , $current);?>><?php echo $tentry['name']; ?></option>
					                            <?php
					                    }
					                }
								?>
							</select>
						</td>
					</tr>
				<?php
			break;
		case 'status':
				$current = $info;
				?>
				<tr>
					<th>
						<label for="<?php echo $key; ?>">Status de Pagamento</label>
					</th>
					<td>
						<select id="<?php echo $key; ?>" name="<?php echo $key; ?>">
							<option value="p" <?php selected( 'p', $current); ?>>Pago</option>
							<option value="v" <?php selected( 'v', $current); ?>>À Verificar</option>
						</select>
						<p class="description">
							Pagamento nº <?php echo $id_pagamento; ?>
						</p>
					</td>
				</tr>
				<?php
			break;
	}
}

?>