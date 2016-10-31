<?php
$nonce = wp_create_nonce('edit_user');
$user_id = esc_attr($_GET['user']);

$birth = date_create_from_format('d/m/Y', get_the_author_meta('birthday', $user_id));
$birth = get_etaria_user($birth);
update_user_meta($user_id, 'fEtaria', $birth);


if(!wp_verify_nonce( $_POST['_wpnonce'], 'edit_user'))
{
	if(isset($_POST['_wpnonce']))
	{
		$message = 'error';
	}
}
else
{
		$args = array(
		    'ID'         => $user_id,
		    'user_email' => esc_attr( $_POST['mail'] ),
		    'user_login' => esc_attr( $_POST['mail'] ),
        'display_name' => esc_attr($_POST['nome'])
		);
		$error = wp_update_user( $args );

        update_user_meta($user_id, 'birthday', $_POST['idade']);

        $birth = date_create_from_format('d/m/Y', get_the_author_meta('birthday', $user_id));
		$birth = get_etaria_user($birth);
		update_user_meta($user_id, 'fEtaria', $birth);
		if($birth !== 'adulto' || $birth !== 'senior'){
			update_user_meta($user_id, 'responsavel', $_POST['responsavel']);
        }
		update_user_meta($user_id, 'phone', $_POST['phone']);
		update_user_meta($user_id, 'cellphone', $_POST['cellphone']);
		update_user_meta( $user_id, 'nacionalidade', $_POST['nacionalidade']);
		update_user_meta($user_id, 'cep', $_POST['cep']);
		update_user_meta($user_id, 'address', $_POST['address']);
		update_user_meta($user_id, 'addressnumber', $_POST['endnumber']);
		update_user_meta($user_id, 'addresscomplement', $_POST['endcomplement']);
		update_user_meta($user_id, 'district', $_POST['district']);
		update_user_meta($user_id, 'city', $_POST['city']);
		update_user_meta($user_id, 'state', $_POST['state']);
    update_user_meta($user_id, 'assoc', $_POST['assoc']);
    update_user_meta($user_id, 'estilo', $_POST['estilo']);
    update_user_meta($user_id, 'data-pratica', date('d/m/Y', strtotime($_POST['data-pratica'])));
    update_user_meta($user_id, 'exp', get_exp_user($_POST['data-pratica']));
    $modalidades = $_POST['modalidade'];
    foreach ($modalidades as $cat) {
        $periodo[$cat] = $_POST['data-'.$cat];
    }
    update_user_meta($user_id, 'modalidades', $periodo);

		$uploadedfile = $_FILES['avatar'];

		if($uploadedfile['error'] == 0 ){
			$upload_overrides = array( 'test_form' => false );
			$movefile = wp_handle_upload( $uploadedfile, $upload_overrides );
			if ( $movefile && ! isset( $movefile['error'] ) ) {
					// echo "File is valid, and was successfully uploaded.\n";
					// $filename should be the path to a file in the upload directory.
					$filename = $movefile['file'];

					// Check the type of file. We'll use this as the 'post_mime_type'.
					$filetype = $movefile['type'];

					// Get the path to the upload directory.
					$wp_upload_dir = wp_upload_dir();

					// Prepare an array of post data for the attachment.
					$attachment = array(
						'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
						'post_mime_type' => $filetype,
						'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
						'post_content'   => '',
						'post_status'    => 'inherit'
					);

					// Insert the attachment.
					$attach_id = wp_insert_attachment( $attachment, $filename, $parent_post_id );

					update_user_meta( $user->ID, 'avatar_id', $attach_id );
				}
		}

		if(is_wp_error( $error )){
			$message = 'error';
		} else {
			$message = 'success';
		}

}

?>
<style type="text/css">
    ul#categoria {
      margin: 5px 0 0 0;
    }
    ul#categoria li {
      display:inline-block;
      margin: 0 10px 0 0;
    }
    ul#categoria li > input {
      margin: 2px;
    }
    ul.modalidade {
      margin: 0 0 10px 0px;
    }
    ul.modalidade li {
      list-style: none;
      /*display: inline-block;*/
      margin: 0 10px 0 0;
    }
    ul.modalidade li > input {
      margin: 2px;
    }
    .margin-10 {
      margin:0 0 0 10px;
    }
