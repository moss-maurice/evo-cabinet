jQuery.noConflict();

/* profile */
jQuery(document).on('click', '.scan button.upload-but', function(e) {
    e.preventDefault();
    var object = jQuery(this);
    if (object.parents('.upload-row').hasClass('done')) {
        return false;
    };
    jQuery(document).find(jQuery(this).parents('.upload-row').find('input[type=file]')).trigger('click');
    return true;
});

jQuery(document).on('click', '.photo-profile button.upload-but', function(e) {
    e.preventDefault();
    var object = jQuery(this);
    jQuery(document).find(jQuery(this).parents('.upload-row').find('input[type=file]')).trigger('click');
    return true;
});

jQuery(document).on('click', '.scan button.add-but', function(e) {
    e.preventDefault();
    var parent = jQuery(this).parents('.upload-row').parents('.row').eq(0);
    var cloneObject = parent.find('.upload-row').filter('.upload:last').clone();
    cloneObject.find('.jq-file').removeClass('changed');
    cloneObject.find('.jq-file').find('.jq-file__name').text('Файл не выбран');
    cloneObject.removeClass('done');
    cloneObject.find('button').filter('.upload-but').find('span').filter('.caption').text('Загрузить');
    cloneObject.find('.upload-mes').text('Файл не загружен');
    var newObject = parent.find('.upload-row').filter('.upload:last').after(cloneObject);
    return true;
});

jQuery(document).on('change', '.scan input[type=file]', function(e) {
    e.preventDefault();
    var object = jQuery(this);
    if (object.parents('.upload-button').find('button.upload-but').hasClass('done')) {
        return false;
    };
    console.log(object.prop('files')[0]);
    object.parents('.upload-row').find('.upload-mes').html('Загрузка файла...');
    if (object.prop('files')[0].size <= parseInt(object.attr('rel-max-size'))) {
        var formData = new FormData();
        formData.append('file', object.prop('files')[0]);
        var url = '/assets/plugins/modx-evo-lk/ajax.php?action=ajax/fileUploadImages&form=' + object.attr('id').replace('[', '').replace(']', '');
        var extClass = '';
        if (object.hasClass('tourist-passport')) {
            url = '/assets/plugins/modx-evo-lk/ajax.php?action=ajax/fileUploadImagesTourist&form=' + object.attr('id').replace('[', '').replace(']', '');
            extClass = ' tourist';
        }
        console.log(url);
        jQuery.ajax({
            url: url,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            type: 'post',
            success: function(response) {
                console.log(response);
                if (response.status === 'success') {
                    object.parents('.upload-row').addClass('done').find('.upload-mes').html(response.result.real_file_name);
                    object.parents('.upload-button').find('button.upload-but').find('span').filter('.caption').text('Загружено');
                    var html = '<div class="col-sm-3 upload-file">'
                        + '<img src="' + response.result.web_file_path + '" alt="">'
                        + '<input type="hidden" name="files[' + response.result.id + '][file_name]" value="' + response.result.file_name + '" />'
                        + '<input type="hidden" name="files[' + response.result.id + '][real_file_name]" value="' + response.result.real_file_name + '" />'
                        + '<input type="hidden" name="files[' + response.result.id + '][file_type]" value="' + response.result.file_type + '" />'
                        + '<input type="hidden" name="files[' + response.result.id + '][file_size]" value="' + response.result.file_size + '" />'
                        + '<a href="#!" class="file-link">Cкачать</a>'
                        + '<div class="upload-remove-file' + extClass + ' unstrict" rel-file="' + response.result.file_name + '">'
                        + '<span class="icon-cancel"></span>'
                        + '</div>'
                        + '</div>';
                    object.parents('.scan').find('.upload-files').children('.row').append(html);
                } else {
                    object.parents('.upload-button').find('button.upload-but').find('span').filter('.caption').text('Ошибка!');
                };
            },
            error: function(error) {
                console.log(error);
                object.parents('.upload-row').find('.upload-mes').html('Ошибка!');
            }
        });
        object.prop('files')[0] = {};
        object.val('');
    };
});

jQuery(document).on('change', '.photo-profile input[type=file]', function(e) {
    e.preventDefault();
    var object = jQuery(this);
    console.log(object.prop('files')[0]);
    object.parents('.upload-row').find('.upload-mes').html('Загрузка файла...');
    if (object.prop('files')[0].size <= parseInt(object.attr('rel-max-size'))) {
        var formData = new FormData();
        formData.append('file', object.prop('files')[0]);
        console.log('/assets/plugins/modx-evo-lk/ajax.php?action=ajax/fileUploadUserpic&form=' + object.attr('id').replace('[', '').replace(']', ''));
        jQuery.ajax({
            url: '/assets/plugins/modx-evo-lk/ajax.php?action=ajax/fileUploadUserpic&form=' + object.attr('id').replace('[', '').replace(']', ''),
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            type: 'post',
            success: function(response) {
                console.log(response);
                if (response.status === 'success') {
                    object.parents('.upload-row').addClass('done').find('.upload-mes').html(response.result.real_file_name);
                    object.parents('.upload-button').find('button.upload-but').find('span').filter('.caption').text('Загружено');
                    var html = '<div class="col-sm-4 upload-photo">'
                        + '<img src="' + response.result.web_file_path + '" alt="">'
                        + '</div>';
                    object.parents('.photo-profile').find('.upload-files').children('.row').html(html);
                } else {
                    object.parents('.upload-button').find('button.upload-but').find('span').filter('.caption').text('Ошибка!');
                };
            },
            error: function(error) {
                console.log(error);
                object.parents('.upload-row').find('.upload-mes').html('Ошибка!');
            }
        });
        object.prop('files')[0] = {};
        object.val('');
    };
});

