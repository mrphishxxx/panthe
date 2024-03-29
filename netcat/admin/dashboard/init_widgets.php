<?php

//--------------------------------------------------------------------------

$system_colors = array(
    // 1  => "Small Business",
    2  => "blue",
    3  => "red",
    4  => "green",
    5  => "cyan",
    6  => "orange",
    // 7  => "SEO",
    8  => "dark",
    // 10 => "Personal",
    12 => "yellow",
);

//--------------------------------------------------------------------------
// Системные виджеты
//--------------------------------------------------------------------------

// О системе
$nc_core->dashboard->register('sys_netcat', array(
    'type'       => 'system',
    'title'      => DASHBOARD_WIDGET_SYS_NETCAT,
    'controller' => $ADMIN_PATH . 'dashboard/widgets/netcat.php',
    'settings'   => false,
    'size'       => array(1,1),
    'color'      => isset($system_colors[$SYSTEM_ID]) ? $system_colors[$SYSTEM_ID] : 'olive',
    'resizeble'  => false,
    'fullscreen' => $ADMIN_PATH . 'about/index.php',
    'icon'       => 'nc-icon-x nc--netcat nc--white',
));


// Пользователь
$nc_core->dashboard->register('sys_user', array(
    'type'       => 'system',
    'title'      => CONTROL_USER,
    'controller' => $ADMIN_PATH . 'dashboard/widgets/user.php',
    'size'       => array(1,1),
    'color'      => 'orange',
    'settings'   => false,
    'resizeble'  => true,
    'fullscreen' => false,
));


// Корзина удаленных объектов
$nc_core->dashboard->register('sys_tools_trash', array(
    'type'       => 'system',
    'title'      => TOOLS_TRASH,
    'controller' => $ADMIN_PATH . 'dashboard/widgets/tools_trash.php',
    'size'       => array(1,1),
    'color'      => 'red',
    'settings'   => false,
    'resizeble'  => false,
    'fullscreen' => $ADMIN_PATH . 'trash/index.php',
    'icon'       => 'nc-icon-x nc--tools-trash nc--white',
));


// Управление задачами
$nc_core->dashboard->register('sys_tools_cron', array(
    'type'       => 'system',
    'title'      => TOOLS_CRON,
    'controller' => $ADMIN_PATH . 'dashboard/widgets/tools_cron.php',
    'size'       => array(1,1),
    'color'      => 'dark',
    'settings'   => false,
    'resizeble'  => true,
    'fullscreen' => $ADMIN_PATH . 'crontasks.php',
    'icon'       => 'nc-icon-x nc--tasks nc--white',
));


if ($perm->isAccessSiteMap() || $perm->isGuest()) {
    // Избранные разделы
    $nc_core->dashboard->register('sys_favorites', array(
        'type'       => 'system',
        'title'      => FAVORITE_HEADERTEXT,
        'controller' => $ADMIN_PATH . 'dashboard/widgets/favorites.php',
        'size'       => array(3,2),
        'color'      => 'lighten',
        'settings'   => false,
        'resizeble'  => true,
        'fullscreen' => false,
    ));
}

// // Виджет компонент
// $nc_core->dashboard->register('sys_widgetclass', array(
//     'type'       => 'system',
//     'title'      => 'Виджет компонент',
//     'controller' => $ADMIN_PATH . 'dashboard/widgets/widgetclass.php',
//     'settings'   => true,
//     'size'       => array(2,1),
//     'color'      => 'light',
//     'fullscreen' => false,
// ));



//--------------------------------------------------------------------------
// Виджеты модулей
//--------------------------------------------------------------------------

$enabled_modules = array();
$modules = $nc_core->modules->get_data();
if ( ! empty($modules) ) {
    foreach ($modules as $module) {
        $enabled_modules[$module['Keyword']] = (bool)$module['Checked'];
    }
}


// Поиск (статистика)
if ( ! empty($enabled_modules['search']) ) {
    $nc_core->dashboard->register('mod_search', array(
        'type'       => 'module',
        'title'      => NETCAT_MODULE_SEARCH_TITLE,
        'controller' => $GLOBALS['HTTP_ROOT_PATH'] . 'modules/search/widget.php',
        'size'       => array(2,2),
        'color'      => 'purple',
        'fullscreen' => $GLOBALS['HTTP_ROOT_PATH'] . 'modules/search/admin.php',
        'icon'       => 'nc-icon-x nc--mod-search nc--white',
    ));
}


