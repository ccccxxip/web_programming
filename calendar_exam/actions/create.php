<?php

include '../config/db.php';
include '../models/task.php';

$model = new Task($pdo);

$model->create($_POST); // сохр в бд из form.php

header("Location: ../index.php");