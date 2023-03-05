<?php
  require_once 'reg/boot.php';
  require_once 'reg/jwtClass.php';
  $user = null;
  $token = new Token(1234);

  $user = $token->verifyCookieToken();

  $url = basename($_SERVER['REQUEST_URI']);
  $url = explode('?', $url);
  $url = $url[0];
  if (empty($url) || $url == 'toolbox') $url = 'index.php';
?>
<div class="main-menu">
  <div>
    <?php if (chech_auth()): ?>
      <a href="/toolbox" class="link <?= $url == 'index.php' ? 'active' : '' ?>">Статистика</a>
      <a href="/toolbox/domain.php" class="link <?= $url == 'domain.php' ? 'active' : '' ?>">Сводная информация</a>
      <a href="/toolbox/account.php" class="link <?= $url == 'account.php' ? 'active' : '' ?>">Список почт</a>
    <?php endif; ?>
  </div>
  <div>
    <?php if (chech_auth()): ?>
      <div class="lk">
        <?= htmlspecialchars($user->username) ?>
        <div class="lk-sub">
          <ul>
            <li><a href="/toolbox/reg/do_logout.php">Выйти</a></li>
          </ul>
        </div>
      </div>
      
    <?php else: ?>
      <a href="/toolbox/reg/login.php" class="link">Авторизация</a>
      <a href="/toolbox/reg/register.php" class="link">Регистрация</a>
    <?php endif; ?>
  </div>
</div>