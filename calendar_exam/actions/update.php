<?php
include '../config/db.php';
include '../models/task.php';

$model = new Task($pdo);
$model->update($_POST);

header("Location: /views/task.php?id=" . $_POST['id']);
exit;