/**
 * Gera a idade do usuario em anos
 * @param  int ano_aniversario Ano de nascimento
 * @param  int mes_aniversario Mês de nascimento
 * @param  int dia_aniversario Dia de nascimento
 * @return int
 */

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

/**
 * Lê a Url da imagem que está fazendo upload
 * @param  DOM input DOM Objeto Input
 * @return string
 */

function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function(e) {
      jQuery('label[for=avatar] img').attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
  }
}

jQuery("#id_assoc").change(function() {
  var value = jQuery(this).val();
  if (value == 'other') {
    var text = '<input type="text" name="assoc_other" id="assoc_other" placeholder="Qual associação?" value="" required/>';
    jQuery("select.input-xxlarge").parent().append(text);
  } else {
    jQuery("#assoc_other").remove();
  }
});

jQuery("ul.modalidade input[type=checkbox]").change(function() {
  var check = jQuery(this).prop('checked');
  if (check == true) {
    var elem = jQuery(this).parent().children('div').children('.categoria');
    elem.show();
  } else {
    var elem = jQuery(this).parent().children('div').children('.categoria');
    elem.hide();
  }
});

function countCheck(){
    var count = 0;
    jQuery('#estilo ul li input:checkbox.active').each(function(i){
        count++;
    });
    jQuery('#seleciona input:radio').each(function(i){
        count++;
    });
    return count;
}

jQuery(document).ready(function($){
var term_url = $('#term_url').val();

  $("#frame").attr("src", term_url);
});

