let PopUp = {
    show: function() {
        let PopUp = this;
        
        if (PopUp.checkActive()) {
            let popUpData = PopUp.getData('/lk/api/popup/data');
            let appearingTime = parseInt(popUpData.appearingTime);
    
            setTimeout(function() {
                PopUp.open(popUpData);
        
                let closingTime = parseInt(popUpData.closingTime);
                let popUpConteiner = jQuery(document).find('div').find('#layout-overlay');
                let inactiveState = true;
        
                if (popUpConteiner.length) {
                    jQuery(popUpConteiner).find('.popup-close').click(function() {
                        jQuery(popUpConteiner).addClass('d-none');
                    });
        
                    jQuery(document).bind('mousemove keydown scroll', function() {
                        inactiveState = false;
                    });
                
                    setTimeout(function() {
                        if (inactiveState) {
                            if (PopUp.checkActive()) {
                                PopUp.close(popUpConteiner);
                            }
                        }
                    }, closingTime);
                }
            }, appearingTime);
        }
    },

    checkActive: function() {
        let PopUp = this;

        let data = PopUp.getData('/lk/api/popup');

        if (data !== undefined) {
            if (data.active) {
                return true;
            }
        }

        return false;
    },

    getData: function(url = '', data = {}) {
        let PopUp = this;

        let responseData;

        jQuery.ajax({
            url: url,
            data: data,
            type: 'POST',
            dataType: 'json',
            async: false,
            success: function(response) {
                console.log('Success PopUp getting data: ', response)

                if (response.data !== undefined) {
                    responseData = response.data;
                }
            },
            error: function(response) {
                console.log('Error: PopUp getting data: ', response)
            }
        });

        return responseData;
    },

    open: function(data) {
        let PopUp = this;

        jQuery(document).find('div').first().append(
            PopUp.prepareContent(data)
        );
    },

    close: function(selector) {
        let PopUp = this;

        jQuery(selector).addClass('d-none');
    },

    prepareContent: function(data) {
        let PopUp = this;

        let html = '';

        if (data !== undefined) {
        
            html += '<div id="layout-overlay">';
                html += '<div class="popup-window">';
                    html += '<h2 align="center">' + data.title + '</h2><br><hr>';

                    html += '<p>' + data.text + '</p>';

                    /**
                     * How to use images.
                     */
                    // html += '<div><img class="img-align-center" src=""></div>';
                    
                    html += '<button class="popup-close" title="Закрыть"></button>';
                html += '</div>';
            html += '</div>';
        }

        return html;
    }
};

jQuery(document).ready(function() {
    PopUp.show();
});

