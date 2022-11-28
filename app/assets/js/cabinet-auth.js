class CabinetAuth {
    apiUrl = '/lk/api';
    apiMethod = 'POST';

    constructor() {
        this.initAuthFormHook();
        this.initRegisterFormHook();
        this.initRemindFormHook();
    }

    logTrace(groupTitle, groupLines = []) {
        if (groupLines.length > 0) {
            console.groupCollapsed('[Cabinet]: ' + groupTitle);

            for (var i = 0; i < groupLines.length; i++) {
                console.log(groupLines[i]);
            }

            console.groupEnd();
        }
    }

    relocation(url) {
        if (url !== undefined) {
            window.location = url;

            thisModule.logTrace('Переадресация', [
                url,
            ]);

            return true;
        }

        return false
    }

    initAuthFormHook() {
        var thisModule = this;

        jQuery(document).find('form#sign-in').submit(function(event) {
            event.preventDefault();

            var action = jQuery(this).attr('action');
            var method = jQuery(this).attr('method');

            jQuery.ajax({
                url: action,
                data: {
                    email: jQuery(this).find('input[name=email]').val(),
                    phone: jQuery(this).find('input[name=phone]').val(),
                    password: jQuery(this).find('input[name=password]').val(),
                    rememberMy: jQuery(this).find('input[name=remember-me]').val(),
                },
                type: method,
                dataType: 'json',
                async: false,
                success: function(response) {
                    thisModule.logTrace('Запрос авторизации (' + action + ')', [
                        response,
                    ]);

                    if (response.code === 200) {
                        jQuery(this).find('.form__msg').html('');

                        thisModule.relocation(response.redirectUrl);
                    } else {
                        jQuery(this).find('.form__msg').html(response.message);
                    }

                    thisModule.relocation(response.redirectUrl);
                }
            });
        });

        return false;
    }

    initRegisterFormHook() {
        var thisModule = this;

        jQuery(document).find('form#sign-up').submit(function(event) {
            event.preventDefault();

            var action = jQuery(this).attr('action');
            var method = jQuery(this).attr('method');

            jQuery.ajax({
                url: action,
                data: {
                    firstName: jQuery(this).find('input[name=firstName]').val(),
                    email: jQuery(this).find('input[name=email]').val(),
                    password: jQuery(this).find('input[name=password]').val(),
                    passwordRetype: jQuery(this).find('input[name=passwordRetype]').val(),
                },
                type: method,
                dataType: 'json',
                async: false,
                success: function(response) {
                    thisModule.logTrace('Запрос регистрации (' + method + ')', [
                        response,
                    ]);

                    if (response.code === 200) {
                        jQuery(this).find('.form__msg').html('');

                        thisModule.relocation(response.redirectUrl);
                    } else {
                        jQuery(this).find('.form__msg').html(response.message);
                    }

                    thisModule.relocation(response.redirectUrl);
                }
            });
        });

        return false;
    }

    initRemindFormHook() {
        var thisModule = this;

        jQuery(document).find('form#remind').submit(function(event) {
            event.preventDefault();

            var action = jQuery(this).attr('action');
            var method = jQuery(this).attr('method');

            jQuery.ajax({
                url: action,
                data: {
                    email: jQuery(this).find('input[name=email]').val(),
                },
                type: method,
                dataType: 'json',
                async: false,
                success: function(response) {
                    thisModule.logTrace('Запрос смены пароля (' + method + ')', [
                        response,
                    ]);

                    if (response.code === 200) {
                        jQuery(this).find('.form__msg').html('');

                        thisModule.relocation(response.redirectUrl);
                    } else {
                        jQuery(this).find('.form__msg').html(response.message);
                    }

                    thisModule.relocation(response.redirectUrl);
                }
            });
        });

        return false;
    }
}