jQuery(document).ready(function($) {
  setInterval(function() {
    $("#tc-page-wrap header.tc-header").css({
      "top": "25px",
      "height": "auto"
    });
    $("#tc-sn").css("top", "25px");
  }, 50);

  setTimeout(function() {
    $("#alerts").remove();
  }, 8000);

  $("#responsavel").hide();
  $("#dateNasc").mask("00/00/0000", {
    placeholder: "00/00/0000"
  });
  $("#phone").mask("(00) 0000-0000", {
    placeholder: "(00) 0000-0000"
  });
  $("#cellphone").mask("(00) 00000-0000", {
    placeholder: "(00) 00000-0000"
  });
  $("#address").val($("#Aaddress").val());
  $("#district").val($("#Adistrict").val());
  $("#city").val($("#Acity").val());
  $("#state").val($("#Astate").val());

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
  jQuery('.list-inscrito li ul li').hover(
    function(){
      jQuery(this).children('.tooltip').addClass('in');
    },
    function(){
      jQuery(this).children('.tooltip').removeClass('in');
    }
  );

  $("#cep").click(function() {
    if ($("#nacionalidade").val() == '') {
      alert("Selecione uma nacionalidade antes");
      $("#nacionalidade").focus();
    } else return
  });

  $("#cep").change(function() {
    var cep_code = $(this).val();

    if ($("#nacionalidade").val() != 'br') {
      $("input#cep").prop('readonly', false);
      $("input#state").prop('readonly', false);
      $("input#city").prop('readonly', false);
      $("input#district").prop('readonly', false);
      $("input#address").prop('readonly', false);
    } else {
      $.get("http://apps.widenet.com.br/busca-cep/api/cep.json", {
        code: cep_code
      },
      function(result) {
        if (result.status != 1) {
          alert(result.message || "Houve um erro desconhecido");
          $("input#cep").val("");
          return;
        }
        $("input#cep").val(result.code);
        $("input#state").val(result.state);
        $("input#city").val(result.city);
        $("input#district").val(result.district);
        $("input#address").val(result.address);
      });
    }
  });

  $("#dateNasc").change(function() {
    var data = $(this).val();
    var dataS = data.split("/");
    var userIdade = idade(dataS[2], dataS[1], dataS[0]);

    if (userIdade < 18) {
      $("#responsavel").show();
      $("#responsavel input").prop("disabled", false);
      $("#responsavel input").prop("required", true);
    } else {
      $("#responsavel").hide();
      $("#responsavel input").prop("disabled", true);
      $("#responsavel input").prop("required", false);
      $("#responsavel input").val().empty();
    }
  });

  $("#confirm_password").change(function() {
    var pass = $("#id_password").val();
    var confirm = $(this).val();

    if (pass == confirm) {
      $("#confirm").show();
      $("#confirm").removeClass('error');
      $("#confirm").html("Senhas iguais");
      $(".input-submit-fix input.btn").prop("disabled", false);
    } else {
      $("#confirm").show();
      $("#confirm").addClass('error');
      $("#confirm").html("Senhas diferentes");
      $(".input-submit-fix input.btn").prop("disabled", true);
    }
  });

  $('#avatar').change(function() {
    readURL(this);
  });

  $("#accept").click(function(){
    var accept = $(this);
    var val = countCheck();
    if(!$(accept).hasClass("accept") && val >= 1){
      $(".btn:submit").prop("disabled", false);
      $(accept).addClass("accept");
    } else if(val == 0){
        alert('Selecione pelo menos uma modalidade');
    } else {
      $(accept).removeClass("accept");
      $(".btn:submit").prop("disabled", true);
    }
  });

  $('#estilo ul li input').click(function(){
    var category_url = $("#category_url").val();
    var caminho = $(this);
    if(!$(caminho).hasClass("active")){
      $body = $("body");
      $.ajaxSetup({
             beforeSend: function(){
               $body.addClass("loading");
             },
             complete: function(){
               $body.removeClass("loading");
             }
          });

      $.post(category_url,
       {
         slug : $(this).attr('id'),
         sexo : $('#sex').val(),
         fetaria : $('#fetaria').val(),
         user_id : $('#user_id').val(),
         post_id: $('#post_id').val()
       },
       function(data, status){
          $(caminho).closest("li").children("div").html(data);
          $(caminho).addClass("active");
       }
     );
    } else {
      $(caminho).closest("li").children("div").html("");
      $(caminho).removeClass("active");
    }
  });

  $('input[type=checkbox]#formasinternas').parent().children("div#formasinternas").unbind().on('click', 'input[type=checkbox]', function () {
    var check = $(this).is(':checked');

    switch ($(this).val()) {
      case '7':
      case '8':
        if(check == true){
          $(this).parent().children('.groups').show();
        } else if(check == false) {
          $(this).parent().children('.groups').hide();
        }
        break;
    }
  });

  $('input[type=checkbox]#formastradicionais').parent().children("div#formastradicionais").unbind().on('click', 'input[type=checkbox]', function () {
    var check = $(this).is(':checked');

    switch ($(this).val()) {
      case '7':
      case '8':
      case '20':
      case '21':
        if(check == true){
          $(this).parent().children('.groups').show();
        } else if(check == false) {
          $(this).parent().children('.groups').hide();
        }
        break;
    }
  });

  $('input[type=checkbox]#formaslivres').parent().children("div#formaslivres").unbind().on('click', 'input[type=checkbox]', function () {
    var check = $(this).is(':checked');

    switch ($(this).val()) {
      case '8':
      case '9':
      case '12':
      case '13':
        if(check == true){
          $(this).parent().children('.groups').show();
        } else if(check == false) {
          $(this).parent().children('.groups').hide();
        }
        break;
    }
  });

  $('input[type=checkbox]#tree').parent().children("div#tree").unbind().on('click', 'input[type=checkbox]', function () {
    var check = $(this).is(':checked');
    if(check == true){
      $(this).parent().children('.groups').show();
    } else if(check == false) {
      $(this).parent().children('.groups').hide();
    }
  });

  $('div').on('click', '.groups .add-member' ,function(e){
    e.stopPropagation();
    var li = document.createElement('LI');
    var ipt = document.createElement('INPUT');
    $(ipt).prop('name', $(this).data('name'));
    $(ipt).prop('type', 'text');
    $(ipt).attr('placeholder', 'Nome do integrante');
    li.appendChild(ipt);
    $(this).parents('.groups').children('li:last').before(li);
  });

  $('div').on('click', '.groups .remove-member' ,function(e){
    e.stopPropagation();
    var count = $(this).parents('.groups').children().length - 1;
    console.log(count);
    if(count <= 5){
      return;
    } else {
      $(this).parents('.groups').children('li:last').prev().remove();
    }
  });

});
