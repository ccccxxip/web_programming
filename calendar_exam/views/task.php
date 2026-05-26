<?php
include '../config/db.php';
include '../models/task.php';

$model = new Task($pdo);
$id = $_GET['id'];
$t = $model->getById($id); // поиск по id 

if (!$t) {
    echo "<h2>Задача не найдена</h2>";
    echo "<a href='../index.php'>Назад к списку</a>";
    die();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Карточка задачи</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container">
    <h2>Карточка задачи</h2>
    
    <div class="task-card">
        <p><strong>Тема:</strong> <?= htmlspecialchars($t['theme']) ?></p>
        <p><strong>Тип:</strong> <?= $t['type'] ?></p>
        <p><strong>Место:</strong> <?= htmlspecialchars($t['place'] ?: 'Не указано') ?></p>
        <p><strong>Дата и время:</strong> <?= date('d.m.Y H:i', strtotime($t['task_datetime'])) ?></p>
        <p><strong>Длительность:</strong> <?= $t['duration'] ?: 'Не указана' ?> мин</p>
        <p><strong>Комментарий:</strong> <?= nl2br(htmlspecialchars($t['comment'] ?: 'Нет')) ?></p>
        <p><strong>Статус:</strong> <?= $t['status'] == 'выполненная' ? 'Выполнена' : 'Текущая' ?></p>
    </div>
    
    <a href="/views/edit.php?id=<?= $t['id'] ?>">Редактировать</a> <!-- ссылка на редактирование -->
    <a href="../index.php">Назад</a> <!-- ссылка на основную страницу -->
</div>

</body>
</html>