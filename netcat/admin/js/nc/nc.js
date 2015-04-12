(function(window, $) {
    /*global nc */

    /**
     * Проверка типа переменной
     *
     * @example
     * nc.is({}, 'object'); // return true
     *
     * @param  {mixed}  obj  Проверяемая переменная
     * @param  {string} type Название типа переменной
     * @return {bool}
     */
    var is = function(obj, type) {
        return typeof obj === type;
    };

    // Singleton
    if (is(window.nc, 'function')) {
        return window.nc;
    }


    //=== GLOBALS ==============================================================
    if (typeof JSON !== 'object') {
        /*jshint -W020 */
        JSON = {};
    }
    JSON.stringify = JSON.stringify || function (obj) {
        var t = typeof (obj);
        if (t !== "object" || obj === null) {
            // simple data type
            if (t === "string") {
                obj = '"'+obj+'"';
            }
            return String(obj);
        }
        else {
            // recurse array or object
            var n, v, json = [], arr = (obj && obj.constructor === Array);
            for (n in obj) {
                v = obj[n]; t = typeof(v);
                if (t === "string") {
                    v = '"'+v+'"';
                }
                else if (t === "object" && v !== null) {
                    v = JSON.stringify(v);
                }
                json.push((arr ? "" : '"' + n + '":') + String(v));
            }
            return (arr ? "[" : "{") + String(json) + (arr ? "]" : "}");
        }
    };

    //=== PRIVATE ==============================================================


    var config = {
        netcat_folder: '/netcat/',
        custom_scroll: true
    };

    // var process_list = {};
    var current_view = false;

    //=== PUBLIC ===============================================================


    /**
     * Функция обертка для $nc()
     * Важно знать: $nc != nc. т.е. нельзя использовать методы jQuery через объект nc: nc.getJSON() // не верно
     * Для этого следует использовать другой способ: nc.jQuery.getJSON() // правильно
     *
     * @example
     * nc('body').css({background: '#F00'});
     *
     * @return {jQuery}
     */
    var nc = function() {
        return $.apply(null, arguments);
    };


    //--------------------------------------------------------------------------

    /**
     * Ссылка на jQuery
     *
     * @type {[type]}
     */
    nc.$ = $;

    /**
     * Ссылка на объект window
     *
     * @type {[type]}
     */
    nc.window = window;

    /**
     * Ссылка на глобальный объект nc. Для простой страницы nc === nc.root
     * Но если мы находимся во фрейме то nc.root - ссылка на объект основного документа
     *
     * @type {nc}
     */
    nc.root = nc;

    /**
     * Контейнер дочерних объектов (объектов iframe) зарегистрированных с помощью nc.register_view(view_key);
     *
     * @example
     * nc.register_view('tree');
     * nc.view.tree('body').css({color:'red'});
     *
     * @type {object}
     */
    nc.view = {};
    nc.process_list = {};

    //--------------------------------------------------------------------------


    if (is(window.parent.nc, 'function')) {
        nc.root = window.parent.nc.root;
    }


    //--------------------------------------------------------------------------


    nc.is = is;


    /**
     * Проверяет существует ли параметр в объекте
     *
     * @example
     * nc.key_exists('a', {a:false, b:true}); // return true
     *
     * @param  {string} key Название свойства (параметра)
     * @param  {object} obj Объект
     * @return {bool}
     */
    nc.key_exists = function(key, obj) {
        return obj ? obj.hasOwnProperty(key) : false;
    };


    /**
     * Проверка: Является ли переменная объектом?
     *
     * @param  {mixed} obj Проверяемый объект
     * @return {bool}
     */
    nc.is_object = function(obj) {
        return is(obj, 'object');
    };


    /**
     * Проверка: Переменная не определена?
     *
     * @param  {mixed} obj Проверяемый объект
     * @return {bool}
     */
    nc.is_undefined = function(obj) {
        return is(obj, 'undefined');
    };


    /**
     * Проверка: Переменная является строкой?
     *
     * @param  {mixed} obj Проверяемый объект
     * @return {bool}
     */
    nc.is_string = function(obj) {
        return is(obj, 'string');
    };


    /**
     * Проверка: Переменная является функцией?
     *
     * @param  {mixed} obj Проверяемый объект
     * @return {bool}
     */
    nc.is_function = function(obj) {
        return is(obj, 'function');
    };


    /**
     * Возвращает true если переменная пуста
     *
     * @param  {mixed} obj Проверяемый объект
     * @return {bool}
     */
    nc.is_empty = function(obj) {

        switch (true) {
            case ( ! obj):
                return true;
            case (obj.length && obj.length > 0):
                return false;
            case (obj.length === 0):
                return true;
        }

        for (var key in obj) {
            if (obj.hasOwnProperty(key)) {
                return false;
            }
        }

        return true;
    };

    //--------------------------------------------------------------------------

    nc.is_touch = function()
    {
        var userag = navigator.userAgent.toLowerCase();
        var agents = ['iphone', 'ipad', 'ipod', 'android'];

        for (var i in agents) {
            if (userag.indexOf( agents[i] ) !== -1) {
                return agents[i];
            }
        }

        return false;
    };

    //--------------------------------------------------------------------------


    /**
     * Является ли текущий объект главным (не фреймовым)
     *
     * @return {bool}
     */
    nc.is_root = function() {
        return nc.root === nc;
    };


    //--------------------------------------------------------------------------


    /**
     * Добавление нового модуля в объект nc
     *
     * @example
     * nc.ext('mod', {test:function(){return 'ok';}});
     * nc.mod.test() // return 'ok'
     *
     * @example Монтирование как подмодуль
     * nc.ext('mod', {test:function(){return 'ok';}}, 'ui');
     * nc.ui.mod.test() // return 'ok'
     *
     * @param  {string} name  Название (ключ) новоно модуля
     * @param  {mixed}  obj   Объект модуля
     * @param  {string} point "Точка монтирования" модуля. (по умолчанию nc.root)
     * @return {null}
     */
    nc.ext = function(name, obj, point) {

        point = point || 'root';

        if (nc.is_undefined(nc[point][name])) {
            nc[point][name] = obj;

            if (nc.key_exists('__init', obj) && nc.is_function(obj.__init)) {
                obj.__init();
            }
        }
        // переназначаем объект для всех nc.{^root}
        else if (point !== 'root') {
            nc[point][name] = obj;
        }

        if (point === 'root') {
            nc.set_global(name);
        }
    };


    /**
     * Делает объект "глобальным" - для всех копий nc
     *
     * @param  {string}  name  Имя объекта ( nc[ {Имя объекта} ] )
     * @param  {[object]} obj  Опционально. Сам объект ( nc[ {Имя объекта} ] = {Сам объект} )
     * @return {null}
     */
    nc.set_global = function(name, obj) {
        if (obj) {
            nc.root[name] = obj;
        }
        nc[name] = nc.root[name];
        for (var k in nc.view) {
            nc.view[k][name] = nc.root[name];
        }
    };


    /**
     * Назначает текущему объекту имя и делает его доступным в других экземплярах nc (iframe)
     *
     * @example
     * nc.register_view('tree');
     * nc.view.tree('body').css({color:'red'});
     *
     * @param  {string} name Имя текущего представления
     * @return {void}
     */
    nc._view = '';
    nc.register_view = function(name) {
        for (var k in nc.root.process_list) {
            if (nc.root.process_list[k].view === name){
                delete(nc.root.process_list[k]);
            }
        }
        nc.process_stop();
        current_view = name;
        nc.ext(name, nc, 'view');
        nc.event.call(['nc','register_view'], name);
    };


    /**
     * Отображать процесс загрузки
     *
     * @example
     * nc.process_start('save.user.form', nc('#btn_save_user_form') );
     *
     * @param  {string} name           Идентификатор запускаемого процесса
     * @param  {[HTMLObject]} link_obj Опционально: Ссылка на HTML элемент "запустивший" процесс
     * @return {null}
     */
    nc.process_start = function(name, obj) {
        nc.root.process_list[name] = {
            view: current_view,
            obj:  obj
        };
        nc.ui.loader_show(obj);
    };


    /**
     * Остановка отображения процесса загрузки
     *
     * @example
     * nc.process_stop('save.user.form');
     *
     * @param  {string}  name                  Идентификатор запускаемого процесса
     * @param  {integer} delay_stop_animation Задержка перед остановкой индикации. Дабы увидеть очень быстрые процессы.
     * @return {null}
     */
    nc.process_stop = function(name, delay_stop_animation) {
        var $obj;
        if (name && nc.key_exists(name, nc.root.process_list)) {
            $obj = nc(nc.root.process_list[name].obj);
            if ($obj) {
                nc.ui.loader_hide($obj, delay_stop_animation);
            }
            delete nc.root.process_list[name];
        }

        if ( nc.is_empty(nc.root.process_list)) {
            nc.ui.loader_hide(null, delay_stop_animation);
        }
    };



    /**
     * Возвращает объект настроек или отдельный параметр настроек
     *
     * @example Все настройки
     * nc.config()
     *
     * @example Получение значения config.netcat_folder
     * nc.config('netcat_folder')
     *
     * @example Установка значения config.netcat_folder = '/site_b/netcat/'
     * nc.config('netcat_folder', '/site_b/netcat/')
     *
     * @param  {[string]} key   [description]
     * @param  {[type]} value [description]
     * @return {[type]}       [description]
     */
    nc.config = function(key, value) {
        if ( nc.is_undefined(key) ) {
            return config;
        }

        if ( ! nc.is_undefined(value) ) {
            config[key] = value;
        }
        else if ( nc.is_object(key) ) {
            for (var k in key) {
                config[k] = key[k];
            }
            return config;
        }

        return config[key];
    };


    //--------------------------------------------------------------------------

    nc.set_global('view');
    nc.set_global('config');

    window.nc = nc;

})(window, jQuery);



