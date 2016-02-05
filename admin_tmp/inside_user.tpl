<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Index</title>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <link rel="stylesheet" type="text/css" href="/admin_tmp/css/datepicker.css" />
        <link rel="stylesheet" type="text/css" href="/admin_tmp/css/tablesorter.css" />
        <link rel="stylesheet" type="text/css" href="/admin_tmp/css/postreg_user.css" />
        <link rel="stylesheet" type="text/css" href="/js/jquery.fancybox.css" />
        <link rel="stylesheet" type="text/css" href="/css/main.css" />
        <link rel="stylesheet" type="text/css" href="/css/prototypes.css" />

        <script type="text/JavaScript" src="/admin_tmp/js/jq.js"></script>
        <script type="text/JavaScript" src="/js/jquery.mousewheel-3.0.6.pack.js"></script>
        <script type="text/JavaScript" src="/js/jquery.fancybox.pack.js"></script>
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
                        <a id="logo" href="/user.php"></a>
                        <!-- balance -->
                        <div id="balance">
                            Баланс: <a href="/user.php?action=decode_balans"><span class="amount">[balans]</span></a> руб.
                            <a class="refill" href="[payment_link]">
                                Пополнить баланс
                            </a>
                        </div>
                        [auth_block]
                    </div>
                </div>
            </div>
            <!-- HEADER END -->

            <!-- CONTENT AREA -->
            <div id="content_area">
                <div class="alignment">
                    <div class='bread_crumbs'><a href='/' >iForget</a> - [brcr]</div>
                    <!-- CANVAS ( WHITE SHEET ) --> 
                    <div class="canvas">
                        <div class="notification" [display_comment]>
                            [main_comment]
                            <a class="close" href="/user.php?action=close_notify"></a>
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

                                <h2><a href="/user.php">Управление</a></h2>
                                <ul>
                                    <li><a href="/user.php?action=birj">Биржи</a></li>
                                    <li><a href="/user.php?action=sayty">Площадки</a></li>
                                    <li><a href="/user.php?action=all_tasks">Все задания</a></li>
                                    <li><a href="/user.php?action=payments">Мои платежи</a></li>
                                    <li><a href="/user.php?action=decode_balans">Расшифровка баланса</a></li>
                                    <li><a href="/user.php?action=partnership">Партнерская программа</a></li>

                                </ul>
                                <h2>Коммуникация</h2>
                                <ul>
                                    <li><a href="/user.php?action=ticket">Тикеты</a><span>([new_tick]/[all_tick])</span></li>
                                    <li><a href="/user.php?action=change_wallet">Смена кошелька</a><span>([new_wallet]/[all_wallet])</span></li>
                                </ul>

                            </div>
                            <a href="http://www.updates.seo-auditor.ru/" id="upd" target="_blank"></a><script type="text/javascript" src="//www.informer.seo-auditor.ru/updates/informer-v1.01.js" charset="utf-8"></script>
                            
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
        <script type="text/javascript">  
            $(document).ready(function (){
                $(".SeoAuditor_Informer").find("a").each(function(){
                    $(this).remove();
                });
            });
        </script>    
    </body>
</html>