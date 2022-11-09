class UsersTabScripts {
    constructor() {
        this.initUserFilterHook();

        this.initUserChangeStatusButtonsHook();
        //this.initUserChangeStatusProfileButtonsHook();

        this.initProfileSaveButtonsHook();
        this.initProfileChangeRoleButtonsHook();
    }

    buildFieldsData(object) {
        var data = {
            item_id: object.find('#user-item').attr('rel-item-id'),
            lastName: object.find('#user-item').find('input[name=last-name]').val(),
            firstName: object.find('#user-item').find('input[name=first-name]').val(),
            middleName: object.find('#user-item').find('input[name=middle-name]').val(),
            dob: object.find('#user-item').find('input[name=dob]').val(),
            phone: object.find('#user-item').find('input[name=phone]').val(),
            mobilephone: object.find('#user-item').find('input[name=mobilephone]').val(),
            email: object.find('#user-item').find('input[name=email]').val(),
            fax: object.find('#user-item').find('input[name=fax]').val(),
            sale: object.find('#user-item').find('input[name=sale]').val(),
            agencyStatus: false,
        }

        if (object.find('#user-item').find('.lk-module-user-change-status-button').length > 0) {
            var agencyStatus = 'false';

            if (object.find('#user-item').find('.lk-module-user-change-status-button').hasClass('btn-success')) {
                agencyStatus = 'true';
            }

            data.agencyStatus = agencyStatus;
        }

        if (object.find('#user-item').find('select[name=country]').length > 0) {
            data.country = object.find('#user-item').find('select[name=country]').children('option:selected').val();
        }

        if (object.find('#user-item').find('input[name=state]').length > 0) {
            data.state = object.find('#user-item').find('input[name=state]').val();
        }

        if (object.find('#user-item').find('input[name=city]').length > 0) {
            data.city = object.find('#user-item').find('input[name=city]').val();
        }

        if (object.find('#user-item').find('input[name=street]').length > 0) {
            data.street = object.find('#user-item').find('input[name=street]').val();
        }

        if (object.find('#user-item').find('input[name=password]').length > 0) {
            data.password = object.find('#user-item').find('input[name=password]').val();
        }

        if (object.find('#user-item').find('input[name=agency]').length > 0) {
            data.agency = object.find('#user-item').find('input[name=agency]').val();
        }

        if (object.find('#user-item').find('input[name=comission]').length > 0) {
            data.comission = object.find('#user-item').find('input[name=comission]').val();
        }

        if (object.find('#user-item').find('input[name=comission-chld]').length > 0) {
            data.comissionChld = object.find('#user-item').find('input[name=comission-chld]').val();
        }

        if (object.find('#user-item').find('input[name=agency-zip]').length > 0) {
            data.agencyZip = object.find('#user-item').find('input[name=agency-zip]').val();
        }

        if (object.find('#user-item').find('select[name=agency-country]').length > 0) {
            data.agencyCountry = object.find('#user-item').find('select[name=agency-country]').val();
        }

        if (object.find('#user-item').find('input[name=agency-state]').length > 0) {
            data.agencyState = object.find('#user-item').find('input[name=agency-state]').val();
        }

        if (object.find('#user-item').find('input[name=agency-city]').length > 0) {
            data.agencyCity = object.find('#user-item').find('input[name=agency-city]').val();
        }

        if (object.find('#user-item').find('input[name=agency-street]').length > 0) {
            data.agencyStreet = object.find('#user-item').find('input[name=agency-street]').val();
        }

        if (object.find('#user-item').find('input[name=agency-legal-address]').length > 0) {
            data.agencyLegalAddress = object.find('#user-item').find('input[name=agency-legal-address]').val();
        }

        if (object.find('#user-item').find('input[name=agency-inn]').length > 0) {
            data.agencyInn = object.find('#user-item').find('input[name=agency-inn]').val();
        }

        if (object.find('#user-item').find('input[name=agency-kpp]').length > 0) {
            data.agencyKpp = object.find('#user-item').find('input[name=agency-kpp]').val();
        }

        if (object.find('#user-item').find('input[name=agency-ogrn]').length > 0) {
            data.agencyOgrn = object.find('#user-item').find('input[name=agency-ogrn]').val();
        }

        if (object.find('#user-item').find('input[name=agency-rs]').length > 0) {
            data.agencyRs = object.find('#user-item').find('input[name=agency-rs]').val();
        }

        if (object.find('#user-item').find('input[name=agency-ks]').length > 0) {
            data.agencyKs = object.find('#user-item').find('input[name=agency-ks]').val();
        }

        if (object.find('#user-item').find('input[name=agency-bank]').length > 0) {
            data.agencyBank = object.find('#user-item').find('input[name=agency-bank]').val();
        }

        if (object.find('#user-item').find('input[name=agency-bik]').length > 0) {
            data.agencyBik = object.find('#user-item').find('input[name=agency-bik]').val();
        }

        return data;
    }

    initUserFilterHook() {
        jQuery(document)
            .on('click', '#ol-filterApply, #ol-filterAgency, #ol-filterUser', function() {
                var tabName = jQuery(this).attr('rel-tab');
                var methodName = jQuery(this).attr('rel-tab-method');
                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);
                var data = {
                    roleId: jQuery(this).attr('rel-role-id'),
                    agency: jQuery(document).find('#ol-filterAgencyName').val(),
                    login: jQuery(document).find('#ol-filterLogin').val(),
                    email: jQuery(document).find('#ol-filterEmail').val(),
                    phone: jQuery(document).find('#ol-filterPhone').val(),
                };

                moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
            });
    }

    initUserChangeStatusButtonsHook() {
        jQuery(document)
            .on('click', '.modx-evo-lk-admin #usersList .lk-module-user-change-status-button', function(event) {
                event.preventDefault();

                var tabName = jQuery(this).attr('rel-tab');
                var methodName = jQuery(this).attr('rel-method');
                var agencyStatus = 'true';

                if (jQuery(this).hasClass('btn-success')) {
                    agencyStatus = 'false';
                }

                var data = {
                    item_id: jQuery(this).attr('rel-item-id'),
                    agencyStatus: agencyStatus,
                }

                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);

                moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
            })
            .on('click', '.modx-evo-lk-admin #user-item .lk-module-user-change-status-button', function(event) {
                event.preventDefault();

                var tabName = moduleObject.getMainDomObject().find('#user-item').attr('rel-tab');
                var methodName = jQuery(this).attr('rel-method');
                var agencyStatus = 'true';

                if (jQuery(this).hasClass('btn-success')) {
                    agencyStatus = 'false';
                }

                var data = {
                    item_id: moduleObject.getMainDomObject().find('#user-item').attr('rel-item-id'),
                    agencyStatus: agencyStatus,
                }

                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);

                moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
            });

        return false;
    }

    /*
    initUserChangeStatusProfileButtonsHook() {
        jQuery(document)
            .on('click', '.modx-evo-lk-admin #user-item .lk-module-user-change-status-profile-button', function(event) {
                if (jQuery(this).hasClass('btn-success')) {
                    jQuery(this).removeClass('btn-success').html('Неактивен');
                    jQuery(this).closest('td').find('input').val('');
                } else {
                    jQuery(this).addClass('btn-success').html('Активен');
                    jQuery(this).closest('td').find('input').val('agency');
                }
            });

        return false;
    }
    */

    initProfileSaveButtonsHook() {
        var thisModuleObject = this;

        jQuery(document)
            .on('click', '.modx-evo-lk-admin #actions .lk-module-user-save-button', function(event) {
                event.preventDefault();

                var tabName = moduleObject.getMainDomObject().find('#user-item').attr('rel-tab');
                var methodName = moduleObject.getMainDomObject().find('#user-item').attr('rel-method');
                var data = thisModuleObject.buildFieldsData(moduleObject.getMainDomObject());
                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);

                moduleObject.setTabContent(pageDomObject, tabName, methodName, data, false);
            });

        return false;
    }

    initProfileChangeRoleButtonsHook() {
        var thisModuleObject = this;

        jQuery(document)
            .on('click', '.modx-evo-lk-admin #user-item .lk-module-user-change-role-to-user-profile-button', function(event) {
                event.preventDefault();

                var tabName = moduleObject.getMainDomObject().find('#user-item').attr('rel-tab');
                var methodName = jQuery(this).attr('rel-method');
                var data = thisModuleObject.buildFieldsData(moduleObject.getMainDomObject());

                data.role = 5;
                data.type = 'none';
                data.agencyStatus = false;

                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);

                moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
            })
            .on('click', '.modx-evo-lk-admin #user-item .lk-module-user-change-role-to-agency-profile-button', function(event) {
                event.preventDefault();

                var tabName = moduleObject.getMainDomObject().find('#user-item').attr('rel-tab');
                var methodName = jQuery(this).attr('rel-method');
                var data = thisModuleObject.buildFieldsData(moduleObject.getMainDomObject());

                data.role = 6;
                data.type = 'agency';
                data.agencyStatus = true;

                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);

                moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
            });

        return false;
    }
}