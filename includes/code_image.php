<?php

include 'session.php';

include 'kcaptcha/kcaptcha.php';
$captcha = new KCAPTCHA();
$_SESSION['Var_session'] = $captcha->getKeyString();
?>