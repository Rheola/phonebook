<?php
/***
 * @var $this  core\Controller
 * @var $template string
 */

use core\App;

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v4.0.1">
    <title>Телефонная книга</title>


    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.css" rel="stylesheet">

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <!-- Custom styles for this template -->
    <link href="/css/main.css" rel="stylesheet">
</head>
<body>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Телефонная книга</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExample07"
                aria-controls="navbarsExample07" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExample07">
            <?php

            $this->menu = [
                ['label' => 'Главная', 'url' => '/'],
            ];


            if (App::$user->isGuest()) {
                $this->menu[] = ['label' => 'Вход', 'url' => '/user/login'];
            } else {
                $this->menu[] = ['label' => 'Справочник', 'url' => '/phone/'];
                $this->menu[] = ['label' => 'Выход', 'url' => '/user/logout'];
            }

            ?>
            <ul class="navbar-nav mr-auto">

                <?php
                $class = '';

                foreach ($this->menu as $item) {
                    $class = '';
                    if ($this->getId() == $item['url']) {
                        $class = 'active';
                    }
                    ?>
                    <li class="nav-item <?= $class ?>">
                        <a class="nav-link" href="<?= $item['url']; ?>"><?= $item['label']; ?></a>

                    </li>
                    <?php
                }
                ?>

            </ul>

        </div>
    </div>
</nav>


<div class="container">


    <main role="main">
        <?php include __DIR__ . '/' . $template . '.php'; ?>
    </main>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script>window.jQuery || document.write('<script src="/js/vendor/jquery.slim.min.js"><\/script>')</script>
<script src="/js/bootstrap.bundle.js"></script>
</body>
</html>
