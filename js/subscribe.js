const Subscribe = {
    hasChampionshipOptions: () => jQuery('#estilo ul li').length > 0,
    hasChampionshipGroups: () => Array.from(jQuery('.groups:visible input:text')).length > 0,
    hasFilledGroups: () => {
        return Array.from(jQuery('.groups:visible input:text')).filter((elem) => jQuery(elem).val() !== '').length > 0
    },
    canAcceptTerms: () => {
        if (Subscribe.hasChampionshipGroups() && !Subscribe.hasFilledGroups()) {
            return false;
        }

        return true;
    },
    selectedOptionsCount: () => {
        let count = 0;

        if (Subscribe.hasChampionshipOptions()) {
            count += jQuery('#estilo ul li input:checkbox.active').length;
        }

        if (jQuery("#seleciona")) {
            count += jQuery('#seleciona input:radio').length;
        }

        return count;
    }
}