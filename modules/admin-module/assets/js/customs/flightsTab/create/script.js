var VoyageCreate = {
    init: function() {
        //this.drawForm();
        this.initDatePicker();
        //this.initSelectizeCurorts();
        //this.drawCrossTable(this.prepareCrossTablePoints());
        this.addSelectizeHandler();
        this.addEventHandlers();
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

    /*initSelectizeCurorts: function() {
        var Voyages = this;

        var options = {
            options: selectizeVoyagesCurorts,
            plugins: ['remove_button'],
            delimiter: ',',
            persist: false
        };

        jQuery('#selectize-landing-curorts').selectize(options);
        jQuery('#selectize-debarkation-curorts').selectize(options);
    },*/

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

    /*clearSelectize: function() {
        jQuery('#selectize-landing-curorts').selectize()[0].selectize.clear();
        jQuery('#selectize-debarkation-curorts').selectize()[0].selectize.clear();
    },*/

    addSelectizeHandler: function() {
        var Voyages = this;

        jQuery('#selectize-landing-curorts').selectize()[0].selectize.on('change', function() {
            Voyages.updateSelectize('selectize-debarkation-curorts', this);
            //Voyages.updateCrossTable();
        });

        jQuery('#selectize-debarkation-curorts').selectize()[0].selectize.on('change', function() {
            Voyages.updateSelectize('selectize-landing-curorts', this);
            //Voyages.updateCrossTable();
        });
    },

    /*prepareCrossTablePoints: function() {
        var Voyages = this;

        var landingPointsSelectize = jQuery('#selectize-landing-curorts').selectize()[0].selectize;
        var debarkationPointsSelectize = jQuery('#selectize-debarkation-curorts').selectize()[0].selectize;

        var landingPoints = landingPointsSelectize.getValue().split(',');
        var debarkationPoints = debarkationPointsSelectize.getValue().split(',');

        if (landingPoints.length > 0 && debarkationPoints.length > 0) {
            var landingTableItems = [];
            var debrkationTableItems = [];

            landingPoints.forEach(function(landingVal, landingKey) {
                selectizeVoyagesCurorts.forEach(function(curortLandVal, curortLandKey) {
                    if (landingVal == curortLandVal.value) {
                        landingTableItems.push({
                            text: curortLandVal.text,
                            value: curortLandVal.value
                        });
                    }
                });
            });

            debarkationPoints.forEach(function(debarkationVal, debarkationKey) {
                selectizeVoyagesCurorts.forEach(function(curortDebarVal, curortDebarKey) {
                    if (debarkationVal == curortDebarVal.value) {
                        debrkationTableItems.push({
                            text: curortDebarVal.text,
                            value: curortDebarVal.value
                        });
                    }
                });
            });

            return {
                landingPoints: landingTableItems,
                debarkationPoints: debrkationTableItems
            };
        }
    },*/

    /*drawCrossTable: function(tableItems, categories) {
        var Voyages = this;

        var crossTableContainer = jQuery(document).find('#cross-table-container');

        var html = '';
        if (typeof(tableItems) !== "undefined") {
            if (tableItems.landingPoints.length > 0 && tableItems.debarkationPoints.length > 0) {

                tableItems.landingPoints.forEach(function(landingVal, landingKey) {
                    tableItems.debarkationPoints.forEach(function(debarkationVal, debarkationKey) {
                        html += '<tr data-points-id=' + landingVal.value + '-' + debarkationVal.value + '>';

                        html += '<td class="tableItem">';
                        html += landingVal.text;
                        html += '</td>';

                        html += '<td class="tableItem">';
                        html += debarkationVal.text;
                        html += '</td>';

                        if (typeof(categories) == 'object' && categories[0] !== undefined) {
                            for (var key in categories) {
                                html += '<td class="tableItem">';
                                html += '<input type="number" data-categorie="' + categories[key]['title'] + '" value=0>';
                                html += '</td>';
                            }
                        }

                        html += '</tr>';
                    });
                });
            }
        }

        jQuery(crossTableContainer).html(html);
    },*/

    /*drawCrossTableHeader: function(categories) {
        var Voyages = this;

        var crossTableHeaderContainer = jQuery(document).find('#categories-header-conteiner');

        var html = '';
        if (typeof(categories) == 'object' && categories[0] !== undefined) {
            html += '<td class="tableHeader">Посадка</td>';
            html += '<td class="tableHeader">Высадка</td>';

            for (var key in categories) {
                html += '<td class="tableHeader text-center">';
                html += categories[key]['title'] ? categories[key]['title'] : '';
                html += '</td>';
            }
        }

        jQuery(crossTableHeaderContainer).html(html);
    },*/

    /*updateCrossTable: function() {
        var Voyages = this;

        if (typeof(busSchemesCategories) == 'object') {
            for (var busSchemeId in busSchemesCategories) {
                if (jQuery(document).find('#bus-scheme-container').find('select.inputBox option:selected').val() == busSchemeId) {
                    Voyages.drawCrossTableHeader(busSchemesCategories[busSchemeId])
                    Voyages.drawCrossTable(Voyages.prepareCrossTablePoints(), busSchemesCategories[busSchemeId]);
                }
            }
        }
    },*/

    /*drawSelectElement: function(items, selectedId = 0, identifier = null, emptyRow = false) {
        var classAttr = '';

        if (identifier !== null) {
            classAttr = ' class="' + identifier + '"';
        }

        var html = '<select' + classAttr + '>';

        if (emptyRow) {
            html += '<option value="0">Нет</option>';
        }

        if (items !== null) {
            for (var i = 0; i < items.length; i++) {
                selected = '';

                if (parseInt(items[i].id) === parseInt(selectedId)) {
                    selected = ' selected="selected"';
                }

                html += '<option value="' + items[i].id + '"' + selected + '>' + items[i].title + '</option>';
            }
        }

        html += '</select>';

        return html;
    },*/

    /*drawRadioBoxElement: function(items, selectedId) {
        var html = '';

        for (var i = 0; i < items.length; i++) {
            selected = '';

            if (parseInt(selectedId) === 0) {
                selectedId = items[i].id;
            }

            if (parseInt(items[i].id) === parseInt(selectedId)) {
                selected = ' checked="checked"';
            }

            var title = items[i].id;

            if (items[i].listTitle !== undefined) {
                title = items[i].listTitle;
            }

            html += '<input name="direction" type="radio" value="' + items[i].id + '"' + selected + '>' + title + '<br />';
        }

        return html;
    },*/

    /*drawForm: function() {
        var Voyages = this;
        var html = '<table style="width: 100%;">' +
            '<tr>' +

            '<td>Дата рейса *:</td>' +
            '<td>' +
            '<div class="dp-column custom-date-picker">' +
            '<input type="text" class="DatePicker custom-date-picker" value="" onblur="documentDirty=true;" autocomplete="off">' +
            '<a href="javascript:" class="button-clear" onclick="" onmouseover="window.status=\'Удалить дату\'; return true;" onmouseout="window.status=\'\'; return true;">' +
            '<i class="fa fa-calendar-times-o" title="Удалить дату"></i>' +
            '</a>' +
            '</div>' +
            '</td>' +

            '<td>Направление *:</td>' +
            '<td id="direction">' + Voyages.drawRadioBoxElement(voyagesDirections, 0, false) + '</td>' +

            '<td id="additions-statuses-title">Статус рейса:</td>' +
            '<td id="additions-statuses">' + Voyages.drawSelectElement(voyageStatuses, 0, 'inputBox') + '</td>' +

            '</tr>' +
            '<tr>' +

            '<td>Схема автобуса:</td>' +
            '<td id="bus-scheme-container">' + Voyages.drawSelectElement(busSchemes, 0, 'inputBox', true) + '</td>' +

            '<td id="additions-seating-title">Выбор мест:</td>' +
            '<td id="additions-seating"><input type="checkbox"></td>' +

            '<td id="additions-places-title">Количество мест:</td>' +
            '<td id="additions-places"><input type="number" value="0"></td>' +

            '</tr>' +
            '<tr>' +

            '<td>Описание рейса:</td>' +
            '<td id="voyage-title-container"><input type="text" value=""></td>' +

            '<td colspan="4"></td>' +

            '</tr>' +
            '</table>';

        jQuery(document).find('#voyages-form').find('.dp-row.control').find('.button-save-voyage').removeClass('d-none');

        jQuery(document).find('#voyages-form').find('#voyages-form-container').html(html);

        Voyages.initDatePicker();
    },*/

    saveForm: function() {
        var tabName = jQuery(document).find('#voyage-item').attr('rel-tab');
        var methodName = jQuery(document).find('#voyage-item').attr('rel-tab-method');
        var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);
        var data = {
            voyageDate: jQuery(document).find('.custom-date-picker.voyage-date').val(),
            voyageDirection: parseInt(jQuery(document).find('#voyage-direction').val()),
            voyageStatus: parseInt(jQuery(document).find('#voyage-status').val()),
            voyageBusScheme: parseInt(jQuery(document).find('#voyage-bus-scheme').val()),
            voyageSeating: jQuery(document).find('#voyage-seating').val(),
            voyagePlaces: parseInt(jQuery(document).find('#voyage-places').val()),
            voyageTitle: jQuery(document).find('#voyage-title').val(),
            voyageLanding: jQuery(document).find('#selectize-landing-curorts').val(),
            voyageDebarkation: jQuery(document).find('#selectize-debarkation-curorts').val()
        };

        moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
    },

    addEventHandlers: function() {
        var Voyages = this;

        jQuery(document).on('click', '.lk-module-voyage-create-button', function() {
            Voyages.saveForm();
        });
    }
};

jQuery(document).ready(function() {
    VoyageCreate.init();
});