/* *****************************************************************************
    nc.event
***************************************************************************** */

//global nc
(function(nc){

    //=== PRIVATE ==============================================================


    var events = {};


    //=== PUBLIC ===============================================================

    /**
     * Регистрация события
     * @param  {string}   selector Название события
     * @param  {Function} fn       Callback функуция
     * @return {null}
     */
    var event = function(selector, fn){
        if (nc.is_undefined(events[selector])) {
            events[selector] = [];
        }
        events[selector].push(fn);
    };


    //--------------------------------------------------------------------------

    /**
     * Текущиее событие
     *
     * @example
     * Для nc.event('dashboard.resize', fn);
     * event.selector === ['dashboard', 'resize'];
     *
     * @type {Array}
     */
    event.selector = [];


    //--------------------------------------------------------------------------

    /**
     * Вызоы событий
     *
     * @example
     * nc.event(['dashboard.resize'], function(event, name){ alert('Hello ' + name); });
     * nc.event(['dashboard'], function(event, name){ alert('By by ' + name); });
     * nc.event.call(['dashboard', 'resize'], 'Zorro'); // Выведет "Hello Zorro", а потом "By by Zorro"
     * nc.event.call(['dashboard'], 'Spok'); // Выведет "By by Spok"
     *
     * @param  {Array} selector Массив с путем к событию ( ['dashboard', 'resize'] )
     * @param  {mixed} arguments... Аргументы передоваемые обработчику
     * @return {null}
     */
    event.call = function(selector){

        event.selector = selector.slice(); // clone

        var i, j,
            call_selectors = [],
            last           = '';

        for (i in selector) {
            last = (last ? last + '.' : '') + selector[i];
            call_selectors.unshift( last );
        }

        var args = [].slice.call(arguments); // clone arguments
        args[0] = event;

        for (i in call_selectors) {
            if ( events[call_selectors[i]] ) {
                for (j in events[call_selectors[i]]) {
                    events[call_selectors[i]][j].apply(null, args);
                }
            }
        }
    };


    //--------------------------------------------------------------------------


    nc.ext('event', event);

})(nc);




