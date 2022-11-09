let loggerObject;
let moduleObject;
let bufferObject;
let ordersTabScripts;
let usersTabScripts;
let flightsTabScripts;
let moduleOverPrice;
let dropzone;
let uploadMessageFiles = [];

let debug = true;

defer(function() {
    jQuery(document).ready(function($) {
        jQuery(parent.document).find('.media-print-styles').remove();

        // Инициализация логгера
        loggerObject = new Logger();

        // Инициализация буфера
        bufferObject = new Buffer();
        // Перечисляем имена методов, которые нужно игнорировать при помещении в буфер
        //bufferObject.methodsIgnored.push('update');

        // Инициализация модуля
        moduleObject = new Module();
        moduleObject.init();

        // Инициализация скриптов табов. Как правила, там только хуки
        ordersTabScripts = new OrdersTabScripts();
        usersTabScripts = new UsersTabScripts()
        flightsTabScripts = new FlightsTabScripts();

        // ???
        moduleOverPrice = new Order();
    });
});

function defer(method) {
    if (window.jQuery) {
        method();
    } else {
        setTimeout(function() {
            defer(method)
        }, 50);
    }
}