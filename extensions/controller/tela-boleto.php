<div class="wrap">
  <h2>Boleto</h2>
  <div class="wrap">
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#principal">Principal</a></li>
      <li><a data-toggle="tab" href="#info">Informações Bancárias</a></li>
      <li><a data-toggle="tab" href="#sobre">Sobre</a></li>
    </ul>
    <div class="tab-content">
      <div id="principal" class="tab-pane fade in active">
        <?php
          if($_POST['form'] == 'principal'){
            extract($_POST);
            update_option('bank', $bank);
            update_option('wallet', $wallet);
            update_option('carteira', $carteira);
            update_option('agencia', $agencia);
            update_option('conta', $conta);
            update_option('conta_dv', $conta_dv);
          }
        ?>
        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
          <input type="hidden" name="form" value="principal">
          <div class="form-group col-xs-12 nopadding margin-col-vert">
            <div><h4><strong>Bancos</strong></h4></div>
            <label class="radio-inline"><input type="radio" name="bank" id="bank" value="cef" <?php checked( get_option( 'bank'), 'cef'); ?>><img src="<?php echo get_stylesheet_directory_uri()?>/extensions/controller/inc/img/logo-caixa.png" width="32px"/>Caixa</label>
            <label class="radio-inline"><input type="radio" name="bank" id="bank" value="itau" <?php checked( get_option( 'bank'), 'itau'); ?>><img src="<?php echo get_stylesheet_directory_uri()?>/extensions/controller/inc/img/logo-itau.png" width="32px"/>Itaú</label>
            <label class="radio-inline"><input type="radio" name="bank" id="bank" value="bradesco" <?php checked( get_option( 'bank'), 'bradesco'); ?>><img src="<?php echo get_stylesheet_directory_uri()?>/extensions/controller/inc/img/logo-bradesco.png" width="32px"/>Bradesco</label>
            <label class="radio-inline"><input type="radio" name="bank" id="bank" value="bb" <?php checked( get_option( 'bank'), 'bb'); ?>><img src="<?php echo get_stylesheet_directory_uri()?>/extensions/controller/inc/img/logo-bb.png" width="32px"/>Banco do Brasil</label>
          </div>
          <div><h4><strong>Carteira</strong></h4></div>
          <div class="form-group col-sm-3 nopadding">
            <label class="radio-inline">
              <input type="radio" name="wallet" id="wallet" value="sr" <?php echo (get_option('wallet') == 'sr') ? 'checked' : ''; ?>>SR
              <input type="hidden" name="carteira" value="175" disabled>
            </label>
            <label class="radio-inline"><input type="radio" name="wallet" id="wallet" value="cr" <?php echo (get_option('wallet') == 'cr' || '' || NULL) ? 'checked' : ''; ?>>CR</label>
              <div class="input-group pull-right hidden" id="cr_option" style="width:150px">
                  <span class="input-group-addon"><span class="glyphicon glyphicon-credit-card"></span></span>
                  <select class="form-control input-sm" name="carteira">
                      <option value="" <?php selected(get_option('carteira'), '' ); ?>>Escolha uma</option>
                      <option value="174" <?php selected(get_option('carteira'), '174' ); ?>>174</option>
                      <option value="104" <?php selected(get_option('carteira'), '104' ); ?>>104</option>
                      <option value="109" <?php selected(get_option('carteira'), '109' ); ?>>109</option>
                      <option value="178" <?php selected(get_option('carteira'), '178' ); ?>>178</option>
                      <option value="157" <?php selected(get_option('carteira'), '157' ); ?>>157</option>
                  </select>
              </div>
          </div>
          <div class="clearfix"></div>
          <div><h4><strong>Dados da sua Conta</strong></h4></div>
          <div class="form-group col-xs-12 nopadding margin-col-vert">
            <div class="input-group input-group-sm col-xs-2 pull-left">
              <span class="input-group-addon" id="sizing-addon3">Agência</span>
              <input type="text" class="form-control" name="agencia" id="agencia" placeholder="Agência" aria-describedby="sizing-addon3" value="<?php echo get_option('agencia');?>">
            </div>
          </div>
          <div class="form-group col-xs-12 margin-col-vert nopadding">
            <div class="input-group input-group-sm col-xs-2 pull-left">
              <span class="input-group-addon" id="sizing-addon3">Conta</span>
              <input type="text" class="form-control" name="conta" id="conta" placeholder="Conta" aria-describedby="sizing-addon3" value="<?php echo get_option('conta');?>">
            </div>
            <div class="input-group input-group-sm margin-col-side col-xs-1 pull-left">
              <span class="input-group-addon" id="sizing-addon3">DV</span>
              <input type="text" class="form-control" name="conta_dv" id="digito" placeholder="DV" aria-describedby="sizing-addon3" value="<?php echo get_option('conta_dv');?>">
            </div>
          </div>

          <div class="form-group col-xs-12 nopadding bottom-column">
            <div class="col-sm-10 nopadding">
              <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
          </div>
        </form>
      </div>
      <div id="info" class="tab-pane fade">
        <?php
          if($_POST['form'] == 'info'){
            extract($_POST);
            update_option('prazo_pgt', $prazo_pgt);
            update_option('tax_boleto', $tax_boleto);
            update_option('razao_social', $razao_social);
            update_option('cnpj', $cnpj);
            update_option('end_empresa', $end_empresa);
            update_option('cidade_empresa', $cidade_empresa);
            update_option('estado_empresa', $estado_empresa);
          }
         ?>
        <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
          <input type="hidden" name="form" value="info">
          <h3>Dados do boleto para o cliente</h3>
          <div class="form-group col-xs-12">
            <label for="prazo-pgt" class="pull-left col-md-3 nopadding">Dias de Prazo para Pagamento</label>
            <div class="input-group margin-col-side col-md-2 pull-left">
              <input type="number" class="form-control" name="prazo_pgt" value="<?php echo (get_option('prazo_pgt') == '') ? '5' : get_option('prazo_pgt'); ?>" min="5" step="1" aria-describeby="basic-addonpgt">
              <span class="input-group-addon" id="basic-addonpgt">Dias</span>
            </div>
          </div>
          <div class="form-group col-xs-12">
            <label for="tax-boleto" class="pull-left col-md-3 nopadding">Taxa do Boleto</label>
            <div class="input-group margin-col-side col-md-2 pull-left">
              <span class="input-group-addon" id="basic-addontax">R$</span>
              <input type="number" class="form-control" name="tax_boleto" value="<?php echo (get_option('tax_boleto') == '') ? '0' : get_option('tax_boleto'); ?>" min="0" step="0.01" aria-describeby="basic-addontax">
            </div>
          </div>
          <h3>Informações para o cliente</h3>
          <div class="form-group">
            <label for="razao-social">Razão Social</label>
            <input type="text" class="form-control" name="razao_social" placeholder="Razão Social" value="<?php echo get_option('razao_social'); ?>">
          </div>
          <div id="cnpj-group" class="form-group col-xs-7 nopadding">
            <label for="cnpj">CNPJ</label>
            <input type="text" name="cnpj" class="form-control" id="cnpj" value="<?php echo get_option('cnpj'); ?>">
            <span class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
            <span id="inputStatusSucess" class="sr-only">(success)</span>
            <span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>
            <span id="inputStatusError" class="sr-only">(error)</span>
          </div>
          <div class="form-group col-xs-12 nopadding">
            <label for="end_empresa">Endereço</label>
            <input type="text" class="form-control" name="end_empresa" placeholder="Endereço" value="<?php echo get_option('end_empresa'); ?>">
          </div>
          <div class="form-group col-xs-5 nopadding">
            <label for="end_empresa">Cidade</label>
            <input type="text" class="form-control" name="cidade_empresa" placeholder="Cidade" value="<?php echo get_option('cidade_empresa'); ?>">
          </div>
          <div class="form-group col-xs-6 margin-col-side nopadding">
            <label for="end_empresa">Estado</label>
            <input type="text" class="form-control" name="estado_empresa" placeholder="Estado" value="<?php echo get_option('estado_empresa'); ?>">
          </div>
          <div class="form-group col-xs-12 nopadding margin-col-vert">
            <div class="col-sm-10 nopadding">
              <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
          </div>
        </form>
      </div>
      <div id="sobre" class="tab-pane fade">
        <h3>Sobre</h3>
        <p>Está pagina foi desenvolvida por <strong>Vitor Hugo Rodrigues Merencio</strong>, para uso da Skigawk&ccecil; e está adaptada para trabalhar de formar conjunta as informações por ela fornecida.</p><br>
        <p>2016, Brazil, São Paulo.</p><br>
      </div>
    </div>
  </div>
</div>
