<?php
//Стартуем сессию
session_start();
//Уничтожаем сессию
session_destroy();
setcookie('login_in', '');
//И сразу перенаправляем на нужную страницу пользователя
header('Location: index.php');
?>