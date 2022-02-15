// /**
//  * Gera a idade do usuario em anos
//  * @param  int ano_aniversario Ano de nascimento
//  * @param  int mes_aniversario Mês de nascimento
//  * @param  int dia_aniversario Dia de nascimento
//  * @return int
//  */

// function idade(ano_aniversario, mes_aniversario, dia_aniversario) {
//   var d = new Date,
//     ano_atual = d.getFullYear(),
//     mes_atual = d.getMonth() + 1,
//     dia_atual = d.getDate(),

//     ano_aniversario = +ano_aniversario,
//     mes_aniversario = +mes_aniversario,
//     dia_aniversario = +dia_aniversario,

//     quantos_anos = ano_atual - ano_aniversario;

//   if (mes_atual < mes_aniversario || mes_atual == mes_aniversario && dia_atual < dia_aniversario) {
//     quantos_anos--;
//   }

//   return quantos_anos < 0 ? 0 : quantos_anos;
// }

// /**
//  * Lê a Url da imagem que está fazendo upload
//  * @param  DOM input DOM Objeto Input
//  * @return string
//  */

// function readURL(input) {
//   if (input.files && input.files[0]) {
//     var reader = new FileReader();

//     reader.onload = function(e) {
//       jQuery('label[for=avatar] img').attr('src', e.target.result);
//     }

//     reader.readAsDataURL(input.files[0]);
//   }
// }

// function nacionalLoad(elem){
//   if (jQuery(elem).val() == 'br') {
//     jQuery("input#state").prop('readonly', true);
//     jQuery("input#city").prop('readonly', true);
//     jQuery("input#district").prop('readonly', true);
//     jQuery("input#address").prop('readonly', true);
//     jQuery("#cep").mask("00000-000", {
//       placeholder: "00000-000"
//     });
//   } else {
//     jQuery("input#cep").prop('readonly', false);
//     jQuery("input#state").prop('readonly', false);
//     jQuery("input#city").prop('readonly', false);
//     jQuery("input#district").prop('readonly', false);
//     jQuery("input#address").prop('readonly', false);
//   }
// }

// function defineIdade(data){
//   var obj = jQuery("#responsavel");
//   var dataS = data.split("/");
//   var userIdade = idade(dataS[2], dataS[1], dataS[0]);

//   if (userIdade < 18) {
//     obj.show();
//     obj.children('input').attr({
//       disabled:"false",
//       required:"required"
//     });
//   } else {
//     obj.hide();
//     obj.children('input').attr({
//       disabled:"disabled",
//       required:"false",
//       value:""
//     });
//   }
// }

// function userMaskLoad(){
//   jQuery("#idade").mask("00/00/0000", {
//     placeholder: "00/00/0000"
//   });
//   jQuery("#data-pratica").mask("00/00/0000", {
//     placeholder: "00/00/0000"
//   });
//   jQuery("#phone").mask("(00) 0000-0000", {
//     placeholder: "(00) 0000-0000"
//   });
//   jQuery("#cellphone").mask("(00) 00000-0000", {
//     placeholder: "(00) 00000-0000"
//   });

//   defineIdade(jQuery('#idade').val() + '');
// }

// jQuery(document).ready(function($) {

//   userMaskLoad();

//   nacionalLoad($('#nacionalidade'));

//   $('.edit_user_form').find('ul.categoria').each(function(){
//     var check = $(this).parents('label').children('input').prop('checked');

//     if(check === true || check == 'true'){
//       $(this).show();
//     } else {
//       $(this).hide();
//     }

//   });

//   $("#nacionalidade").on('change', function() {
//     if ($(this).val() == 'br') {
//       $("input#state").prop('readonly', true);
//       $("input#city").prop('readonly', true);
//       $("input#district").prop('readonly', true);
//       $("input#address").prop('readonly', true);
//       $("#cep").mask("00000-000", {
//         placeholder: "00000-000"
//       });
//     } else {
//       $("input#cep").prop('readonly', false);
//       $("input#state").prop('readonly', false);
//       $("input#city").prop('readonly', false);
//       $("input#district").prop('readonly', false);
//       $("input#address").prop('readonly', false);
//     }
//   });

//   $("#cep").click(function() {
//     if ($("#nacionalidade").val() == '') {
//       alert("Selecione uma nacionalidade antes");
//       $("#nacionalidade").focus();
//     } else return
//   });

//   $("#cep").change(function() {
//     var cep_code = $(this).val();

//     if ($("#nacionalidade").val() != 'br') {
//       $("input#cep").prop('readonly', false);
//       $("input#state").prop('readonly', false);
//       $("input#city").prop('readonly', false);
//       $("input#district").prop('readonly', false);
//       $("input#address").prop('readonly', false);
//     } else {
//       $.get(`https://viacep.com.br/ws/${cep_code}/json/`,
//       function(result) {
//         if (result.erro) {
//           alert("Cep não encontrado!");
//           $("input#cep").val("");
//           return;
//         }

//         $("input#cep").val(result.cep);
//         $("input#state").val(result.uf);
//         $("input#city").val(result.localidade);
//         $("input#district").val(result.bairro);
//         $("input#address").val(result.logradouro);
//       });
//     }
//   });

//   $('td.modalidade').on('change', 'input[type=checkbox]', function(){
//     var obj = $(this).parent().children('div').children('.categoria');

//     if($(this).is(':checked')){
//       obj.show();
//     } else {
//       obj.hide();
//     }

//   });

//   $('#avatar').change(function() {
//     readURL(this);
//   });

//   $("#idade").change(function() {
//     var data = $(this).val() + '';
//     var obj = $("#responsavel");
//     var dataS = data.split("/");
//     var userIdade = idade(dataS[2], dataS[1], dataS[0]);

//     if (userIdade < 18) {
//       obj.show();
//       obj.children('td').children('input').prop('required', true);
//       obj.children('td').children('input').prop('disabled', false);
//       obj.children('td').children('input').val("");
//     } else {
//       obj.children('td').children('input').val("");
//       obj.hide();
//       obj.children('td').children('input').prop('required', false);
//       obj.children('td').children('input').prop('disabled', true);
//     }
//   });
// });
