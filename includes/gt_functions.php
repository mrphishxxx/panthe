<?php
    /**
     * User: silver
     * Date: 24.07.14
     * Time: 14:39
     */

    error_reporting(E_ERROR);
    set_time_limit(0);

    phpQuery::ajaxAllowHost(
        'miralinks.ru',
        'www.miralinks.ru'
    );

    $ajaxRequestWriteData = array('bRegex' => 'undefined',
        'bRegex_0' => 'false',
        'bRegex_1' => 'false',
        'bRegex_2' => 'false',
        'bRegex_3' => 'false',
        'bRegex_4' => 'false',
        'bRegex_5' => 'false',
        'bRegex_6' => 'false',
        'bRegex_7' => 'false',
        'bRegex_8' => 'false',
        'bSearchable_0' => 'true',
        'bSearchable_1' => 'true',
        'bSearchable_2' => 'true',
        'bSearchable_3' => 'true',
        'bSearchable_4' => 'true',
        'bSearchable_5' => 'true',
        'bSearchable_6' => 'true',
        'bSearchable_7' => 'true',
        'bSearchable_8' => 'true',
        'bSortable_0' => 'false',
        'bSortable_1' => 'true',
        'bSortable_2' => 'true',
        'bSortable_3' => 'true',
        'bSortable_4' => 'true',
        'bSortable_5' => 'true',
        'bSortable_6' => 'true',
        'bSortable_7' => 'true',
        'bSortable_8' => 'false',
        'iColumns' => '9',
        'iDisplayLength' => '300',
        'iDisplayStart' => '0',
        'iSortCol_0' => '6',
        'iSortingCols' => '1',
        'mDataProp_0' => '0',
        'mDataProp_1' => '1',
        'mDataProp_2' => '2',
        'mDataProp_3' => '3',
        'mDataProp_4' => '4',
        'mDataProp_5' => '5',
        'mDataProp_6' => '6',
        'mDataProp_7' => '7',
        'mDataProp_8' => '8',
        'sColumns' => '',
        'sEcho' => '6',
        'sSearch' => 'undefined',
        'sSearch_0' => '',
        'sSearch_1' => '',
        'sSearch_2' => '',
        'sSearch_3' => '',
        'sSearch_4' => '',
        'sSearch_5' => '',
        'sSearch_6' => '',
        'sSearch_7' => '',
        'sSearch_8' => '',
        'sSortDir_0' => 'desc',
        'tsDataTableConfigType' => 'dataTable.WmAllCmArticlesList',
        'tsDataTableType' => 'with_checking',
        'type' => 'null'
    );

    $ajaxRequestPlaceData = array('bRegex' => 'undefined',
        'bRegex_0' => 'false',
        'bRegex_1' => 'false',
        'bRegex_2' => 'false',
        'bRegex_3' => 'false',
        'bRegex_4' => 'false',
        'bRegex_5' => 'false',
        'bRegex_6' => 'false',
        'bRegex_7' => 'false',
        'bSearchable_0' => 'true',
        'bSearchable_1' => 'true',
        'bSearchable_2' => 'true',
        'bSearchable_3' => 'true',
        'bSearchable_4' => 'true',
        'bSearchable_5' => 'true',
        'bSearchable_6' => 'true',
        'bSearchable_7' => 'true',
        'bSortable_0' => 'false',
        'bSortable_1' => 'true',
        'bSortable_2' => 'true',
        'bSortable_3' => 'true',
        'bSortable_4' => 'true',
        'bSortable_5' => 'true',
        'bSortable_6' => 'true',
        'bSortable_7' => 'false',
        'iColumns' => '8',
        'iDisplayLength' => '15',
        'iDisplayStart' => '0',
        'iSortCol_0' => '5',
        'iSortingCols' => '1',
        'mDataProp_0' => '0',
        'mDataProp_1' => '1',
        'mDataProp_2' => '2',
        'mDataProp_3' => '3',
        'mDataProp_4' => '4',
        'mDataProp_5' => '5',
        'mDataProp_6' => '6',
        'mDataProp_7' => '7',
        'sColumns' => '',
        'sEcho' => '2',
        'sSearch' => 'undefined',
        'sSearch_0' => '',
        'sSearch_1' => '',
        'sSearch_2' => '',
        'sSearch_3' => '',
        'sSearch_4' => '',
        'sSearch_5' => '',
        'sSearch_6' => '',
        'sSearch_7' => '',
        'sSortDir_0' => 'desc',
        'tsDataTableConfigType' => 'dataTable.WmAllArticlesList',
        'tsDataTableType' => 'with_checking',
        'type' => 'null'
    );

    $login = '';
    $pass = '';

    function loadMainSuccess($browser) {
        global $login, $pass;
        

        //->find("input[id=UserLogin]")
        $browser
            ->WebBrowser("loginTryDone")
            ->find("[type=text]")
            ->val($login)->end()
            ->find("[type=password]")
            ->val($pass)
            ->parents('form')
            ->submit();
    }

    function loginTryDone($browser) {
        $browser
            ->WebBrowser("locationToTasksWriteTableDone")
            ->location(MIRALINKS_URL . "ground_articles/allCmArticlesList/achtung#/start:0/count:30/sort:6.desc");
    }

    function loadMainSuccess1($browser) {
        global $login, $pass;

        $browser
            ->WebBrowser("loginTryDone1")
            ->find("[type=text]")
            ->val($login)->end()
            ->find("[type=password]")
            ->val($pass)
            ->parents('form')
            ->submit();
    }

    function loginTryDone1($browser) {
        $browser
            ->WebBrowser("locationToTasksPlaceTableDone")
            ->location(MIRALINKS_URL . "ground_articles/allArticlesList/achtung#/start:0/count:30/sort:5.asc");
    }

    function loadMainSuccess_getPlaceExtended($browser) {
        global $login, $pass;

        $browser
            ->WebBrowser("loginTryDone_getPlaceExtended")
            ->find("[type=text]")
            ->val($login)->end()
            ->find("[type=password]")
            ->val($pass)
            ->parents('form')
            ->submit();
    }

    static $current_place_ind = 0;
    function loginTryDone_getPlaceExtended($browser) {
        global $result_data, $current_place_ind;

        $current_place_ind = 0;
        for ($i = 0, $n = count($result_data['place']); $i < $n ; $i++) {
            $current_place_ind = $i;
            $article_id = $result_data['place'][$i]["AllUserArticle.article_id"];
            //echo $article_id . '<br>';
            if ($result_data['place'][$i]["Article.current_status"] == 'ground_choosed') {
                //echo '===222' . '<br>';
                try {
                    $browser
                        ->WebBrowser("locationToArticleViwDone")
                        ->location(MIRALINKS_URL . "project_articles/view/" . $article_id);
                    usleep(2000000);
                } catch (Zend_Http_Client_Exception $e) {
                    $i--;
                    echo 'Zend_Http_Client_Exception function - loginTryDone_getPlaceExtended (if): ',  $e->getMessage(), "\n";
                    usleep(10000000);
                }
            } else
                $result_data['place'][$i]['extended_data'] = NULL;
        }
    }

    function locationToArticleViwDone($browser) {
        global $result_data, $current_place_ind;

        $extended_data = array();
        $extended_data['ex_title'] = '';
        $extended_data['ex_keywords'] = '';
        $extended_data['ex_url'] = '';
        foreach(pq('div[class^=row-holder]') as $div) {
            // iteration returns PLAIN dom nodes, NOT phpQuery objects
            $childNodes = $div->childNodes;

            $name = trim($childNodes->item(1)->textContent);
            $value = trim($childNodes->item(3)->textContent);

            //echo $name . "==" . $value . '<br>';
            if (strtolower($name) == 'title') {
                $extended_data['ex_title'] = $value;
            }
            if (strtolower($name) == 'keywords') {
                $extended_data['ex_keywords'] = $value;
            }
            //if (strtolower($name) == 'адрес url') {
            if (strpos(strtolower($name), 'url') > 0) {
                $extended_data['ex_url'] = $value;
            }
        }

        // links
        $links_table = pq('div[class*=widget-pageComponent-ArticleLinksTable]');
        $links_data = $links_table->attr('data');
        $links_data = base64_decode($links_data);
        $links_data = json_decode($links_data);
        $links_data = (array)$links_data;
        $links_data = (array)$links_data['links'];
        //var_dump($links_data);
        $extended_data['ex_links'] = $links_data;

        // article text
        $article = pq('blockquote[id=aticle-plain]');
        $article_text = $article->html();
        //echo $article_text;
        $extended_data['ex_article_text'] = $article_text;

        $result_data['place'][$current_place_ind]['extended_data'] = $extended_data;
    }

    function locationToTasksWriteTableDone($browser) {
        global $ajaxRequestWriteData, $current_xhr;
        //var_dump($browser);

        //$ajaxRequestWriteData['sSearch_5'] = "{'type':'enum','value':['cm_accepted,cm_accepted_a_copywriter']}";
        $xhr = $browser->document->xhr;
        $current_xhr = $xhr;
        $result = phpQuery::ajax(array(
            'type' => 'POST',
            'url' => MIRALINKS_URL . "ajaxPort/loadDataTableDataAllCmArticlesTable",
            'data' => $ajaxRequestWriteData,
            'success' => "etJsTaskWriteCallback"
        ), $xhr, true);
    }

    function locationToTasksPlaceTableDone($browser) {
        global $ajaxRequestPlaceData, $current_xhr;

        $xhr = $browser->document->xhr;
        $current_xhr = $xhr;
        $result = phpQuery::ajax(array(
            'type' => 'POST',
            'url' => MIRALINKS_URL . "ajaxPort/loadDataTableDataWmAllArticlesTable",
            'data' => $ajaxRequestPlaceData,
            'success' => "etJsTaskPlaceCallback"
        ), $xhr, true);
    }

    function etJsTaskWriteExtendedCallback($data) {
        global $result_data;
        //var_dump($data);

        $decoded_data = json_decode($data);
        $decoded_data = (array)$decoded_data->data;
        //var_dump($decoded_data);
        $result_data['write_place'][count($result_data['write_place']) - 1]['extended_data'] = $decoded_data;
    }

    function etJsTaskWriteCallback($data) {
        global $result_data, $ajaxRequestWriteData, $current_xhr;
        //var_dump($data);

        $decoded_data = json_decode($data);
        //var_dump($decoded_data);

        // all records count
        $iTotalRecords = $decoded_data->iTotalDisplayRecords;

        $decoded_data = $decoded_data->aaData;
        $iReceivedRecords = count($decoded_data);
        //echo $iTotalRecords . "==" . $iReceivedRecords . '<br>';
        for ($i = 0; $i < $iReceivedRecords; $i++) {
            $result_data['write_place'][] = (array)($decoded_data[$i]->rowData);

            // get extended data
            if ($result_data['write_place'][count($result_data['write_place']) - 1]["AllUserArticle.cm_status"] == 'accepted') {
                $offer_id = $result_data['write_place'][count($result_data['write_place']) - 1]["AllUserArticle.cm_offer_id"];
                try {
                    $result = phpQuery::ajax(array(
                        'type' => 'GET',
                        'url' => MIRALINKS_URL . "ajaxPort/ajaxGetCmInfo/?cmId=" . $offer_id,
                        'success' => "etJsTaskWriteExtendedCallback"
                    ), $current_xhr, true);
                    usleep(2000000);
                    //todo: delay
                } catch (Exception $e) {
                    $i--;
                    echo 'Выброшено исключение function - etJsTaskWriteCallback (for): ',  $e->getMessage(), "\n";
                    usleep(10000000);
                }
            } else
                $result_data['write_place'][count($result_data['write_place']) - 1]['extended_data'] = NULL;
        }

        if (count($result_data['write_place']) < $iTotalRecords) { //need to get next data
            //$ajaxRequestWriteData['sSearch_5'] = "{'type':'enum','value':['cm_accepted,cm_accepted_a_copywriter']}";
            $ajaxRequestWriteData['iDisplayStart'] = count($result_data['write_place']) - 1;//iDisplayLength
            try {
                $result = phpQuery::ajax(array(
                    'type' => 'POST',
                    'url' => MIRALINKS_URL . "ajaxPort/loadDataTableDataAllCmArticlesTable",
                    'data' => $ajaxRequestWriteData,
                    'success' => "etJsTaskWriteCallback"
                ), $current_xhr, true);
                usleep(2000000);
                //todo: delay
            } catch (Zend_Http_Client_Exception $e) {
                echo 'Выброшено исключение function - etJsTaskWriteCallback (if): ',  $e->getMessage(), "\n";
                usleep(10000000);
            }
        }
    }


    function etJsTaskPlaceCallback($data) {
        global $result_data, $ajaxRequestPlaceData, $current_xhr;
        //var_dump($data);

        $decoded_data = json_decode($data);
        //var_dump($decoded_data);

        $old_xhr = $current_xhr;

        // all records count
        $iTotalRecords = $decoded_data->iTotalDisplayRecords;
        
        $decoded_data = $decoded_data->aaData;
        $iReceivedRecords = count($decoded_data);
        //echo $iTotalRecords . "==" . $iReceivedRecords . '\r\n';
        for ($i = 0; $i < $iReceivedRecords; $i++) {
            $result_data['place'][] = (array)($decoded_data[$i]->rowData);
        }

        $current_xhr = $old_xhr;

        if (count($result_data['place']) < $iTotalRecords) { //need to get next data
            //$ajaxRequestPlaceData['sSearch_5'] = "{'type':'enum','value':['cm_accepted,cm_accepted_a_copywriter']}";
            $ajaxRequestPlaceData['iDisplayStart'] = count($result_data['place']) - 1;
            try {
                $result = phpQuery::ajax(array(
                    'type' => 'POST',
                    'url' => MIRALINKS_URL . "ajaxPort/loadDataTableDataWmAllArticlesTable",
                    'data' => $ajaxRequestPlaceData,
                    'success' => "etJsTaskPlaceCallback"
                ), $current_xhr, true);
                usleep(2000000);
                //todo: delay
            } catch (Zend_Http_Client_Exception $e) {
                echo 'Выброшено исключение function - etJsTaskPlaceCallback (if): ',  $e->getMessage(), "\n";
                usleep(5000000);
                //echo 'error<br>'.$e;
            }
        }
    }

    static $result_data = array();
    static $current_xhr = '';

    function getTasks($l, $p) {
        global $result_data;
        global $login, $pass;

        if (!empty($l) && !empty($p)) {
            $login = $l;
            $pass = $p;

            phpQuery::browserGet(MIRALINKS_URL, "loadMainSuccess");
            usleep(2000000);
            phpQuery::browserGet(MIRALINKS_URL, "loadMainSuccess1");
            usleep(2000000);
            phpQuery::browserGet(MIRALINKS_URL, "loadMainSuccess_getPlaceExtended");
            //phpQuery::browserGet(MIRALINKS_URL.'/users/logout', function ($browser) {});

            return $result_data;
        } else
            return null;

    }
?>
