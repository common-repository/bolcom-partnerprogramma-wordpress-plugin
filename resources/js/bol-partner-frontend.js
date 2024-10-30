var BolPartner = {
    SelectedProducts : {
      fillPlaceHolders : function(data)
      {
          var url = bol_partner_plugin_base + '/src/ajax/bol-products-widget.php';
          for (var id in data) {
              var element = jQuery('#' + id);
              element.load(url, data[id]);
          }
      }
    },
    Bestsellers : {
        fillPlaceHolders : function(data)
        {
            var url = bol_partner_plugin_base + '/src/ajax/bol-bestsellers-widget.php';
            for (var id in data) {
                var element = jQuery('#' + id);
                element.load(url, data[id]);
            }
        }
    },
    SearchForm : {

        data : null,

        init : function(data)
        {
            BolPartner.SearchForm.data = data;
            jQuery('.bol_pml_box button.searchButton').live('click', BolPartner.SearchForm.search);
            jQuery('.bol_pml_box .pager .pagerLink').live('click', BolPartner.SearchForm.page);
            jQuery('.bol_pml_box select.catSelect').live('change', BolPartner.SearchForm.select);
        },
        fillPlaceHolders : function(data)
        {
            var url = bol_partner_plugin_base + '/src/ajax/bol-search-form-widget.php';
            for (var id in data) {
                var element = jQuery('#' + id);
                element.load(url, data[id]);
            }
        },
        search : function(event)
        {
            var id = jQuery(event.target).parents('.bol_pml_box').attr('id').substr(1);
            var data = {};
            data[id] = BolPartner.SearchForm.data[id];

            var search = jQuery(event.target).prev('input').val();

            data[id].default_search = search;
            BolPartner.SearchForm.fillPlaceHolders(data);
        },
        page : function(event)
        {
            event.preventDefault();
            var id = jQuery(event.target).parents('.bol_pml_box').attr('id').substr(1);
            var data = {};
            data[id] = BolPartner.SearchForm.data[id];

            var offset = (jQuery(event.target).attr('href').substr(1) - 1) * data[id].limit;

            data[id].offset = offset;
            BolPartner.SearchForm.fillPlaceHolders(data);
            return false;
        },
        select : function(event)
        {
            var id = jQuery(event.target).parents('.bol_pml_box').attr('id').substr(1);
            var data = {};
            data[id] = BolPartner.SearchForm.data[id];

            var catId = jQuery(event.target).val();

            data[id].cat_id = catId;
            BolPartner.SearchForm.fillPlaceHolders(data);
        }
    }

};
