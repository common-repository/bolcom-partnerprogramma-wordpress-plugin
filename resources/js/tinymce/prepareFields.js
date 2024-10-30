function showcssFunc(el) {
    if (el.attr('checked')) {
        jQuery('#labelCssstyle').removeClass('hideElement');
    } else {
        jQuery('#labelCssstyle').addClass('hideElement');
        jQuery('#cssstyle').val('');
    }
}

function hideSub(name) {
    jQuery("#label"+name).addClass('hideElement');
    jQuery("#ddl"+name).html("<option value='0'></option>");
}

jQuery("iframe", top.document).load(function () {
    jQuery('.colorpickerfield').ColorPicker({
        onSubmit: function(hsb, hex, rgb, el) {
            jQuery(el).val(hex).change();
            jQuery(el).ColorPickerHide();
        },
        onBeforeShow: function () {
            jQuery(this).ColorPickerSetColor(this.value);
        }
    }).bind('keyup', function(){
            jQuery(this).ColorPickerSetColor(this.value);
    });
});

jQuery(document).ready(function(){
    jQuery('input[name=bolSearchField]').live('focus', function(){
        if (!jQuery(this).is('[old-value]')) {
            jQuery(this).attr('old-value', jQuery(this).val());
            jQuery(this).val('');
        }
    });
    jQuery('input[name=bolSearchField]').live('blur', function(){
        if ('' == jQuery(this).val()) {
            jQuery(this).val(jQuery(this).attr('old-value'));
            jQuery(this).removeAttr('old-value');
        }
    });
});
