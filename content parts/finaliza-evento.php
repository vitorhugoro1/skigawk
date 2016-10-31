<div class="relation-post">
	<h3><?php echo get_the_title($post_id); ?></h3>
	<div>
		<?php echo wpautop(get_excerpt($post_id)); ?>
	</div>
	<div>
		<p>Níveis de inscrição disponíveis: </p>
		<ul>
			<?php $niveis = get_post_meta( $post_id, 'category_insider_group', true );

			if(! empty($niveis)){
				foreach( (array) $niveis as $tkey => $tentry){
					if(isset($_POST['option-'.sanitize_title($tentry['name'])]) && (number_format($tentry['price'], 2, '.', '') == $subscriberPrice)){
						$mod = $tentry['name'];
						$category = sanitize_title($tentry['name']);
						$priceOption = esc_attr($_POST['priceOption-'.sanitize_title($tentry['name'])]);
					}
					?>
						<li class="">
							<?php echo $tentry['name'].' - R$'.number_format($tentry['price'], 2, '.', ''); ?>
							<div class="info">
								<?php echo wpautop($tentry['description']); ?>
							</div>
						</li>
					<?php
				}
			}
			 ?>
		</ul>
	</div>

	<div>
		<p>
			O valor da inscrição no evento <b><?php echo get_the_title($post_id); ?></b> será de <b>R$<?php echo number_format($subscriberPrice, 2, ',', ''); ?></b>, que é referente a modalidade de inscrição <b><?php echo $mod; ?></b> que foi selecionada na página anterior.
		</p>
		<p>
            O pagamento do valor acima deve ser realizado para nos dados da conta descritos abaixo:
        </p>
        <p>
					<?php
						$options = unserialize(get_option('deposito'));

						echo sprintf('%s<br> %s<br>Agência: %s<br>Conta: %s', $options['banco'], $options['beneficiario'], $options['agencia'], $options['conta']);
					 ?>
        </p>
        <p>
            Ao realizar o pagamento enviar o comprovante para o e-mail <a href="mailto:adriel@skigawk.com.br">adriel@skigawk.com.br</a>.
        </p>
		<p>
			Se o item selecionado estiver correto, aperte em <b>finalizar</b>, se não aperte em <b>voltar</b>.
		</p>
	</div>
	<form action="<?php echo home_url(); ?>/wp-content/themes/skigawk/includes/cadastrar.php" method="post" class="form-inline">
	      <?php if($priceOption == 's') { ?>
		      <input type="hidden" name="priceTotal" value="<?php echo $subscriberPrice; ?>">
		  <?php  } else { ?>
		      <input type="hidden" name="priceTotal" value="0">
		  <?php  } ?>
		<input type="hidden" name="category" value="<?php echo $category; ?>">
		<input type="hidden" name="pay" value="<?php echo $priceOption; ?>">
	    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
	    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
	    <input type="hidden" name="insider" value="<?php echo ($insider) ? 's' : 'n'; ?>">
	    <input type="submit" class="btn btn-primary fp-button" value="Finalizar"/>
			<a href="javascript:history.back()" class="btn fp-button">Voltar</a>
    </form>
</div>
