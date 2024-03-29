<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Index</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="/css/main.css" />
        <link rel="stylesheet" type="text/css" href="/css/prototypes.css" />


        <link rel="stylesheet" type="text/css" href="/admin_tmp/css/datepicker.css" />
        <link rel="stylesheet" type="text/css" href="/admin_tmp/css/tablesorter.css" />

        <script type="text/JavaScript" src="/admin_tmp/js/jq.js"></script>
        <script type="text/JavaScript" src="/js/main.js"></script>

        <script src="/admin_tmp/js/bootstrap.js" type="text/javascript"></script>
        <script src="/admin_tmp/js/bootstrap-datepicker.js" type="text/JavaScript"></script>
        <script src="/admin_tmp/js/jquery.tablesorter.js" type="text/javascript"></script>
        <script src="/admin_tmp/js/index.js" type="text/javascript"></script>
        <script src="/admin_tmp/js/jquery.leanModal.min.js" type="text/javascript"></script>

    </head>
    <body>
        <div id="page" class="back_end">




            <!-- HEADER -->
            <div id="header">
                <div class="alignment">

                    <!-- BAR -->
                    <div class="bar">

                        <!-- logo -->
                        <a id="logo" href="/admin.php?module=admins"></a>

                        <!-- balance
                        <div id="balance">

                            Баланс: <span class="amount">0</span> руб.

                            <a class="refill" href="#">
                                Пополнить баланс
                            </a>

                        </div> -->
                        [auth_block]

                    </div>

                </div>
            </div>
            <!-- HEADER END -->

            <!-- HOT BAR -->
            <div id="hotbar" class="clear">

                <ul>
                    <li id="applications" href="#"><a href="?module=admins&action=tasks&status=vrabote"><span class="ico"></span><span class="large">[vrabote]</span> в работе</a></li>
                    <li id="new_applications" href="#"><a href="?module=admins&action=tasks&status=navyklad"><span class="ico"></span><span class="large">[navyklad]</span> на выклад</a></li>
                    <li id="not_applications" href="#"><a href="?module=admins&action=tasks&status=neobrabot"><span class="ico"></span><span class="large">[neobrabot]</span> не обраб</a></li>
                    <li id="in_applications" href="#"><a href="?module=admins&action=tasks&status=vilojeno"><span class="ico"></span><span class="large">[vilojeno]</span> вылож</a></li>
                    <li id="toremove_applications" href="#"><a href="?module=admins&action=tasks&status=to_remove"><span class="ico"></span><span class="large">[to_remove]</span> на удаление</a></li>
                    <li id="removed_applications" href="#"><a href="?module=admins&action=tasks&status=removed"><span class="ico"></span><span class="large">[removed]</span> удалено</a></li>
                </ul>

            </div>
            <!-- HOT BAR END -->

            <!-- CONTENT AREA -->
            <div id="content_area">
                <div class="alignment">
                    <!-- CANVAS ( WHITE SHEET ) --> 
                    <div class="canvas">

                        <!-- PAGE TITLE / NOTIFICATION AREA 
                        <h1>Мониторинг и управление</h1>
                        -->
                        <!-- notifications area 

                        <div class="notification">
                            [main_comment]
                            <div class="pointer"></div>
                        </div>

                        <!-- PAGE TITLE / NOTIFICATION AREA -->

                        <!-- PAGE CONTENT -->
                        <div id="page_content">
                            <!-- ////////////////////////////////////////////////// -->
                            [search]					
                            
                            [content]

                            <!-- ////////////////////////////////////////////////// -->
                        </div>
                        <!-- PAGE CONTENT END -->

                        <!-- SIDEBAR -->
                        <div class="sidebar">

                            <!-- navigation -->
                            <div class="navigation">

                                <h2>Управление</h2>
                                <ul>
                                    <li><a href="?module=admins&action=viewusers">Пользователи</a></li>
                                    <li><a href="?module=admins">Сайты в работе</a></li>
                                    <li><a href="?module=admins&action=articles">Задания из sape</a></li>
                                    <li><a href="?module=admins&action=checketxt">Задачи из ETXT</a></li>
                                    <li><a href="?module=admins&action=xls">Выгрузка Excel</a></li>
                                </ul>

                                <h2 style="float:left"><a href="?module=admins&action=ticket">Тикеты</a></h2>
                                <span style="float:right;margin: 30px 15px 5px;">([new_tick])</span>
                                <div class="clear"></div>
                                <ul>
                                    <li><a href="?module=admins&action=ticket&type=user">Пользователи</a><span>([new_tick_user])</span></li>
                                    <li><a href="?module=admins&action=ticket&type=moder">Модераторы</a><span>([new_tick_moder])</span></li>
                                    <li><a href="?module=admins&action=ticket&type=copywriter">Копирайтеры</a><span>([new_tick_copywriter])</span></li>
                                    <li><a href="?module=admins&action=ticket&type=manager">Менеджеры</a><span>([new_tick_manager])</span></li>
                                    <li><hr /></li>
                                    <li><a href="?module=admins&action=ticket&type=archive">Архив</a><span>([old_tickets])</span></li>
                                </ul>
                                
                                <h2>Оповещения</h2>
                                <ul>
                                    <li><a href="?module=admins&action=notify&action2=parser">Парсер<span class="red text-bold">[notify_parser]</span></a></li>
                                </ul>
                                
                                <h2>Запросы</h2>
                                <ul>
                                    <li><a href="?module=admins&action=change_wallet">Смена кошелька<span>([new_wallet]/[all_wallet])</span></a></li>
                                </ul>
                                
                                <h2>Копирайтеры</h2>
                                <ul>
                                    <li><a href="?module=admins&action=copywriters">Статистика задач</a></li>
                                    <li><a href="?module=admins&action=copywriters&action2=balance">Баланс</a></li>
                                    <li><a href="?module=admins&action=copywriters&action2=withdrawal">Выведено средств</a></li>
                                    <li><a href="?module=admins&action=copywriters&action2=blacklist">Черный список</a></li>
                                    <li><a href="?module=admins&action=copywriters&action2=whitelist">Белый список</a></li>
                                </ul>

                                <h2>Модераторы</h2>
                                <ul>
                                    <li><a href="?module=admins&action=moders">Статистика задач</a></li>
                                    <li><a href="?module=admins&action=moders&action2=balance">Баланс</a></li>
                                    <li><a href="?module=admins&action=moders&action2=withdrawal">Выведено средств</a></li>
                                </ul>

                                <h2>Финансы</h2>
                                <ul>
                                    <li><a href="?module=admins&action=balance">Пополнить баланс</a></li>
                                </ul>

                                <h2>Дополнительно</h2>
                                <ul>
                                    <li><a href="?module=admins&action=statistics">Статистика</a></li>
                                    <li><a href="?module=admins&action=birj">Биржи</a></li>
                                    <li><a href="?module=admins&action=users_non_active">Неактивные пользователи</a></li>
                                    <li><a href="?module=admins&action=send_mail_users_bl_etxt">Сообщение для БЛ ETXT</a></li>
                                    <li><a href="?module=admins&action=verify_compliance_deadlines_order">Перезапуск задач в ETXT</a></li>
                                </ul>

                                <h2><a href="/faq.php">F.A.Q.</a></h2>

                            </div>

                        </div>
                        <!-- SIDEBAR END -->

                    </div>
                    <!-- CANVAS END -->


                </div>
            </div>
            <!-- CONTENT AREA END -->



            <!-- FOOTER -->
            <div id="footer">
                <div class="alignment">	

                    <span id="copyrights">
                        © 2013 iForget — система автоматической монетизации
                    </span>

                    <ul class="navigation">
                        <li>
                            <!-- begin WebMoney Transfer : attestation label --> 
                            <a href="https://passport.webmoney.ru/asp/certview.asp?wmid=243525969589" target=_blank style="border:none">
                                <IMG SRC="/images/acc_blue_on_transp_ru.png" title="Здесь находится аттестат нашего WM идентификатора 243525969589" border="0" /><br />
                                <font size=1>Проверить аттестат</font>
                            </a>
                            <!-- end WebMoney Transfer : attestation label -->
                        </li>
                        <li><a href="/about/">О системе</a></li>
                        <li><a href="/price/">Цены</a></li>
                        <li><a href="/convention/">Соглашение</a></li>
                        <li><a href="/register.php">Регистрация</a></li>
                        <li><a href="/contacts/">Контакты</a></li>
                    </ul>

                </div>
            </div>
            <!-- FOOTER END -->




        </div>


        <script>
            (function (i, s, o, g, r, a, m) {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function () {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
            ga('create', 'UA-6397720-17', 'iforget.ru');
            ga('send', 'pageview');
        </script>

        <script type="text/javascript">
            (window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=nY8Nd*aijShWd1kCSfe1XeNCsyvaLoTOrbLP9Jj6zmJaVXkgP4B4MEvjAEBVNPaweE3/UJbgbh*TYM3eM64biDMshlKkCqZda/P1KGO09IfK2ub*pOTgYpCGiF*A2gPITA1OhlymWIsrC4yUsnico7Jg2pYPSLHveJZQqmnmzrc-';
        </script>

    </body>
</html>