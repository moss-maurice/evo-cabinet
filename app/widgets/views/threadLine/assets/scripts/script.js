defer(function() {
    jQuery(document).ready(function($) {
        dropzoneInit();

        if (!('ThreadLineWidget' in window)) {
            jQuery(document)
                .on('click', '.modx-evo-lk-admin .lk-module-thread-add-message-button', function(event) {
                    event.preventDefault();

                    console.log(uploadMessageFiles);

                    var tabName = moduleObject.getMainDomObject().find('#order-item').attr('rel-tab');
                    var methodName = jQuery(this).attr('rel-method');
                    var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);
                    var data = {
                        item_id: moduleObject.getMainDomObject().find('#order-item').attr('rel-item-id'),
                        message: moduleObject.getMainDomObject().find('textarea[name=message]').val(),
                        replyToMessageId: moduleObject.getMainDomObject().find('input[name=reply-to-message-id]').val(),
                        files: uploadMessageFiles,
                    }

                    uploadMessageFiles = [];

                    moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
                })
                .on('click', '.modx-evo-lk-admin .lk-module-thread-update-message-button', function(event) {
                    event.preventDefault();

                    console.log(uploadMessageFiles);

                    var tabName = moduleObject.getMainDomObject().find('#order-item').attr('rel-tab');
                    var methodName = jQuery(this).attr('rel-method');
                    var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);
                    var data = {
                        item_id: moduleObject.getMainDomObject().find('#order-item').attr('rel-item-id'),
                        message: moduleObject.getMainDomObject().find('textarea[name=message]').val(),
                        messageId: moduleObject.getMainDomObject().find('input[name=message-id]').val(),
                        senderId: moduleObject.getMainDomObject().find('input[name=sender-id]').val(),
                        replyToMessageId: moduleObject.getMainDomObject().find('input[name=reply-to-message-id]').val(),
                        files: uploadMessageFiles,
                    }

                    moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
                })
                .on('click', '.modx-evo-lk-admin .lk-module-thread-delete-message-button', function(event) {
                    event.preventDefault();

                    var tabName = moduleObject.getMainDomObject().find('#order-item').attr('rel-tab');
                    var methodName = jQuery(this).attr('rel-method');
                    var pageDomObject = moduleObject.getMainDomObject().find('.tab-page').filter('#tab_' + tabName);
                    var data = {
                        item_id: moduleObject.getMainDomObject().find('#order-item').attr('rel-item-id'),
                        messageId: jQuery(this).parents('tr').attr('rel-message-id'),
                    }

                    moduleObject.setTabContent(pageDomObject, tabName, methodName, data);
                })
                .on('click', '.modx-evo-lk-admin .lk-module-thread-edit-message-button', function(event) {
                    event.preventDefault();

                    console.log(uploadMessageFiles);

                    dropzoneClear();

                    moduleObject.getMainDomObject().find('input[name=reply-to-message-id]').val(jQuery(this).parents('tr').attr('rel-reply-to-message-id'));
                    moduleObject.getMainDomObject().find('input[name=message-id]').val(jQuery(this).parents('tr').attr('rel-message-id'));
                    moduleObject.getMainDomObject().find('input[name=sender-id]').val(jQuery(this).parents('tr').attr('rel-sender-id'));
                    moduleObject.getMainDomObject().find('textarea[name=message]').val(jQuery(this).parents('tr').attr('rel-message'));

                    jQuery(this).parents('tr').find('ul.files').find('li.available').each(function(i) {
                        var mockFile = {
                            path: jQuery(this).attr('rel-file-path'),
                            name: jQuery(this).attr('rel-file-name'),
                            size: parseInt(jQuery(this).attr('rel-file-size')),
                            type: jQuery(this).attr('rel-file-type'),
                            id: jQuery(this).attr('rel-file-id'),
                        };

                        dropzone.displayExistingFile(mockFile, jQuery(this).attr('rel-file-path'));
                    });

                    jQuery(document).find('.modx-evo-lk-admin .add-control').addClass('d-none').removeClass('d-block');
                    jQuery(document).find('.modx-evo-lk-admin .edit-control').addClass('d-block').removeClass('d-none');
                })
                .on('click', '.modx-evo-lk-admin .lk-module-thread-cancel-edit-message-button', function(event) {
                    event.preventDefault();

                    dropzoneClear();

                    moduleObject.getMainDomObject().find('input[name=reply-to-message-id]').val('');
                    moduleObject.getMainDomObject().find('input[name=message-id]').val('');
                    moduleObject.getMainDomObject().find('input[name=sender-id]').val(0);
                    moduleObject.getMainDomObject().find('textarea[name=message]').val('');

                    jQuery(document).find('.modx-evo-lk-admin .add-control').addClass('d-block').removeClass('d-none');
                    jQuery(document).find('.modx-evo-lk-admin .edit-control').addClass('d-none').removeClass('d-block');
                })
                .on('click', '.modx-evo-lk-admin .lk-module-thread-reply-message-button', function(event) {
                    event.preventDefault();

                    moduleObject.getMainDomObject().find('input[name=reply-to-message-id]').val(jQuery(this).parents('tr').attr('rel-message-id'));
                    moduleObject.getMainDomObject().find('.message-quote').find('span.text').html(jQuery(this).parents('tr').find('.message'));
                    moduleObject.getMainDomObject().find('.message-quote').removeClass('d-none');
                })
                .on('click', '.modx-evo-lk-admin .lk-module-message-quote-cancel-button', function(event) {
                    event.preventDefault();

                    moduleObject.getMainDomObject().find('input[name=reply-to-message-id]').val('');
                    moduleObject.getMainDomObject().find('.message-quote').find('span.text').html('');
                    moduleObject.getMainDomObject().find('.message-quote').addClass('d-none');
                });

            dropzone
                .on('success', function(file, response) {
                    console.log('success');

                    if (response) {
                        var data = response;

                        data.uuid = file.upload.uuid;

                        uploadMessageFiles.push(data);
                    }
                })
                .on('removedfile', function(file, response) {
                    console.log('removedfile');

                    for (var i = 0; i < uploadMessageFiles.length; i++) {
                        if (file.upload !== undefined) {
                            if (uploadMessageFiles[i].uuid != file.upload.uuid) {
                                continue;
                            } else {
                                uploadMessageFiles.splice(i, 1);

                                break;
                            }
                        } else {
                            if (uploadMessageFiles[i].file != file.path) {
                                continue;
                            } else {
                                uploadMessageFiles.splice(i, 1);

                                break;
                            }
                        }
                    }

                    if (jQuery(document).find('form#media-upload').find('.dz-preview').length > 0) {
                        jQuery(document).find('form#media-upload').addClass('dz-started').addClass('dz-clickable');
                    } else {
                        jQuery(document).find('form#media-upload').removeClass('dz-started').removeClass('dz-clickable');
                    }
                })
                .on('resetFiles', function() {
                    console.log('removedfile');

                    this.removeAllFiles(true);

                    jQuery(document).find('form#media-upload').removeClass('dz-started').removeClass('dz-clickable').find('.dz-preview').each(function(i) {
                        jQuery(this).remove();
                    });

                    console.log(this);
                })
                .on('addedfile', function(file) {
                    console.log('addedfile');

                    if (file.upload === undefined) {
                        var data = {
                            file: file.path,
                            name: file.name,
                            type: file.type,
                            size: file.size,
                            id: file.id,
                        }

                        uploadMessageFiles.push(data);
                    }
                });

            ThreadLineWidget = true;
        }
    });
});

function defer(method) {
    if (moduleObject && window.jQuery) {
        method();
    } else {
        setTimeout(function() {
            defer(method)
        }, 50);
    }
};

function dropzoneInit() {
    dropzone = new Dropzone('form.dropzone', {
        addRemoveLinks: true,
        url: '/assets/plugins/modx-evo-lk/modules/admin-module/api.php?tabName=ordersTab&method=fileUpload'
    });
};

function dropzoneClear() {
    dropzone.emit('resetFiles');

    uploadMessageFiles = [];
    uploadMessageFiles.length = 0;
}