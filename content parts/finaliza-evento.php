<div class="relation-post">
    <h3><?php echo get_the_title($post_id); ?></h3>
    <div>
        <?php echo wpautop(get_excerpt($post_id)); ?>
    </div>
    <div>
        <p>Níveis de inscrição disponíveis: </p>
        <ul>
            <?php $niveis = get_post_meta($post_id, 'category_insider_group', true);

            if (!empty($niveis)) {
                foreach ((array) $niveis as $tkey => $tentry) {
                    if (isset($_POST['option-' . sanitize_title($tentry['name'])]) && (number_format($tentry['price'], 2, '.', '') == $subscriberPrice)) {
                        $mod = $tentry['name'];
                        $category = sanitize_title($tentry['name']);
                        $priceOption = esc_attr($_POST['priceOption-' . sanitize_title($tentry['name'])]);
                    }
            ?>
                    <li>
                        <?php echo $tentry['name'] . ' - R$' . number_format($tentry['price'], 2, '.', ''); ?>
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
            $banco = get_post_meta($_POST['camp_id'], '_vhr_banco', true);
            $beneficiario = get_post_meta($_POST['camp_id'], '_vhr_beneficiario', true);
            $agencia = get_post_meta($_POST['camp_id'], '_vhr_agencia', true);
            $conta = get_post_meta($_POST['camp_id'], '_vhr_conta', true);

            if ($banco == '' || $beneficiario == '' || $agencia == '' || $conta == '') {
                echo sprintf('%s<br> %s<br>Agência: %s<br>Conta: %s', $options['banco'], $options['beneficiario'], $options['agencia'], $options['conta']);
            } else {
                echo sprintf('%s<br> %s<br>Agência: %s<br>Conta: %s', $banco, $beneficiario, $agencia, $conta);
            }
            ?>
        </p>
        <p>
            Ao realizar o pagamento enviar o comprovante para o e-mail <a href="mailto:adriel@skigawk.com.br">adriel@skigawk.com.br</a>.
        </p>
        <p>
            Se o item selecionado estiver correto, aperte em <b>finalizar</b>, se não aperte em <b>voltar</b>.
        </p>
    </div>
    <form action="<?php echo admin_url('admin-post.php'); ?>" method="post" class="form-inline">
        <input type="hidden" name="action" value="vhr_cadastrar_evento" />
        <?php wp_nonce_field('vhr_cadastrar_evento') ?>
        <input type="hidden" name="priceTotal" value="<?php echo ($priceOption == 's') ? $subscriberPrice : '0'; ?>">
        <input type="hidden" name="category" value="<?= $category ?>">
        <input type="hidden" name="pay" value="<?= $priceOption ?>">
        <input type="hidden" name="post_id" value="<?= $post_id ?>">
        <input type="hidden" name="user_id" value="<?= $user_id ?>">
        <input type="hidden" name="insider" value="<?php echo ($insider) ? 's' : 'n'; ?>">
        <input type="submit" class="btn btn-primary fp-button" value="Finalizar" />
        <a href="javascript:history.back()" class="btn fp-button">Voltar</a>
    </form>
</div>