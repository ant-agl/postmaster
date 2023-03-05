<?php
  require_once 'reg/boot.php';
  if (!chech_auth()) {
    header('Location: reg/login.php');
  }
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Статистика</title>
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="./style/reset.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <?php include 'menu.php'; ?>
            <nav class="nav">
                <div>
                    <div class="dropdown dropdown-mail">
                        <p class="dropbtn menu-link">Почта</p>
                        <div class="dropdown-content">
                            
                        </div>
                    </div>
                    <div class="dropdown dropdown-domain">
                        <p class="dropbtn menu-link">Домен</p>
                        <div class="dropdown-content">
                            
                        </div>
                    </div>
                </div>
                <ul class="menu-list menu-center">
                    <ul class="menu-list menu-left filter">
                        <li class="menu-item">
                            <p class="menu-link link active" data-period="day">День</a>
                        </li>
                        <li class="menu-item">
                            <p class="menu-link link" data-period="week">Неделя</a>
                        </li>
                        <li class="menu-item">
                            <p class="menu-link link" data-period="month">Месяц</a>
                        </li>
                    </ul>
                    <ul class="menu-list menu-right menu-color">
                        <li class="menu-item green">
                            <span class="menu-link">Доставлено</span>
                        </li>
                        <li class="menu-item yellow">
                            <span class="menu-link">Возможно спам</span>
                        </li>
                        <li class="menu-item red">
                            <span class="menu-link">Только спам</span>
                        </li>
                    </ul>
                </ul>               
            </nav>
        </div>
    </header>
    <main class="main">
        <section class="content">
            <div class="container">
                <div class="block-table">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Дата</th>
                                <th>Письма</th>
                                <th>Жалоб</th>
                                <th>Репутация, %</th>
                                <th>Тенденция, %</th>
                                <th>Прочит.</th>
                                <th>Удал. прочит.</th>
                                <th>Удал. непрочит.</th>
                                <th>Доставлено, %</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>

                    <div class="pagination"></div>
                </div>
            </div>
        </section>
    </main>
    
    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="scripts/script.js"></script>
    <script src="scripts/statistics.js"></script>
</body>
</html>