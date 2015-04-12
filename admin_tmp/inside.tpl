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
                        <a id="logo" href="/"></a>

                        [auth_block]

                    </div>

                </div>
            </div>
            <!-- HEADER END -->
            <!-- HOT BAR -->
            <div id="hotbar" class="clear">

                <ul>
                    <li id="new_applications" href="#"><a href="?module=user&action=tasks_moder&status=navyklad"><span class="ico"></span><span class="large">[all_navyklad]</span> всего на выклад</a></li>
                    <li id="not_applications" href="#"><a href="?module=user&action=tasks_moder&status=dorabotka"><span class="ico"></span><span class="large">[all_dorabotka]</span> всего на доработке</a></li>
                    <li id="in_applications" href="#"><a href="?module=user&action=tasks_moder&status=vilojeno"><span class="ico"></span><span class="large">[all_vilojeno]</span> всего выложено</a></li>
                </ul>

            </div>
            <!-- HOT BAR END -->

            <!-- CONTENT AREA -->
            <div id="content_area">
                <div class="alignment">
                    <!-- <div class='bread_crumbs'><a href='/' >iForget</a> - [brcr]</div>--> 
                    <!-- CANVAS ( WHITE SHEET ) --> 
                    <div class="canvas">

                        <!-- PAGE TITLE / NOTIFICATION AREA -->

                        <div class="notification" style="display:[display_comment]">
                            [main_comment]
                            <a class="close" href="/user.php?action=birj&act2=close_notify"></a>
                            <div class="pointer"></div>
			</div>

                        <!-- PAGE CONTENT -->
                        <div id="page_content">
                            <!-- ////////////////////////////////////////////////// -->

                            [content]

                            <!-- ////////////////////////////////////////////////// -->
                        </div>
                        <!-- PAGE CONTENT END -->

                        <!-- SIDEBAR -->
                        <div class="sidebar">

                            <!-- navigation -->
                            <div class="navigation">
                                <h2>Баланс <span class="balance"><a href="/user.php?action=money&action2=output">[balance]</a></span></h2>
                                <ul>
                                    <li><a href="/user.php?action=money&action2=output">Вывод средств</a></li>
                                    <li>Выполнено задач<span>[num]</span></li>
                                </ul>

                                <h2>Управление</h2>
                                <ul>
                                    <li><a href="/user.php">Задания</a></li>
                                </ul>

                                <h2>Коммуникация</h2>
                                <ul>
                                    <li><a href="/user.php?action=ticket">Тикеты</a><span>([new_tick]/[all_tick])</span></li>
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
                            <a href="https://passport.webmoney.ru/asp/certview.asp?wmid=243525969589" target=_blank style="border:none"><IMG SRC="/images/acc_blue_on_transp_ru.png" title="Здесь находится аттестат нашего WM идентификатора 243525969589" border="0"><br><font size=1>Проверить аттестат</font></a>
                            <!-- end WebMoney Transfer : attestation label -->
                        </li>
                        <li><a href="/about/">О системе</a></li>
                        <li><a href="/price/">Цены</a></li>
                        <li><a href="/convention/">Соглашение</a></li>
                        <!--				<li><a href="/copywriters/">Копирайтерам</a></li>
                                                        <li><a href="/web-masters/">Вебмастерам</a></li>!-->
                        <li><a href="/register.php">Регистрация</a></li>
                        <li><a href="/contacts/">Контакты</a></li>
                    </ul>

                </div>
            </div>
            <!-- FOOTER END -->




        </div>

        <script>
    (function(i, s, o, g, r, a, m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)}, i[r].l = 1 * new Date(); a = s.createElement(o),
            m = s.getElementsByTagName(o)[0]; a.async = 1; a.src = g; m.parentNode.insertBefore(a, m)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
            ga('create', 'UA-6397720-17', 'iforget.ru');
            ga('send', 'pageview');</script>


        <!-- Yandex.Metrika counter --><script type="text/javascript">(function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter23267395 = new Ya.Metrika({id:23267395, webvisor:true, clickmap:true, trackLinks:true, accurateTrackBounce:true}); } catch (e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks");</script><noscript><div><img src="//mc.yandex.ru/watch/23267395" style="position:absolute; left:-9999px;" alt="" /></div></noscript><!-- /Yandex.Metrika counter -->

    </body>
</html>