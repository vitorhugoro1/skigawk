<?php
$user_id = $_GET['user'];
?>
<div class="wrap">
	<h2>Editar Usuário - <b><?php echo get_the_author_meta( 'display_name', $user_id ); ?></b></h2>
	<?php
		if(isset($_GET['m']) && $_GET['m'] == '1'){
		?>
			<div id="message" class="updated fade"><p><strong>Dados atualizados com sucesso</strong></p></div>
		<?php
	} else if(isset($_GET['m']) && $_GET['m'] == '0'){
		?>
			<div id="message" class="error fade"><p><strong>Erro ao atualizar os dados</strong></p></div>
		<?php
		}
    ?>
	<form action="admin-post.php" method="post" class="edit_user_form">
		<input type="hidden" name="action" value="vhr_edit_user">
		<input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
		<?php	wp_nonce_field( 'vhr_edit_user' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="nome">Nome</label>
				</th>
				<td>
					<input type="text" name="nome" id="nome" class="regular-text" value="<?php echo esc_attr(get_the_author_meta( 'display_name', $user_id )); ?>" required/>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="idade">Idade</label>
				</th>
				<td>
					<input type="text" id="idade" name="idade" value="<?php echo esc_attr(get_the_author_meta( 'birthday', $user_id )); ?>" required/>
				</td>
			</tr>
      <tr id="responsavel">
          <th scope="row">
              <label for="id_responsavel">Responsavel</label>
          </th>
          <td>
              <input type="text" id="id_responsavel" name="responsavel" class="regular-text" value="<?php echo get_the_author_meta( 'responsavel', $user_id ); ?>" />
          </td>
      </tr>
			<tr>
				<th scope="row">
					<label for="mail">Email</label>
				</th>
				<td>
					<input type="email" name="mail" id="mail" class="regular-text" value="<?php echo esc_attr(get_userdata( $user_id )->user_email); ?>"  required/>
                  <p class="description"> Ao alterar o e-mail, o usuário para acessar passa a ser o novo e-mail cadastrado.</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="phone">Telefone Fixo</label>
				</th>
				<td>
					<input type="text" id="phone" name="phone" value="<?php echo get_the_author_meta('phone', $user_id); ?>" required/>
				</td>
			</tr>
      <tr>
      	<th scope="row">
      		<label for="cellphone">Celular</label>
      	</th>
      	<td>
      		<input type="text" id="cellphone" name="cellphone" value="<?php echo get_the_author_meta('cellphone', $user_id); ?>" />
      	</td>
      </tr>
						<tr>
            	<th scope="row">
            		<label for="nacionalidade">Nacionalidade</label>
            	</th>
            	<td>
								<select name="nacionalidade" id="nacionalidade" required>
									<?php $nacionalidade = get_the_author_meta('nacionalidade', $user_id); ?>
									<option value="br" <?php selected($nacionalidade, 'br'); ?>>Brasil</option>
									<option value="py" <?php selected($nacionalidade, 'py'); ?>>Paraguai</option>
									<option value="ra" <?php selected($nacionalidade, 'ra'); ?>>Argentina</option>
								</select>
            	</td>
            </tr>
            <tr>
            	<th scope="row">
            		<label for="cep">CEP/ Zip Code</label>
            	</th>
            	<td>
            		<input type="text" id="cep" name="cep" value="<?php echo get_the_author_meta('cep', $user_id); ?>" required/>
            	</td>
            </tr>
            <tr>
            	<th scope="row">
            		<label for="address">Endereço</label>
            	</th>
            	<td>
            		<input type="text" name="address" id="address" class="regular-text" value="<?php echo get_the_author_meta('address', $user_id); ?>" />
            	</td>
            </tr>
            <tr>
            	<th scope="row">
            		<label for="endnumber">Número</label>
            	</th>
            	<td>
            		<input type="text" name="endnumber" id="endnumber" value="<?php echo get_the_author_meta('addressnumber', $user_id); ?>" />
            	</td>
            </tr>
            <tr>
            	<th scope="row">
            		<label for="endcomplement">Complemento</label>
            	</th>
            	<td>
            		<input type="text" name="endcomplement" id="endcomplement" value="<?php echo get_the_author_meta('addresscomplement', $user_id); ?>">
            	</td>
            </tr>
            <tr>
            	<th scope="row">
            		<label for="district">Bairro</label>
            	</th>
            	<td>
            		<input type="text" name="district" id="district" class="regular-text" value="<?php echo get_the_author_meta('district', $user_id); ?>" />
            	</td>
            </tr>
            <tr>
            	<th scope="row">
            		<label for="city">Cidade</label>
            	</th>
            	<td>
            		<input type="text" name="city" id="city" value="<?php echo get_the_author_meta('city', $user_id); ?>" />
            	</td>
            </tr>
            <tr>
            	<th scope="row">
            		<label for="state">Estado</label>
            	</th>
            	<td>
            		<input type="text" name="state" id="state" value="<?php echo get_the_author_meta('state', $user_id); ?>" />
            	</td>
            </tr>
						<tr>
							<th>
								<label for="avatar">Avatar</label>
							</th>
							<td>
								<?php
								 	$avatar_id = get_the_author_meta( 'avatar_id', $user_id );
									if($avatar_id){ ?>
										<img src="<?php echo wp_get_attachment_url($avatar_id); ?>" width="150px" alt="Avatar" />
									<?php
									}
								 ?>
								<input type="file" name="avatar" id="avatar"/>
							</td>
						</tr>
            <tr>
            	<th>
            		<label for="assoc">Nome da Associação</label>
            	</th>
            	<td>
                    <select name="assoc" id="assoc" required>
                        <?php
                        $assoc = get_terms('academia', array('fields'   => 'all', 'hide_empty' => false));
                        foreach($assoc as $term){
                            ?>
                            <option value="<?php echo $term->slug; ?>" <?php selected(get_the_author_meta('assoc', $user_id), $term->slug); ?>><?php echo $term->name; ?></option>
                            <?php
                        }
                        ?>
                    </select>
            	</td>
            </tr>
            <tr>
            	<th>
            		<label for="estilo">Estilo Principal</label>
            	</th>
            	<td>
            		<input type="text" name="estilo" id="estilo" class="regular-text" value="<?php echo get_the_author_meta('estilo', $user_id); ?>" required/>
            	</td>
            </tr>
            <tr>
                <th>
                    <label for="data-pratica">Data de início de treino</label>
                </th>
                <td>
                    <?php
                    $new = str_replace("/", "-", get_the_author_meta('birthday', $user_id));
                    $birthday = date('Y-m-d', strtotime($new));
                    if(get_the_author_meta('data-pratica', $user_id) !== ''){
                        $date = str_replace("/", "-", get_the_author_meta('data-pratica', $user_id));
                        $date = date('Y-m-d', strtotime($date));
                    } else {
                        $date = date('Y-m-d');
                    }
                    ?>
                    <input type="date" name="data-pratica" id="data-pratica" value="<?php echo $date; ?>" min="<?php echo $birthday; ?>" required/>
                </td>
            </tr>
            <tr>
            	<th>
            		<label for="modalidade">Modalidade</label>
            	</th>
            	<td class="modalidade">
            		<fieldset>
                    <?php
                        $list = get_terms('categoria', array('fields' => 'all', 'hide_empty' => false));
                        foreach ($list as $term) {
                            $modal = get_the_author_meta('modalidades',$user_id);
                            $checkmodal = array_key_exists($term->slug, $modal);
                            $checked = $modal[$term->slug];
                        ?>
                            <label for="<?php echo $term->slug; ?>">
                                <input type="checkbox" name="modalidade[]" id="<?php echo $term->slug; ?>" value="<?php echo $term->slug; ?>" <?php echo ($checkmodal) ? 'checked' : ''; ?>/> <?php echo $term->name; ?>
                                <div id="<?php echo $term->slug; ?>">
                                    <ul id="categoria" class="categoria">

                                      <li><input type="radio" name="data-<?php echo $term->slug;?>" value="novato" <?php checked( $checked, 'novato' ); ?>>Novato (até 01 ano)</li>

                                      <li><input type="radio" name="data-<?php echo $term->slug;?>" value="iniciante" <?php checked( $checked, 'iniciante' ); ?>>Iniciante (até 2 anos)</li>

                                      <li><input type="radio" name="data-<?php echo $term->slug;?>" value="intermediario" <?php checked( $checked, 'intermediario' ); ?>>Intermediário (até 3 anos)</li>

                                      <li><input type="radio" name="data-<?php echo $term->slug;?>" value="avancado" <?php checked( $checked, 'avancado' ); ?>>Avançado (acima de 4 anos)</li>

                                    </ul>
                                </div>
                            </label>
                            <br>
                        <?php
                        }
                    ?>
                    </fieldset>
            	</td>
            </tr>
		</table>
		<p class="submit"><?php submit_button(__('Save'), 'primary'); ?></p>
	</form>
</div>
