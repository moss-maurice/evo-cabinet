class Module {
    apiUrl = '';
    apiMethod = 'POST';

    constructor() {
        this.apiUrl = apiUrl;
    }

    init() {
        var thisModule = this;

        console.log(startTabName);
        if (startTabName === null) {
            console.log(thisModule.getDomObject().eq(0));
            thisModule.setTabContent(thisModule.getDomObject().eq(0));
        } else {
            console.log(thisModule.getDomObject().filter('#tab_' + startTabName));
            thisModule.setTabContent(thisModule.getDomObject().filter('#tab_' + startTabName), startTabName, startMethodName);
        }

        thisModule.initMainTabHook();
        thisModule.initButtonsHook();
        thisModule.initBackwardButtonHook();
        thisModule.initReloadButtonHook();
        thisModule.initCustomDatePickerCleanButtonHandler();

        //thisModule.initAutocompleteHook();
    }

    loaderOn() {
        jQuery(window.parent.document).find('#mainloader').addClass('show');
    }

    loaderOff() {
        jQuery(window.parent.document).find('#mainloader').removeClass('show');
    }

    getTabName(domObject) {
        return domObject.attr('id').replace(/^tab\_/g, "");
    }

    getDomObject() {
        return this.getMainDomObject().find('.tab-page');
    }

    getMainDomObject() {
        return jQuery(document).find('.modx-evo-lk-admin');
    }

    getAjaxContent(tabName = null, methodName = 'index', properties = {}, successCallback = null, failCallback = null) {
        var thisModule = this;

        var ajaxConfig = {
            type: thisModule.apiMethod,
            url: thisModule.apiUrl,
            data: properties
        };

        if (properties instanceof FormData) {
            tabName = properties.get('tabName');
            methodName = properties.get('methodName');

            ajaxConfig = Object.assign(ajaxConfig, {
                processData: false,
                contentType: false
            });
        } else {
            properties = Object.assign(properties, {
                a: a,
                id: id,
                tabName: tabName,
                method: methodName,
            });
        }

        jQuery.ajax(ajaxConfig)
            .done(function(response) {
                loggerObject.logTrace('Загрузка ajax "' + tabName + '::' + methodName + '"', [
                    thisModule.apiMethod + ': ' + thisModule.apiUrl,
                    properties,
                    {
                        response: response,
                    },
                ]);

                if (Object.prototype.toString.call(successCallback) === '[object Function]') {
                    successCallback(response);
                }

                return true;
            })
            .fail(function(response) {
                loggerObject.logTrace('Ошибка загрузки ajax "' + tabName + '::' + methodName + '"', [
                    thisModule.apiMethod + ': ' + thisModule.apiUrl,
                    properties,
                    {
                        response: response,
                    },
                ]);

                if (Object.prototype.toString.call(failCallback) === '[object Function]') {
                    failCallback(response);
                }
            });
    }

    setTabContent(pageDomObject, tabName = null, methodName = 'index', properties = {}, indexed = true) {
        var thisModule = this;

        jQuery(document).find('.modx-evo-lk-admin .tab-page').hide().html('');

        if (thisModule.getMainDomObject().children('#actions').length > 0) {
            thisModule.getMainDomObject().children('#actions').each(function(index) {
                jQuery(this).fadeOut('fast', function() {
                    jQuery(this).remove();
                });
            });
        }

        thisModule.loaderOn();

        if ((tabName === null) || (tabName === '')) {
            tabName = thisModule.getTabName(pageDomObject);
        }

        var data = {
            a: parseInt(a),
            id: parseInt(id),
            tabName: tabName,
            method: methodName,
        };

        data = Object.assign(properties, data);

        jQuery.ajax({
                type: thisModule.apiMethod,
                url: thisModule.apiUrl,
                data: data
            }).done(function(response) {
                if (methodName && indexed) {
                    bufferObject.set(pageDomObject, tabName, methodName, properties);
                }

                if (jQuery(document).find('.sectionBody > .tab-pane .tab-row > .tab').length > 0) {
                    jQuery(document).find('.sectionBody > .tab-pane .tab-row > .tab').filter('.selected').removeClass('selected');

                    jQuery(document).find('.sectionBody > .tab-pane .tab-row > .tab').each(function(i) {
                        if (jQuery(this).attr('data-target') === '#tab_' + tabName) {
                            jQuery(this).addClass('selected');
                        }
                    });
                }

                loggerObject.logTrace('Загрузка таба "' + tabName + '::' + methodName + '"', [
                    thisModule.apiMethod + ': ' + thisModule.apiUrl,
                    data,
                    {
                        response: response,
                    },
                ]);

                pageDomObject.append(response);

                if (pageDomObject.find('#actions').length > 0) {
                    thisModule.getMainDomObject().prepend(pageDomObject.find('#actions'));

                    pageDomObject.find('#actions').remove();
                }

                thisModule.initDatePicker();
                thisModule.loaderOff();

                pageDomObject.fadeIn('fast');

                return true;
            })
            .fail(function(response) {
                loggerObject.logTrace('Ошибка загрузки таба "' + tabName + '::' + methodName + '"', [
                    thisModule.apiMethod + ': ' + thisModule.apiUrl,
                    data,
                    {
                        response: response,
                    },
                ]);

                var html = '<h4 class="p-4 text-center alert alert-danger">' +
                    '<strong>' +
                    '<i class="fas fa-exclamation-triangle"></i> ' +
                    'При запросе произошла ошибка! ' +
                    (debug ? 'Подробный лог выведен в консоль.' : '') +
                    '</strong>' +
                    '<br />' +
                    '<br />' +
                    '<span class="btn-group">' +
                    '<span class="btn btn-large btn-secondary lk-module-reload-button">Повторить запрос</span>' +
                    '<span class="btn btn-large btn-secondary lk-module-backward-button">Вернуться назад</span>' +
                    '</span>' +
                    '</h4>';

                pageDomObject.append(html);

                if (pageDomObject.find('#actions').length > 0) {
                    thisModule.getMainDomObject().prepend(pageDomObject.find('#actions'));

                    pageDomObject.find('#actions').remove();
                }

                thisModule.loaderOff();

                pageDomObject.fadeIn('fast');
            });
    }

    initDatePicker() {
        var dpOffset = -10;
        var dpformat = 'dd-mm-YYYY';
        var dpdayNames = ['Воскресенье', 'Понедельник', 'Вторник', 'Среда', 'Четверг', 'Пятница', 'Суббота'];
        var dpmonthNames = ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'];
        var dpstartDay = 1;
        var DatePickers = document.querySelectorAll('input.custom-date-picker');
        if (DatePickers) {
            for (var i = 0; i < DatePickers.length; i++) {
                let format = DatePickers[i].getAttribute('data-format');
                new DatePicker(DatePickers[i], {
                    yearOffset: dpOffset,
                    format: format !== null ? format : dpformat,
                    dayNames: dpdayNames,
                    monthNames: dpmonthNames,
                    startDay: dpstartDay
                });
            };
        };
    }

    initMainTabHook() {
        var thisModule = this;

        jQuery(document).on('click', '.modx-evo-lk-admin  .tab-row > h2.tab', function(event) {
            event.preventDefault();

            var tabSelector = jQuery(this).attr('data-target');

            thisModule.setTabContent(thisModule.getDomObject().filter(tabSelector));
        });

        return false;
    }

    initButtonsHook() {
        var thisModule = this;

        jQuery(document).on('click', '.modx-evo-lk-admin .lk-module-button', function(event) {
                event.preventDefault();

                var methodName;
                var tabName;
                var data = {};

                jQuery.each(jQuery(this)[0].attributes, function(index, attr) {
                    var attributeName = attr.name.match(/^rel\-(.*)$/i);

                    if ((attributeName !== null) && (attributeName.length > 0)) {
                        switch (attributeName[1]) {
                            case 'tab':
                                tabName = attr.value;

                                break;
                            case 'method':
                                methodName = attr.value;

                                break;
                            default:
                                data[attributeName[1].replace(/(\-)/i, '_')] = attr.value;

                                break;
                        }
                    }
                });

                var pageDomObject = thisModule.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);

                thisModule.setTabContent(pageDomObject, tabName, methodName, data);
            })
            // Очистить фильтр рейсов
            .on('click', '.filter-area .filter-clear', function() {
                var tabName = jQuery(this).attr('rel-tab');
                var methodName = jQuery(this).attr('rel-tab-method');
                var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);
                var data = {};

                moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
            });

        return false;
    }

    initBackwardButtonHook() {
        var thisModule = this;

        jQuery(document).on('click', '.modx-evo-lk-admin .lk-module-backward-button', function(event) {
            event.preventDefault();

            var prevPage = bufferObject.prev();

            thisModule.setTabContent(prevPage.pageDomObject, prevPage.tabName, prevPage.methodName, prevPage.properties, false);
        });

        return false;
    }

    initReloadButtonHook() {
        var thisModule = this;

        jQuery(document).on('click', '.modx-evo-lk-admin .lk-module-reload-button', function(event) {
            event.preventDefault();

            var currentPage = bufferObject.current();

            thisModule.setTabContent(currentPage.pageDomObject, currentPage.tabName, currentPage.methodName, currentPage.properties, false);
        });

        return false;
    }

    initCustomDatePickerCleanButtonHandler() {
        jQuery(document).on('click', 'a.button-clear', function() {
            jQuery(this).parents('.dp-container').find('input.DatePicker').val('');

            return true;
        });
    }

    initAutocompleteHook() {
        var thisModule = this;

        var data = {
            tabName: '',
            method: '',
            text: ''
        };

        jQuery(document).on('paste keyup', '#search-by input', function() {
            if (this.value.length < 2) return;

            var thisInput = this;

            data.tabName = jQuery(this).attr('rel-tab');
            data.method = jQuery(this).attr('rel-tab-method');
            data.text = this.value;

            jQuery.ajax({
                type: thisModule.apiMethod,
                url: thisModule.apiUrl,
                data: data,
                async: false
            }).done(function(response) {
                if (response !== null && response.length > 0) {
                    var titles = [];

                    if (response.length == 1) {
                        jQuery(thisInput).val(response[0].title);
                        jQuery(thisInput).attr('rel-item-id', response[0].dataId)

                        jQuery(thisInput).autocomplete({ source: [] });
                    } else {
                        jQuery.each(response, function(key, value) {
                            titles.push(value.title);
                        });
                    }

                    function setDataId(event, ui) {
                        jQuery.each(response, function(key, value) {
                            if (value.title == ui.item.label) {
                                jQuery(thisInput).attr('rel-item-id', value.dataId)
                            }
                        });
                    }

                    jQuery(thisInput).autocomplete({
                        source: titles,
                        select: setDataId
                    });
                }
            });
        });
    }
}