
<?php
require_once 'boot.php';
require_once 'jwtClass.php';
$user = null;
$token = new Token(1234);

$user = $token->verifyCookieToken();

?>
<?php if (($user))  { ?>

    <h1>Welcome back, <?=htmlspecialchars($user->username)?>!</h1>

    <form class="mt-5" method="post" action="do_logout.php">
        <button type="submit" class="btn btn-primary">Logout</button>
    </form>

<?php } else if (!$user)
{  header('Location: /login.php'); }

