<!DOCTYPE html>
<html lang="ru">
    <head>
        <!--Let browser know website is optimized for mobile-->
        <meta charset="utf-8" /> 
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <!--Import materialize.css-->
        <link type="text/css" rel="stylesheet" href="css/materialize.css" media="screen,projection" />
        <link type="text/css" rel="stylesheet" href="css/style.css" />

        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>{$Title}</title>

        <script type="text/javascript" src="https://www.google.com/jsapi"></script>


    </head>

    <body>
        <header>
            <nav class="navbar-fixed grey darken-3">
                <div class="nav-wrapper">
                    <ul id="dropdown1" class="dropdown-content collection">
                        <li class="collection-item avatar disabled">
                            <i class="material-icons circle blue">person</i>
                            <span class="title"><strong>Name Surname</strong></span>
                        </li>
                        <li class="divider"></li>
                        <li><a href="#!"><i class="material-icons right">portrait</i>Личный кабинет</a></li>
                        <li class="divider"></li>
                        <li><a href="/faq.php?action=userFaq"><i class="material-icons right">help</i>FAQ</a></li>
                        <li class="divider"></li>
                        <li><a href="#"><i class="material-icons right">close</i>Выйти</a></a></li>
                    </ul>
                    <a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
                    <a href="/faq.php" class="brand-logo center"><img src="images/interface/logo.png" class="logo" /></a>

                    <ul class="side-nav right" id="mobile-demo">
                        <li><a href="#"><i class="material-icons right">portrait</i>Личный кабинет</a></li>
                        <li class="divider"></li>
                        <li><a href="/faq.php?action=userFaq"><i class="material-icons right">help</i>FAQ</a></li>
                        <li class="divider"></li>
                        <li><a href="#"><i class="material-icons right">close</i>Выйти</a></li>
                    </ul>
                    <ul class="right hide-on-med-and-down">
                        <li><a href="#"><i class="material-icons right">search</i>Search</a></a></li>
                        <li><a class="dropdown-button" href="#" data-activates="dropdown1"><i class="material-icons right">person</i>Admin</a></li>
                    </ul>
                </div>
            </nav>
        </header>

        <section class="container">