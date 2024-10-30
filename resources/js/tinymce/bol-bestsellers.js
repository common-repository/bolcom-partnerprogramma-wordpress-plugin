if (typeof(tinyMCEPopup) !== 'undefined') {
    tinyMCEPopup.requireLangPack();
}

var BolProductDialog = {

    Items : null,
    product : null,

    init : function() {
    },

    insert : function(widget) {
        if (jQuery('#txtLimit').val() > 25 || jQuery('#txtLimit').val() < 1)
        {
            alert('Please, enter a limit from 1-25');
            return false;
        }

        var tmp = jQuery("#ddlBolCategory").val();
        var content;
        if (tmp != '0') {
            if (jQuery("#ddlBolSubCategory") && jQuery("#ddlBolSubCategory").val() > 0)
            {
                tmp = jQuery("#ddlBolSubCategory").val();
            }
            if (jQuery("#ddlBolSub2Category") && jQuery("#ddlBolSub2Category").val() > 0)
            {
                tmp = jQuery("#ddlBolSub2Category").val();
            }
            if (jQuery("#ddlBolSub3Category") && jQuery("#ddlBolSub3Category").val() > 0)
            {
                tmp = jQuery("#ddlBolSub3Category").val();
            }
            if (parseInt(jQuery('#priceRangeList').val())) {
                tmp += '+' + jQuery('#priceRangeList').val()
            }

            content = '[bol_bestsellers category="'+tmp+'"';
            content += ' price_range="'+jQuery('#priceRangeList').val()+'"';
            if (jQuery('#txtLimit').val()) content += ' limit="'+jQuery('#txtLimit').val()+'"';

            if (jQuery('#txtName').val()) content += ' name="'+jQuery('#txtName').val()+'"';
            if (jQuery('#txtSubid').val()) content += ' subid="'+jQuery('#txtSubid').val()+'"';

            if (jQuery('#txtTitle').val()) content += ' title="'+jQuery('#txtTitle').val()+'"';
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
        } else {
            alert('You have to select product group');
        }

        if (widget) {

            var settings = {
                "widget": jQuery('#widget').val(),
                "category": tmp,
                "limit": jQuery('#txtLimit').val(),
                "title": jQuery('#txtTitle').val(),
                "priceRangeId": jQuery('#priceRangeList').val(),
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
