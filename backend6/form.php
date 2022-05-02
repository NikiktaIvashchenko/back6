

<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Backend6</title>
    <link rel="stylesheet" href="css/reset.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&amp;family=Nunito&amp;family=Open+Sans:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
    <script src="JavaScript/counter.js"></script>
    <script src="JavaScript/hello.js"></script>
    <script src="JavaScript/server.js"></script>
</head>

<body>
    <div class="avtr">
        <p>
        <a href="admin.php">Учетная запись администратора</a><br>
        <a href="login.php">Авторизация по логину и паролю</a>
        </p>
    </div>
   <?php
if (!empty($messages)) {
  print('<div id="messages">');
  // Выводим все сообщения.
  foreach ($messages as $message) {
    print($message);
  }
  print('</div>');
}
    ?>
    <div class="d-flex flex-column">
    <section class="pb-5 form order-3 order-sm-2 d-flex justify-content-center" id="form">
       <div>
        
            <div class="py-5 px-5 wrap1 order-1 order-sm-2">
                
                <form action="" method="POST">
                    <div class="inp_name">
                        <label for="name">Имя: </label><br><input name="name" id="name" type="text" <?php if ($errors['name']) {print 'class="error"';} else {print 'class="form_inp"';}?> value="<?php print $values['name']; ?>" />
                        <br><br><label for="e-mail">E-mail:</label><br><input name="e-mail" id="e-mail" type="email" <?php if ($errors['e-mail']) {print 'class="error"';} else {print 'class="form_inp"';} ?> value="<?php print $values['e-mail']; ?>"  />
                        <br><br><label for="date">Дата: </label><br><input name="date" id="date" type="date"  <?php if ($errors['date']) {print 'class="error"' ;} else {print 'class="form_inp"';} ?> value="<?php print $values['date']; ?>" >
                    </div>

                    <div  <?php if ($errors['floor']) {print 'class="error"';} else {print 'class="mb-3"';} ?> value="<?php print $values['floor']; ?>">
                        <br>
                        <b>Gender</b>
                        <br>
                        <label for="radi">Мужчина </label><input id="Man" value="man" name="floor" type="radio"  /><br>
                        <label for="radi">Женщина: </label><input  id="Wooman" value="wooman" name="floor" type="radio" />
                    </div>
                    <div id="mb-3">
                        <b>Количество конечностей</b>
                        <br>
                        <label>1</label><input value="1" name="limbs" type="radio" checked="checked">
                        <label>2</label><input value="2" name="limbs" type="radio">
                        <label>4</label><input value="4" name="limbs" type="radio">
                        <label>5</label><input value="5" name="limbs" type="radio">
                        <label>8</label><input value="8" name="limbs" type="radio">
                    </div>
                    <div class="superpow">
                        <b>Выберите Сверхспособность</b><br>
                        <select name="superpower" id="superpowers">
                            <option value="Бессмертие">Бессмертие</option>
                            <option value="Прохождение ">Прохождение сквозь стены</option>
                            <option value="Левитация">Левитация</option>
                            <option value="Невидимость">Невидимость</option>
                        </select>
                    </div>
                    <br><label>
                        <b>Ваша биография</b>
                        <br><textarea name="bio" style="margin: 10px 0; width: 200px; height: 110px;" placeholder="Напишите о себе" <?php if ($errors['bio']) {print 'class="error"';} ?> value="<?php print $values['bio']; ?>" ></textarea>
                        <br></label>
                        <br>
                    <input type="checkbox" name="chek" value="yes">
                    с контрактом ознакомлен
                    <br><input type="submit" value="Отправить данные!" style="margin: 20px 0">
                    
                    <?php
                    if(isset($_COOKIE['login_in'])){
                        print('<div class="exit"><a href="out.php">ВЫХОД</a></div>');}
                        ?>
                </form>
            </div>
            
        </div>
    </section>
    
    </div>
</body>
</html>