jQuery(document).on('click', '.upload-remove-file', function(e) {
    e.preventDefault();
    var object = jQuery(this);
    console.log(object.attr('rel-file'));
    var url = '/assets/plugins/modx-evo-lk/ajax.php?action=ajax/fileRemove&file=' + object.attr('rel-file') + '&form=' + object.parents('.scan').find('.upload-row').filter('.upload:first').find('input[type=file]').attr('name').replace('[', '').replace(']', '')
    if (object.hasClass('tourist') && object.attr('rel-id') !== undefined && object.attr('rel-id') !== 'undefined') {
        url = '/assets/plugins/modx-evo-lk/ajax.php?action=ajax/fileTouristRemove&file=' + object.attr('rel-file') + '&form=' + object.parents('.scan').find('.upload-row').filter('.upload:first').find('input[type=file]').attr('name').replace('[', '').replace(']', '') + '&tourist=' + object.attr('rel-id');
    }
    console.log(url);
    if (object.hasClass('unstrict')) {
        object.parents('.upload-file').remove();
    } else {
        jQuery.ajax({
            url: url,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: {},
            type: 'post',
            success: function(response) {
                console.log(response);
                if ((response.status === 'success')) {
                    object.parents('.upload-file').remove();
                };
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
});

jQuery(document).on('click', 'form button#save-but', function (e) {
    e.preventDefault();
    jQuery(this).parents('form').submit();
});

/* messages */
jQuery(document).on('click', '.detail-message button.upload-but', function(e) {
    e.preventDefault();
    var object = jQuery(this);
    jQuery(document).find(jQuery(this).parents('.message-buttons').find('input[type=file]')).trigger('click');
    return true;
});

jQuery(document).on('change', '.detail-message input[type=file]', function(e) {
    e.preventDefault();
    var object = jQuery(this);
    object.parents('.message-buttons').find('.upload-text').html('Загрузка...');
    console.log(object.prop('files'));
    if (object.parents('.upload-button').find('button.upload-but').hasClass('done')) {
        return false;
    };
    var countDown = 10;
    if (jQuery(document).find('.message-docs').children('.message-doc').length < countDown) {
        countDown = countDown - jQuery(document).find('.message-docs').children('.message-doc').length;
    };
    var filesCount = object.prop('files').length;
    if (filesCount > 0) {
        if (filesCount > countDown) {
            filesCount = countDown;
        }
        for (var i = 0; i < filesCount; i++) {
            console.log('file', object.prop('files')[i]);
            if (object.prop('files')[i].size <= parseInt(object.attr('rel-max-size'))) {
                var formData = new FormData();
                formData.append('file', object.prop('files')[i]);
                console.log('/assets/plugins/modx-evo-lk/ajax.php?action=ajax/fileUploadMsg&form=' + object.attr('id').replace('[', '').replace(']', ''));
                jQuery.ajax({
                    url: '/assets/plugins/modx-evo-lk/ajax.php?action=ajax/fileUploadMsg&form=' + object.attr('id').replace('[', '').replace(']', ''),
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    type: 'post',
                    success: function(response) {
                        console.log(response);
                        if (response.status === 'success') {
                            var html = '<div class="message-doc">'
                                + '<input type="hidden" name="fileList[' + response.result.id + '][id]" value="' + response.result.id + '" />'
                                + '<input type="hidden" name="fileList[' + response.result.id + '][file_ext]" value="' + response.result.file_ext + '" />'
                                + '<input type="hidden" name="fileList[' + response.result.id + '][file_name]" value="' + response.result.file_name + '" />'
                                + '<input type="hidden" name="fileList[' + response.result.id + '][file_path]" value="' + response.result.file_path + '" />'
                                + '<input type="hidden" name="fileList[' + response.result.id + '][file_size]" value="' + response.result.file_size + '" />'
                                + '<input type="hidden" name="fileList[' + response.result.id + '][file_type]" value="' + response.result.file_type + '" />'
                                + '<input type="hidden" name="fileList[' + response.result.id + '][form]" value="' + response.result.form + '" />'
                                + '<input type="hidden" name="fileList[' + response.result.id + '][real_file_name]" value="' + response.result.real_file_name + '" />'
                                + '<input type="hidden" name="fileList[' + response.result.id + '][upload_path]" value="' + response.result.upload_path + '" />'
                                + '<input type="hidden" name="fileList[' + response.result.id + '][web_file_path]" value="' + response.result.web_file_path + '" />'
                                + '<input type="hidden" name="fileList[' + response.result.id + '][time]" value="' + response.result.time + '" />'
                                + '<a href="' + response.result.web_file_path + '">'
                                + '<span class="icon-document"></span>'
                                + '<strong>' + response.result.real_file_name + '</strong>'
                                + '</a>'
                                + '</div>';
                            object.parents('.message-buttons').find('.message-docs').append(html);
                            object.parents('.message-buttons').find('.upload-text').html('Готово!');
                        } else {
                            object.parents('.message-buttons').find('.upload-text').html('Ошибка!');
                        };
                    },
                    error: function(error) {
                        console.log(error);
                        object.parents('.message-buttons').find('.upload-text').html('Ошибка!');
                    }
                });
                object.prop('files')[i] = {};
            };
        }
        object.val('');
    }
});

