customsOrdersTabListScriptDefer(function() {
    jQuery(document).ready(function($) {
        jQuery(document).on('click', '.toggleTouristsRow', function(e) {
            let id = jQuery(e.target).closest('tr').data('id');
            let tr = jQuery('#orderDetailsRow-' + id);

            jQuery('.ol-orderDetails').not(tr).addClass('d-none');

            tr.toggleClass('d-none');
        });
    });
});

function customsOrdersTabListScriptDefer(method) {
    if (window.jQuery) {
        method();
    } else {
        setTimeout(function() {
            defer(method)
        }, 50);
    }
}