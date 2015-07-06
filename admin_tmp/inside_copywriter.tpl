<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>[page_title]</title>
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
                        <a id="logo" href="/copywriter.php"></a>
                        [auth_block]
                    </div>

                </div>
            </div>
            <!-- HEADER END -->

            <!-- CONTENT AREA -->
            <div id="content_area">
                <div class="alignment">

                    <!-- CANVAS ( WHITE SHEET ) --> 
                    <div class="canvas">

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
                                <h2>Баланс <span class="balance"><a href="/copywriter.php?action=money&action2=output">[balance]</a></span></h2>

                                <ul>
                                    <li><a href="/copywriter.php?action=money&action2=output">Вывод средств</a></li>

                                </ul>

                                <h2>Управление</h2>
                                <ul>
                                    <li><a href="/copywriter.php">Новые задачи</a></li>
                                    <li><a href="/copywriter.php?action=tasks">Мои задачи</a></li>
                                </ul>

                                <h2>Коммуникация</h2>
                                <ul>
                                    <li><a href="/copywriter.php?action=ticket">Тикеты</a><span>([new_tick]/[all_tick])</span></li>
                                </ul>

                                <h2>Статистика</h2>
                                <ul>
                                    <li>Выполнено задач <span>[vipolneno]</span></li>
                                    <li>Задач в работе <span>[vrabote]</span></li>
                                </ul>

                                <h2>Помощь</h2>
                                <ul>
                                    <li><a href="/copywriter.php?action=help&action2=work">Как выполнять задачи?</a></li>
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
                        <li><!-- begin WebMoney Transfer : attestation label --> 
                            <a href="https://passport.webmoney.ru/asp/certview.asp?wmid=243525969589" target=_blank style="border:none">
                                <IMG SRC="/images/acc_blue_on_transp_ru.png" title="Здесь находится аттестат нашего WM идентификатора 243525969589" border="0" /><br />
                                <font size=1>Проверить аттестат</font>
                            </a>
                            <!-- end WebMoney Transfer : attestation label -->
                        </li>
                        <li><a href="/about/">О системе</a></li>
                        <li><a href="/price/">Цены</a></li>
                        <li><a href="/convention/">Соглашение</a></li>
                        <!--<li><a href="/copywriters/">Копирайтерам</a></li>-->
                        <li><a href="/register.php">Регистрация</a></li>
                        <li><a href="/contacts/">Контакты</a></li>
                    </ul>

                </div>
            </div>
            <!-- FOOTER END -->

            <!-- Yandex.Metrika counter --><script type="text/javascript">(function (d, w, c) {
                    (w[c] = w[c] || []).push(function () {
                        try {
                            w.yaCounter23267395 = new Ya.Metrika({ id: 23267395, webvisor: true, clickmap: true, trackLinks: true, accurateTrackBounce: true });
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
        </div>
    </body>
</html>