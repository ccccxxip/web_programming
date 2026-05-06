<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$folder = "applications";
$file = $folder . "/data.txt";
$separator = "|";

if (!is_dir($folder)) {
    mkdir($folder);
}
if (!file_exists($file)) {
    file_put_contents($file, "");
}

// удаление
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {

    $lines = file($file, FILE_IGNORE_NEW_LINES);
    $new = [];

    foreach ($lines as $i => $line) {

        $fields = explode($separator, $line);

        if (count($fields) < 10) continue;

        if (isset($_POST['delete_ids']) && in_array($i, $_POST['delete_ids'])) {
            $fields[9] = "deleted";
        }

        $new[] = implode($separator, $fields);
    }

    file_put_contents($file, implode(PHP_EOL, $new) . PHP_EOL);
}

$lines = file($file, FILE_IGNORE_NEW_LINES);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Админка</title>
<style>
body {
    font-family: Arial, sans-serif;
    margin: 20px;
    background: #fff;
}
h2 {
    background: #333;
    color: #fff;
    padding: 10px;
    margin: 0 0 20px 0;
}
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
}
th {
    background: #f2f2f2;
}
tr:hover {
    background: #f9f9f9;
}
button {
    background: #d9534f;
    color: white;
    border: none;
    padding: 8px 20px;
    cursor: pointer;
    margin-top: 15px;
    font-size: 14px;
}
button:hover {
    background: #c9302c;
}
.checkbox-col {
    text-align: center;
    width: 30px;
}
.empty {
    text-align: center;
    padding: 30px;
    color: #999;
}
</style>
</head>
<body>

<h2>Список заявок</h2>

<form method="POST">
<table>
    <tr>
        <th class="checkbox-col"></th>
        <th>Имя</th>
        <th>Фамилия</th>
        <th>Email</th>
        <th>Телефон</th>
        <th>Тематика</th>
        <th>Оплата</th>
        <th>Рассылка</th>
        <th>Дата</th>
        <th>IP</th>
    </tr>
    <?php 
    $count = 0;
    foreach ($lines as $i => $line): 
        $fields = explode($separator, $line);
        if (count($fields) < 10) continue;
        if ($fields[9] == "deleted") continue;
        $count++;
    ?>
    <tr>
        <td class="checkbox-col"><input type="checkbox" name="delete_ids[]" value="<?= $i ?>"></td>
        <?php for ($j = 0; $j < 9; $j++): ?>
            <td><?= htmlspecialchars($fields[$j]) ?></td>
        <?php endfor; ?>
    </tr>
    <?php endforeach; ?>
    
    <?php if ($count == 0): ?>
    <tr>
        <td colspan="10" class="empty">Нет заявок</td>
    </tr>
    <?php endif; ?>
</table>

<?php if ($count > 0): ?>
<button type="submit" name="delete" onclick="return confirm('Точно удалить?')">Удалить выбранные</button>
<?php endif; ?>
</form>

</body>
</html>