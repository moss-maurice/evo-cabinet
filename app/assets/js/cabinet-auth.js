class CabinetAuth {
    apiUrl = '/lk/api';
    apiMethod = 'POST';

    constructor() {
        var thisModule = this;

        thisModule.initAuthFormHook();
        thisModule.initRegisterFormHook();
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

        jQuery(document).find('.cab-container .cab-login form.form-signin').submit(function(event) {
            event.preventDefault();

            console.log('adsdas');

            jQuery.ajax({
                url: thisModule.apiUrl + '/auth/login',
                data: {
                    login: jQuery(this).find('input[name=login]').val(),
                    password: jQuery(this).find('input[name=password]').val(),
                    rememberMy: jQuery(this).find('input[name=remember-me]').val(),
                },
                type: 'POST',
                dataType: 'json',
                async: false,
                success: function(response) {
                    thisModule.logTrace('Запрос авторизации (' + thisModule.apiUrl + '/auth/login)', [
                        response,
                    ]);

                    if (response.code === 200) {
                        jQuery(this).find('div.form-message').html('');

                        thisModule.relocation(response.data.redirect);
                    } else {
                        jQuery(this).find('div.form-message').html(response.message);
                    }

                    thisModule.relocation(response.data.redirect);
                }
            });
            return false;
        });

        return false;
    }

    initRegisterFormHook() {
        var thisModule = this;

        jQuery(document).find('.cab-container .cab-reg form.form-signin').submit(function(event) {
            event.preventDefault();

            console.log('adsdas');

            jQuery.ajax({
                url: thisModule.apiUrl + '/auth/register',
                data: {
                    login: jQuery(this).find('input[name=login]').val(),
                    password: jQuery(this).find('input[name=password]').val(),
                    passwordRetype: jQuery(this).find('input[name=password-retype]').val(),
                    email: jQuery(this).find('input[name=email]').val(),
                    fields: {
                        phone: jQuery(this).find('input[name=phone]').val(),
                        mobilephone: jQuery(this).find('input[name=phone-mobile]').val(),
                        fax: jQuery(this).find('input[name=fax]').val(),
                        zip: jQuery(this).find('input[name=zip-code]').val(),
                        country: jQuery(this).find('select[name=country] option:selected').val(),
                        state: jQuery(this).find('input[name=region]').val(),
                        city: jQuery(this).find('input[name=city]').val(),
                        street: jQuery(this).find('input[name=street]').val(),
                        gender: jQuery(this).find('select[name=sex] option:selected').val(),
                    },
                },
                type: 'POST',
                dataType: 'json',
                async: false,
                success: function(response) {
                    thisModule.logTrace('Запрос регистрации (' + thisModule.apiUrl + '/auth/register)', [
                        response,
                    ]);

                    if (response.code === 200) {
                        jQuery(this).find('div.form-message').html('');

                        thisModule.relocation(response.data.redirect);
                    } else {
                        jQuery(this).find('div.form-message').html(response.message);
                    }

                    thisModule.relocation(response.data.redirect);
                }
            });
            return false;
        });

        return false;
    }
}