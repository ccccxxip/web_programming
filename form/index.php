<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$errors = [];

$data = [
    'first_name' => '',
    'last_name' => '',
    'email' => '',
    'phone' => '',
    'topic' => '',
    'payment' => '',
    'newsletter' => 0
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    foreach ($data as $key => $value) {
        if ($key !== 'newsletter') {
            $data[$key] = trim($_POST[$key] ?? '');
        }
    }

    $data['newsletter'] = isset($_POST['newsletter']) ? 1 : 0;

    if ($data['first_name'] == '') $errors[] = "Введите имя";
    if ($data['last_name'] == '') $errors[] = "Введите фамилию";
    if ($data['email'] == '') $errors[] = "Введите email";
    if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = "Некорректный email";
    if ($data['phone'] == '') $errors[] = "Введите телефон";
    if ($data['topic'] == '') $errors[] = "Выберите тематику";
    if ($data['payment'] == '') $errors[] = "Выберите способ оплаты";

    if (empty($errors)) {

        if (!is_dir("applications")) {
            mkdir("applications");
        }

        $filename = "applications/application_" . time() . "_" . rand(100,999) . ".txt";

        $content =
            "Имя: {$data['first_name']}\n" .
            "Фамилия: {$data['last_name']}\n" .
            "Email: {$data['email']}\n" .
            "Телефон: {$data['phone']}\n" .
            "Тематика: {$data['topic']}\n" .
            "Оплата: {$data['payment']}\n" .
            "Рассылка: " . ($data['newsletter'] ? "Да" : "Нет") . "\n" .
            "Дата: " . date("Y-m-d H:i:s");

        file_put_contents($filename, $content);

        header("Location: " . $_SERVER['PHP_SELF'] . "?success=1");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Заявка на конференцию</title>

<style>
body {
    font-family: Arial;
    background: #f4f6f9;
    padding: 20px;
}

.container {
    width: 520px;
    margin: 30px auto;
    background: #fff;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

h2 {
    text-align: center;
    color: #2c3e50;
}

input, select {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
}

button {
    width: 100%;
    padding: 10px;
    background: #4a90e2;
    border: none;
    color: white;
    border-radius: 6px;
    cursor: pointer;
}

button:hover {
    background: #357bd8;
}

.error {
    background: #ffe6e6;
    color: #c0392b;
    padding: 8px;
    margin-bottom: 10px;
    border-radius: 6px;
}

.success {
    background: #e6f9ec;
    color: #1e7e34;
    padding: 10px;
    margin-bottom: 15px;
    border-radius: 6px;
    text-align: center;
}
</style>

</head>
<body>

<div class="container">

<h2>Заявка на конференцию</h2>

<?php if (isset($_GET['success'])): ?>
    <div class="success">Заявка успешно принята!</div>
<?php endif; ?>

<?php foreach ($errors as $error): ?>
    <div class="error"><?= $error ?></div>
<?php endforeach; ?>

<form method="POST">

Имя:
<input type="text" name="first_name" value="<?= htmlspecialchars($data['first_name']) ?>">

Фамилия:
<input type="text" name="last_name" value="<?= htmlspecialchars($data['last_name']) ?>">

Email:
<input type="text" name="email" value="<?= htmlspecialchars($data['email']) ?>">

Телефон:
<input type="text" name="phone" value="<?= htmlspecialchars($data['phone']) ?>">

Тематика:
<select name="topic">
    <option value="">-- выберите --</option>
    <option value="Бизнес" <?= $data['topic']=="Бизнес"?"selected":"" ?>>Бизнес</option>
    <option value="Технологии" <?= $data['topic']=="Технологии"?"selected":"" ?>>Технологии</option>
    <option value="Реклама и Маркетинг" <?= $data['topic']=="Реклама и Маркетинг"?"selected":"" ?>>Реклама и Маркетинг</option>
</select>

Оплата:
<select name="payment">
    <option value="">-- выберите --</option>
    <option value="WebMoney" <?= $data['payment']=="WebMoney"?"selected":"" ?>>WebMoney</option>
    <option value="Яндекс.Деньги" <?= $data['payment']=="Яндекс.Деньги"?"selected":"" ?>>Яндекс.Деньги</option>
    <option value="PayPal" <?= $data['payment']=="PayPal"?"selected":"" ?>>PayPal</option>
    <option value="Карта" <?= $data['payment']=="Карта"?"selected":"" ?>>Кредитная карта</option>
</select>

<label>
<input type="checkbox" name="newsletter" <?= $data['newsletter'] ? "checked" : "" ?>>
Получать рассылку
</label>

<br><br>

<button type="submit">Отправить</button>

</form>

</div>

</body>
</html>