<?php
global $wpdb;
$table = $wpdb->prefix.'payments';
$categorias = $inscricoes[$post_id]['categorias'];
$pagamentos = $inscricoes[$post_id]['pagamento'];

foreach($categorias as $key => $value){
	if($key == 'formaslivres' || $key == 'formasinternas' || $key == 'formastradicionais' || $key == 'formasolimpicas'){
		foreach ($value as $info){
			if(!is_null($info['id_pagamento']))
				$ids[] = $info['id_pagamento'];
		}
	}
	if(!is_null($value['id_pagamento']))
		$ids[] = $value['id_pagamento'];
}

$ids = array_unique($ids);

foreach($ids as $id){
	if(isset($_POST['select-'.$id])){
		foreach($pagamentos as $key => $value){
			if($id == $value['id_pagamento']){
				$inscricoes[$post_id]['pagamento'][$key]['status'] = $_POST['select-'.$id];
			}
		}
		update_user_meta($user_id, 'insiders', $inscricoes);
		$pagamentos = $inscricoes[$post_id]['pagamento'];
		$message = 'success';
	}
}

$info = array();
foreach($pagamentos as $key => $value){
	if(in_array($value['id_pagamento'], $ids)){
		$info[] = array('id' => $value['id_pagamento'], 'key' => $key, 'status' => $value['status']);
	}
}

foreach ($info as $value){
	$id = $value['id'];
	$cat = $wpdb->get_var("SELECT cat_inscricao FROM $table WHERE id = $id");
	$cat = unserialize($cat);

	$data[] = array(
		'key'	=> $value['key'],
		'id'	=> $id,
		'cat'	=> $cat,
		'status' => $value['status']
	);
}

if(isset($message) && $message == 'success'){
	?>
	<div id="message" class="updated fade"><p><strong>Dados atualizados com sucesso</strong></p></div>
	<?php
} else if(isset($message) && $message == 'error'){
	?>
	<div id="message" class="error fade"><p><strong>Erro ao atualizar os dados</strong></p></div>
	<?php
}

foreach($data as $value){
	$current = $value['status'];
	$sexo = get_the_author_meta('sex', $user_id);
	$fetaria = get_the_author_meta('fEtaria', $user_id);
	$cats = array();
	foreach($value['cat'] as $key => $item){
		if($key == 'formaslivres' || $key == 'formasinternas' || $key == 'formastradicionais' || $key == 'formasolimpicas'){
			foreach ($item as $info){
				if(!is_null($info['peso']))
					$cats[] = get_weight($key, $info['peso'], $sexo, $fetaria);
			}
		} else {
			$term = get_term_by('slug', $key, 'categoria');
			$cats[] = $term->name;
		}
	}
	?>
	<tr>
		<th>
		   <label for="<?php echo $value['id']; ?>">Pagamento Nº<?php echo $value['id']; ?></label>
		</th>
		<td>
			<select id="<?php echo $value['id']; ?>" name="select-<?php echo $value['id']; ?>">
				<option value="v" <?php selected('v', $current); ?>>Verificar</option>
				<option value="p" <?php selected('p', $current); ?>>Pago</option>
			</select>
			<p class="description">
				<?php echo implode(", ", $cats); ?>
			</p>
		</td>
	</tr>
<?php
}