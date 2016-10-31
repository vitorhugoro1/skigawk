<?php
$user = wp_get_current_user();
$pages_ids = pages_group_ids();
?>
<section class="tc-content <?php echo $_layout_class; ?>">
    <?php do_action( '__before_content' ); ?>
        <div class="entry-content">
            <div class="hentry">
            	<div>
	            	<h3 class="titleField">Dados Pessoais</h3>
                <?php $avatar_id = get_the_author_meta( 'avatar_id', $user->ID ); ?>

                <div class="row-fluid">
                    <?php if($avatar_id){ ?>
                        <img src="<?php echo wp_get_attachment_url( $avatar_id ) ?>" class="img-responsive avatar" alt="Avatar" />
                    <?php  } ?>
                </div>
	            	<span><b>Nome:</b> <?php echo get_the_author_meta( 'first_name', $user->ID );?></span>
	            	<span><b>Idade:</b> <?php print_r(get_user_age($user->ID)); ?> anos</span><br>
	            	<?php if(get_user_age($user->ID) < 18){ ?>
		            	<span><b>Responsavel:</b> <?php echo get_the_author_meta('responsavel', $user->ID ); ?></span>
	            	<?php } ?>
            	</div>
            	<div>
            		<h3 class="titleField">Contato</h3>
	            	<span><b>Email:</b> <?php echo $user->user_email; ?></span><br>
	            	<span><b>Telefone:</b> <?php echo get_the_author_meta('phone', $user->ID );?></span>
	            	<span><b>Celular:</b> <?php echo get_the_author_meta('cellphone', $user->ID );?></span><br>
	            	<span><b>Endereço:</b> <?php echo get_the_author_meta('address', $user->ID );?></span>
	            	<span><b>Número:</b> <?php echo get_the_author_meta('addressnumber', $user->ID ); ?></span>
                    <?php if(get_the_author_meta('addresscomplement', $user->ID ) !== '') { ?>
                        <span><b>Complemento:</b> <?php echo get_the_author_meta('addresscomplement', $user->ID ); ?></span>
                    <?php } ?><br>
	            	<span><b>CEP/Zip Code:</b> <?php echo get_the_author_meta('cep', $user->ID ); ?></span>
	            	<span><b>Cidade:</b> <?php echo get_the_author_meta('city', $user->ID ); ?></span>
	            	<span><b>Estado:</b> <?php echo get_the_author_meta('state', $user->ID ); ?></span>
                <span><b>País:</b> <?php echo strtoupper(get_the_author_meta( 'nacionalidade', $user->ID )); ?></span>
            	</div>
            	<div>
            		<h3 class="titleField">Estilo</h3>
                    <?php
                      $data_pratica = get_the_author_meta('data-pratica', $user->ID);
                      $term = get_term_by('slug', get_the_author_meta('assoc', $user->ID), 'academia');
                    ?>
	            	<span><b>Nome da Associação:</b> <?php echo ( ! empty($term) ) ? $term->name : 'Não cadastrada'; ?></span> <br>
	            	<span><b>Estilo Principal:</b> <?php echo get_the_author_meta('estilo', $user->ID); ?></span> <br>
                <span><b>Data que começou a praticar: </b><?php echo (!is_null($data_pratica)) ? $data_pratica : 'sem cadastro / <b>Contate o administrador </b>'; ?></span> <br>
                <span><b>Estilos Praticados</b></span>
                <ul class="inline">
                  <?php
                    $modalidades = get_the_author_meta( 'modalidades', $user->ID );

                    foreach($modalidades as $key => $value){
                      $term = get_term_by('slug', $key, 'categoria');
                      if($value == 'avancado'){
                          $value = 'avançado';
                      }
                      if($value == 'intermediario'){
                          $value = 'intermediário';
                      }

                      ?>
                        <li>
                          <b><?php echo $term->name; ?></b> - <?php echo ucfirst($value); ?>
                        </li>
                      <?php
                    }

                   ?>
                </ul>
            	</div>
            	<a class="btn btn-primary fp-button" href="<?php echo get_permalink($pages_ids['editar-perfil']);?>">Editar Perfil</a>
            </div>
            <div class="alignright">
                <a class="btn btn-primary btn-large" href="<?php echo get_permalink($pages_id['fale']);?>">Fale Conosco</a>
            </div>
        </div>
    <?php do_action( '__after_content' ); ?>
</section>
