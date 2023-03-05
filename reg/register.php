<?php
  require_once 'boot.php';
  if (chech_auth()) {
    header('Location: /toolbox');
    die;
  }
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link rel="stylesheet" href="../style/style.css">
    <link rel="stylesheet" href="../style/reset.css">
</head>
<body>
    <main class="main">
        <section class="content">
            <div class="container">
              <?php include '../menu.php'; ?>
              <form method="post" action="do_register.php">
                <h1 class="form-title">Регистрация</h1>
                <?php flash(); ?>
                <div class="form-row">
                  <h4>Имя</h4>
                  <input type="text" id="username" name="username" required>
                </div>
                  <div class="form-row">
                      <h4>Почта</h4>
                      <input type="text" id="email" name="email" required>
                  </div>
                <div class="form-row">
                  <h4>Пароль</h4>
                  <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn">Регистрация</button>
              </form>
            </div>
        </section>
    </main>
</body>
</html>