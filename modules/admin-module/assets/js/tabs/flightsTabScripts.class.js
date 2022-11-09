class FlightsTabScripts {
    constructor() {
        this.initVoyagesFilterHook();
    }

    initVoyagesFilterHook() {
        jQuery(document)
            // Применить фильтр рейсов
            .on('click', '#ol-voyageApply', function() {
                var tabName = jQuery(this).attr('rel-tab');
                var methodName = jQuery(this).attr('rel-tab-method');
                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);
                var data = {
                    voyageId: jQuery(document).find('#ol-voyageId').val(),
                    voyageDate: jQuery(document).find('#ol-voyageDate').val(),
                    voyageTourId: jQuery(document).find('#ol-voyageTourId').val(),
                    voyageTourName: jQuery(document).find('#ol-voyageTourName').val(),
                    voyageHotelId: jQuery(document).find('#ol-voyageHotelId').val(),
                    voyageHotelName: jQuery(document).find('#ol-voyageHotelName').val(),
                    voyageCities: jQuery(document).find('#ol-voyageCities').val(),
                    voyageDirection: jQuery(document).find('select#ol-voyageDirection').children('option:selected').val(),
                };

                moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
            })
            // Сохранение таба рейса
            .on('click', '.lk-module-voyage-save-button', function() {
                // пока ничего
            })
            // Страница печати схемы рейса
            .on('click', '#actions .lk-module-voyage-print-button', function() {
                var tabName = moduleObject.getMainDomObject().find('#voyage-item').attr('rel-tab');
                var methodName = jQuery(this).attr('rel-method');
                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);
                var data = {
                    item_id: moduleObject.getMainDomObject().find('#voyage-item').attr('rel-item-id'),
                }

                moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
            })
            .on('click', '#voyagesList .lk-module-voyage-print-button', function() {
                var tabName = jQuery(this).attr('rel-tab');
                var methodName = jQuery(this).attr('rel-tab-method');
                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);
                var data = {
                    item_id: jQuery(this).attr('rel-item-id'),
                }

                moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
            })
            // Кнопка печати схемы
            .on('click', '.lk-module-voyage-send-page-to-print-button', function(event) {
                event.preventDefault();

                window.print();
            });
    }
}