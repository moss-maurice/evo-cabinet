class Order {

    /**
     * Add default vars
     */
    constructor() {
        this.data;
        this.orderId;
        this.lastPaymentId;
        this.newPaymentId;
        this.addedIds = [];
        this.tabName;
        this.apiUrl = '/assets/plugins/modx-evo-lk/modules/admin-module/api.php';

        this.addEventHandlers();
    }

    /**
     * Drow each new row in the table
     */
    drawRow() {
        var Order = this;
        var lastPasedPaymentId = parseInt(jQuery(document).find('#overPrice').find('div.payment-id').last().html());
        Order.newPaymentId = lastPasedPaymentId + 1;

        var html = '<tr id="tr-over-price">' +
            '<td class="tableItem"><div class="payment-id">' + Order.newPaymentId + '</div></td>' +
            '<td class="tableItem"><input type="text" id="comment-over-price"></td>' +
            '<td class="tableItem"><input type="text" class="value-over-price" id="value-over-price"></td>' +
            '<td class="tableItem" id="actions-over-price">' +
            '<div align="right" class="saved-payment-actions">' +
            '<input type="button" class="btn btn-success save-over-price" value="Добавить" style="margin: 7px;">' +
            '<input type="button" class="btn btn-danger cansel-over-price" value="Отменить" style="margin: 7px;">' +
            '<input type="button" class="btn btn-warning update-over-price d-none" value="Обновить" style="margin: 7px;">' +
            '<input type="button" class="btn btn-danger delete-over-price d-none" value="Удалить" style="margin: 7px;">' +
            '</div>' +
            '</td>' +
            '</tr>';

        jQuery(document).find('#overPrice').find('tbody').append(html);
    }

    /**
     * Drow a structure of table
     */
    drawTable() {
        var Order = this;

        Order.newPaymentId++;

        var html = '<br><table class="table data sm-4 over-price-data" cellpadding="1" cellspacing="1">' +
            '<thead>' +
            '<tr>' +
            '<td class="tableHeader">#</td>' +
            '<td class="tableHeader"><div align="center">Комментарий</div></td>' +
            '<td class="tableHeader"><div align="center">Сумма</div></td>' +
            '<td class="tableHeader"><div align="center">Действие/Статус</div></td>' +
            '</tr>' +
            '</thead>' +
            '<tbody class="tbody over-price-data">' +
            '<tr id="tr-over-price">' +
            '<td class="tableItem"><div class="payment-id">' + Order.newPaymentId + '</div></td>' +
            '<td class="tableItem"><input type="text" id="comment-over-price"></td>' +
            '<td class="tableItem"><input type="text" class="value-over-price" id="value-over-price"></td>' +
            '<td class="tableItem" id="actions-over-price">' +
            '<div align="right" class="saved-payment-actions">' +
            '<input type="button" class="btn btn-success save-over-price" value="Добавить" style="margin: 7px;">' +
            '<input type="button" class="btn btn-danger cansel-over-price" value="Отменить" style="margin: 7px;">' +
            '<input type="button" class="btn btn-warning update-over-price d-none" value="Обновить" style="margin: 7px;">' +
            '<input type="button" class="btn btn-danger delete-over-price d-none" value="Удалить" style="margin: 7px;">' +
            '</div>' +
            '</td>' +
            '</tr>' +
            '</tbody>' +
            '</table>';

        jQuery(document).find('.btn-secondary-add').addClass('d-none');
        jQuery(document).find('.btn-secondary-close').removeClass('d-none');

        jQuery(document).find('#overPrice').html(html);
    }

    /**
     * Cut table from HTML
     */
    hideTable() {
        var Order = this;

        jQuery(document).find('.btn-secondary-close').addClass('d-none');
        jQuery(document).find('.btn-secondary-add').removeClass('d-none');

        jQuery(document).find('#overPrice').children().remove();

        Order.newPaymentId = Order.lastPaymentId;
    }

    /**
     * 
     * var selector - is jQuery target element
     * var method   - is string value which consist action name
     */
    actionTableRow(selector, method) {
        var Order = this;

        var tabMethod = {
            save: 'addOrderPayment',
            update: 'updateOrderPayment',
            delete: 'deleteOrderPayment'
        };

        Order.tabName = jQuery(document).find('#order-item').attr('rel-tab');
        var overPricePaymentId = parseInt(jQuery(selector).closest('tr').find('.payment-id').html());
        var overPriceComment = jQuery(selector).closest('tr').find('#comment-over-price').val();
        var overPriceOrderId = parseInt(Order.orderId);

        var overPriceValue = Order.validateTransactionValueInput(jQuery(selector).closest('tr').find('#value-over-price').val());

        var jsonData = {
            tabName: Order.tabName,
            method: tabMethod[method],
            paymentId: undefined,
            orderId: overPriceOrderId,
            transactionType: 1,
            transactionValue: overPriceValue,
            comment: overPriceComment
        };

        if (method !== 'save') {
            jsonData.paymentId = Order.getRelatedPaymentId(overPricePaymentId);
        }

        if (typeof(jsonData.orderId) === 'number' && !isNaN(jsonData.orderId) &&
            typeof(jsonData.transactionType) === 'number' && !isNaN(jsonData.transactionType) &&
            typeof(jsonData.transactionValue) === 'string' && jsonData.transactionValue !== '' &&
            typeof(jsonData.comment) === 'string' && jsonData.comment !== '') {
            jQuery.ajax({
                url: Order.apiUrl,
                type: 'POST',
                data: jsonData,
                dataType: 'JSON',
                async: false,
                success: function(response) {
                    if (response == false) {
                        jQuery(selector).closest('tr').find('#actions-over-price').find('div').html('Возникла ошибка');
                    } else {
                        if (method == 'save') {
                            var dataIds = {};
                            var fieldName = overPricePaymentId;

                            dataIds[fieldName] = response;
                            Order.addedIds.push(dataIds);

                            jQuery(selector).closest('tr').find('.update-over-price').removeClass('d-none');
                            jQuery(selector).closest('tr').find('.delete-over-price').removeClass('d-none');
                            jQuery(selector).closest('tr').find('.save-over-price').addClass('d-none');
                            jQuery(selector).closest('tr').find('.cansel-over-price').addClass('d-none');
                        } else if (method == 'delete') {
                            jQuery(selector).closest('tr').find('.delete-over-price').attr('disabled', true);
                            jQuery(selector).closest('tr').find('.update-over-price').attr('disabled', true);
                        }
                    }
                }
            });
        } else {
            jQuery(selector).closest('tr').find('.saved-payment-actions').html('Не все поля заполнены');
        }
    }

    /**
     * Method update payments and price balance in the real-time.
     * 
     * var action   - string value of target action [save, update, delete]
     * var updatedTransactionValue - added, updated or deleted value
     * var lastTransactionValue - if transaction had been updated, this is the first value
     */
    updatePriceBalance(action, updatedTransactionValue, lastTransactionValue = 0) {
        var Order = this;

        var paymentsSumSelector = jQuery(document).find('#payments-sum-all');
        var paymentsPriceBalanceSelector = jQuery(document).find('#payments-price-balance');

        var paymentTransactionValue = parseFloat(updatedTransactionValue);
        var lastPaymentTransactionValue = parseFloat(lastTransactionValue);
        var paymentsSum = parseFloat(jQuery(paymentsSumSelector).html());
        var paymentsPriceBalance = parseFloat(jQuery(paymentsPriceBalanceSelector).html());

        if (action == 'save') {
            var sum = paymentsSum + paymentTransactionValue;
            var priceBalance = paymentsPriceBalance - paymentTransactionValue;

            jQuery(paymentsSumSelector).html(Order.validateTransactionValueInput(sum));
            jQuery(paymentsPriceBalanceSelector).html(Order.validateTransactionValueInput(priceBalance));
        } else if (action == 'update') {
            var sum = paymentsSum - lastPaymentTransactionValue;
            sum += paymentTransactionValue;

            var priceBalance = paymentsPriceBalance + lastPaymentTransactionValue;
            priceBalance -= paymentTransactionValue;

            jQuery(paymentsSumSelector).html(Order.validateTransactionValueInput(sum));
            jQuery(paymentsPriceBalanceSelector).html(Order.validateTransactionValueInput(priceBalance));
        } else if (action == 'delete') {
            var sum = paymentsSum - paymentTransactionValue;
            var priceBalance = paymentsPriceBalance + paymentTransactionValue;

            jQuery(paymentsSumSelector).html(Order.validateTransactionValueInput(sum));
            jQuery(paymentsPriceBalanceSelector).html(Order.validateTransactionValueInput(priceBalance));
        }
    }

    /**
     * Method update payments table in the real-time.
     * 
     * var selector - 'this' of target method, where had been event.
     * var action   - string value of target action [save, update, delete]
     */
    updatePaymentsTable(selector, action) {
        var Order = this;

        Order.getOrderData();

        var orderDataPayments = Order.data.payments;
        var paymentsTableTBody = jQuery(document).find('#payments-data-table').find('tbody');
        var lastPaymentIdInTable = parseInt(jQuery(paymentsTableTBody).find('tr').filter(':first').find('td').filter(':first').html());

        if (action == 'save') {

            var html = '';
            for (var i = 0; i < orderDataPayments.length; i++) {
                if (lastPaymentIdInTable !== parseInt(orderDataPayments[i].id)) {
                    if (!parseInt(orderDataPayments[i].deleted)) {

                        html += '<tr>';
                        html += '<td class="tableItem" width="32">' + orderDataPayments[i].id + '</td>';
                        html += '<td class="tableItem" width="32"><strong>' + ((orderDataPayments[i].payer !== null) ? orderDataPayments[i].payer : orderDataPayments[i].editor) + '</strong></td>';
                        html += '<td class="tableItem" width="32">' + orderDataPayments[i].transaction_type + '</td>';
                        html += '<td class="tableItem" width="32">' + orderDataPayments[i].transaction_value + '</td>';
                        html += '<td class="tableItem" width="32">' + orderDataPayments[i].comment + '</td>';
                        html += '<td class="tableItem" width="60">' + Order.prepareTransactionDate(orderDataPayments[i].date) + '</td>';
                        html += '</tr>';

                        jQuery(paymentsTableTBody).prepend(html);

                        Order.updatePriceBalance(action, orderDataPayments[i].transaction_value);
                    }
                } else
                    break;
            }
        } else if (action == 'delete') {
            var deletedPaymentId = parseInt(jQuery(selector).closest('tr').find('td').filter(':first').find('div.payment-id').html());
            deletedPaymentId = Order.getRelatedPaymentId(deletedPaymentId);

            jQuery.each(jQuery(paymentsTableTBody).find('tr'), function(index) {
                var paymentId = parseInt(jQuery(this).find('td').filter(':first').html());

                if (paymentId == deletedPaymentId) {
                    jQuery(this).remove();

                    Order.updatePriceBalance(action, orderDataPayments[index].transaction_value);

                    return false;
                }
            });
        } else if (action == 'update') {
            var updatedPaymentId = parseInt(jQuery(selector).closest('tr').find('td').filter(':first').find('div.payment-id').html());
            updatedPaymentId = Order.getRelatedPaymentId(updatedPaymentId);
            var updatedPaymentValue;

            var html = '';
            for (var i = 0; i < orderDataPayments.length; i++) {
                if (updatedPaymentId === parseInt(orderDataPayments[i].id)) {
                    if (!parseInt(orderDataPayments[i].deleted)) {
                        html += '<td class="tableItem" width="32">' + orderDataPayments[i].id + '</td>';
                        html += '<td class="tableItem" width="32"><strong>' + ((orderDataPayments[i].payer !== null) ? orderDataPayments[i].payer : orderDataPayments[i].editor) + '</strong></td>';
                        html += '<td class="tableItem" width="32">' + orderDataPayments[i].transaction_type + '</td>';
                        html += '<td class="tableItem" width="32">' + orderDataPayments[i].transaction_value + '</td>';
                        html += '<td class="tableItem" width="32">' + orderDataPayments[i].comment + '</td>';
                        html += '<td class="tableItem" width="60">' + Order.prepareTransactionDate(orderDataPayments[i].date) + '</td>';

                        updatedPaymentValue = orderDataPayments[i].transaction_value;
                    } else {
                        break;
                    }
                }
            }

            jQuery.each(jQuery(paymentsTableTBody).find('tr'), function(index) {
                var paymentId = parseInt(jQuery(this).find('td').filter(':first').html());

                if (paymentId == updatedPaymentId) {
                    var paymentValue = jQuery(this).find('td').eq(3).html();

                    Order.updatePriceBalance(action, updatedPaymentValue, paymentValue);

                    jQuery(this).html(html);
                }
            });
        }
    }

    /**
     * This method getting data, when table drow
     */
    getOrderData() {
        var Order = this;

        Order.tabName = jQuery(document).find('#order-item').attr('rel-tab');
        var tabMethod = 'getOrderById';
        var id = parseInt(jQuery(document).find('#order-item').attr('rel-item-id'));

        if (id !== undefined && !isNaN(id)) {
            jQuery.ajax({
                url: Order.apiUrl,
                data: {
                    tabName: Order.tabName,
                    method: tabMethod,
                    orderId: id,
                },
                type: 'POST',
                dataType: 'json',
                async: false,
                success: function(response) {
                    if (response !== false) {
                        Order.data = response;

                        if (Order.data.payments !== null) {
                            Order.lastPaymentId = parseInt(Order.data.payments[0].id);
                        } else {
                            Order.lastPaymentId = 0;
                        }

                        Order.newPaymentId = Order.lastPaymentId;
                        Order.orderId = parseInt(Order.data.id);
                    }
                }
            });
        }
    }

    /**
     * Method, which compare saved IDs into DB and visibility user IDs.
     * 
     * var targetId - raw visibility id.
     */
    getRelatedPaymentId(targetId) {
        var Order = this;

        var matchedId;

        if (typeof(Order.addedIds) == 'object') {
            for (var id in Order.addedIds) {
                if (Order.addedIds[id][targetId] !== undefined) {
                    matchedId = Order.addedIds[id][targetId];
                }
            }

            if (typeof(matchedId) !== undefined || !isNaN(matchedId)) {
                return matchedId;
            }
        }
    }

    /**
     * var strDate - raw date string.
     */
    prepareTransactionDate(strDate) {
        var date = new Date(strDate);

        var day = date.getDate();
        day = (day <= 9) ? day = '0' + day : day;
        var month = date.getMonth() + 1;
        month = (month <= 9) ? month = '0' + month : month;
        var year = date.getFullYear()
        var hours = date.getHours();
        hours = (hours <= 9) ? hours = '0' + hours : hours;
        var minutes = date.getMinutes();
        minutes = (minutes <= 9) ? minutes = '0' + minutes : minutes;
        var seconds = date.getSeconds();
        seconds = (seconds <= 9) ? seconds = '0' + seconds : seconds;

        return day + '.' + month + '.' + year + ' ' + hours + ':' + minutes + ':' + seconds;
    }

    /**
     * var string - raw user input of transaction value
     */
    validateTransactionValueInput(str, modifier = '') {
        var offset = 2;

        var rawStr = new String(str);
        rawStr = rawStr.replace(',', '.');

        if (modifier !== '') {
            var plusIndex = rawStr.indexOf('+');
            var minusIndex = rawStr.indexOf('-');

            if (plusIndex >= 0) {
                rawStr = new String(rawStr).replace('+', '')
            } else if (minusIndex >= 0) {
                rawStr = new String(rawStr).replace('-', '')
            }
        }

        rawStr = modifier + rawStr.trim();

        var pointIndex = rawStr.indexOf('.');

        var result = new String(rawStr);

        if (isNaN(result[parseInt(pointIndex + offset)])) {
            result += '0';
        } else
            result = result.toString();

        return result;
    }

    updateTourOrderPrice(price) {
        var Order = this;

        Order.tabName = jQuery(document).find('#order-item').attr('rel-tab');
        var tabMethod = 'updateTourOrderPrice';

        var orderId = parseInt(jQuery(document).find('#order-item').attr('rel-item-id'));
        var result = false;

        if (orderId !== undefined && !isNaN(orderId)) {
            jQuery.ajax({
                url: Order.apiUrl,
                data: {
                    tabName: Order.tabName,
                    method: tabMethod,
                    price: price,
                    orderId: orderId
                },
                type: 'POST',
                dataType: 'json',
                async: false,
                success: function(response) {
                    if (response !== false) {
                        result = true;
                    }
                }
            });
        }

        return result;
    }

    /**
     * Events handlers
     */
    addEventHandlers() {
        var Order = this;

        jQuery(document).on('click', '.btn-secondary-add', function() {
            Order.getOrderData();
            Order.drawTable();

            jQuery(document).find('.tour-price-cost').removeClass('d-none');
        }).on('click', '.btn-secondary-close', function() {
            Order.hideTable();
            jQuery(document).find('.tour-price-cost').addClass('d-none');
        }).on('change', '.value-over-price', function() {
            if (jQuery(this).closest('tr').find('#value-over-price').val().length >= 0) {
                if (jQuery(this).closest('tr').next().length == 0) {
                    Order.drawRow();
                }
            }
        }).on('click', '.cansel-over-price', function() {
            if (jQuery(this).closest('tbody').find('tr').length <= 2) {
                Order.hideTable();
            } else {
                jQuery(this).closest('tr').remove();

                var idContainers = jQuery(document).find('#overPrice').find('div.payment-id');
                Order.newPaymentId = Order.lastPaymentId + 1;

                /**
                 * Re-count all id from table
                 */
                var i = 0;
                while (i < idContainers.length) {
                    jQuery(idContainers[i]).html(Order.newPaymentId);

                    i++;
                    Order.newPaymentId++;
                }
            }
        }).on('click', '.save-over-price', function save() {
            Order.actionTableRow(this, 'save');
            Order.updatePaymentsTable(this, 'save');
        }).on('click', '.update-over-price', function() {
            Order.actionTableRow(this, 'update');
            Order.updatePaymentsTable(this, 'update');
        }).on('click', '.delete-over-price', function() {
            Order.updatePaymentsTable(this, 'delete');
            Order.actionTableRow(this, 'delete');
        }).on('input change', '.update-tour-price-cost', function() {
            if (jQuery(this).length > 0) {
                if (jQuery(jQuery(this)[0]).val().length > 0) {
                    jQuery(this).closest('div').find('.btn-secondary-save-tour-price-cost').removeClass('d-none');
                } else {
                    jQuery(this).closest('div').find('.btn-secondary-save-tour-price-cost').addClass('d-none');
                }
            }
        }).on('click', '.btn-secondary-save-tour-price-cost', function() {
            var tourPriceInput = jQuery(this).closest('div').find('.update-tour-price-cost');

            if (jQuery(this).closest('div').find('.update-tour-price-cost').length > 0) {
                if (Order.updateTourOrderPrice(jQuery(tourPriceInput[0]).val())) {
                    jQuery(this).closest('div').find('.btn-secondary-save-tour-price-cost').addClass('d-none');
                }
            }
        });
    }
};