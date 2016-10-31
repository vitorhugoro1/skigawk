    <?php do_action( '__before_content' ); ?>

        <section class="tc-content <?php echo $_layout_class; ?>">
            <div class="entry-content">
                <div class="clear">
                <?php
                    if($_POST['nome'] && $_POST['email'] && $_POST['message'] !== ''){
                        $to = get_option('admin_email');
                        $subject = 'Mensagem de '.esc_attr($_POST['nome']).' '.esc_attr($_POST['assunto']);
                        $message = esc_attr($_POST['message']);
                        $headers = array(
                          'From'	=> sanitize_email($_POST['email']),
                          'Reply-To' => sanitize_email($_POST['email']),
                          'Content-Type: text/html; charset=UTF-8'
                        );
                        wp_mail( $to, $subject, $message, $headers);

                    } else {
                        if(isset($_POST['nome'])){
                            if(empty($_POST['nome'])){
                               ?>
                            <style type="text/css">
                            #alerts.error {
                                    background-color: #b94a48;
                                    color: white;
                                    font-weight: bold;
                                    text-align: center;
                                    margin-bottom: 5px;
                                }
                            </style>
                            <div id="alerts" class="error">Preencha o <b>Nome</b></div>
                            <script type="text/javascript">
                                $(document).ready(function(){
                                    setTimeout(function(){$("#alerts").remove();},8000);
                                });
                            </script>
                            <?php
                            }
                        }
                        if(isset($_POST['message'])){
                            if(empty($_POST['message'])){
                               ?>
                            <style type="text/css">
                            #alerts.error {
                                    background-color: #b94a48;
                                    color: white;
                                    font-weight: normal;
                                    text-align: center;
                                    margin-bottom: 5px;
                                }
                            </style>
                            <div id="alerts" class="error">Escreva uma <b>Mensagem</b></div>
                            <script type="text/javascript">
                                $(document).ready(function(){
                                    setTimeout(function(){$("#alerts").remove();},8000);
                                });
                            </script>
                            <?php
                            }
                        }
                    }
                 ?>
                    <?php the_content(); ?>
                    <form action="<?php echo get_permalink();?>" method="post" id="form" class="form-horizontal" enctype="multipart/form-data" encoding="multipart/form-data">
                        <div class="control-group" id="group">
                            <label for="nome">Nome</label>
                            <input type="text" name="nome" id="nome" class="input-xlarge span8" placeholder="Digite o seu nome"/>
                            <label for="email">E-mail</label>
                            <input type="email" name="email" id="email" placeholder="Digite o seu e-mail" class="input-xlarge span8" />
                            <label for="assunto">Assunto</label>
                            <input type="text" name="assunto" id="assunto" class="input-xlarge span8" placeholder="Digite o assunto" />
                            <label for="message">Mensagem</label>
                            <textarea class="span8" name="message" id="message" placeholder="Digite a mensagem"></textarea>
                        </div>
                        <input type="submit" id="btn" class="btn btn-primary" value="Enviar"/>
                    </form>
                </div>
            </div>
        </section>

    <?php do_action( '__after_content' ); ?>
