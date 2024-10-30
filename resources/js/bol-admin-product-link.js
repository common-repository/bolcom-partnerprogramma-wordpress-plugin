var productPage = 0;
var productFilterObj = [];

var BolProductDialog = {

    Items : null,
    product : null,

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

        // Fill the categories
        jqBol('#ddlBolCategory').after('<span class="loader" id="categories-loader"></span>');
        jqBol.ajax({
            url: bol_partner_plugin_base + '/src/ajax/bol-search.php?get=categories',
            type: 'post',
            data: {},
            success: function(response) {
                jqBol("#categories-loader").remove();

                // Check the result of the response
                if (response.indexOf('option') != -1) {
                    jqBol("#ddlBolCategory").append(response);
                } else {
                    // Error in the response, add the error
                    jqBol('h4').after(response);
                }
            }
        });

        jqBol('#widthSlider').slider({
            min: 180,
            max: 800,
            value: jqBol('#txtWidth').val(),
            slide: function(event, ui) {
                jqBol('#txtWidth').val(ui.value);
                BolProductDialog.calculateRowsCols();
            },
            stop: BolProductDialog.getProductPreview
        });
        jqBol('#txtWidth').blur(function (event) {
            jqBol('#widthSlider').slider("value", jqBol('#txtWidth').val());
            BolProductDialog.calculateRowsCols();
        });

        jqBol('#colsSlider').slider({
            min: 1,
            max: 2,
            value: jqBol('#txtCols').val(),
            slide: function(event, ui) {
                jqBol('#txtCols').val(ui.value);
            },
            stop: BolProductDialog.getProductPreview
        });
        jqBol('#txtCols').blur(function (event) {
            jqBol('#colsSlider').slider("value", jqBol('#txtCols').val());
            BolProductDialog.calculateRowsCols();
        });

        BolProductDialog.calculateRowsCols();
        BolProductDialog.initStyleUpdater();

        jqBol( "#tabs-container" ).bind( "tabsselect", function(event, ui) {
            if (ui.tab.hash == '#tab-widget') {
                BolProductDialog.getProductPreview();
            }
        });

        // Init the tabs
        jqBol('#tabs-container').tabs().fadeIn(300);

        // Disable the second tab by default
        jqBol('#tabs-container').tabs("disable", 1);

        // Attach event to the 'next-step' button and disable it by default
        jqBol('#next-step').click(function(){
            jqBol('#tabs-container').tabs("select", 1);
			//jqBol('#tabs-container').tabs( "option", "active", 1 ); //sitch with above row if jquery upgrade
        }).attr("disabled", "disabled").addClass("disabled");

        jqBol('.bol_pml_box .pager .pagerLink').live('click', BolProductDialog.page);

        // Attach event handlers to search button and preview button
        jqBol('#apply-search').click(BolProductDialog.getProductSearch);
        jqBol('#txtBolSearch').keypress(function(event) {
            if ( event.which == 13 ) {
                event.preventDefault();
                BolProductDialog.getProductSearch(event);
            }
        });
        jqBol('#apply-preview').click(BolProductDialog.getProductPreview);

        jqBol('.triggerPreview').change(BolProductDialog.getProductPreview);

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
            BolProductDialog.getProductPreview
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
            /*jqBol('#colsSlider').slider('max', 1);*/
            jqBol('#colsDisplay').html(1);
            jqBol('#txtCols').val(1);
            return;
        }

        jqBol('#colsSlider').slider('enable');
        /*jqBol('#colsSlider').slider('option', 'max', cols);*/

        var currentColValue = jqBol('#txtCols').val();
        if (currentColValue > cols) {
            jqBol('#colsDisplay').html(cols);
            jqBol('#txtCols').val(cols);
        } else {
            jqBol('#colsSlider').slider('value', currentColValue);
        }
    },

    insert : function(widget) {

        var properties = BolProductDialog._getProperties();

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
            var content = '[bol_product_links';
            for (var i in properties) {
                content += ' ' + i + '="' + properties[i] + '"';
            }
            content += ']\n';

            setTimeout(function(){
                tinyMCEPopup.execCommand("mceInsertContent", false, content);
                tinyMCEPopup.close();}, 500);
        }

    },

    /**
     * Collects the products associated with the search params
     * @return {Boolean}
     */
    getProductSearch : function(page) {

        var page = page > 0 ? page : 1;

        if (jqBol('#txtBolSearch').val() == '') {
            alert(i10n.requiresearchword);
            return false;
        }

        jqBol('#dvResults').html('<span class="loader">' + i10n.productsareloaded + '</span>');

        jqBol.ajax({
            url: bol_partner_plugin_base + '/src/ajax/bol-search.php',
            type: 'post',
            data: {
                'text'      : jqBol("#txtBolSearch").val(),
                'category'  : jqBol("#ddlBolCategory").val(),
                'page'      : page
            },
            success: function(response) {
                jqBol("#dvResults").html(response);
                if (jqBol("#dvResults div.bol_pml_element").size() > 0) {
                    var inputValue = jqBol("#hdnBolProducts").val();
                    var productArray = inputValue.length ? inputValue.split(",") : [];
                    jqBol("#dvResults div.bol_pml_element").each(function() {
                        productId = jqBol(this).attr('rel');

                        var exists = false;
                        for(var i = 0; i < productArray.length; i++) {
                            if (productArray[i] == productId) {
                                exists = true;
                            };
                        }

                        if (exists) {
                            jqBol(this).css('opacity', '0.3');
                        }

                        var toggle = jqBol('<a href="#' + productId + '" title="Select product" class="toggle-product-icon"></a>');
                        toggle.click(BolProductDialog.toggleProduct);
                        jqBol(this).append(toggle);
                    });
                } else {
                    jqBol("#dvResults").html('<div class="productlist">' + response + '</div>');
                }
            }
        });

        return false;
    },

    page : function(event)
    {
        event.preventDefault();
        var page = (jqBol(event.target).attr('href').substr(1));

        BolProductDialog.getProductSearch(page);
        return false;
    },

    /**
     * Moves the product from result to selection list and back
     * @param event
     */
    toggleProduct : function(event) {
        event.preventDefault();

        id = jqBol(this).attr('href').substr(1);

        var inputValue = jqBol("#hdnBolProducts").val();

        var productArray = inputValue.length ? inputValue.split(",") : [];
        var inSelected = false;

        // Find if it already exists in selected
        for (var i in productArray) {
            if (productArray[i] == id) {
                inSelected = true;
                break;
            }
        }

        var parent = jqBol(this).parents('#dvResults');

        if (inSelected && parent.length > 0) {
            return false;
        }

        var product = jqBol("#dvResults .bol_pml_element[rel="+id+"]");

        if (inSelected) { // in selected -> remove
            productArray.splice(i, 1);
            jqBol("#dvSelectedProducts .bol_pml_element[rel="+id+"]").next('.spacer').remove();
            jqBol("#dvSelectedProducts .bol_pml_element[rel="+id+"]").remove();
            if (product) {
                product.css('opacity', '1');
            }
        } else { // if not in selected array -> add
            productArray.push(id);
            var selectedProduct = product.clone(true);
            selectedProduct.find('a.toggle-product-icon').attr('title', 'Remove product');
            jqBol("#dvSelectedProducts .productlist .bol_pml_box_inner").append(selectedProduct);
            jqBol("#dvSelectedProducts .productlist .bol_pml_box_inner").append('<div class="clearer spacer"></div>');
            product.css('opacity', '0.3');
        }

        var productList = productArray.join(',');

        jqBol("#hdnBolProducts").val(productList);

        // Check if any product is selected
        if (productArray.length > 0) {
            jqBol('#tabs-container').tabs("enable", 1);
            jqBol('#next-step').removeAttr("disabled").removeClass("disabled");
            jqBol('#no-products-label').hide();
            jqBol('#selected-products-label').show();
        } else {
            jqBol('#no-products-label').show();
            jqBol('#selected-products-label').hide();
            jqBol('#tabs-container').tabs("disable", 1);
            jqBol('#next-step').attr("disabled", "disabled").addClass("disabled");
        }
    },

    getProductPreview : function() {

        var properties = BolProductDialog._getProperties();

        if (properties.limit > 25 || properties.limit < 1)
        {
            alert('Kies een limiet van 1-25');
            return false;
        }

        jqBol('#bol_previewParent').html('<span class="loader">' + i10n.loadpreview + '</span><div id="' + properties.block_id + '"></div>');

        jqBol.ajax({
            url: bol_partner_plugin_base + '/src/ajax/bol-products-widget.php',
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
            block_id    : jqBol('#blockId').val(),
            products    : jqBol('#hdnBolProducts').val()
        };

        for (var i in BolProductDialog.properties) {

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

            properties[BolProductDialog.properties[i]] = val;
        }

        // Add extra property so on the server side we can identify this was a preview call
        properties['admin_preview'] = 1;

        return properties;
    }
};

