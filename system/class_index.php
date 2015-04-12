<?php

class class_index {

    public $GLOBAL;
    public $system;

    function content() {
        
        $mtime = microtime();
        $mtime = explode(" ", $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $tstart = $mtime;

        global $db;
        $fp = 0;
        $this->system();

        if (!$url = $_GET['url']) {
            $url = $this->system['firstpage'];
            $fp = 1;
        }

        if (strpos($url, '/')) {
            $url = explode('/', $url);
        }
        else
            $url = array($url);

        foreach ($url as $k => $v) {
            $url[$k] = htmlspecialchars(strip_tags($v), ENT_QUOTES);
        }
        $this->GLOBAL['url'] = $url;

        //загружаем шаблон

        $content = file_get_contents(PATH . 'tmp/index.tpl');

        $this->page($url, $modules);

        //Запускаем модули обработки информации		
        $query = $db->Execute('select * from urls order by id asc');
        while ($res = $query->FetchRow()) {
            if ($res['in_template'] == 1) {
                if ($res['url'] == 'auth') {
                    
                } elseif ($res['url'] == 'menu')
                    $this->menu();
                else
                    $this->modules($res['module'], $url);
            }
        }

        $this->meta($url, $fp);
        foreach ($this->GLOBAL as $key => $value) {
            $content = str_replace('[' . $key . ']', $value, $content);
        }

        $content = htmlspecialchars_decode($content, ENT_QUOTES);

        $mtime = microtime();
        $mtime = explode(" ", $mtime);
        $mtime = $mtime[1] + $mtime[0];
        $tend = $mtime;
        $totaltime = ($tend - $tstart);
        $totaltime = substr($totaltime, 0, 6);
        $content = str_replace('[totaltime]', $totaltime, $content);

        echo $content;
    }

    function system() {
        global $db;
        $query = $db->Execute('select * from system');
        $res = $query->FetchRow();
        $this->system = $res;
    }

    function meta($url, $fp) {
        if (!$this->GLOBAL['title'])
            $this->GLOBAL['title'] = $this->system['title'];
        else
            $this->GLOBAL['title'] .= $this->system['title_r'] . $this->system['title'];

        if (!$this->GLOBAL['description'])
            $this->GLOBAL['description'] = $this->system['description'];
        else
            $this->GLOBAL['description'] .= $this->system['description_r'] . $this->system['description'];

        if (!$this->GLOBAL['keywords'])
            $this->GLOBAL['keywords'] = $this->system['keywords'];
        else
            $this->GLOBAL['keywords'] .= $this->system['keywords_r'] . $this->system['keywords'];

        $this->GLOBAL['footer'] = $this->system['footer'];
        $this->GLOBAL['phones'] = $this->system['phones'];
        $this->GLOBAL['vr'] = nl2br($this->system['vr']);

        $this->GLOBAL['logo'] = $this->system['logo'];
    }

    function menu() {
        global $db;

        $uid = (int) $this->GLOBAL['user']['id'];



        $query = $db->Execute('select * from menu_template');
        while ($res = $query->FetchRow()) {
            $menuid = $res['id'];
            $menu = '';
            $query2 = $db->Execute("select * from menu where id=$menuid order by position asc");
            while ($res2 = $query2->FetchRow()) {
                if ($menu)
                    $menu .= file_get_contents(PATH . 'tmp/' . $res['template'] . '/span.tpl');
                $url = $res2['url'];
                if ($this->GLOBAL['url'][0] != 'pages') {
                    $url = str_replace('/', '', $url);
                    $url = str_replace('.html', '', $url);
                    if ($url != $this->GLOBAL['url'][0])
                        $menu .= file_get_contents(PATH . 'tmp/' . $res['template'] . '/a.tpl');
                    else
                        $menu .= file_get_contents(PATH . 'tmp/' . $res['template'] . '/a_sel.tpl');
                } else {
                    if ($url != '/' . $this->GLOBAL['url'][0] . '/' . $this->GLOBAL['url'][1] . '.html')
                        $menu .= file_get_contents(PATH . 'tmp/' . $res['template'] . '/a.tpl');
                    else
                        $menu .= file_get_contents(PATH . 'tmp/' . $res['template'] . '/a_sel.tpl');
                }
                $menu = str_replace('[url]', $res2['url'], $menu);
                $menu = str_replace('[text]', $res2['text'], $menu);
                if ($res2['blank'] == 1)
                    $blank = 'target="_blank"';
                else
                    $blank = '';
                $menu = str_replace('[blank]', $blank, $menu);
            }


            if ($new_messages) {
                $new_messages = '<span style="color:red">(' . $new_messages . ')</span>';
                $menu = str_replace('[new_mess]', $new_messages, $menu);
            }
            else
                $menu = str_replace('[new_mess]', '', $menu);

            if ($new_friends) {
                $new_friends = '<span style="color:red">(' . $new_friends . ')</span>';
                $menu = str_replace('[new_friends]', $new_friends, $menu);
            }
            else
                $menu = str_replace('[new_friends]', '', $menu);

            $this->GLOBAL[$res['template']] = $menu;
        }
    }

    function page($url) {
        global $db;

        $u = $url[0];
        $query = $db->Execute("select * from urls where url='$u'");
        $res = $query->FetchRow();
        $module = $res['module'];
        if (!$module) {
            header('location:/404.html');
            exit;
        }
        if ('auth' != $url[0])
            include PATH . 'modules/' . $module . '/class_' . $module . '.php';

        $class = new $module;

        $GLOBAL = $class->content($db, $this->GLOBAL['url']);

        $this->GLOBAL['title'] = $GLOBAL['title'];
        $this->GLOBAL['description'] = $GLOBAL['description'];
        $this->GLOBAL['keywords'] = $GLOBAL['keywords'];
        $this->GLOBAL['content'] = $GLOBAL['content'];
        $this->GLOBAL['page_title'] = $GLOBAL['page_title'];
    }

    function modules($name, $url) {
        global $db;
        if ($name != $url[0])
            include PATH . 'modules/' . $name . '/class_' . $name . '.php';
        $mod = new $name();
        $this->GLOBAL[$name] = $mod->in_template($db, $url);
    }

}

?>