jQuery(document).ready(function() {
    jQuery(document).find('#order #form form').submit(function(event) {
        event.preventDefault();

        var action = jQuery(this).attr("action");
        var method = jQuery(this).attr("method");

        var data = {
            comment: jQuery(this).find("textarea[name=comment]").val(),
            price: parseFloat(jQuery(this).find("input[name=price]").val()),
            tourId: parseInt(jQuery(this).find("input[name=tourId]").val()),
        };

        jQuery.ajax({
            url: action,
            data: data,
            type: method,
            dataType: "json",
            async: false,
            success: function (response) {
                console.log(data);
                console.log(response);

                jQuery(document).find('#order #form').addClass('d-none').removeClass('d-block');

                if (response.status = 'success') {
                    jQuery(document).find('#order #success').find('a').attr('href', response.data.link);

                    jQuery(document).find('#order #fail').addClass('d-none').removeClass('d-block');
                    jQuery(document).find('#order #success').addClass('d-block').removeClass('d-none');
                } else {
                    jQuery(document).find('#order #success').find('a').attr('href', '');

                    jQuery(document).find('#order #success').addClass('d-none').removeClass('d-block');
                    jQuery(document).find('#order #fail').addClass('d-block').removeClass('d-none');
                }
            },
        });

        return false;
    });
});