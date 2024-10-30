var productPage = 0;
var productFilterObj = [];

var BolSearchDialog = {

    Items : null,
    product : null,
    bolSearchLink : '',

    // the display property options
    properties : {
        'txtName'               : 'name',
        'txtSubid'              : 'sub_id',
        'txtTitleColor'         : 'link_color',
        'txtSubtitleColor'      : 'subtitle_color',
        'txtPriceTypeColor'     : 'pricetype_color',
        'txtPriceColor'         : 'price_color',
        'txtDeliveryTimeColor'  : 'deliverytime_color',
        'txtBackgroundColor'    : 'background_color',
        'txtBorderColor'        : 'border_color',
        'txtWidth'              : 'width',
        'txtCols'               : 'cols',
        'chkBolheader'          : 'show_bol_logo',
        'chkPrice'              : 'show_price',
        'chkRating'             : 'show_rating',
        'chkDeliveryTime'       : 'show_deliverytime',
        'rbLinkTarget'          : 'link_target',
        'rbImageSize'           : 'image_size'
    },

    init : function() {

        // search.php link
        BolSearchDialog.bolSearchLink = bol_partner_plugin_base + '/src/ajax/bol-search.php';

        // Fill the categories and set the change handlers
        jqBol('#ddlBolCategory').after('<span class="loader" id="categories-loader"></span>');
        jqBol.ajax({
            url: BolSearchDialog.bolSearchLink + '?get=selected-categories',
            type: 'post',
            data: {},
            success: function(response) {
                jqBol("#categories-loader").remove();

                // Check the result of the response
                if (response.indexOf('option') != -1) {
                    jqBol("#ddlBolCategory").append(response);
                } else {
                    // Error in the response, add the error
                    alert(response);
                }

            }
        });

        jqBol('#widthSlider').slider({
            min: 180,
            max: 800,
            value: jqBol('#txtWidth').val(),
            slide: function(event, ui) {
                jqBol('#txtWidth').val(ui.value);
                BolSearchDialog.calculateRowsCols();
            },
            stop: BolSearchDialog.getProductPreview
        });
        jqBol('#txtWidth').blur(function (event) {
            jqBol('#widthSlider').slider("value", jqBol('#txtWidth').val());
            BolSearchDialog.calculateRowsCols();
        });

        jqBol('#colsSlider').slider({
            min: 1,
            max: 2,
            value: jqBol('#txtCols').val(),
            slide: function(event, ui) {
                jqBol('#txtCols').val(ui.value);
            },
            stop: BolSearchDialog.getProductPreview
        });
        jqBol('#txtCols').blur(function (event) {
            jqBol('#colsSlider').slider("value", jqBol('#txtCols').val());
            BolSearchDialog.calculateRowsCols();
        });

        BolSearchDialog.calculateRowsCols();
        BolSearchDialog.initStyleUpdater();

        // Attach event handlers to preview button
        jqBol('#apply-preview').click(BolSearchDialog.getProductPreview);

        jqBol('#chkCustomCss').click(function() {
            if (jqBol(this).attr('checked')) {
                jqBol('#txtCustomCss').removeClass('hideElement');
            } else {
                jqBol('#txtCustomCss').addClass('hideElement');
                jqBol('#txtCustomCss').val('');
            }
        });

        jqBol('.triggerPreview').change(BolSearchDialog.getProductPreview);
    },

    initStyleUpdater : function() {
        jqBol('#ddlBolCategory').change(function() {

            // Link the dropdown values to the correct category
            // This link determines which add array shown
            var categories = {
                'audio_navigatie' : [4005, 10714],
                'baby' : [11271],
                'boeken_int' : [8292, 8296, 8294, 8298, 8297, 8299],
                'boeken_nl' : [8293, 8299],
                'camera' : [4781],
                'computer' : [4770, 10455, 10460, 7142, 7000, 7068, 3134],
                //'crosscategorie' : [],
                'dier_tuin_klussen' : [12748, 13155, 12974],
                'dvd' : [3133],
                'ebooks' : [8299],
                'elektronica' : [4006, 10715, 4663, 7291, 7894, 3136],
                'games' : [3135],
                'home_entertainment' : [3136],
                'huishoudelijk' : [10759, 11057],
                'koken_tafelen_huishouden' : [10768, 11764],
                'mooi_en_gezond' : [10823, 12382],
                'muziek' : [3132],
                'speelgoed' : [7934],
                'telefoon_tablet' : [8349, 10656, 3137],
                'wonen' : [14035]
            };

            var addCategory = new Array();
            var selectedValue = jqBol('#ddlBolCategory').val();
            for (var key in categories) {

                categoryGroup = categories[key];

                for (var categoryKey in categoryGroup) {
                    categoryId = categoryGroup[categoryKey];
                    if (selectedValue == categoryId) {
                        addCategory[key] = key;
                    }
                }
            }

            // Hide all adds
            jqBol('.add').addClass('hide');

            // Remove the hide from the adds that should be shown
            for (var key in addCategory) {
                var key = '.add.' + addCategory[key];
                jqBol(key).removeClass('hide');
            }

            jqBol('.promotions').removeClass('hide');
        });

        jqBol('.hndle').click(function() {
           jqBol('.adds').toggleClass('hide');
        })

        jqBol('#chkBolheader').click(function() { jqBol('.BolWidgetLogo').toggleClass('hide'); });
        jqBol('#chkRating').click(function() { jqBol('div.rating-stars').toggleClass('hide'); });
        jqBol('#chkPrice').click(function() { jqBol('.bol_pml_price').toggleClass('hide'); });
        jqBol('#chkDeliveryTime').click(function() { jqBol('.bol_available').toggleClass('hide'); });

        jqBol('#txtTitleColor').change(function() {
            jqBol('.bol_pml_box .bol_pml_box_inner .product_details_mini .title').css('color', '#' + jqBol(this).val());
            jqBol('.bol_pml_box .pager a').css('color', '#' + jqBol(this).val());
            alert('foo');

        });

        jqBol('#txtSubtitleColor').change(function() {
            jqBol('.bol_pml_box .bol_pml_box_inner .product_details_mini .subTitle').css('color', '#' + jqBol(this).val());
        });

        jqBol('#txtPriceTypeColor').change(function() {
            jqBol('.bol_pml_box .bol_pml_box_inner .product_details_mini .bol_pml_price .bol_pml_price_type').css('color', '#' + jqBol(this).val());
        });

        jqBol('#txtPriceColor').change(function() {
            jqBol('.bol_pml_box .bol_pml_box_inner .product_details_mini .bol_pml_price').css('color', '#' + jqBol(this).val());
        });

        jqBol('#txtDeliveryTimeColor').change(function() {
            jqBol('.bol_pml_box .bol_pml_box_inner .product_details_mini .bol_available').css('color', '#' + jqBol(this).val());
        });

        jqBol('#txtBackgroundColor').change(function() {
            jqBol('.bol_pml_box').css('background-color', '#' + jqBol(this).val());
        });

        jqBol('#txtBorderColor').change(function() {
            jqBol('.bol_pml_box').css('border-color', '#' + jqBol(this).val());
        });

        jqBol('input[name="rbImageSize"]').click(
            BolSearchDialog.getProductPreview
        );
    },

    calculateRowsCols: function(){

        nrOfItems = jqBol('#txtLimit').val();

        // get max available columns
        cols = Math.floor(jqBol('#txtWidth').val() / 180);
        if(cols == 0){
            cols = 1;
        } else if(cols > nrOfItems){
            cols = nrOfItems;
        }

        // enable or disable the columns slider
        if(cols <= 1){
            jqBol('#colsSlider').slider('disable');
            jqBol('#colsSlider').slider('value', 1);
            jqBol('#colsSlider').slider('max', 1);
            jqBol('#colsDisplay').html(1);
            jqBol('#txtCols').val(1);
            return;
        }

        jqBol('#colsSlider').slider('enable');
        jqBol('#colsSlider').slider('option', 'max', cols);

        var currentColValue = jqBol('#txtCols').val();
        if (currentColValue > cols) {
            jqBol('#colsDisplay').html(cols);
            jqBol('#txtCols').val(cols);
        } else {
            jqBol('#colsSlider').slider('value', currentColValue);
        }
    },

    insert : function(widget) {

        var properties = BolSearchDialog._getProperties();

        if (properties.name.length < 1) {
            alert(i10n.requirename);
            return false;
        }

        if (widget) {
            var url = bol_partner_plugin_base + '/src/ajax/save-widget-data.php';
            properties.widget = jqBol('#widget').val();

            jqBol.post(url, properties, function(response){
                if (response == 'success') {
                    jqBol("#save-result").html(i10n.changessaved);
                } else {
                    alert(i10n.savingerror);
                }
            })
        } else {
            var content = '[bol_search_form';
            for (var i in properties) {
                content += ' ' + i + '="' + properties[i] + '"';
            }
            content += ']\n';

            setTimeout(function(){
                tinyMCEPopup.execCommand("mceInsertContent", false, content);
                tinyMCEPopup.close();}, 500);
        }
    },

    updateProduct : function( id ) {
    },

    getProductPreview : function() {

        var properties = BolSearchDialog._getProperties();

        if (properties.limit > 25 || properties.limit < 1)
        {
            alert(i10n.chooselimit)
            return false;
        }

        jqBol('#bol_previewParent').html('<span class="loader">' + i10n.loadpreview + '</span><div id="' + properties.block_id + '"></div>');

        jqBol.ajax({
            url: bol_partner_plugin_base + '/src/ajax/bol-search-form-widget.php',
            type: 'post',
            data: properties,
            success: function(response) {
                jqBol('#bol_previewParent').html(response);

                // Make sure the correct fields are hidden
                for(var key in properties) {
                    var value = properties[key];
                    if (key == 'show_bol_logo' && properties[key] == 0) {
                        jqBol('.BolWidgetLogo').toggleClass('hide');
                    }
                    if (key == 'show_rating' && properties[key] == 0) {
                        jqBol('span.rating').toggleClass('hide');
                    }
                    if (key == 'show_price' && properties[key] == 0) {
                        jqBol('.bol_pml_price').toggleClass('hide');
                    }
                    if (key == 'show_deliverytime' && properties[key] == 0) {
                        jqBol('.bol_available').toggleClass('hide');
                    }
                }
            }
        })
    },

    _getProperties : function()
    {
        var properties = {
            limit           : jqBol('#txtLimit').val(),
            block_id        : jqBol('#blockId').val(),
            cat_id          : BolSearchDialog._getSelectedCategory(),
            cat_select      : jqBol('input[name="rbShowCat"]:checked').val(),
            default_search  : jqBol('#txtSearch').val()
        };

        for (var i in BolSearchDialog.properties) {
            var id = '.property[name="' + i + '"]';

            var type = jqBol(id).attr('type');

            var val;
            if (type == 'checkbox') {
                val = jqBol(id).attr('checked') ? 1 : 0;
            } else if (type == 'radio') {
                val = jqBol(id + ':checked').val();
            } else {
                val = jqBol(id).val();
            }

            properties[BolSearchDialog.properties[i]] = val;
        }

        // Add extra property so on the server side we can identify this was a preview call
        properties['admin_preview'] = 1;

        return properties;
    },

    _getSelectedCategory : function()
    {
        return jqBol('#ddlBolCategory').val();
    }
};

if (typeof(tinyMCEPopup) !== 'undefined') {
    tinyMCEPopup.requireLangPack();
    tinyMCEPopup.onInit.add(BolSearchDialog.init, BolSearchDialog);
} else {
    jqBol(document).ready(BolSearchDialog.init);
}
