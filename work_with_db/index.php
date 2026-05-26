<?php
session_start();

require_once __DIR__ . '/classes/application.php';

$errors = [];

$data = $_SESSION['old'] ?? [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $application = new Application($_POST);

    $errors = $application->validate();

    if (empty($errors)) {

        $application->save();

        $_SESSION['success'] = "Заявка успешно отправлена!";
        unset($_SESSION['old']);

        header("Location: index.php");
        exit;

    } else {

        $_SESSION['old'] = $_POST;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Регистрация на конференцию</title>

<style>
body{
    font-family: Arial;
    background:#f4f6f9;
    padding:20px;
}

.container{
    width:520px;
    margin:30px auto;
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}

h2{
    text-align:center;
    color:#2c3e50;
}

input, select{
    width:100%;
    padding:10px;
    margin-top:5px;
    margin-bottom:15px;
    border:1px solid #ccc;
    border-radius:6px;
    box-sizing:border-box;
}

button{
    width:100%;
    padding:12px;
    background:#4a90e2;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-size:16px;
}

button:hover{
    background:#357bd8;
}

.error{
    background:#ffe6e6;
    color:#c0392b;
    padding:10px;
    margin-bottom:10px;
    border-radius:6px;
}

.success{
    background:#e6f9ec;
    color:#1e7e34;
    padding:10px;
    margin-bottom:15px;
    border-radius:6px;
    text-align:center;
}
</style>

</head>
<body>

<div class="container">

<h2>Регистрация на конференцию</h2>

<?php if (isset($_SESSION['success'])): ?>
    <div class="success">
        <?= $_SESSION['success'] ?>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php foreach ($errors as $error): ?>
    <div class="error">
        <?= $error ?>
    </div>
<?php endforeach; ?>

<form method="POST">

<label>Имя</label>
<input type="text"
       name="name"
       value="<?= htmlspecialchars($data['name'] ?? '') ?>">

<label>Фамилия</label>
<input type="text"
       name="lastname"
       value="<?= htmlspecialchars($data['lastname'] ?? '') ?>">

<label>Email</label>
<input type="text"
       name="email"
       value="<?= htmlspecialchars($data['email'] ?? '') ?>">

<label>Телефон</label>
<input type="text"
       name="tel"
       value="<?= htmlspecialchars($data['tel'] ?? '') ?>">

<label>Тематика</label>
<select name="subject_id">

<option value="">Выберите тему</option>

<?php foreach (Application::$subjects as $id => $subject): ?>

<option value="<?= $id ?>"
<?= (($data['subject_id'] ?? '') == $id) ? 'selected' : '' ?>>

<?= $subject ?>

</option>

<?php endforeach; ?>

</select>

<label>Способ оплаты</label>

<select name="payment_id">

<option value="">Выберите оплату</option>

<?php foreach (Application::$payments as $id => $payment): ?>

<option value="<?= $id ?>"
<?= (($data['payment_id'] ?? '') == $id) ? 'selected' : '' ?>>

<?= $payment ?>

</option>

<?php endforeach; ?>

</select>

<label>
<input type="checkbox"
       name="mailing"
       <?= isset($data['mailing']) ? 'checked' : '' ?>>

Получать рассылку
</label>

<br><br>

<button type="submit">
Отправить заявку
</button>

</form>

</div>

</body>
</html>