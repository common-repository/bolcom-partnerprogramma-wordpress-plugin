(function(){



    tinymce.PluginManager.add('bolpartnerplugin', function(editor, url) {

        editor.bolpartnerpluginUrl = url + '/../../../';

        editor.addCommand('BolCom.product-link', function(ui, v) {
            var script = editor.bolpartnerpluginUrl + 'src/ajax/popup/product-link.php';
            editor.windowManager.open(
                {
                    file        : script,
                    width       : 960,
                    height      : 500,
                    inline      : 1,
                    auto_focus  : 0
                }
            );
        });
        editor.addCommand('BolCom.bestsellers', function(ui, v) {
            var script = editor.bolpartnerpluginUrl + 'src/ajax/popup/bestsellers.php';
            editor.windowManager.open(
                {
                    file        : script,
                    width       : 960,
                    height      : 500,
                    inline      : 1,
                    auto_focus  : 0
                }
            );
        });
        editor.addCommand('BolCom.search-form', function(ui, v) {
            var script = editor.bolpartnerpluginUrl + 'src/ajax/popup/search-form.php';
            editor.windowManager.open(
                {
                    file        : script,
                    width       : 960,
                    height      : 500,
                    inline      : 1,
                    auto_focus  : 0
                }
            );
        });

        editor.addButton('bolpartnerplugin', {
            title: 'Bol.com Products/Widgets toevoegen',
            image: editor.bolpartnerpluginUrl + 'resources/images/bol.gif',
            type: 'menubutton',
            menu: [
                {
                    text: 'Productlink',
                    onclick: function () {
                        editor.execCommand('BolCom.product-link');
                    }
                },
                {
                    text: 'Bestsellerslijst',
                    onclick: function () {
                        editor.execCommand('BolCom.bestsellers');
                    }
                },
                {
                    text: 'Zoekwidget',
                    onclick: function () {
                        editor.execCommand('BolCom.search-form');
                    }
                }
            ]
        });
    });
})();
