defer(function() {
    jQuery(document).ready(function($) {
        if (!('PaginatorWidget' in window)) {
            jQuery(document)
                .on('click', '.modx-evo-lk-admin .lk-module-pagination > span', function(event) {
                    event.preventDefault();

                    var methodName = jQuery(this).parent('.lk-module-pagination').attr('rel-method');
                    var tabName = jQuery(this).parent('.lk-module-pagination').attr('rel-tab');
                    var data = {
                        page: parseInt(jQuery(this).attr('rel-page')),
                        dataId: parseInt(jQuery(this).parent('.lk-module-pagination').attr('rel-id'))
                    };
                    var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);

                    moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
                });

            PaginatorWidget = true;
        }
    });
});

function defer(method) {
    if (moduleObject && window.jQuery) {
        method();
    } else {
        setTimeout(function() {
            defer(method)
        }, 50);
    }
}