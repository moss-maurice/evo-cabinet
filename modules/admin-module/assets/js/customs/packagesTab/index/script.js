customsPackagesTabIndexScriptDefer(function() {
    jQuery(document).ready(function($) {
        jQuery(document).on('click', '.exchange-csv-export', function(e) {
            var tabName = jQuery(this).attr('rel-tab');
            var methodName = jQuery(this).attr('rel-method');

            moduleObject.getAjaxContent(tabName, methodName, {}, function(response) {
                let blob = new Blob([response], {
                    type: 'application/csv; charset=UTF-8'
                });

                let link = document.createElement('a');

                link.setAttribute('href', URL.createObjectURL(blob));
                link.setAttribute('download', 'Прайс-лист.csv');
                link.click();
            });
        });

        jQuery(document).find('.exchange-csv-import').change(function(e) {
            if (jQuery(document).find('form#exchange-csv-import').length > 0) {
                jQuery(document).find('form#exchange-csv-import').each(function(i) {
                    var formData = new FormData(this);

                    moduleObject.getAjaxContent(null, 'index', formData, function(response) {
                        jQuery(document).find('input[name=file]').val('');
                    }, function(response) {
                        jQuery(document).find('input[name=file]').val('');
                    });
                });
            }
        });
    });
});

function customsPackagesTabIndexScriptDefer(method) {
    if (window.jQuery) {
        method();
    } else {
        setTimeout(function() {
            defer(method)
        }, 50);
    }
}