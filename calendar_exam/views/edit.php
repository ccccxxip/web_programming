<?php
include '../config/db.php';
include '../models/task.php';

$model = new Task($pdo);
$id = $_GET['id'];
$t = $model->getById($id); // поиск по id 

if (!$t) {
    echo "Задача не найдена";
    echo "<br><a href='../index.php'>Назад</a>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Редактирование</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

<div class="container">
    <h2>Редактировать задачу</h2>
    
    <form method="POST" action="/actions/update.php"> 
        <input type="hidden" name="id" value="<?= $t['id'] ?>">
        
        <label>Тема</label>
        <input type="text" name="theme" value="<?= htmlspecialchars($t['theme']) ?>" required>
        
        <label>Тип</label>
        <select name="type">
            <option value="встреча" <?= $t['type'] == 'встреча' ? 'selected' : '' ?>>встреча</option>
            <option value="звонок" <?= $t['type'] == 'звонок' ? 'selected' : '' ?>>звонок</option>
            <option value="совещание" <?= $t['type'] == 'совещание' ? 'selected' : '' ?>>совещание</option>
            <option value="дело" <?= $t['type'] == 'дело' ? 'selected' : '' ?>>дело</option>
        </select>
        
        <label>Место</label>
        <input type="text" name="place" value="<?= htmlspecialchars($t['place']) ?>">
        
        <label>Дата и время</label>
        <input type="datetime-local" name="task_datetime" value="<?= date('Y-m-d\TH:i', strtotime($t['task_datetime'])) ?>" required>
        
        <label>Длительность (минуты)</label>
        <input type="number" name="duration" value="<?= $t['duration'] ?>">
        
        <label>Комментарий</label>
        <textarea name="comment" rows="4"><?= htmlspecialchars($t['comment']) ?></textarea>
        
        <label>Статус</label>
        <select name="status">
            <option value="текущая" <?= $t['status'] == 'текущая' ? 'selected' : '' ?>>текущая</option>
            <option value="выполненная" <?= $t['status'] == 'выполненная' ? 'selected' : '' ?>>выполненная</option>
        </select>
        
        <button type="submit">Сохранить</button>
        <a href="/views/task.php?id=<?= $t['id'] ?>">Отмена</a>
    </form>
</div>

</body>
</html>