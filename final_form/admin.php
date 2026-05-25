<?php
require_once "classes/application.php";

$separator = "|";
$file = "applications/data.txt";

$lines = Application::getAll();

// пометка deleted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $new = [];

    foreach ($lines as $i => $line) {

        $f = explode($separator, $line);

        if (isset($_POST['delete']) &&
            in_array($i, $_POST['delete'])) {

            $f[9] = "deleted";
        }

        $new[] = implode($separator, $f);
    }

    file_put_contents($file, implode(PHP_EOL, $new));

    header("Location: admin.php");
    exit;
}

$lines = Application::getAll();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Админка</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #f5f5f5;
    padding: 30px 20px;
}

.container {
    max-width: 1300px;
    margin: 0 auto;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.header {
    background: white;
    padding: 20px 25px;
    border-bottom: 2px solid #e0e0e0;
}

.header h2 {
    color: #2c3e50;
    font-size: 24px;
    font-weight: 500;
}

.count {
    color: #7f8c8d;
    font-size: 14px;
    margin-top: 5px;
}

.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #f8f9fa;
    color: #2c3e50;
    padding: 12px 10px;
    text-align: left;
    font-weight: 600;
    font-size: 13px;
    border-bottom: 2px solid #dee2e6;
}

td {
    padding: 10px;
    border-bottom: 1px solid #ecf0f1;
    color: #34495e;
    font-size: 13px;
}

tr:hover {
    background: #f8f9fa;
}

.checkbox-col {
    width: 40px;
    text-align: center;
}

input[type="checkbox"] {
    width: 16px;
    height: 16px;
    cursor: pointer;
}

.delete-form {
    padding: 20px 25px;
    background: white;
    border-top: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.select-all-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-size: 14px;
    color: #555;
}

.select-all-btn:hover {
    color: #2c3e50;
}

button {
    background: #e74c3c;
    color: white;
    border: none;
    padding: 8px 20px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: background 0.2s;
}

button:hover {
    background: #c0392b;
}

.empty {
    text-align: center;
    padding: 50px;
    color: #999;
    font-size: 14px;
}

@media (max-width: 768px) {
    body {
        padding: 15px;
    }
    
    .header h2 {
        font-size: 20px;
    }
    
    th, td {
        padding: 8px 6px;
        font-size: 12px;
    }
    
    .delete-form {
        padding: 15px;
    }
}
</style>

</head>
<body>

<div class="container">
    <div class="header">
        <h2>Заявки</h2>
        <?php 
        $count = 0;
        foreach ($lines as $line) {
            $f = explode($separator, $line);
            if (count($f) >= 10 && $f[9] != "deleted") $count++;
        }
        ?>
        <div class="count">Всего: <?= $count ?></div>
    </div>

    <form method="POST">
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th class="checkbox-col"></th>
                        <th>Имя</th>
                        <th>Фамилия</th>
                        <th>Email</th>
                        <th>Телефон</th>
                        <th>Тема</th>
                        <th>Оплата</th>
                        <th>Рассылка</th>
                        <th>Дата</th>
                        <th>IP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $hasRows = false;
                    foreach ($lines as $i => $line): 
                        $f = explode($separator, $line);
                        if (count($f) < 10) continue;
                        if ($f[9] == "deleted") continue;
                        $hasRows = true;
                    ?>
                    <tr>
                        <td class="checkbox-col">
                            <input type="checkbox" name="delete[]" value="<?= $i ?>">
                        </td>
                        <?php for ($j = 0; $j < 9; $j++): ?>
                        <td><?= htmlspecialchars($f[$j]) ?></td>
                        <?php endfor; ?>
                    </tr>
                    <?php endforeach; ?>
                    
                    <?php if (!$hasRows): ?>
                    <tr>
                        <td colspan="10" class="empty">
                            Нет заявок
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <?php if ($hasRows): ?>
        <div class="delete-form">
            <label class="select-all-btn">
                <input type="checkbox" id="selectAll"> Выбрать все
            </label>
            <button type="submit" onclick="return confirm('Удалить выбранные заявки?')">Удалить</button>
        </div>
        <?php endif; ?>
    </form>
</div>

<script>
const selectAll = document.getElementById('selectAll');
if (selectAll) {
    selectAll.onclick = function() {
        const checkboxes = document.querySelectorAll('input[name="delete[]"]');
        for (let cb of checkboxes) {
            cb.checked = this.checked;
        }
    }
}
</script>

</body>
</html>