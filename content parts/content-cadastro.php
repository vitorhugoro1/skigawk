<?php
$pages_ids = pages_group_ids();
?>
<section class="tc-content <?php echo $_layout_class; ?>">
    <?php do_action('__before_content'); ?>
        <div class="entry-content">
          <div class="hentry">
              <?php the_content(); ?>
              <form action="<?php echo admin_url('admin-post.php');?>" method="post" enctype="multipart/form-data" encoding="multipart/form-data">
                  <input type="hidden" name="action" value="vhr_cadastrar_usuario"/>
                  <?php wp_nonce_field( 'vhr_cadastrar_usuario' ); ?>
                  <div class="clear">
                  <label for="name"><b>Nome completo</b> <span class="red">*</span></label>
                      <input type="text" name="name" id="name" placeholder="Nome Completo"  required>
                  <label for="mail"><b>Email</b><span class="red">*</span></label>
                      <input type="email" name="mail" id="mail" placeholder="email@email.com"  required>
                  <label for="password"><b>Senha Nova</b></label>
                      <input type="password" id="id_password" name="password" placeholder="Senha"  required>
                  <label for="confirm_password"><b>Confirmar Senha Nova</b><br>
                      <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirme a senha"  required>
                      <div id="confirm" class="hide"></div>
                  </label>
                  <label for="idade" class="alignleft"><b>Data de nascimento</b><span class="red">*</span><br>
                    <input type="text" class="input-medium" id="idade" name="idade" required>
                  </label>
                  <label for="id_sex" class="alignleft label-mod margin-10"><b>Sexo</b> <span class="red">*</span><br>
                    <label for="masc">
                      <input type="radio" name="sex" id="masc" value="m" required>Masculino
                    </label>
                    <label for="fem">
                      <input type="radio" name="sex" id="fem" value="f">Feminino
                    </label>
                  </label><br><br>
                  <div class="clearfix"></div>
                  <label id="responsavel" for="responsavel"><b>Responsavel</b><span class="red">*</span><br>
                      <input type="text" name="responsavel" disabled>
                  </label>
                  <div class="row-fluid">
                    <label for="nacionalidade" class="alignleft"><b>Nacionalidade</b><br>
                      <select class="" name="nacionalidade" id="nacionalidade">
                        <option value="">Selecione um País</option>
                        <option value="br">Brasil</option>
                        <option value="py">Paraguai</option>
                        <option value="ra">Argentina</option>
                      </select>
                    </label>
                    <label for="cep" class="alignleft margin-10"><b>CEP/Zip Code</b><br>
                        <input type="text" name="id_cep" id="cep" class="input-medium"  required>
                    </label>
                  </div>
                  <label for="address"><b>Endereço</b><br>
                      <input type="text" id="address" name="address" readonly>
                  </label>
                  <label for="addressnumber"><b>Número</b><br>
                      <input type="text" id="addressnumber" name="addressnumber"  required>
                  </label>
                  <label for="addresscomplement"><b>Complemento</b><br>
                    <input type="text" name="addresscomplement" id="addresscomplement" >
                  </label>
                  <label for="district"><b>Bairro</b><br>
                      <input type="text" id="district" name="district" readonly>
                  </label>
                  <label for="city"><b>Cidade</b><br>
                      <input type="text" id="city" name="city" readonly>
                  </label>
                  <label for="state"><b>Estado</b><br>
                      <input type="text" id="state" name="state" readonly>
                  </label>
                  <label for="phone"><b>Telefone</b><br>
                      <input type="text" id="phone" name="phone" >
                  </label>
                  <label for="cellphone"><b>Celular</b><br>
                      <input type="text" id="cellphone" name="cellphone" >
                  </label>
                  <label for="avatar"><b>Avatar</b><br>
                    <input type="file" name="avatar" id="avatar" />
                  </label>
                  <label for="assoc"><b>Nome da Associação</b><br>
                      <select name="assoc" class="input-xxlarge" id="assoc" required>
                          <option value="">Selecione uma associação</option>
                          <?php
                          $assoc = get_terms('academia', array('fields'   => 'all', 'hide_empty' => false));
                          foreach($assoc as $term){
                              ?>
                              <option value="<?php echo $term->slug; ?>"><?php echo $term->name; ?></option>
                              <?php
                          }
                          ?>
                              <option value="other">Outra</option>
                      </select>
                  </label>
                  <label for="estilo"><b>Estilo Principal</b><br>
                      <input type="text" id="estilo" name="estilo" required>
                  </label>
                  <label for="inicio-pratica">
                      <b>Data que começou a praticar</b>
                      <input type="text" name="data-pratica" id="inicio-pratica" class="input-medium" required/>
                  </label>
                  <label for="modalidade">
                    <b>Qual(is) modalidade(s) é praticante?</b>
                    <ul class="modalidade">
                      <?php
                           $list = get_terms('categoria', array('fields' => 'all', 'hide_empty' => false));
                           foreach ($list as $term) {
                               ?>
                               <li>
                               <input type="checkbox" id="<?php echo $term->slug; ?>" name="modalidade[]" value="<?php echo $term->slug; ?>" />
                               <?php echo $term->name; ?>
                               <div id="<?php echo $term->slug; ?>">
                                  <ul id="categoria" class="categoria">

                                    <li><input type="radio" name="data-<?php echo $term->slug;?>" value="novato">Novato (até 01 ano)</li>

                                    <li><input type="radio" name="data-<?php echo $term->slug;?>" value="iniciante">Iniciante (até 2 anos)</li>

                                    <li><input type="radio" name="data-<?php echo $term->slug;?>" value="intermediario">Intermediário (até 3 anos)</li>

                                    <li><input type="radio" name="data-<?php echo $term->slug;?>" value="avancado">Avançado (acima de 4 anos)</li>

                                  </ul>
                                </div>
                               </li>
                      <?php

                           }
                       ?>
                    </ul>
                  </label>
                  <br>
                  </div>
                  <div class="input-submit-fix">
                      <input type="submit" class="btn btn-primary fp-button" value="Cadastrar">
                  </div>
            </form>
        </div>
                </div>
    <?php do_action('__after_content'); ?>
</section>
