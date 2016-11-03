<?php
$pages_ids = pages_group_ids();
$user_id = get_current_user_id();
?>

<section class="tc-content <?php echo $_layout_class; ?>">
    <?php do_action( '__before_content' ); ?>
        <div class="entry-content">
            <div class="hentry">
            <?php if(isset($_GET['m']) && $_GET['m'] == '1'){ ?>
              <div id="alerts" class="sucess">Perfil Atualizado</div>
            <?php } ?>
	    <form action="<?php echo admin_url('admin-post.php');?>" method="post" enctype="multipart/form-data" encoding="multipart/form-data">
        <input type="hidden" name="action" value="vhr_editar_perfil">
        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
        <?php wp_nonce_field('vhr_editar_perfil'); ?>
                    <div class="clear">
                        <label for="nome"><b>Nome</b><br>
                          <input type="text" name="nome" id="nome" value="<?php echo get_the_author_meta('display_name', $user_id); ?>">
                        </label>
                        <label for="password"><b>Senha Nova</b><br>
                            <input type="password" id="password" name="password" value="">
                        </label>
                        <label for="confirm_password"><b>Confirmar Senha Nova</b><br>
                            <input type="password" id="confirm_password" name="confirm_password" value="">
                            <div id="confirm" class="hide"></div>
                        </label>
                        <label for="mail"><b>Email</b><br>
                            <input type="email" name="mail" id="mail" value="<?php echo get_the_author_meta('user_email', $user_id); ?>">
                        </label>
                        <label for="phone">
                          <b>Telefone</b><br>
                          <input type="text" id="phone" name="phone" value="<?php echo get_the_author_meta('phone', $user_id); ?>">
                        </label>
                        <label for="cellphone"><b>Celular</b><br>
                            <input type="text" id="cellphone" name="cellphone" value="<?php echo get_the_author_meta('cellphone', $user_id); ?>">
                        </label>
                        <div class="row-fluid">
                          <?php $pais = get_the_author_meta( 'nacionalidade', $user_id ); ?>
                          <label for="nacionalidade" class="alignleft">
                            <b>Nacionalidade</b><br>
                            <select name="nacionalidade" id="nacionalidade">
                              <option value="br" <?php selected( $pais, 'br'); ?>>Brasil</option>
                              <option value="py" <?php selected( $pais, 'py'); ?>>Paraguai</option>
                              <option value="ra" <?php selected( $pais, 'ra'); ?>>Argentina</option>
                            </select>
                          </label>
                          <label for="cep" class="alignleft margin-10">
                            <b>CEP/Zip Code</b><br>
                            <input type="text" id="cep" name="cep" value="<?php echo get_the_author_meta('cep', $user_id); ?>" required/>
                          </label>
                        </div>
                        <div class="row-fluid">
                          <label for="id_end"><b>Endereço</b><br>
                            <input type="text" name="address" id="address" value="<?php echo get_the_author_meta('address', $user_id); ?>" />
                          </label>
                          <label for="id_endnumber" class="alignleft">
                            <b>Número</b><br>
                            <input type="text" name="endnumber" id="endnumber" class="input-small" value="<?php echo get_the_author_meta('addressnumber', $user_id); ?>" />
                          </label>
                          <label for="id_endcomplement" class="alignleft margin-10">
                            <b>Complemento</b><br>
                            <input class="input-small" type="text" name="endcomplement" id="endcomplement" value="<?php echo get_the_author_meta('addresscomplement', $user_id); ?>">
                          </label>
                        </div>
                        <div class="row-fluid">
                          <label for="id_district" class="alignleft">
                            <b>Bairro</b><br>
                            <input type="text" name="district" id="district" class="input-medium" value="<?php echo get_the_author_meta('district', $user_id); ?>" />
                          </label>
                          <label for="id_city" class="alignleft margin-10">
                            <b>Cidade</b><br>
                            <input type="text" name="city" id="city" class="input-medium" value="<?php echo get_the_author_meta('city', $user_id); ?>" />
                          </label>
                          <label for="id_state" class="alignleft margin-10">
                            <b>Estado</b><br>
                            <input class="input-small" type="text" name="state" id="state" value="<?php echo get_the_author_meta('state', $user_id); ?>" />
                          </label>
                        </div>
                        <label for="avatar"><b>Avatar</b><br>
                          <?php if(get_the_author_meta('avatar_id', $user_id)){ ?>
                              <img src="<?php echo wp_get_attachment_url( get_the_author_meta('avatar_id', $user_id) ) ?>" class="img-responsive avatar" alt="Avatar" />
                          <?php  } ?>
                          <input type="file" name="avatar" class="margin-10" id="avatar" value="">
                        </label>
                    </div>
                    <div class="input-submit-fix">
                        <input type="submit" class="btn btn-primary fp-button" value="Atualizar Perfil">
                        <a href="<?php echo get_permalink($pages_ids['perfil']);?>" class="btn fp-button">Voltar</a>
                    </div>
        </form>

    </div>
            </div>
    <?php do_action( '__after_content' ); ?>
</section>
