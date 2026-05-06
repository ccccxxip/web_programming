<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$errors = [];
$separator = "|";

$folder = "applications";
$file = $folder . "/data.txt";

$data = [
    'first_name' => '',
    'last_name' => '',
    'email' => '',
    'phone' => '',
    'topic' => '',
    'payment' => '',
    'newsletter' => 0
];

// папка + файл
if (!is_dir($folder)) {
    mkdir($folder);
}
if (!file_exists($file)) {
    file_put_contents($file, "");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    foreach ($data as $key => $value) {
        if ($key !== 'newsletter') {
            $data[$key] = trim($_POST[$key] ?? '');

            if (strpos($data[$key], $separator) !== false) {
                $errors[] = "Нельзя использовать символ |";
            }
        }
    }

    $data['newsletter'] = isset($_POST['newsletter']) ? 1 : 0;

    if ($data['first_name'] == '') $errors[] = "Введите имя";
    if ($data['last_name'] == '') $errors[] = "Введите фамилию";
    if ($data['email'] == '') $errors[] = "Введите email";
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = "Некорректный email";
    if ($data['phone'] == '') $errors[] = "Введите телефон";
    if ($data['topic'] == '') $errors[] = "Выберите тематику";
    if ($data['payment'] == '') $errors[] = "Выберите оплату";

    if (empty($errors)) {

        $line = implode($separator, [
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['phone'],
            $data['topic'],
            $data['payment'],
            $data['newsletter'],
            date("Y-m-d H:i:s"),
            $_SERVER['REMOTE_ADDR'],
            "active"
        ]) . PHP_EOL;

        file_put_contents($file, $line, FILE_APPEND);

        header("Location: index.php?success=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Регистрация</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: #f5f5f5;
    margin: 0;
    padding: 20px;
}
.container {
    max-width: 450px;
    margin: 0 auto;
    background: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
h2 {
    text-align: center;
    color: #333;
    margin-top: 0;
}
input, select {
    width: 100%;
    padding: 8px;
    margin: 5px 0 15px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box;
}
label {
    display: block;
    margin: 10px 0;
}
button {
    width: 100%;
    background: #5cb85c;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}
button:hover {
    background: #4cae4c;
}
.success {
    background: #dff0d8;
    color: #3c763d;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 4px;
    text-align: center;
}
.error {
    background: #f2dede;
    color: #a94442;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 4px;
    font-size: 14px;
}
</style>
</head>
<body>

<div class="container">
    <h2>Заявка на конференцию</h2>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="success">Заявка принята!</div>
    <?php endif; ?>
    
    <?php foreach ($errors as $e): ?>
        <div class="error"><?= $e ?></div>
    <?php endforeach; ?>
    
    <form method="POST">
        <input type="text" name="first_name" placeholder="Имя" value="<?= htmlspecialchars($data['first_name']) ?>">
        <input type="text" name="last_name" placeholder="Фамилия" value="<?= htmlspecialchars($data['last_name']) ?>">
        <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($data['email']) ?>">
        <input type="text" name="phone" placeholder="Телефон" value="<?= htmlspecialchars($data['phone']) ?>">
        
        <select name="topic">
            <option value="">Тематика</option>
            <option <?= $data['topic'] == 'Бизнес' ? 'selected' : '' ?>>Бизнес</option>
            <option <?= $data['topic'] == 'Технологии' ? 'selected' : '' ?>>Технологии</option>
            <option <?= $data['topic'] == 'Реклама и Маркетинг' ? 'selected' : '' ?>>Реклама и Маркетинг</option>
        </select>
        
        <select name="payment">
            <option value="">Оплата</option>
            <option <?= $data['payment'] == 'WebMoney' ? 'selected' : '' ?>>WebMoney</option>
            <option <?= $data['payment'] == 'Яндекс.Деньги' ? 'selected' : '' ?>>Яндекс.Деньги</option>
            <option <?= $data['payment'] == 'PayPal' ? 'selected' : '' ?>>PayPal</option>
            <option <?= $data['payment'] == 'Кредитная карта' ? 'selected' : '' ?>>Кредитная карта</option>
        </select>
        
        <label>
            <input type="checkbox" name="newsletter" <?= $data['newsletter'] ? 'checked' : '' ?>>
            Получать рассылку
        </label>
        
        <button type="submit">Отправить</button>
    </form>
</div>

</body>
</html>