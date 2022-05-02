<?php

/**
 * Задача 6. Реализовать вход администратора с использованием
 * HTTP-авторизации для просмотра и удаления результатов.
 **/

// Пример HTTP-аутентификации.
// PHP хранит логин и пароль в суперглобальном массиве $_SERVER.
// Подробнее см. стр. 26 и 99 в учебном пособии Веб-программирование и веб-сервисы.
if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] != 'admin' ||
    md5($_SERVER['PHP_AUTH_PW']) != md5('123')) {
  header('HTTP/1.1 401 Unanthorized');
  header('WWW-Authenticate: Basic realm="My site"');
  print('<h1>401 Требуется авторизация</h1>');
  exit();
}

print('Вы успешно авторизовались и видите защищенные паролем данные.');

// *********
// Здесь нужно прочитать отправленные ранее пользователями данные и вывести в таблицу.
// Реализовать просмотр и удаление всех данных.
// *********

$db_user = 'u20985';   // Логин БД
$db_pass = '5087148';  // Пароль БД
$db_host = "localhost"; 
$db_base = 'u20985'; // Имя БД
$db_table = "login"; // Имя Таблицы БД
$db = new PDO("mysql:host=localhost;dbname=$db_base", $db_user, $db_pass,  [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $db->prepare('DELETE FROM login WHERE name = ?');
        $stmt->execute(array(
            $_POST['remove']
        ));
        $stmt = $db->prepare('DELETE FROM login WHERE login = ?');
        $stmt->execute(array(
            $_POST['remove']
        ));
    } catch (PDOException $e) {
        echo 'Ошибка: ' . $e->getMessage();
        exit();
    }
}

try {
    $stmt = $db->query(
        'SELECT * FROM login'
    );
    $stmt2 = $db->query(
        'SELECT superpower, COUNT(superpower) FROM login
        GROUP BY superpower;
        '
    );
    ?>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Панель Администратора</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.2/css/bulma.min.css">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div style="text-align: center; "class="st"><h1>СТАТИСТИКА</h1></div>
        <table class="table is-hoverable is-fullwidth">
            <tbody>
                <?php
                    while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                        print('<tr>');
                        foreach ($row as $c) {
                            print('<td><b>' . $c . '</b></td>');
                        }
                        print('</tr>');
                    }
                ?>
            </tbody>
        </table>
    <form action="" method="post">
        <table class="table is-hoverable is-fullwidth">
            <thead>
            <tr>
                <th>id</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Год гождения</th>
                <th>Пол</th>
                <th>Количество конечностей</th>
                <th>Сверхспособности</th>
                <th>Биография</th>
                <th>Согласие</th>
                <th>Логин</th>
                <th>Пароль</th>
                <th>Удалить</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                print('<tr>');
                foreach ($row as $c) {
                    print('<td>' . $c . '</td>');
                }
                print('<td><button class="button is-info is-small is-danger is-light" name="remove" type="submit" value="' . $row['login'] . '">DELETE</button></td>
                <td><button class="button is-info is-small is-danger is-light" name="remove" type="submit" value="' . $row['login'] . '">Редакт.</button></td>
                ');
                print('</tr>');
            }
            ?>
            </tbody>
        </table>
    </form>
    </body>
        <?php
} catch (PDOException $e) {
    echo 'Ошибка: ' . $e->getMessage();
    exit();
}