<?php
session_start();

require_once "classes/application.php";

$errors = [];

$data = $_SESSION['old'] ?? [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $application = new Application($_POST);

    $errors = $application->validate();

    if (empty($errors)) {

        $application->save();

        $_SESSION['success'] = "Заявка успешно принята!";
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
<title>Регистрация | Конференция</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', Arial, sans-serif;
    background: #eef2f7;
    padding: 40px 20px;
}

.container {
    max-width: 550px;
    margin: 0 auto;
}

.form-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    overflow: hidden;
}

.form-header {
    background: white;
    padding: 25px 30px 15px 30px;
    border-bottom: 2px solid #e0e7ef;
}

.form-header h2 {
    color: #2c3e50;
    font-size: 26px;
    font-weight: 500;
}

.form-header p {
    color: #7f8c8d;
    font-size: 14px;
    margin-top: 6px;
}

.form-body {
    padding: 30px;
}

.form-group {
    margin-bottom: 18px;
}

label {
    display: block;
    margin-bottom: 6px;
    color: #2c3e50;
    font-size: 14px;
    font-weight: 500;
}

input, select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #d0d7de;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s;
    background: white;
}

input:focus, select:focus {
    outline: none;
    border-color: #5a9fd4;
    box-shadow: 0 0 0 3px rgba(90, 159, 212, 0.1);
}

input:hover, select:hover {
    border-color: #b0b8c2;
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 20px 0;
}

.checkbox-group input {
    width: 18px;
    height: 18px;
    margin: 0;
    cursor: pointer;
}

.checkbox-group label {
    margin: 0;
    cursor: pointer;
    font-weight: normal;
}

button {
    width: 100%;
    background: #4a7c9c;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s;
    margin-top: 10px;
}

button:hover {
    background: #3a6280;
}

.message {
    padding: 14px 18px;
    border-radius: 10px;
    margin-bottom: 25px;
    font-size: 14px;
}

.message-success {
    background: #e8f5e9;
    color: #2e7d32;
    border-left: 4px solid #4caf50;
}

.message-error {
    background: #ffebee;
    color: #c62828;
    border-left: 4px solid #f44336;
    margin-bottom: 20px;
    padding: 10px 15px;
}

.message-error:not(:last-child) {
    margin-bottom: 12px;
}

select {
    cursor: pointer;
}

option {
    padding: 8px;
}

@media (max-width: 600px) {
    .form-body {
        padding: 20px;
    }
    
    .form-header {
        padding: 20px;
    }
    
    .form-header h2 {
        font-size: 22px;
    }
}
</style>

</head>
<body>

<div class="container">
    <div class="form-card">
        <div class="form-header">
            <h2>Регистрация на конференцию</h2>
            <p>Заполните форму для участия</p>
        </div>
        
        <div class="form-body">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="message message-success">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php foreach ($errors as $e): ?>
                <div class="message message-error">
                    <?= htmlspecialchars($e) ?>
                </div>
            <?php endforeach; ?>

            <form method="POST">
                <div class="form-group">
                    <label>Имя</label>
                    <input type="text" name="first_name" placeholder="Введите имя"
                           value="<?= htmlspecialchars($data['first_name'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Фамилия</label>
                    <input type="text" name="last_name" placeholder="Введите фамилию"
                           value="<?= htmlspecialchars($data['last_name'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" placeholder="example@mail.ru"
                           value="<?= htmlspecialchars($data['email'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Телефон</label>
                    <input type="tel" name="phone" placeholder="+7 (999) 123-45-67"
                           value="<?= htmlspecialchars($data['phone'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label>Тема конференции</label>
                    <select name="topic">
                        <option value="">Выберите тему</option>
                        <option value="Бизнес" <?= ($data['topic'] ?? '') == 'Бизнес' ? 'selected' : '' ?>>Бизнес</option>
                        <option value="Технологии" <?= ($data['topic'] ?? '') == 'Технологии' ? 'selected' : '' ?>>Технологии</option>
                        <option value="Маркетинг" <?= ($data['topic'] ?? '') == 'Маркетинг' ? 'selected' : '' ?>>Маркетинг</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Способ оплаты</label>
                    <select name="payment">
                        <option value="">Выберите способ</option>
                        <option value="WebMoney" <?= ($data['payment'] ?? '') == 'WebMoney' ? 'selected' : '' ?>>WebMoney</option>
                        <option value="Яндекс.Деньги" <?= ($data['payment'] ?? '') == 'Яндекс.Деньги' ? 'selected' : '' ?>>Яндекс.Деньги</option>
                        <option value="PayPal" <?= ($data['payment'] ?? '') == 'PayPal' ? 'selected' : '' ?>>PayPal</option>
                        <option value="Карта" <?= ($data['payment'] ?? '') == 'Карта' ? 'selected' : '' ?>>Банковская карта</option>
                    </select>
                </div>

                <div class="checkbox-group">
                    <input type="checkbox" name="newsletter" id="newsletter" 
                           <?= isset($data['newsletter']) ? 'checked' : '' ?>>
                    <label for="newsletter">Получать новости и анонсы</label>
                </div>

                <button type="submit">Отправить заявку</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>