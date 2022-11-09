var VoyageUpdate = {
    count: 0,

    init: function() {
        this.initDatePicker();
        this.initSelectizeCurorts();
        this.addEventHandlers();
        this.count += 1;

        console.log(this.count);
    },

    initDatePicker: function() {
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
    },

    initSelectizeCurorts: function() {
        var Voyages = this;

        var options = {
            options: selectizeVoyagesCurorts,
            plugins: ['remove_button'],
            delimiter: ',',
            persist: false
        };

        jQuery('#selectize-landing-curorts').selectize(options);
        jQuery('#selectize-debarkation-curorts').selectize(options);

        for (let item of Object.entries(selectizeLandingItems)) {
            jQuery('#selectize-landing-curorts').selectize()[0].selectize.addItem(item[1].value, 0);
        }
        for (let item of Object.entries(selectizeDebarkationItems)) {
            jQuery('#selectize-debarkation-curorts').selectize()[0].selectize.addItem(item[1].value, 0);
        }

        jQuery('#selectize-landing-curorts').selectize()[0].selectize.on('change', function() {
            Voyages.updateSelectize('selectize-debarkation-curorts', this);
        });

        jQuery('#selectize-debarkation-curorts').selectize()[0].selectize.on('change', function() {
            Voyages.updateSelectize('selectize-landing-curorts', this);
        });
    },

    updateSelectize: function(jQuerySelector, thisValue) {
        var Voyage = this;

        var landingSelectizeValues = thisValue.getValue().split(',');

        if (landingSelectizeDiffValues.length > 0 || landingSelectizeValues.length > 0) {

            landingSelectizeValues.forEach(function(val, key) {
                landingSelectizeDiffValues.forEach(function(valDiff, keyDiff) {
                    if (landingSelectizeValues.indexOf(valDiff) == -1) {
                        selectizeVoyagesCurorts.forEach(function(valMain, keyMain) {
                            if (valMain.value == valDiff) {
                                jQuery('#' + jQuerySelector + '').selectize()[0].selectize.addOption(valMain);
                            }
                        });
                    }
                });
            });

            landingSelectizeDiffValues = landingSelectizeValues;
        } else {
            landingSelectizeDiffValues = landingSelectizeValues;
        }

        jQuery('#' + jQuerySelector + '').selectize()[0].selectize.removeOption(landingSelectizeValues[landingSelectizeValues.length - 1]);
    },

    saveForm: function() {
        var crossTablePoints = [];

        var crossTableRows = jQuery(document).find('#cross-table-container').find('tr');

        if (crossTableRows.length > 0) {
            jQuery(crossTableRows).each(function(row, key) {

                var rowDataId = jQuery(this).attr('data-points-id').split('-');
                var tdsDataCategories = jQuery(this).find('td');

                var crossTablePointsData = {
                    landingPoint: rowDataId[0],
                    debarkationPoint: rowDataId[1]
                }

                if (tdsDataCategories.length > 0) {
                    jQuery(tdsDataCategories).each(function(td, key) {
                        var dataAlias = jQuery(this).find('input').attr('data-categorie');

                        if (dataAlias !== undefined) {
                            crossTablePointsData[dataAlias] = jQuery(this).find('input').val();
                        }
                    });
                }

                if (rowDataId.length > 0) {
                    crossTablePoints.push(crossTablePointsData);
                }
            });
        }

        var tabName = jQuery(document).find('#voyage-item').attr('rel-tab');
        var methodName = jQuery(document).find('#voyage-item').attr('rel-tab-method');
        var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);
        var data = {
            item_id: parseInt(jQuery(document).find('#item-id').val()),
            voyageDate: jQuery(document).find('.custom-date-picker.voyage-date').val(),
            voyageDirection: parseInt(jQuery(document).find('[name=direction]').filter(':checked').val()),
            voyageStatus: parseInt(jQuery(document).find('#voyage-status').val()),
            voyageBusScheme: parseInt(jQuery(document).find('#voyage-bus-scheme').val()),
            voyageSeating: jQuery(document).find('#voyage-seating').val(),
            voyagePlaces: parseInt(jQuery(document).find('#voyage-places').val()),
            voyageTitle: jQuery(document).find('#voyage-title').val(),
            voyageLanding: jQuery(document).find('#selectize-landing-curorts').val(),
            voyageDebarkation: jQuery(document).find('#selectize-debarkation-curorts').val(),
            crossTablePoints: crossTablePoints
        };

        moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
    },

    addEventHandlers: function() {
        var Voyages = this;

        jQuery(document)
            .off('click', '.lk-module-voyage-save-button')
            .on('click', '.lk-module-voyage-save-button', function() {
                Voyages.saveForm();
            });

        jQuery(document).find('#bus-scheme-container').find('select.inputBox').on('change', function() {
            if (parseInt(jQuery(document).find('#bus-scheme-container').find('select.inputBox option:selected').val())) {
                jQuery(document).find('.without-bus-scheme').addClass('d-none');

                if (parseInt(jQuery(document).find('#bus-scheme-container').find('select.inputBox option:selected').val()) == parseInt(voyageItem['bus_scheme_id'])) {
                    Voyages.updateCrossTable(breakPointsCategories);
                } else {
                    Voyages.updateCrossTable([]);
                }
            } else {
                jQuery(document).find('.without-bus-scheme').removeClass('d-none');

                jQuery(document).find('#categories-header-conteiner').html('<td><h1 align="center">Схема автобуса не выбрана</h1></td>');
                jQuery(document).find('#cross-table-container').html('');
            }
        });
    }
};

jQuery(document).ready(function() {
    VoyageUpdate.init();
});