// Логгирование
if ( ! empty($enabled_modules['logging']) ) {
    $nc_core->dashboard->register('mod_logging', array(
        'type'       => 'module',
        'title'      => NETCAT_MODULE_LOGGING,
        'controller' => $GLOBALS['HTTP_ROOT_PATH'] . 'modules/logging/widget.php',
        'size'       => array(4,1),
        'color'      => 'lighten',
        'fullscreen' => $SUB_FOLDER . $HTTP_ROOT_PATH . 'modules/logging/admin.php',
        'resizeble'  => true,
        'icon'       => 'nc-icon-x nc--mod-logging',
    ));
}

// Комментраии (статистика)
if ( ! empty($enabled_modules['comments']) ) {
    $nc_core->dashboard->register('mod_comments', array(
        'type'       => 'module',
        'title'      => NETCAT_MODULE_COMMENTS,
        'controller' => $GLOBALS['HTTP_ROOT_PATH'] . 'modules/comments/widget.php',
        'size'       => array(1,1),
        'color'      => 'cyan',
        'resizeble'  => false,
        'fullscreen' => $SUB_FOLDER . $HTTP_ROOT_PATH . 'modules/comments/admin.php',
        'icon'       => 'nc-icon-x nc--mod-comments nc--white',
    ));
}


// Пользователи (статистика)
if ( ! empty($enabled_modules['auth']) ) {
    $nc_core->dashboard->register('mod_auth', array(
        'type'       => 'module',
        'title'      => DASHBOARD_WIDGET_MOD_AUTH,
        'controller' => $ADMIN_PATH . 'dashboard/widgets/user_stat.php',
        'size'       => array(1,2),
        'color'      => 'yellow',
        'resizeble'  => false,
        // 'fullscreen' => $SUB_FOLDER . $HTTP_ROOT_PATH . 'modules/comments/admin.php',
    ));
}


// Блоги (статистика)
if ( ! empty($enabled_modules['blog']) ) {
    $nc_core->dashboard->register('mod_blog', array(
        'type'       => 'module',
        'title'      => NETCAT_MODULE_BLOG,
        'controller' => $SUB_FOLDER . $HTTP_ROOT_PATH . 'modules/blog/widget.php',
        'size'       => array(2,1),
        'color'      => 'dark',
        'resizeble'  => false,
        // 'fullscreen' => $SUB_FOLDER . $HTTP_ROOT_PATH . 'modules/comments/admin.php',
    ));
}

if ( ! ($Catalogue_ID = $nc_core->catalogue->get_current('Catalogue_ID')) ) {
    $nc_core->catalogue->get_by_host_name($nc_core->HTTP_HOST, true);
    $Catalogue_ID = $nc_core->catalogue->get_current('Catalogue_ID');
}

// Интернет магазин (статистика заказов)
if ( ! empty($enabled_modules['netshop']) ) {
    $nc_core->dashboard->register('mod_netshop', array(
        'type'       => 'module',
        'title'      => NETCAT_MODULE_NETSHOP,
        'controller' => $SUB_FOLDER . $HTTP_ROOT_PATH . 'modules/netshop/widget.php',
        'size'       => array(1,1),
        'color'      => 'green',
        'resizeble'  => false,
        'query'      => 'Catalogue_ID=' . $Catalogue_ID,
        // 'fullscreen' => $SUB_FOLDER . $HTTP_ROOT_PATH . 'modules/comments/admin.php',
    ));
}


// // Календарь
// if ( ! empty($enabled_modules['calendar']) ) {
//     $nc_core->dashboard->register('mod_calendar', array(
//         'type'       => 'module',
//         'title'      => 'Календарь',
//         'controller' => $GLOBALS['HTTP_ROOT_PATH'] . 'modules/calendar/widget.php',
//         'settings'   => true,
//         'size'       => array(2,1),
//         'color'      => 'blue',
//     ));
// }

