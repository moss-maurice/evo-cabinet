class OrdersTabScripts {
    constructor() {
        this.initOrderSearchHook();
        this.initOrderListHook();
        this.initOrderSaveButtonsHook();
        this.initOrderPersonSaveButtonsHook();
        this.initOrderPersonsHotelRoomSaveButtonsHook();
        this.initOrderPersonsBusPlaceSaveButtonsHook();
        this.initOrderMailsTestHook();
    }

    initOrderSearchHook() {
        jQuery(document)
            .on('change', '#ol-searchStatus', function(e) {
                var tabName = jQuery(this).attr('rel-tab');
                var methodName = jQuery(this).attr('rel-tab-method');
                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);
                var data = {
                    status: jQuery(this).find('option:selected').val(),
                };

                moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
            })
            .on('click', '#ol-searchApply', function() {
                var tabName = jQuery(this).attr('rel-tab');
                var methodName = jQuery(this).attr('rel-tab-method');
                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);
                var data = {
                    item_id: jQuery(document).find('#ol-searchId').val(),
                    agency: jQuery(document).find('#ol-searchAgency').val(),
                    client: jQuery(document).find('#ol-searchClient').val(),
                    tour: jQuery(document).find('#ol-searchTour').val(),
                    tourDate: jQuery(document).find('#ol-searchOrderDate').val(),
                    hotel: jQuery(document).find('#ol-searchHotel').val(),
                    login: jQuery(document).find('#ol-searchLogin').val(),
                    email: jQuery(document).find('#ol-searchEmail').val(),
                    phone: jQuery(document).find('#ol-searchPhone').val(),
                    tourVoyageOutDate: jQuery(document).find('#ol-searchTourVoyageOutDate').val(),
                    tourVoyageInDate: jQuery(document).find('#ol-searchTourVoyageInDate').val(),
                };

                moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
            })
            .on('click', '#ol-searchClear', function() {
                var tabName = jQuery(this).attr('rel-tab');
                var methodName = jQuery(this).attr('rel-tab-method');
                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);
                var data = {};

                moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
            });
    }

    initOrderListHook() {
        jQuery(document)
            .on('click', '.removeOrder', function() {
                var tabName = jQuery(this).attr('rel-tab');
                var methodName = jQuery(this).attr('rel-tab-method');
                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);
                var data = {
                    orderId: parseInt(jQuery(this).parents('tr').attr('data-id')),
                }

                moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
            });
    }

    initOrderSaveButtonsHook() {
        jQuery(document)
            .on('input', '#room-number', function(event) {
                jQuery(this).closest('tr').find('span').removeClass('d-none');

                if (jQuery(this).val().length <= 0) {
                    jQuery(this).closest('tr').find('span').addClass('d-none');
                }
            })
            .on('click', '.lk-module-order-save-button', function() {
                var tabName = moduleObject.getMainDomObject().find('#order-item').attr('rel-tab');
                var methodName = moduleObject.getMainDomObject().find('#order-item').attr('rel-method');

                var data = {
                    item_id: moduleObject.getMainDomObject().find('#order-item').attr('rel-item-id'),
                    user_id: moduleObject.getMainDomObject().find('input#user-id').val(),
                    status_id: moduleObject.getMainDomObject().find('#order-item').find('select#status-id').children('option:selected').val(),
                }

                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);

                moduleObject.setTabContent(pageDomObject, tabName, methodName, data, false);
            })
            .on('click', '.lk-module-button-save-hroom-number', function(event) {
                event.preventDefault();

                var tabName = moduleObject.getMainDomObject().find('#order-item').attr('rel-tab');
                var methodName = moduleObject.getMainDomObject().find('#order-item').attr('rel-method');
                var data = {
                    item_id: moduleObject.getMainDomObject().find('#order-item').attr('rel-item-id'),
                    user_id: moduleObject.getMainDomObject().find('input#user-id').val(),
                    status_id: moduleObject.getMainDomObject().find('#order-item').find('select#status-id').children('option:selected').val(),
                    roomNumber: moduleObject.getMainDomObject().find('#room-number').val()
                }
                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);

                moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
            })
            .on('click', '.lk-module-transaction-add-button', function() {
                if (moduleObject.getMainDomObject().find('input#transaction-amount').val() == '') {
                    moduleObject.getMainDomObject().find('input#transaction-amount').addClass('border-outline-danger');

                    return false;
                }

                var tabName = jQuery(this).attr('rel-tab');
                var methodName = jQuery(this).attr('rel-method');

                var data = {
                    item_id: jQuery(this).attr('rel-order-id'),
                    comment: moduleObject.getMainDomObject().find('input#transaction-description').val(),
                    transactionValue: moduleObject.getMainDomObject().find('input#transaction-amount').val(),
                }

                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);

                moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
            })
            .on('change', '#transaction-amount', function() {
                jQuery(this).removeClass('border-outline-danger');
            })
            .on('click', '.lk-module-transaction-delete-button', function() {
                var tabName = jQuery(this).attr('rel-tab');
                var methodName = jQuery(this).attr('rel-method');

                var data = {
                    item_id: jQuery(this).attr('rel-order-id'),
                    paymentId: jQuery(this).attr('rel-item-id'),
                }

                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);

                moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
            });

        return false;
    }

    initOrderPersonsHotelRoomSaveButtonsHook() {
        jQuery(document)
            .on('input', '.modx-evo-lk-admin .guests-table .hotel-room', function(event) {
                event.preventDefault();

                jQuery(this).closest('td').find('span').removeClass('d-none');

                if (jQuery(this).val().length <= 0) {
                    jQuery(this).closest('td').find('span').addClass('d-none');
                }
            })
            .on('click', '.modx-evo-lk-admin .lk-module-button-save-hotel-room', function(event) {
                event.preventDefault();

                var tabName = jQuery(this).attr('rel-tab');
                var methodName = jQuery(this).attr('rel-method');
                var data = {
                    item_id: jQuery(this).attr('rel-order-id'),
                    touristId: jQuery(this).attr('rel-item-id'),
                    touristHotelRoom: jQuery(this).closest('td').find('.hotel-room').val()
                };
                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);

                moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
            });

        return false;
    }

    initOrderPersonsBusPlaceSaveButtonsHook() {
        jQuery(document)
            .on('input', '.modx-evo-lk-admin .guests-table .bus-place', function(event) {
                event.preventDefault();

                jQuery(this).closest('td').find('span').removeClass('d-none');

                if (jQuery(this).val().length <= 0) {
                    jQuery(this).closest('td').find('span').addClass('d-none');
                }
            })
            .on('click', '.modx-evo-lk-admin .lk-module-button-save-bus-place', function(event) {
                event.preventDefault();

                var tabName = jQuery(this).attr('rel-tab');
                var methodName = jQuery(this).attr('rel-method');
                var data = {
                    orderId: jQuery(this).attr('rel-order-id'),
                    item_id: jQuery(this).attr('rel-order-id'),
                    personId: jQuery(this).attr('rel-item-id'),
                    placeId: jQuery(this).closest('td').find('.bus-place').find('option:selected').val(),
                    voyageId: jQuery(this).attr('rel-voyage-id'),
                };
                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);

                moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
            });

        return false;
    }

    initOrderPersonSaveButtonsHook() {
        jQuery(document)
            .on('click', '.modx-evo-lk-admin #actions .lk-module-person-save-button', function(event) {
                event.preventDefault();

                var tabName = moduleObject.getMainDomObject().find('#order-item').attr('rel-tab');
                var methodName = moduleObject.getMainDomObject().find('#order-item').attr('rel-method');
                var data = {
                    item_id: moduleObject.getMainDomObject().find('#order-item').attr('rel-item-id'),
                    order_id: moduleObject.getMainDomObject().find('#order-item').attr('rel-order-id'),
                    surname: moduleObject.getMainDomObject().find('#order-item').find('input#surname').val(),
                    name: moduleObject.getMainDomObject().find('#order-item').find('input#name').val(),
                    middlename: moduleObject.getMainDomObject().find('#order-item').find('input#middlename').val(),
                    date: moduleObject.getMainDomObject().find('#order-item').find('input#date').val(),
                    document: moduleObject.getMainDomObject().find('#order-item').find('input#document').val(),
                    phone: moduleObject.getMainDomObject().find('#order-item').find('input#phone').val(),
                    email: moduleObject.getMainDomObject().find('#order-item').find('input#email').val(),
                    comment: moduleObject.getMainDomObject().find('#order-item').find('textarea#comment').val(),
                }
                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);

                moduleObject.setTabContent(pageDomObject, tabName, methodName, data, false);
            });

        return false;
    }

    initOrderMailsTestHook() {
        jQuery(document)
            .on('click', '.modx-evo-lk-admin .lk-module-send-mails-button', function(event) {
                event.preventDefault();

                var tabName = jQuery(this).attr('rel-tab');
                var methodName = jQuery(this).attr('rel-method');
                var data = {
                    item_id: jQuery(this).attr('rel-item-id'),
                };
                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);

                moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
            });

        return false;
    }
}