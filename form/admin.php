<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$folder = "applications";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {

    if (!empty($_POST['files'])) {
        foreach ($_POST['files'] as $file) {
            $path = $folder . "/" . basename($file);
            if (file_exists($path)) {
                unlink($path);
            }
        }
        $message = "Заявки удалены!";
    } else {
        $message = "Ничего не выбрано.";
    }
}

$files = array_diff(scandir($folder), ['.', '..']);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Админ панель</title>

<style>
body {
    font-family: Arial;
    background: #eef1f5;
    padding: 20px;
}

.container {
    width: 800px;
    margin: auto;
    background: white;
    padding: 25px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

h2 {
    text-align: center;
}

.item {
    border: 1px solid #ddd;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 6px;
    background: #fafafa;
}

pre {
    white-space: pre-wrap;
}

button {
    background: #e74c3c;
    color: white;
    padding: 10px;
    border: none;
    border-radius: 6px;
    width: 100%;
    cursor: pointer;
}

button:hover {
    background: #c0392b;
}

.success {
    background: #e6f9ec;
    color: #1e7e34;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 6px;
    text-align: center;
}
</style>

</head>
<body>

<div class="container">

<h2>Админ-панель заявок</h2>

<?php if (!empty($message)): ?>
    <div class="success"><?= $message ?></div>
<?php endif; ?>

<form method="POST">

<?php if (empty($files)): ?>
    <p>Заявок нет</p>
<?php else: ?>

<?php foreach ($files as $file): ?>
    <div class="item">
        <label>
            <input type="checkbox" name="files[]" value="<?= $file ?>">
            Удалить
        </label>
        <pre><?= htmlspecialchars(file_get_contents($folder.'/'.$file)) ?></pre>
    </div>
<?php endforeach; ?>

<button type="submit" name="delete">Удалить выбранные</button>

<?php endif; ?>

</form>

</div>

</body>
</html>