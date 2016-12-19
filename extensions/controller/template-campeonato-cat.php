<?php
foreach($inscricao as $key => $info)
{
	$term = get_term_by('slug', $key, 'categoria');
	$selected = $info['peso'];
	?>
		<tr>
			<th>
				<label for="<?php echo $term->slug ?>"><?php echo $term->name; ?></label>
			</th>
			<td>
				
				<?php if ($term->slug == 'formaslivres' || $term->slug == 'formasinternas' || $term->slug == 'formastradicionais' || $term->slug == 'formasolimpicas' || $term->slug == 'tree'){

					foreach($info as $item){
						$selected[] = $item['peso'];
					}
					?>
					<select id="<?php echo $term->slug; ?>" name="categoria-<?php echo $term->slug; ?>[]" multiple>
						<?php
							switch ($term->slug){
								default:
									foreach($category[$term->slug] as $chave => $cat){
										?>
										<option value="<?php echo $chave; ?>" <?php echo (in_array($chave, $selected)) ? 'selected' : ''; ?>><?php echo $cat; ?></option>
										<?php
									}
									break;
							}
						?>
					</select>
					<?php } else { ?>
				<select id="<?php echo $term->slug; ?>" name="categoria-<?php echo $term->slug; ?>">
					<?php
						switch($term->slug)
						{
							case 'cassetete':
							case 'guardas':
							case 'semi':
							case 'kuolight':
							case 'kuoleitai':
							case 'guardas':
							case 'muaythai':
							case 'wushu':
							case 'mma':
							case 'cmma':
							    if($sexo == 'm'){
							    	foreach($category[$term->slug]['masculino'] as $chave => $cat){
										?>
											<option value="<?php echo $chave; ?>" <?php selected( $selected, $chave); ?>><?php echo $cat; ?></option>
										<?php
									}
							    } else if($sexo == 'f') {
							      foreach($category[$term->slug]['feminino'] as $chave => $cat){
										?>
											<option value="<?php echo $chave; ?>" <?php selected( $selected, $chave); ?>><?php echo $cat; ?></option>
										<?php
									}
							    }
							  break;
							case 'shuai':
							    switch($fetaria){
							      case 'mirim':
							      case 'infantil':
							      case 'junior':
							        break;
							      case 'ijuvenil':
							        if($sexo == 'm'){
							        	foreach($category[$term->slug]['infanto-juvenil']['masculino'] as $chave => $cat){
											?>
												<option value="<?php echo $chave; ?>" <?php selected( $selected, $chave); ?>><?php echo $cat; ?></option>
											<?php
										}
							        } else if($sexo == 'f') {
							        	foreach($category[$term->slug]['infanto-juvenil']['feminino'] as $chave => $cat){
											?>
												<option value="<?php echo $chave; ?>" <?php selected( $selected, $chave); ?>><?php echo $cat; ?></option>
											<?php
										}
							        }
							      break;
							      case 'juvenil':
							        if($sexo == 'm'){
							        	foreach($category[$term->slug]['juvenil']['masculino'] as $chave => $cat){
											?>
												<option value="<?php echo $chave; ?>" <?php selected( $selected, $chave); ?>><?php echo $cat; ?></option>
											<?php
										}
							        } else if($sexo == 'f') {
							        	foreach($category[$term->slug]['juvenil']['feminino'] as $chave => $cat){
											?>
												<option value="<?php echo $chave; ?>" <?php selected( $selected, $chave); ?>><?php echo $cat; ?></option>
											<?php
										}
							        }
							      break;
								  case 'senior':
							      case 'adulto':
							        if($sexo == 'm'){
							        	foreach($category[$term->slug]['adulto']['masculino'] as $chave => $cat){
											?>
												<option value="<?php echo $chave; ?>" <?php selected( $selected, $chave); ?>><?php echo $cat; ?></option>
											<?php
										}
							        } else if($sexo == 'f') {
							        	foreach($category[$term->slug]['adulto']['feminino'] as $chave => $cat){

											?>
												<option value="<?php echo $chave; ?>" <?php selected( $selected, $chave); ?>><?php echo $cat; ?></option>
											<?php
										}
							        }
							      break;
							    }
							  break;
							default:
								foreach($category[$term->slug] as $chave => $cat){
									?>
										<option value="<?php echo $chave; ?>" <?php selected( $selected, $chave); ?>><?php echo $cat; ?></option>
									<?php
								}
						}

					 ?>
				</select>
				<?php } ?>
				<input type="checkbox" name="delete[]" value="<?php echo $term->slug; ?>"/> Excluir?
			</td>
		</tr>
	<?php
}
?>
