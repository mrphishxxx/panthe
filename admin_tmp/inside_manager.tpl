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
                        <a id="logo" href="/management.php"></a>
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

                        <!-- PAGE CONTENT -->
                        <div id="page_content">
                            <!-- ////////////////////////////////////////////////// -->
                            [search]					<br/><br/>

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
                                    <!--<li><a href="?module=admins&action=xls">Выгрузка Excel</a></li>-->
                                </ul>

                                <h2 style="float:left"><a href="?module=managers&action=ticket">Тикеты</a></h2>
                                <span style="float:right;margin: 30px 5px 5px;">([new_tick]/[all_tick])</span>
                                <div class="clear"></div>
                                <ul>
                                    <li><a href="?module=managers&action=ticket&type=user">Пользователи</a><span>([new_tick_user]/[all_tick_user])</span></li>
                                    <li><a href="?module=managers&action=ticket&type=moder">Модераторы</a><span>([new_tick_moder]/[all_tick_moder])</span></li>
                                    <li><a href="?module=managers&action=ticket&type=copywriter">Копирайтеры</a><span>([new_tick_copywriter]/[all_tick_copywriter])</span></li>
                                    <li><a href="?module=managers&action=ticket&type=admin">Администрация</a><span>([new_tick_admin]/[all_tick_admin])</span></li>
                                </ul>
                                <h2>Копирайтеры</h2>
                                <ul>
                                    <li><a href="?module=admins&action=copywriters">Копирайтеры</a></li>
                                    <li><a href="?module=admins&action=copywriters&action2=blacklist">Черный список</a></li>
                                </ul>

                                <h2>Дополнительно</h2>
                                <ul>
                                    <li><a href="?module=admins&action=moders">Модераторы</a></li>
                                    <li><a href="?module=admins&action=send_mail_users_bl_etxt">Сообщение для БЛ ETXT</a></li>
                                    <li><a href="?module=admins&action=verify_compliance_deadlines_order">Перезапуск задач в ETXT</a></li>
                                </ul>

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
                                <IMG SRC="/images/acc_blue_on_transp_ru.png" title="Здесь находится аттестат нашего WM идентификатора 243525969589" border="0" />
                                <br />
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
                    (i[r].q = i[r].q || []).push(arguments);
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                        m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m);
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-6397720-17', 'iforget.ru');
            ga('send', 'pageview');
        </script>

        <!-- Yandex.Metrika counter --><script type="text/javascript">(function (d, w, c) {
                (w[c] = w[c] || []).push(function () {
                    try {
                        w.yaCounter23267395 = new Ya.Metrika({ id:23267395, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true });
                    } catch (e) {
                    }
                });
                var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () {
                    n.parentNode.insertBefore(s, n);
                };
                s.type = "text/javascript";
                s.async = true;
                s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";
                if (w.opera == "[object Opera]") {
                    d.addEventListener("DOMContentLoaded", f, false);
                } else {
                    f();
                }
            })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/23267395" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->

        <script type="text/javascript">
            (window.Image ? (new Image()) : document.createElement('img')).src = location.protocol + '//vk.com/rtrg?r=nY8Nd*aijShWd1kCSfe1XeNCsyvaLoTOrbLP9Jj6zmJaVXkgP4B4MEvjAEBVNPaweE3/UJbgbh*TYM3eM64biDMshlKkCqZda/P1KGO09IfK2ub*pOTgYpCGiF*A2gPITA1OhlymWIsrC4yUsnico7Jg2pYPSLHveJZQqmnmzrc-';
        </script>
    </body>
</html>