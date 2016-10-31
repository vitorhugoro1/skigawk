jQuery("#cep").change(function(){
        alert(jQuery(this).val());
        var cep_code = jQuery(this).val();
        if(cep_code.length <= 0 ) return;
        jQuery.get("http://apps.widenet.com.br/busca-cep/api/cep.json", {code: cep_code},
            function(result){
                if(result.status!=1){
                    alert(result.message || "Houve um erro desconhecido");
                    return;
                }
                jQuery("input#cep").val(result.code);
                jQuery("input#state").val(result.code);
                jQuery("input#city").val(result.code);
                jQuery("input#district").val(result.code);
                jQuery("input#end").val(result.code);
            });
    });