/* *****************************************************************************
    nc.ui
***************************************************************************** */

(function(nc){

    //=== PRIVATE ==============================================================


    var loader_process_timeout = false;


    //=== PUBLIC ===============================================================


    var ui = function(){};


    //--------------------------------------------------------------------------


    ui.__init = function() {};


    /**
     * Отображать процесс загрузки
     */
    ui.loader_show = function(obj) {
        if (obj) {
            nc(obj).addClass('nc--loading');
        }

        if (loader_process_timeout) {
            clearTimeout(loader_process_timeout);
        }
        nc.root('#nc-navbar-loader').show();
    };


    /**
     * Остановка отображения процесса загрузки
     */
    ui.loader_hide = function($link, delay_stop_animation) {

        delay_stop_animation = delay_stop_animation || 300;

        if ($link) {
            setTimeout(function(){
                $link.removeClass('nc--loading');
            }, delay_stop_animation);
        }
        else {
            loader_process_timeout = setTimeout(function(){
                nc.root('#nc-navbar-loader').hide();
            }, delay_stop_animation);
        }
    };



    /**
     * Расширение объекта nc.ui
     * @param  {string} name  Название (ключ) новоно модуля
     * @param  {mixed}  obj   Объект модуля
     * @return {null}
     */
    ui.ext = function(name, obj){
        nc.root.ui[name] = obj;
        // nc.ui[name] = nc.root.ui[name];
        // nc.set_global('ui');
    };


    /**
     * Заменить стандартный скролл кастомным
     * @param  {jQuery} $obj Объект со стандартным скроллом
     * @return {jScrollPane,bool}
     */
    ui.custom_scroll = function($obj) {
        if (nc.config('custom_scroll') && $obj.length) {
            return $obj.jScrollPane({
                autoReinitialise: true
            });
        }
        return false;
    };

    //--------------------------------------------------------------------------

    nc.ext('ui', ui);

})(nc);