</style>
<div class="wrap">
	<h2>Editar Usuário - <b><?php echo get_the_author_meta( 'display_name', $_REQUEST['user'] ); ?></b></h2>
	<?php
		// $message = 'error';
		if(isset($message) && $message == 'success'){
		?>
			<div id="message" class="updated fade"><p><strong>Dados atualizados com sucesso</strong></p></div>
		<?php
		} else if(isset($message) && $message == 'error'){
		?>
			<div id="message" class="error fade"><p><strong>Erro ao atualizar os dados</strong></p></div>
		<?php
		}
    ?>
	<form method="post">
		<input type="hidden" name="_wpnonce" value="<?php echo $nonce; ?>" />
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="nome">Nome <!-- <?php echo get_the_author_meta('fEtaria', $user_id);?> --></label>
				</th>
				<td>
					<input type="text" name="nome" id="nome" class="regular-text" value="<?php echo esc_attr(get_the_author_meta( 'display_name', $user_id )); ?>" />
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="idade">Idade</label>
				</th>
				<td>
					<input type="text" id="idade" name="idade" value="<?php echo esc_attr(get_the_author_meta( 'birthday', $user_id )); ?>" />
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
					<input type="email" name="mail" id="mail" class="regular-text" value="<?php echo esc_attr(get_userdata( $user_id )->user_email); ?>" />
                    <p class="description"> Ao alterar o e-mail, o usuário para acessar passa a ser o novo e-mail cadastrado.</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<label for="phone">Telefone Fixo</label>
				</th>
				<td>
					<input type="text" id="phone" name="phone" value="<?php echo get_the_author_meta('phone', $user_id); ?>" />
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
								<select name="nacionalidade" id="nacionalidade">
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
            		<input type="text" id="cep" name="cep" value="<?php echo get_the_author_meta('cep', $user_id); ?>" />
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
                    <select name="assoc" id="assoc">
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
            		<input type="text" name="estilo" id="estilo" class="regular-text" value="<?php echo get_the_author_meta('estilo', $user_id); ?>" />
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
                    <input type="date" name="data-pratica" id="data-pratica" value="<?php echo $date; ?>" min="<?php echo $birthday; ?>"/>
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
	<script type="text/javascript">
        function cep(){
					var cep_code = jQuery(this).val();

					if (jQuery("#nacionalidade").val() != 'br') {
						jQuery("input#cep").prop('readonly', false);
						jQuery("input#state").prop('readonly', false);
						jQuery("input#city").prop('readonly', false);
						jQuery("input#district").prop('readonly', false);
						jQuery("input#address").prop('readonly', false);
					} else {
						jQuery.get("http://apps.widenet.com.br/busca-cep/api/cep.json", {
							code: cep_code
						},
						function(result) {
							if (result.status != 1) {
								alert(result.message || "Houve um erro desconhecido");
								jQuery("input#cep").val("");
								return;
							}
							jQuery("input#cep").val(result.code);
							jQuery("input#state").val(result.state);
							jQuery("input#city").val(result.city);
							jQuery("input#district").val(result.district);
							jQuery("input#address").val(result.address);
						});
					}
        }

        jQuery(document).ready(function($){
            jQuery("div ul.categoria").each(function(){
                var check = jQuery(this).parent().parent().children('input').prop('checked');
                if(check == true){
                } else {
                  jQuery(this).hide();
                }
            });
            jQuery("#idade").mask("00/00/0000",{placeholder: "00/00/0000"});
            data = jQuery("#idade").val();
            fieldResp(data);
            // jQuery("#cep").mask("00000-000", {placeholder: "00000-000"});
            jQuery("#phone").mask("(00) 0000-0000", {placeholder: "(00) 0000-0000"});
            jQuery("#cellphone").mask("(00) 00000-0000", {placeholder: "(00) 00000-0000"});
            jQuery("#address").val(jQuery("#Aaddress").val());
            jQuery("#district").val(jQuery("#Adistrict").val());
            jQuery("#city").val(jQuery("#Acity").val());
            jQuery("#state").val(jQuery("#Astate").val());
            cep();

						$("#nacionalidade").on('change', function() {
					    if ($(this).val() != 'br') {
					      $("input#cep").prop('readonly', false);
					      $("input#state").prop('readonly', false);
					      $("input#city").prop('readonly', false);
					      $("input#district").prop('readonly', false);
					      $("input#address").prop('readonly', false);
					    } else {
					      $("input#state").prop('readonly', true);
					      $("input#city").prop('readonly', true);
					      $("input#district").prop('readonly', true);
					      $("input#address").prop('readonly', true);
					      $("#cep").mask("00000-000", {
					        placeholder: "00000-000"
					      });
					    }
					  });

            jQuery("td.modalidade input[type=checkbox]").change(function(){
              var check = jQuery(this).prop('checked');
              if(check == true){
                var elem = jQuery(this).parent().children('div').children('.categoria');
                elem.show();
              } else {
                var elem = jQuery(this).parent().children('div').children('.categoria');
                elem.hide();
              }
            });
        });
        jQuery("#cep").change(function(){
					var cep_code = jQuery(this).val();

					if (jQuery("#nacionalidade").val() != 'br') {
						jQuery("input#cep").prop('readonly', false);
						jQuery("input#state").prop('readonly', false);
						jQuery("input#city").prop('readonly', false);
						jQuery("input#district").prop('readonly', false);
						jQuery("input#address").prop('readonly', false);
					} else {
						jQuery.get("http://apps.widenet.com.br/busca-cep/api/cep.json", {
							code: cep_code
						},
						function(result) {
							if (result.status != 1) {
								alert(result.message || "Houve um erro desconhecido");
								jQuery("input#cep").val("");
								return;
							}
							jQuery("input#cep").val(result.code);
							jQuery("input#state").val(result.state);
							jQuery("input#city").val(result.city);
							jQuery("input#district").val(result.district);
							jQuery("input#address").val(result.address);
						});
					}
        });
        function idade(ano_aniversario, mes_aniversario, dia_aniversario) {
            var d = new Date,
                ano_atual = d.getFullYear(),
                mes_atual = d.getMonth() + 1,
                dia_atual = d.getDate(),

                ano_aniversario = +ano_aniversario,
                mes_aniversario = +mes_aniversario,
                dia_aniversario = +dia_aniversario,

                quantos_anos = ano_atual - ano_aniversario;

            if (mes_atual < mes_aniversario || mes_atual == mes_aniversario && dia_atual < dia_aniversario) {
                quantos_anos--;
            }

            return quantos_anos < 0 ? 0 : quantos_anos;
        }

        function fieldResp(data){

            var dataS = data.split("/");
            var userIdade = idade(dataS[2],dataS[1],dataS[0]);
            if(userIdade < 18){
                jQuery("#responsavel").show();
                jQuery("#responsavel input").prop("disabled", false);
                jQuery("#responsavel input").prop("required", true);
            } else {
                jQuery("#responsavel").hide();
                jQuery("#responsavel input").prop("disabled", true);
                jQuery("#responsavel input").prop("required", false);
            }
        }

        jQuery("#idade").change(fieldResp(jQuery(this).val()));

        jQuery("#confirm_password").change(function(){
            var pass = jQuery("#id_password").val();
            var confirm = jQuery(this).val();

            if(pass == confirm){
                jQuery("#confirm").show();
                jQuery("#confirm").removeClass('error');
                jQuery("#confirm").html("Senhas iguais");
                jQuery(".input-submit-fix input.btn").prop("disabled", false);
            } else {
                jQuery("#confirm").show();
                jQuery("#confirm").addClass('error');
                jQuery("#confirm").html("Senhas diferentes");
                jQuery(".input-submit-fix input.btn").prop("disabled", true);
            }

        });
    </script>
</div>