var bol_Pager = {
    productPage : 0,
    perPage : 5,

    //setup arrow and paginations
    setupPager : function(qvan, perPage) {

        bol_Pager.perPage = perPage;

        if (qvan <= QVAN) {
            jqBol(".pager ul").html("<li class='current'>1</li>");
            jqBol(".pager .amount").html(qvan);
            return;
        }

        bol_Pager.productPage = 0;

        var list = "";
        var pager = '<li><span title="vorige" class="previous">vorige</span></li>'+
            '<li><span title=" volgende" class="next"> volgende</span></li>';

        for (var i = 0; i < Math.ceil(qvan / perPage); i++) {
            list += "<li><span>" + (i + 1) + "</span></li>";
        }
        list += pager;

        jqBol(".pager ul").html(list);
        jqBol(".pager .amount").html(qvan);

        jqBol(".pager li").each(function() {
            jqBol(this).click(function() {
                bol_Pager.showProducts(jqBol("span", this).html());
            });
        });
    },

    showProducts : function(str) {

        //switch in paginations
        var pager = bol_Pager.productPage;
        switch (str) {
            case "next":
                pager++;
                break;

            case "prev":
                pager--;
                break;

            default:
                bol_Pager.productPage = Number(str) - 1;

        }

        bol_Pager.productPage = pager;

        //if first start add items to productFilterObj
        if (productFilterObj.length == 0) {
            jqBol("#dvResults .productlist li").each(function(index) {
                productFilterObj.push(jqBol(this));
            });
        }


        var pages = Math.ceil(productFilterObj.length / bol_Pager.perPage);
        var next = jqBol(".pager .next");
        var prev = jqBol(".pager .previous");

        //disable arrow button
        if (bol_Pager.productPage < 0) {
            bol_Pager.productPage = 0;
            return;
        }
        if (bol_Pager.productPage > pages - 1) {
            bol_Pager.productPage = pages - 1;
            return;
        }

        //hide all items and remove bottom line
        jqBol("#dvResults .productlist li").hide();
        jqBol("#dvResults .productlist li").removeClass("line");

        //show items by current page
        for (var i = 0; i<productFilterObj.length; i++) {
            if (i >= bol_Pager.productPage * bol_Pager.perPage && i < (bol_Pager.productPage + 1) * bol_Pager.perPage) {
                jqBol(productFilterObj[i]).show();
            }
        }


        //add line on bottom for evrey 3 items in visible elements
        var visible = jqBol("#dvResults .productlist li:visible");
        var stratIndex = 0;
        var currentIndex = 2;

        while (currentIndex < visible.length) {
            if (currentIndex != visible.length - 1) {
                for (var i = stratIndex; i <= currentIndex; i++) {
                    jqBol(visible[i]).addClass("line");
                }
            }
            stratIndex = currentIndex + 1;
            currentIndex = currentIndex + 3;
        }


        //show/hide arrow buttons
        next.each(function() {
            jqBol(this).addClass("disable");
        });
        prev.each(function() {
            jqBol(this).addClass("disable");
        });

        if (bol_Pager.productPage < pages - 1) {
            next.removeClass("disable");
        }
        if (bol_Pager.productPage > 0) {
            prev.removeClass("disable");
        }

        //select current page
        jqBol(".pages").each(function() {
            jqBol("li", this).each(function(index) {
                jqBol(this).removeClass("current");
                if (index == bol_Pager.productPage) {
                    jqBol(this).addClass("current");
                }
            });
        });
    }
}

jqBol( document ).ready(function() {
	if (typeof(tinyMCEPopup) !== 'undefined') {
		tinyMCEPopup.requireLangPack();
		tinyMCEPopup.onInit.add(BolProductDialog.init, BolProductDialog);
	} else {
		jqBol(document).ready(BolProductDialog.init);
	}
});
