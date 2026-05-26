<?php

require_once __DIR__ . '/classes/application.php';

// удаление
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_POST['delete'])) {

        foreach ($_POST['delete'] as $id) {

            Application::delete($id);
        }
    }

    header("Location: admin.php");
    exit;
}

$participants = Application::getAll();

?>

<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="UTF-8">
<title>Админка</title>

<style>

body{
    font-family:Arial;
    background:#eef1f5;
    padding:20px;
}

.container{
    width:1100px;
    margin:auto;
    background:white;
    padding:25px;
    border-radius:10px;
    box-shadow:0 4px 12px rgba(0,0,0,0.1);
}

h2{
    text-align:center;
    margin-bottom:20px;
}

table{
    width:100%;
    border-collapse:collapse;
}

th, td{
    border:1px solid #ddd;
    padding:10px;
    text-align:center;
}

th{
    background:#f4f4f4;
}

tr:nth-child(even){
    background:#fafafa;
}

button{
    width:100%;
    padding:12px;
    margin-top:15px;
    background:#e74c3c;
    color:white;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-size:16px;
}

button:hover{
    background:#c0392b;
}

</style>

</head>
<body>

<div class="container">

<h2>Админ-панель заявок</h2>

<form method="POST">

<table>

<tr>
    <th></th>
    <th>ID</th>
    <th>Имя</th>
    <th>Фамилия</th>
    <th>Email</th>
    <th>Телефон</th>
    <th>Тема</th>
    <th>Оплата</th>
    <th>Рассылка</th>
    <th>Дата</th>
</tr>

<?php while($row = $participants->fetch_assoc()): ?>

<tr>

<td>
<input type="checkbox"
       name="delete[]"
       value="<?= $row['id'] ?>">
</td>

<td><?= $row['id'] ?></td>

<td><?= htmlspecialchars($row['name']) ?></td>

<td><?= htmlspecialchars($row['lastname']) ?></td>

<td><?= htmlspecialchars($row['email']) ?></td>

<td><?= htmlspecialchars($row['tel']) ?></td>

<td>
<?= Application::$subjects[$row['subject_id']] ?>
</td>

<td>
<?= Application::$payments[$row['payment_id']] ?>
</td>

<td>
<?= $row['mailing'] ? 'Да' : 'Нет' ?>
</td>

<td><?= $row['created_at'] ?></td>

</tr>

<?php endwhile; ?>

</table>

<button type="submit">
Удалить выбранные
</button>

</form>

</div>

</body>
</html>