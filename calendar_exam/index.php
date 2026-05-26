<?php

include 'config/db.php';
include 'models/task.php';

$model = new Task($pdo);

$filter = $_GET['filter'] ?? 'current'; // фильтр по типу 
$date = $_GET['date'] ?? ''; // фильтр по дате


if (!empty($date)) {
    $tasks = $model->getByDate($date); // дата
} elseif ($filter == 'current') {
    $tasks = $model->getCurrent(); // текущ
} elseif ($filter == 'expired') {
    $tasks = $model->getExpired(); // просрочен
} elseif ($filter == 'completed') {
    $tasks = $model->getCompleted(); // выполнен
} elseif ($filter == 'all') {
    $tasks = $model->getAll(); // все 
} else {
    $tasks = $model->getCurrent(); // по дефолту текущ
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="assets/style.css">
    <title>Календарь</title>
</head>
<body>

<div class="container">

<?php include 'views/form.php'; ?> 
<?php include 'views/list.php'; ?>

</div>

</body>
</html>