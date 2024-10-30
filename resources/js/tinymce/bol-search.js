if (typeof(tinyMCEPopup) !== 'undefined') {
    tinyMCEPopup.requireLangPack();
}

var BolProductDialog = {

    Items : null,
    product : null,

    init : function() {
    },

    insert : function(widget) {
        var srch = jQuery('#txtSearch').val();

            content = '[bol_search default="'+srch+'"';
            content += ' showCat="'+(jQuery('#rbShowCat1').attr('checked') ? 'false' : 'true') + '"';
            if (jQuery('#ddlBolCategory').val()) content += ' category="'+jQuery('#ddlBolCategory').val()+'"';
            if (jQuery('#txtLimit').val()) content += ' limit="'+jQuery('#txtLimit').val()+'"';

            if (jQuery('#txtName').val()) content += ' name="'+jQuery('#txtName').val()+'"';
            if (jQuery('#txtSubid').val()) content += ' subid="'+jQuery('#txtSubid').val()+'"';

            if (jQuery('#txtBackgroundColor').val()) content += ' background_color="#'+jQuery('#txtBackgroundColor').val()+'"';
            if (jQuery('#txtLinkColor').val()) content += ' link_color="#'+jQuery('#txtLinkColor').val()+'"';
            if (jQuery('#txtBorderColor').val()) content += ' border_color="#'+jQuery('#txtBorderColor').val()+'"';

            if (jQuery('#txtWidth').val()) content += ' width="'+jQuery('#txtWidth').val()+'"';
            if (jQuery('#txtCols').val()) content += ' cols="'+jQuery('#txtCols').val()+'"';
            if (jQuery('#chkRating')) content += ' rating="'+jQuery('#chkRating').attr('checked')+'"';
            if (jQuery('#chkPrice')) content += ' price="'+jQuery('#chkPrice').attr('checked')+'"';
            if (jQuery('#show_bolheader')) content += ' bolheader="'+jQuery('#show_bolheader').attr('checked')+'"';

            if (jQuery('#rbTarget1').val()) content += ' target="'+jQuery('#rbTarget1').attr('checked')+'"';
            if (jQuery('#rbImageSize1').val()) content += ' image_size="'+jQuery('#rbImageSize1').attr('checked')+'"';
            content += ' image_position="left"';
            if (jQuery('#filename').val()) content += ' css_file="'+jQuery('#filename').val()+'"';
            content += ']\n';

            jQuery('#cssstyle1').val(jQuery('#cssstyle').val());
            jQuery('#saveCss').submit();

        if (widget) {

            var settings = {
                "widget": jQuery('#widget').val(),
                "default": srch,
                "showcat": (jQuery('#rbShowCat1').attr('checked')) ? false : true,
                "catID": jQuery('#ddlBolCategory').val(),
                "name": jQuery('#txtName').val(),
                "subid": jQuery('#txtSubid').val(),
                "background_color": jQuery('#txtBackgroundColor').val(),
                "link_color": jQuery('#txtLinkColor').val(),
                "border_color": jQuery('#txtBorderColor').val(),
                "width": jQuery('#txtWidth').val(),
                "cols": jQuery('#txtCols').val(),
                "rating": jQuery('#chkRating').attr('checked'),
                "price": jQuery('#chkPrice').attr('checked'),
                "bolheader": jQuery('#show_bolheader').attr('checked'),
                "target": jQuery('#rbTarget1').attr('checked'),
                "image_size": jQuery('#rbImageSize1').attr('checked'),
                "image_position": 'left',
                "css_file": jQuery('#filename').val()
            };

            jQuery.post('/wp-content/plugins/bol/bol-save-data.php', settings, function(response){
                if (response == 'success') {
                    jQuery("#save-result").html('Changes saved.');
                } else {
                    alert('Saving error');
                }
            })
        } else {
            setTimeout(function(){
                tinyMCEPopup.execCommand("mceInsertContent", false, content);
                tinyMCEPopup.close();}, 500);
        }
    },

    updateProduct : function( id ) {
    }

};

if (typeof(tinyMCEPopup) !== 'undefined') {
    tinyMCEPopup.onInit.add(BolProductDialog.init, BolProductDialog);
}
