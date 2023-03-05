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
    <title>Список почт</title>
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="./style/reset.css">
</head>
<body>
    <main class="main">
        <section class="content">
            <div class="container">
                <?php include 'menu.php'; ?>
                <h1>Список добавленных почт</h1>
                <br>
                <table class="table table_ta-left">
                    <tbody></tbody>
                </table>
                <br><br>
                <a href="add.php" class="btn">+ Добавить почту</a>
            </div>
        </section>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="scripts/script.js"></script>
    <script src="scripts/account.js"></script>
</body>
</html>