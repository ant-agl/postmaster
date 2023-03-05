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
    <title>Добавить почту</title>
    <link rel="stylesheet" href="./style/style.css">
    <link rel="stylesheet" href="./style/reset.css">
</head>
<body>
    <main class="main">
        <section class="content">            
            <div class="container">
                <?php include 'menu.php'; ?>
                <form class="add-email">
                    <h1 class="form-title">Добавление почты</h1>
                    <div class="form-row">
                        <h4>Email</h4>
                        <input type="email" required="true" name="email">
                    </div>
                    <div class="form-row">
                        <h4>JSON с токенами</h4>
                        <textarea type="tokens_json" required="true" name="tokens_json"></textarea>
                    </div>
                    <button type="button" class="btn btn__add-email">Добавить домен</button>
                    <p class="message"></p>
                </form>
            </div>
        </section>
    </main>

    <script src="https://code.jquery.com/jquery-3.6.3.min.js"></script>
    <script src="scripts/script.js"></script>
    <script src="scripts/add.js"></script>
</body>
</html>