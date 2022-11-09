defer(function() {
    jQuery(document).ready(function($) {
        if (!('SortChevronWidget' in window)) {
            jQuery(document)
                .on('click', '.order-control', function() {
                    var tabName = jQuery(this).attr('rel-tab');
                    var methodName = jQuery(this).attr('rel-tab-method');
                    var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);
                    var data = {
                        page: parseInt(jQuery(this).attr('rel-page')),
                        field: jQuery(this).attr('rel-sort-field'),
                        direction: jQuery(this).attr('rel-sort-direction'),
                    };

                    moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
                });

            SortChevronWidget = true;
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