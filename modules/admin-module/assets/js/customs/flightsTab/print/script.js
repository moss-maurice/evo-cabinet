jQuery(document).ready(function() {
    var mediaPrintStyle = document.createElement('link');

    mediaPrintStyle.rel = 'stylesheet';
    mediaPrintStyle.href = hrefLink;
    mediaPrintStyle.type = 'text/css';
    mediaPrintStyle.media = 'all';
    mediaPrintStyle.class = 'media-print-styles';

    parent.document.getElementsByTagName('head')[0].appendChild(mediaPrintStyle);

    setTimeout(function() {
        jQuery(document).find('.lk-module-voyage-send-page-to-print-button').trigger('click');
    }, 500);
});